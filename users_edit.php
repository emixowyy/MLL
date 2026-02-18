<?php

header('Content-Type: application/json');
require 'db.php';
require_once 'token_verification.php';
try {
    authentication();
    //Data consists of: 'password' and 'param' and 'value'
    $data = json_decode(file_get_contents("php://input"), true);
    $ip = $_SERVER['REMOTE_ADDR'];
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    if (!$data || !isset($data['password'], $data['param'], $data['value'])) {
        $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
        $log->execute([$ip,$useragent,'edt','no_data',0]);
        echo json_encode(["success" => false, "message" => "No data"]);
        exit;
    }
    $password = $data['password'];
    $value = $data['value'];
    $getuserdata = $pdo->prepare("SELECT username,email,pass_hash FROM users WHERE uid = ?");
    $getuserdata->execute([$uid]);
    $userdata = $getuserdata->fetch(PDO::FETCH_ASSOC);
    $passhash = $userdata['pass_hash'];
    $oldusername = $userdata['username'];
    $oldemail = $userdata['email'];

    if (!password_verify($password, $passhash)) {
        $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
        $log->execute([$ip,$useragent,'edt','validation',0]);
        echo json_encode(['success' => false, 'message' => 'Password Incorrect']);
        exit;
    }

    if ($data['param'] === 'password') { 
        if (strlen($value) < 8) {
            $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
            $log->execute([$ip,$useragent,'edt','length',0]);
            echo json_encode(['success' => false, 'message' => 'The password is too short']);
            exit;
        }
        $new_password = password_hash($value, PASSWORD_BCRYPT);
        $edituser = $pdo->prepare("UPDATE users SET pass_hash = ? WHERE uid = ?");
        $edituser -> execute([$new_password, $uid]);
        $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
        $log->execute([$ip,$useragent,'edt',null,1]);
        echo json_encode(["success" => true, "message" => "Password changed successfully"]);
    }
    elseif ($data['param'] === 'email') {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
            $log->execute([$ip,$useragent,'edt','email',0]);
            echo json_encode(['success' => false, 'message' => 'Incorrect email format']);
            exit;
        }
        if ($value === $oldemail) {
            $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
            $log->execute([$ip,$useragent,'edt','email',0]);
            echo json_encode(['success' => false, 'message' => 'That is your current email']);
            exit; 
        }
        $getdoesemailexist = $pdo->prepare("SELECT email FROM users WHERE email = ?");
        $getdoesemailexist->execute([$value]);
        $doesemailexist = $getdoesemailexist->fetch(PDO::FETCH_ASSOC);
        if ($doesemailexist) {
            $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
            $log->execute([$ip,$useragent,'edt','email',0]);
            echo json_encode(['success' => false, 'message' => 'Email already in use']);
            exit; 
        }
        $edituser = $pdo->prepare("UPDATE users SET email = ? WHERE uid = ?");
        $edituser -> execute([$value,$uid]);
        $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
        $log->execute([$ip,$useragent,'edt',null,1]);
        echo json_encode(["success" => true, "message" => "Email changed successfully"]);
    }
    elseif ($data['param'] === 'username') {
        if ($value === $oldusername) {
            $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
            $log->execute([$ip,$useragent,'edt','username',0]);
            echo json_encode(['success' => false, 'message' => 'This is your current username']);
            exit;
        }
        $getdoesusernameexist = $pdo->prepare("SELECT username FROM users WHERE username = ?");
        $getdoesusernameexist->execute([$value]);
        $doesusernameexist = $getdoesusernameexist->fetch(PDO::FETCH_ASSOC);
        if ($doesusernameexist) {
            $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
            $log->execute([$ip,$useragent,'edt','username',0]);
            echo json_encode(['success' => false, 'message' => 'Username already in use']);
            exit; 
        }
        $edituser = $pdo->prepare("UPDATE users SET username = ? WHERE uid = ?");
        $edituser -> execute([$value,$uid]);
        $log = $pdo->prepare("INSERT INTO user_logs (ip_address,user_agent,action,fail_reason,success) VALUES (?,?,?,?,?)");
        $log->execute([$ip,$useragent,'edt',null,1]);
        echo json_encode(["success" => true, "message" => "Username changed successfully"]);
    }
}
catch (EXCEPTION $e) {
    echo json_encode(['success' => false, 'message' => 'Error, try again LATER (later is not 5 seconds from now btw)']);
    exit;
}