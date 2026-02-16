<?php
require 'db.php';
require_once 'token_verification.php';
authentication();

$uid = $GLOBALS['auth_uid'];
if (!$uid) {
    echo json_encode(['success' => false, 'message' => 'No uid']);
}
$selectbooks = $pdo->prepare("SELECT * FROM user_books WHERE uid=?");
$selectbooks->execute([$uid]);
$userbooks = $selectbooks->fetchALL(PDO::FETCH_ASSOC);
echo json_encode(['success' => true, 'data' => $userbooks]);