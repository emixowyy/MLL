<?php

header('Content-Type: application/json');
require 'db.php';
//Data consists of: 'title' or 'isbn' or 'author' or 'categories' and/or 'nresults'
$title = $_GET['title'] ?? null;
$isbn = $_GET['isbn'] ?? null;
$author = $_GET['author'] ?? null;
$categories = $_GET['categories'] ?? null;
$nresults = $_GET['nresults'] ?? 5;


if (isset($title)) {
    $dbquery = "SELECT authors.name, books.* FROM books JOIN authors ON books.aid = authors.aid WHERE title like ? LIMIT $nresults";
    $param = ["%$title%"];
    $queryparam = urlencode("intitle:$title");
}
elseif (isset($isbn)) {
    $dbquery = "SELECT authors.name, books.* FROM books JOIN authors ON books.aid = authors.aid WHERE isbn = ? LIMIT $nresults";
    $param = [$isbn];
    $queryparam = urlencode("isbn:$isbn");
}
elseif (isset($author)) {
    $dbquery = "SELECT authors.name, books.* FROM books JOIN authors ON books.aid = authors.aid WHERE authors.name LIKE ? LIMIT $nresults";
    $param = ["%$author%"];
    $queryparam = urlencode("inauthor:$author");
}
elseif (isset($categories)) {
    $dbquery = "SELECT authors.name, books.* FROM books JOIN authors ON books.aid = authors.aid WHERE categories like ? LIMIT $nresults";
    $param = ["%$categories%"];
    $queryparam = urlencode("subject:$categories");
}
$dbqueryexec = $pdo->prepare($dbquery);
$dbqueryexec->execute($param);

$queryresult = $dbqueryexec->fetchALL(PDO::FETCH_ASSOC);

if (count($queryresult) === 0 ) {
    $url = curl_init("https://www.googleapis.com/books/v1/volumes/?q=$queryparam&maxResults=10&printType=books&orderBy=relevance");
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    $getbooks = curl_exec($url);
    curl_close($url);
    $books = json_decode($getbooks, true);
    foreach ($books as $book) {

    }
    // function selectBestBook($items) {
    //     $bestBook = null;
    //     $bestScore = 0;

    //     foreach ($items as $book) {
    //         $info = $book['volumeInfo'] ?? [];
    //         $score = 0;

    //         if (!empty($info['title'])) $score += 5;
    //         if (!empty($info['authors'][0])) $score += 5;
    //         if (!empty($info['publishedDate'])) $score += 3;

    //         if (!empty($info['industryIdentifiers'])) {
    //             foreach ($info['industryIdentifiers'] as $id) {
    //                 if ($id['type'] === 'ISBN_13') {
    //                     $score += 5;
    //                     break;
    //                 }
    //             }
    //         }
    //         if (!empty($info['description'])) $score += 2;
    //         if (!empty($info['categories'])) $score += 2;
    //         if (!empty($info['imageLinks']['thumbnail'])) $score += 2;
    //         if (!empty($info['averageRating'])) $score += 1;
    //         if (!empty($info['language'])) $score += 1;

    //         if ($score > $bestScore) {
    //             $bestScore = $score;
    //             $bestBook = $book;
    //         }
    //     }

    //     return $bestBook;
    // }
    // $bestBook = selectBestBook($books['items']);
    // if (empty($books['items'])) {
    // echo json_encode(["success" => false, "message" => "No books found"]);
    // exit;
    // }

    $info = $bestBook['volumeInfo'];

    $titlein       = $info['title'];
    $authorin      = $info['authors'][0];
    $publisheddate = $info['publishedDate'] ?? null;
    $maturity      = $info['maturityRating'] ?? null;
    $description   = $info['description'] ?? null;
    $categoriesin  = $info['categories'] ?? null;
    $language      = $info['language'] ?? null;
    $averagerating = $info['averageRating'] ?? null;
    $thumbnail     = $info['imageLinks']['thumbnail'] ?? null;
    $isbnin = null;

    if (!empty($info['industryIdentifiers'])) {
        foreach ($info['industryIdentifiers'] as $id) {
            if ($id['type'] === 'ISBN_13') {
                $isbnin = $id['identifier'];
                break;
            }
        }
        if (!$isbnin) {
            $isbnin = $info['industryIdentifiers'][0]['identifier'] ?? null;
        }
    }

    $insertbooks = $pdo->prepare("INSERT IGNORE INTO books (aid,isbn,title,pub_date,b_desc,categories,maturity,rating,language,thumbnail) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $checkauthor = $pdo->prepare("SELECT aid FROM authors WHERE name = ?");
    $checkauthor->execute([$authorin]);
    $authorid = $checkauthor->fetch(PDO::FETCH_ASSOC);
    if ($authorid == false) {
        $insertauthor = $pdo->prepare("INSERT INTO authors (name) VALUES (?)");
        $insertauthor->execute([$authorin]);
        $aid = $pdo->lastInsertId();
    }
    else {
        $aid = $authorid['aid'];
    }
    $insertbooks->execute([
        $aid,
        $isbnin,
        $titlein,
        $publisheddate,
        $description,
        is_array($categoriesin) ? json_encode($categoriesin) : null,
        $maturity,
        $averagerating,
        $language,
        $thumbnail
    ]);





    $anotherquerry = $pdo->prepare("SELECT authors.name,books.* FROM books JOIN authors ON books.aid WHERE books.title LIKE ?");
    $anotherquerry -> execute(["%$titlein%"]);
    $anotherquerryresult = $anotherquerry->fetch(PDO::FETCH_ASSOC);
    echo json_encode(["success: " => true, "Data: " => $anotherquerryresult]);
}
else {
    echo json_encode(["success: " => true, "Data" => $queryresult]);
}
