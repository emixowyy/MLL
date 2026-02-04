<?php
~
$host = 'sql2.7m.pl';
$dbname = 'mllemixowyy_mylittlelibrary';
$user = 'mllemixowyy_mylittlelibrary';
$pass = 'Lubiemiesoja2';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['Error' => 'Błąd połączenia z bazą']);
    exit;
}