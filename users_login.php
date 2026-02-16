<?php

header('Content-Type: application/json');
require 'db.php';
require_once 'auth.php';
//Data consists of: 'username' or 'email' and 'password'
$data = json_decode(file_get_contents("php://input"), true);
$ip = $_SERVER['REMOTE_ADDR'];
$useragent = $_SERVER['HTTP_USER_AGENT'];
if (!$data || !isset($data['password'])) {
    echo json_encode(["success" => false, "message" => "No data"]);
    exit;
}
$password = $data['password'];

if (isset($data['username'])) {
    $username = $data['username'];
    $getuser = $pdo->query("SELECT username, pass_hash, uid FROM users WHERE username = '$username'");
    $user = $getuser->fetch(PDO::FETCH_ASSOC);
    $uid = $user['uid'];
}
else if (isset($data['email'])) {
    $email = $data['email'];
    $getuser = $pdo->query("SELECT email, pass_hash, uid FROM users WHERE email = '$email'");
    $user = $getuser->fetchALL(PDO::FETCH_ASSOC);
    $uid = $user['uid'];
}
else {
    echo json_encode(["success" => false, "message" => "Incorrect data"]);
}

if (!$user || !password_verify($password, $user['pass_hash'])) {
    echo json_encode(["success" => false, "message" => "Password or username incorrect"]);
    $userloginin = $pdo->prepare("INSERT INTO user_logins (uid,ip_address,user_agent, success) VALUES (?,?,?,?)");
    $userloginin->execute([$uid,$ip,$useragent,0]);
    exit;
}
$sessiontoken = Auth::createSessionToken($uid, $pdo);
$refreshtoken = Auth::createRefreshToken($uid, $pdo);
setcookie('sessionToken', $sessiontoken, time() + 86400, '/', '', true, true);
setcookie('refreshToken', $refreshtoken, time() + 14*24*3600, '/', '', true, true);
$userloginin = $pdo->prepare("INSERT INTO user_logins (uid,ip_address,user_agent, success) VALUES (?,?,?,?)");
$userloginin->execute([$uid,$ip,$useragent,1]);
echo json_encode(["success" => true, "message" => "Login successful"]);