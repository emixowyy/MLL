<?php

header('Content-Type: application/json');
require 'db.php';
require_once 'token_verification.php';
try {
    authentication();
    //Data consists of: 'password'
    $data = json_decode(file_get_contents("php://input"), true);
    $ip = $_SERVER['REMOTE_ADDR'];
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    if (!$data || !isset($data['password'])) {
        $log = $pdo->prepare("INSERT INTO user_logs (uid,ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?,?)");
        $log->execute([$uid, $ip, $useragent, 'del', 'no_password',0]);
        echo json_encode(["success" => false, "message" => "No password"]);
        exit;
    }
    $password = $data['password'];
    $getpasshash = $pdo->prepare("SELECT pass_hash FROM users WHERE uid = ?");
    $getpasshash->execute([$uid]);
    $passhash = $getpasshash->fetch(PDO::FETCH_ASSOC);

    if (!$passhash) {
        $log = $pdo->prepare("INSERT INTO user_logs (uid,ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?,?)");
        $log->execute([$uid, $ip, $useragent, 'del', 'validation',0]);
        echo json_encode(['success' => false, 'message' => 'No password hash']);
        exit;
    }

    if (!password_verify($password, $passhash['pass_hash'])) {
        $log = $pdo->prepare("INSERT INTO user_logs (uid,ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?,?)");
        $log->execute([$uid, $ip, $useragent, 'del', 'validation',0]);
        echo json_encode(['success' => false, 'message' => 'Password incorrect']);
        exit;
    }
    $deluser = $pdo->prepare("DELETE FROM users WHERE uid = ?");
    $deluser->execute([$uid]);
    if ($deluser->rowCount() !==1) {
        $log = $pdo->prepare("INSERT INTO user_logs (uid,ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?,?)");
        $log->execute([$uid, $ip, $useragent, 'del', 'err',0]);
        echo json_encode(['success' => false, 'message' => 'Error user did not get deleted']);
        exit;
    }
    echo json_encode(["success" => true, "message" => "Account deleted successfully"]);
}
catch (EXCEPTION $e) {
    echo json_encode(['success' => false, 'message' => 'Error, try again LATER (later is not 5 seconds from now btw)']);
    exit;
}