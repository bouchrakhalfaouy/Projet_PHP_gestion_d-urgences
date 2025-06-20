<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Médecin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'function.php'; ?>
    <header>
        <div class="container">
            <div class="logo">
               <a href="index.html">
                <img src="chu.jpg"  alt="Logo CHU" class="logo-image">
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
        <div class="form-title">
            <h2>Ajouter un Médecin</h2>
        </div>
            <form action="ajouterMédecin.php" method="post" class="form-common">
    <label for="nomMéd">Nom:</label>
    <input type="text" id="nomMéd" name="nomMéd" required><br>

    <label for="spécialité">Spécialité:</label>
    <input type="text" id="spécialité" name="spécialité" required><br>

    <label for="emailMéd">Email:</label>
    <input type="email" id="emailMéd" name="emailMéd" required><br>

    <label for="motPasse">Mot de Passe:</label>
    <input type="password" id="motPasse" name="motPasse" required><br>

    <input type="submit" value="Ajouter Médecin">
    <input type="reset" value="Annuler">
</form>



            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nomMéd = $_POST['nomMéd'];
                $spécialité = $_POST['spécialité'];
                $emailMéd = $_POST['emailMéd'];
                $motPasseMéd = $_POST['motPasse'];

                try {
                    $conn = getConnection();
                    $sql = "INSERT INTO Médecin (nomMéd, spécialité, emailMéd, motPasseMéd) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$nomMéd, $spécialité, $emailMéd, $motPasseMéd]);

                    echo "<p>Médecin ajouté avec succès.</p>";
                } catch (PDOException $e) {
                    echo "<p>Erreur : " . $e->getMessage() . "</p>";
                } finally {
                    $conn = null; // Fermer la connexion
                }
            }
            ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Centre Hospitalier. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
