<?php
require 'db.php';
require_once 'token_verification.php';
authentication();
//GET consists of: 'param' and 'bid' and 'status' and/or 'rating' and/or 'review' and/or 'reviewpub' and/or 'startdate' and/or 'enddate' and/or 'pagesread'
$uid = $GLOBALS['auth_uid'];
$param = $_GET['param'];
$bid = $_GET['bid'];
$status = $_GET['status'];
$rating = $_GET['rating'] ?? null;
$review = $_GET['review'] ?? null;
$reviewpub = $_GET['reviewpub'] ?? null;
$startdate = $_GET['startdate'] ?? null;
$enddate = $_GET['enddate'] ?? null;
$pagesread = $_GET['pagesread'] ?? null;

if (!isset($uid, $bid, $status,$param)) {
    echo json_encode (['success' => false, 'message' => 'Not enough data']);
    exit;
}
if ($param == 'INSERT') {
    $query = $pdo->prepare("INSERT INTO user_books (status,rating,review,review_pub,start_date,end_date,pages_read, uid, bid) VALUES (?,?,?,?,?,?,?,?,?)");
    $query -> execute([$status, $rating, $review, $reviewpub, $startdate, $enddate, $pagesread, $uid, $bid]);
    echo json_encode(['success' => true, 'message' => 'Book inserted into user library']);
}
elseif ($param == 'UPDATE') {
    $query = $pdo->prepare("UPDATE user_books SET status=?, rating=?, review=?, review_pub=?, start_date=?, end_date=?, pages_read=? WHERE uid=? AND bid=?");
    $query -> execute([$status, $rating, $review, $reviewpub, $startdate, $enddate, $pagesread, $uid, $bid]);
    echo json_encode(['success' => true, 'message' => 'Entry updated in user library']);
}
elseif ($param == 'DELETE') {
    $query = $pdo->prepare("DELETE FROM user_books WHERE uid=? AND bid=?");
    $query -> execute([$uid, $bid]);
    echo json_encode(['success' => true, 'message' => 'Book deleted from user library']);
}

