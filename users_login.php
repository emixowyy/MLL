<?php

header('Content-Type: application/json');
require 'db.php';
//Data consists of: 'username' or 'email' and 'password'
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
    echo json_encode(["success" => false, "message" => "Password or username incorrect"]);
    exit;
}

echo json_encode(["success" => true, "message" => "Login successful"]);