<?php
session_start();

if (!isset($_SESSION['medecin_id'])) {
    header('Location: connexion_medecin.php');
    exit;
}

include 'function.php';

$medecin_id = $_SESSION['medecin_id'];
$medecin_nom = $_SESSION['medecin_nom'];

try {
    $conn = getConnection();

    $sql = "SELECT Urgence.idUrgence, Patient.nom, Urgence.symptôme, Urgence.date_admission, Urgence.état 
            FROM Urgence 
            JOIN Patient ON Urgence.idPatient = Patient.idPatient 
            WHERE Urgence.idMédecin = :medecin_id AND Urgence.état = 'encore'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':medecin_id', $medecin_id);
    $stmt->execute();
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $conn = null; // Fermer la connexion
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Médecin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="index.html">
                    <img src="chu.jpg" alt="Logo CHU" class="logo-image">
                </a>
                <h1>Gestion des Urgences</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.html">Accueil</a></li>
                    <li><a href="deconnexion.php">Déconnexion</a></li>
                    <li><a href="urgencesSupprimees.php">Urgences Supprimées</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Bienvenue Dr. <?= htmlspecialchars($medecin_nom); ?></h2>
            <h3>Liste des Patients en Urgence</h3>
            <?php if (count($patients) > 0): ?>
                <table>
                    <tr>
                        <th>Nom du Patient</th>
                        <th>Symptôme</th>
                        <th>Date d'Admission</th>
                        <th>État</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?= htmlspecialchars($patient['nom']); ?></td>
                            <td><?= htmlspecialchars($patient['symptôme']); ?></td>
                            <td><?= htmlspecialchars($patient['date_admission']); ?></td>
                            <td><?= htmlspecialchars($patient['état']); ?></td>
                            <td>
                                <a href="modifierUrgence.php?idUrgence=<?= htmlspecialchars($patient['idUrgence']); ?>">Modifier</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>Aucun patient en urgence.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Centre Hospitalier. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
