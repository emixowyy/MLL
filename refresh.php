<?php
header('Content-Type: application/json');
require 'db.php';
require 'auth.php';

$refreshtoken = $_COOKIE['refreshToken'] ?? null;

if (!$refreshtoken) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No refresh token']);
    exit;
}

$newsessiontoken = Auth::refreshSessionToken($refreshtoken, $pdo);
if (!$newsessiontoken) {
    setcookie('sessionToken', '', time() - 3600, '/', '', true, true);
    setcookie('refreshToken', '', time() - 3600, '/', '', true, true);
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Session expired. Log in again.']);
    exit;
}

setcookie('sessionToken', $newsessiontoken['sessiontoken'], time() + 86400, '/', '', true, true);
echo json_encode(['success' => true, 'uid' => $newsessiontoken['uid']]);

