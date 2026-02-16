<?php

header('Content-Type: application/json');
require 'db.php';
require_once 'token_verification.php';
authentication();
//Data consists of: 'username' or 'email', 'password', 'param' and 'new_password' or 'new_email' or 'new_username'
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['password'])) {
    echo json_encode(["success" => false, "message" => "No data"]);
    exit;
}
$password = $data['password'];

if (isset($data['username'])) {
    $username = $data['username'];
    $getuser = $pdo->query("SELECT username, pass_hash FROM users WHERE username = '$username'");
    $user = $getuser->fetchALL(PDO::FETCH_ASSOC);
    

}
else if (isset($data['email'])) {
    $email = $data['email'];
    $getuser = $pdo->query("SELECT email, pass_hash FROM users WHERE email = '$email'");
    $user = $getuser->fetchALL(PDO::FETCH_ASSOC);
}
else {
    echo json_encode(["success" => false, "message" => "Incorrect data"]);
}

if (!$user || !password_verify($password, $user[0]['pass_hash'])) {
    echo json_encode(["success" => false, "message" => "Username or password incorrect"]);
    exit;
}

if ($data['param'] == 'password') {
    $new_password = password_hash($data['new_password'], PASSWORD_BCRYPT);
    $edituser = $pdo->prepare("UPDATE users SET pass_hash = ? WHERE email = ? OR username = ?");
    $edituser -> execute([$new_password, $email ?? '', $username ?? '']);
    echo json_encode(["success" => true, "message" => "Password changed successfully"]);
}
else if ($data['param'] == 'email') {
    $new_email = $data['new_email'];
    $edituser = $pdo->prepare("UPDATE users SET email = ? WHERE email = ? OR username = ?");
    $edituser -> execute([$new_email, $email ?? '', $username ?? '']);
    echo json_encode(["success" => true, "message" => "Email changed successfully"]);
}
else if ($data['param'] == 'username') {
    $new_username = $data['new_username'];
    $edituser = $pdo->prepare("UPDATE users SET username = ? WHERE email = ? OR username = ?");
    $edituser -> execute([$new_username, $email ?? '', $username ?? '']);
    echo json_encode(["success" => true, "message" => "Username changed successfully"]);
}