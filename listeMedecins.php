<?php
include 'function.php';

try {
    $conn = getConnection();

    // Requête SQL pour obtenir tous les médecins
    $sql = "SELECT * FROM Médecin";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $medecins = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Liste des Médecins - Gestion des Urgences</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
            <a href="index.html">
                    <img src="chu.jpg" alt="Logo CHU" class="logo-image">
                </a>
                <h1>Gestion des Urgences - Admin</h1>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.html">Accueil</a></li>
                    <li><a href="connexion_medecin.php">Connexion Médecin</a></li>
                    <li><a href="admin.php">Interface Admin</a></li>
                    <li><a href="about.php">À Propos</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Liste des Médecins</h2>
            <?php if ($medecins): ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Spécialité</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($medecins as $medecin): ?>
                            <tr>
                                <td><?= htmlspecialchars($medecin['idMédecin']) ?></td>
                                <td><?= htmlspecialchars($medecin['nomMéd']) ?></td>
                                <td><?= htmlspecialchars($medecin['spécialité']) ?></td>
                                <td><?= htmlspecialchars($medecin['emailMéd']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucun médecin trouvé.</p>
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
