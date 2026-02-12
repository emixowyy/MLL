<?php

header('Content-Type: application/json');
require 'db.php';
//Data consists of: 'username', 'email' and 'password'
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['username'], $data['email'], $data['password'])) {
    echo json_encode(["success" => false, "message" => "Niepoprawne dane JSON"]);
    exit;
}

$username = $data['username'];
$email = $data['email'];
$password = $data['password'];

$pass_hash = password_hash($password, PASSWORD_DEFAULT);

$getuser = $pdo->prepare("SELECT username,email FROM users WHERE username = ? OR email = ?");
$getuser->execute([$username, $email]);
if ($getuser->rowCount() > 0) {
    echo json_encode(["success" => false, "message" => "Nazwa użytkownika lub email już istnieje"]);
    exit;
}

$insertuser = $pdo->prepare("INSERT INTO users (username, email, pass_hash) VALUES (?, ?, ?)");
$insertuser -> execute([$username, $email, $pass_hash]);

echo json_encode(["success" => true, "message" => "Użytkownik zarejestrowany pomyślnie"]);