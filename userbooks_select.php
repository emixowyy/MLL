<?php
require 'db.php';
require_once 'token_verification.php';
header('Content-Type: application/json');
try {
    authentication();
    $ip = $_SERVER['REMOTE_ADDR'];
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $uid = $GLOBALS['auth_uid'];
    if (!$uid) {
        $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
        $log->execute([$ip,$useragent,'ubsl','no_uid',0]);
        echo json_encode(['success' => false, 'message' => 'No uid']);
        exit;
    }
    $selectbooks = $pdo->prepare("SELECT * FROM user_books WHERE uid=?");
    $selectbooks->execute([$uid]);
    $userbooks = $selectbooks->fetchALL(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $userbooks]);
}
catch (EXCEPTION $e) {
    echo json_encode(['success' => false, 'message' => 'Error, try again LATER (later is not 5 seconds from now btw)']);
    exit;
}