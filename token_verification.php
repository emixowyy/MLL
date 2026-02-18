<?php
header('Content-Type: application/json');
require 'db.php';
require 'auth.php';

function authentication() {
    global $pdo;

    $sessiontoken = $_COOKIE['sessionToken'] ?? null;
    if (!$sessiontoken) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'No session token']);
        exit;
    }

    $uid = Auth::validateSessionToken($sessiontoken, $pdo);
    if (!$uid) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Session token expired']);
        exit;
    }
    $GLOBALS['auth_uid'] = $uid;

}
