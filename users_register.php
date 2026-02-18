<?php

header('Content-Type: application/json');
require 'db.php';
try {
    //Data consists of: 'username' and 'email' and 'password1' and 'password2'
    $data = json_decode(file_get_contents("php://input"), true);
    $ip = $_SERVER['REMOTE_ADDR'];
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    if (!$data || !isset($data['username'], $data['email'], $data['password1'], $data['password2'])) {
        $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
        $log->execute([$ip,$useragent,'reg','no_data',0]);
        echo json_encode(["success" => false, "message" => "Incorrect data"]);
        exit;
    }

    $username = $data['username'];
    $email = $data['email'];
    $password1 = $data['password1'];
    $password2 = $data['password2'];
    if ($password1 !== $password2) {
        $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
        $log->execute([$ip,$useragent,'reg','validation',0]);
        echo json_encode(['success' => false, 'message' => 'The passwords do not match']);
        exit;
    }
    if (strlen($password1)<8) {
        $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
        $log->execute([$ip,$useragent,'reg','length',0]);
        echo json_encode(['success' => false, 'message' => 'The password is too short']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
        $log->execute([$ip,$useragent,'reg','email',0]);
        echo json_encode(['success' => false, 'message' => 'Incorrect email format']);
        exit;
    }

    $getuser = $pdo->prepare("SELECT username,email FROM users WHERE username = ? OR email = ?");
    $getuser->execute([$username, $email]);
    if ($getuser->rowCount() > 0) {
        $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
        $log->execute([$ip,$useragent,'reg','already_exists',0]);
        echo json_encode(["success" => false, "message" => "Username or email already exists"]);
        exit;
    }
    $pass_hash = password_hash($password1, PASSWORD_DEFAULT);
    $insertuser = $pdo->prepare("INSERT INTO users (username, email, pass_hash) VALUES (?, ?, ?)");
    $insertuser -> execute([$username, $email, $pass_hash]);
    $uid = $pdo->lastInsertId();
    $userlogin = $pdo->prepare("INSERT INTO user_logs (uid,ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?,?)");
    $userlogin->execute([$uid,$ip,$useragent,'reg',null,1]);

    echo json_encode(["success" => true, "message" => "Registration successful"]);
} 
catch (EXCEPTION $e) {
    echo json_encode(['success' => false, 'message' => 'Error, try again LATER (later is not 5 seconds from now btw)']);
    exit;
}