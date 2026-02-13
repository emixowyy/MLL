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
    $dbquery = "SELECT * FROM books WHERE title like ? LIMIT $nresults";
    $param = ["%$title%"];
    $queryparam = urlencode("intitle:$title");
}
elseif (isset($isbn)) {
    $dbquery = "SELECT * FROM books WHERE isbn = ? LIMIT $nresults";
    $param = ["%$isbn%"];
    $queryparam = urlencode("isbn:$isbn");
}
elseif (isset($author)) {
    $dbquery = "SELECT books.* FROM books JOIN authors ON books.aid = authors.aid WHERE authors.name LIKE ? LIMIT $nresults";
    $param = ["%$author%"];
    $queryparam = urlencode("inauthor:$author");
}
elseif (isset($categories)) {
    $dbquery = "SELECT * FROM books WHERE categories like ? LIMIT $nresults";
    $param = ["%$categories%"];
    $queryparam = urlencode("subject:$categories");
}
$dbqueryexec = $pdo->prepare($dbquery);
$dbqueryexec->execute($param);

$queryresult = $dbqueryexec->fetchALL(PDO::FETCH_ASSOC);

if (count($queryresult) === 0 ) {
    $url = curl_init("https://www.googleapis.com/books/v1/volumes/?q=$queryparam&maxResults=$nresults&printType=books&orderBy=relevance&projection=lite");
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    $getbooks = curl_exec($url);
    curl_close($url);
    $books = json_decode($getbooks, true);


    function selectBestBook($items) {
        $bestBook = null;
        $bestScore = 0;

        foreach ($items as $book) {
            $info = $book['volumeInfo'] ?? [];
            $score = 0;

            if (!empty($info['title'])) $score += 5;
            if (!empty($info['authors'][0])) $score += 5;
            if (!empty($info['publishedDate'])) $score += 3;

            if (!empty($info['industryIdentifiers'])) {
                foreach ($info['industryIdentifiers'] as $id) {
                    if ($id['type'] === 'ISBN_13') {
                        $score += 5;
                        break;
                    }
                }
            }
            if (!empty($info['description'])) $score += 2;
            if (!empty($info['categories'])) $score += 2;
            if (!empty($info['imageLinks']['thumbnail'])) $score += 2;
            if (!empty($info['averageRating'])) $score += 1;
            if (!empty($info['language'])) $score += 1;

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestBook = $book;
            }
        }

        return $bestBook;
    }

    $info = $bestBook['volumeInfo'] ?? [];

    $titlein       = $info['title'] ?? null;
    $authorin      = $info['authors'][0] ?? null;
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

    $insertbooks = $pdo->prepare("INSERT INTO books (aid,isbn,title,pub_date,b_desc,categories,maturity,rating,language,thumbnail) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $checkauthor = $pdo->prepare("SELECT aid FROM authors WHERE name = ?");
    $checkauthor->execute([$authorin]);
    $authorid = $checkauthor->fetch(PDO::FETCH_ASSOC);
    if ($authorid) {
        $aid = $authorid['aid'];
    }
    else {
        $insertauthor = $pdo->prepare("INSERT INTO authors (name) VALUES (?)");
        $insertauthor->execute([$authorin]);
        $aid = $pdo->lastInsertId();
    }
    $insertbooks->execute([
        $aid,
        $isbnin,
        $titlein,
        $publisheddate,
        $description,
        is_array($categoriesin) ? implode(',', $categoriesin) : null,
        $maturity,
        $averagerating,
        $language,
        $thumbnail
    ]);

    $anotherquerry = $pdo->prepare("SELECT * FROM books WHERE title = ?");
    $anotherquerry -> execute([$titlein]);
    $anotherquerryresult = $anotherquerry->fetch(PDO::FETCH_ASSOC);
    echo json_encode(["success: " => true, "Data: " => $anotherquerryresult]);
}
else {
    echo json_encode(["success: " => true, "Data" => $queryresult]);
}
