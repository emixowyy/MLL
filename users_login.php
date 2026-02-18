<?php

header('Content-Type: application/json');
require 'db.php';
require_once 'auth.php';
try {
    //Data consists of: 'username' or 'email' and 'password'
    $data = json_decode(file_get_contents("php://input"), true);
    $ip = $_SERVER['REMOTE_ADDR'];
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    if (!$data || !isset($data['password']) || (!isset($data['email']) && !isset($data['username']))) {
        $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
        $log->execute([$ip,$useragent,'log','no_data',0]);       
        echo json_encode(["success" => false, "message" => "No data"]);
        exit;
    }
    $password = $data['password'];

    if (isset($data['username'])) {
        $username = $data['username'];
        $getuser = $pdo->prepare("SELECT username, pass_hash, uid FROM users WHERE username = ?");
        $getuser->execute([$username]);
        $user = $getuser->fetch(PDO::FETCH_ASSOC);
    }
    elseif (isset($data['email'])) {
        $email = $data['email'];
        $getuser = $pdo->prepare("SELECT email, pass_hash, uid FROM users WHERE email = ?");
        $getuser->execute([$email]);
        $user = $getuser->fetch(PDO::FETCH_ASSOC);
    }
    else {
        $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
        $log->execute([$ip,$useragent,'log','validation',0]);
        echo json_encode(["success" => false, "message" => "Incorrect data"]);
        exit;
    }

    if (!$user || !password_verify($password, $user['pass_hash'])) {
        $uid = $user['uid'];
        $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
        $log->execute([$ip,$useragent,'log','validation',0]);
        echo json_encode(["success" => false, "message" => "Password or username incorrect"]);
        exit;
    }
    $sessiontoken = Auth::createSessionToken($uid, $pdo);
    $refreshtoken = Auth::createRefreshToken($uid, $pdo);
    setcookie('sessionToken', $sessiontoken, time() + 86400, '/', '', true, true);
    setcookie('refreshToken', $refreshtoken, time() + 14*24*3600, '/', '', true, true);
    $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
    $log->execute([$ip,$useragent,'log',null,1]);  
    echo json_encode(["success" => true, "message" => "Login successful"]);
}
catch (EXCEPTION $e) {
    echo json_encode(['success' => false, 'message' => 'Error, try again LATER (later is not 5 seconds from now btw)']);
    exit;
}