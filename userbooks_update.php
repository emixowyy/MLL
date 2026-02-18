<?php
header('Content-Type: application/json');
require 'db.php';
require_once 'token_verification.php';

try {
    authentication();
    //data consists of:  and 'bid' and 'status' and/or 'rating' and/or 'review' and/or 'reviewpub' and/or 'startdate' and/or 'enddate' and/or 'pagesread'
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$uid || !isset($data['bid'])) {
        echo json_encode (['success' => false, 'message' => 'Not enough data']);
        exit;
    }
    $bid = $data['bid'];
    $status = $data['status'];
    $rating = $data['rating'] ?? null;
    $review = $data['review'] ?? null;
    $reviewpub = $data['reviewpub'] ?? 0;
    $startdate = $data['startdate'] ?? null;
    $enddate = $data['enddate'] ?? null;
    $pagesread = $data['pagesread'] ?? 0;


    $bookin = $pdo->prepare("UPDATE user_books SET status=?, rating=?, review=?, review_pub=?, start_date=?, end_date=?, pages_read=? WHERE uid=? AND bid=?");
    $bookin -> execute([$status, $rating, $review, $reviewpub, $startdate, $enddate, $pagesread, $uid, $bid]);
    if ($bookin->rowCount()===0) {
        echo json_encode(['success' => false, 'message' => 'Nothing was updated']);
        exit;
    }
    echo json_encode(['success' => true, 'message' => 'Entry updated in user library']);

}
catch (EXCEPTION $e) {
    echo json_encode(['success' => false, 'message' => 'Error, try again LATER (later is not 5 seconds from now btw)']);
    exit;
}

