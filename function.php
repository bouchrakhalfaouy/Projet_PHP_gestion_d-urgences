<?php
function getConnection() {
    $dsn = 'mysql:host=localhost;dbname=urgenceBD;charset=utf8';
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Échec de connexion: " . $e->getMessage());
    }

    return $conn;
}
?>
