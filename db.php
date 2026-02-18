<?php
~
$host = 'sql2.7m.pl';
$dbname = 'mllemixowyy_mylittlelibrary';
$user = 'mllemixowyy_mylittlelibrary';
$pass = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (EXCEPTION $e) {
    echo json_encode(['success' => false, 'message' => 'Error, try again LATER (later is not 5 seconds from now btw)']);
    exit;
}