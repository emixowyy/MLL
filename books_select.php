<?php
require 'db.php';
require_once 'token_verification.php';
header('Content-Type: application/json');
try {
    authentication();
    //GET consists of: 'title' or 'isbn' or 'author' or 'categories' and/or 'nresults' and/or 'language'
    $title = $_GET['title'] ?? null;
    $isbn = $_GET['isbn'] ?? null;
    $author = $_GET['author'] ?? null;
    $categories = $_GET['categories'] ?? null;
    $nresults = $_GET['nresults'] ?? 5;
    $language = $_GET['language'] ?? 'en';
    $nresults = min(max((int)$nresults, 1), 40);

    if (isset($title)) {
        $dbquery = "SELECT authors.name, books.* FROM books JOIN authors ON books.aid = authors.aid WHERE title like ? AND language = ? LIMIT ?";
        $param = ["%$title%", $language, $nresults];
        $queryparam = urlencode("intitle:$title");
    }
    elseif (isset($isbn)) {
        $dbquery = "SELECT authors.name, books.* FROM books JOIN authors ON books.aid = authors.aid WHERE isbn = ? LIMIT ?";
        $param = [$isbn, $nresults];
        $queryparam = urlencode("isbn:$isbn");
    }
    elseif (isset($author)) {
        $dbquery = "SELECT authors.name, books.* FROM books JOIN authors ON books.aid = authors.aid WHERE authors.name LIKE ? AND language = ? LIMIT ?";
        $param = ["%$author%", $language, $nresults];
        $queryparam = urlencode("inauthor:$author");
    }
    elseif (isset($categories)) {
        $dbquery = "SELECT authors.name, books.* FROM books JOIN authors ON books.aid = authors.aid WHERE categories like ? AND language = ? LIMIT ?";
        $param = ["%$categories%", $language, $nresults];
        $queryparam = urlencode("subject:$categories");
    }
    else {
        echo json_encode(["success" => false, "message" => "Error help no query parameter"]);
        exit;
    }
    $dbqueryexec = $pdo->prepare($dbquery);
    $dbqueryexec->execute($param);

    $queryresult = $dbqueryexec->fetchALL(PDO::FETCH_ASSOC);

    if (count($queryresult) < $nresults ) {
        $url = curl_init("https://www.googleapis.com/books/v1/volumes/?q=$queryparam&maxResults=$nresults&printType=books&orderBy=relevance&langRestrict=$language");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        $getbooks = curl_exec($url);
        curl_close($url);
        $books = json_decode($getbooks, true);
        if (empty($books['items'])) {
            echo json_encode(['success' => true, 'data' => []]);
            exit;
        }
        foreach ($books['items'] as $book) {
            $info = $book['volumeInfo'];

            $titlein       = $info['title'];
            $authorin      = $info['authors'][0] ?? 'Unknown';
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
        }
        $dbqueryexec->execute($param);
        $queryresult= $dbqueryexec->fetchALL(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "data" => $queryresult]);
    }
    else {
        echo json_encode(["success" => true, "data" => $queryresult]);
    }
}
catch (EXCEPTION $e) {
    echo json_encode(['success' => false, 'message' => 'Error, try again LATER (later is not 5 seconds from now btw)']);
    exit;
}