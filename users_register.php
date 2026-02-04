<?php

header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['username'], $data['email'], $data['password'])) {
    echo json_encode(["success" => false, "message" => "Niepoprawne dane JSON"]);
    exit;
}

$username = $data['username'];
$email = $data['email'];
$password = $data['password'];

$pass_hash = password_hash($password, PASSWORD_DEFAULT);

$insertuser = $pdo->prepare("INSERT INTO users (username, email, pass_hash) VALUES (?, ?, ?)");
$insertuser -> execute([$username, $email, $pass_hash]);

echo json_encode(["success" => true, "message" => "Użytkownik zarejestrowany pomyślnie"]);