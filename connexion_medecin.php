<?php
session_start();

if (isset($_SESSION['medecin_id'])) {
    header('Location: medecin_dashboard.php');
    exit;
}

include 'function.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $conn = getConnection();

        $sql = "SELECT * FROM Médecin WHERE emailMéd = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $medecin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($medecin && $password === $medecin['motPasseMéd']) {
            $_SESSION['medecin_id'] = $medecin['idMédecin'];
            $_SESSION['medecin_nom'] = $medecin['nomMéd'];
            header('Location: medecin_dashboard.php');
            exit;
        } else {
            $error = "Email ou mot de passe incorrect.";
        }

        $conn = null; // Fermer la connexion
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Médecin - Gestion des Urgences</title>
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
                    <li><a href="connexion_medecin.php">Connexion Médecin</a></li>
                    <li><a href="admin.php">Interface Admin</a></li>
                    <li><a href="about.php">À Propos</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Connexion Médecin</h2>
            <form method="post" action="connexion_medecin.php" class="form-common">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required><br>
                <input type="submit" value="Se Connecter">
            </form>
            <?php if (!empty($error)): ?>
                <p><?= htmlspecialchars($error); ?></p>
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
