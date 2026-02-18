<?php
header('Content-Type: application/json');
require 'db.php';
require_once 'token_verification.php';

try {
    authentication();
    //data consists of: 'bid'
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$uid || !isset($data['bid'])) {
        echo json_encode (['success' => false, 'message' => 'Not enough data']);
        exit;
    }
    $bid = $data['bid'];

    $bookremove = $pdo->prepare("DELETE FROM user_books WHERE uid = ? AND bid = ?");
    $bookremove -> execute([$uid, $bid]);
    if ($bookremove->rowCount() !== 1) {
        echo json_encode(['success' => false, 'message' => 'Failed to delete the book']);
        exit;
    }
    echo json_encode(['success' => true, 'message' => 'Book deleted from user library']);

}
catch (EXCEPTION $e) {
    echo json_encode(['success' => false, 'message' => 'Error, try again LATER (later is not 5 seconds from now btw)']);
    exit;
}

