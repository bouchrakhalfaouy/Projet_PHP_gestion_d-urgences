<?php
include 'function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idType = $_POST['idType'];
    $id = $_POST['id'];

    // Connexion à la base de données
    $conn = getConnection();

    if ($idType === 'Medecin') {
        $stmt = $conn->prepare("SELECT nomMéd FROM Médecin WHERE idMédecin = ?");
    } elseif ($idType === 'Patient') {
        $stmt = $conn->prepare("SELECT nom FROM Patient WHERE idPatient = ?");
    } else {
        echo json_encode(['exists' => false]);
        exit;
    }

    $stmt->execute([$id]);
    $result = $stmt->fetch();

    if ($result) {
        $response = [
            'exists' => true,
            'nom' => $result['nom'] ?? $result['nomMéd']
        ];
    } else {
        $response = ['exists' => false];
    }

    echo json_encode($response);
    $conn = null;
}
?>
