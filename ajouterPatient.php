<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Patient</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
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
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
        <div class="form-title">
            <h2>Ajouter un Patient</h2>
        </div>
            <form action="ajouterPatient.php" method="post" class="form-common">
    <label for="nom">Nom:</label>
    <input type="text" id="nom" name="nom" required><br>

    <label for="age">Âge:</label>
    <input type="number" id="age" name="age" required><br>

    <label for="sexe">Sexe:</label>
    <select id="sexe" name="sexe" required>
        <option value="Homme">Homme</option>
        <option value="Femme">Femme</option>
    </select><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="telephone">Téléphone:</label>
    <input type="tel" id="telephone" name="telephone" required><br>

    <label for="adresse">Adresse:</label>
    <textarea id="adresse" name="adresse" required></textarea><br>

    <input type="submit" value="Ajouter le Patient">
    <input type="reset" value="Annuler">
</form>


        </div>
    </main>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include 'function.php';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST['nom'];
        $age = $_POST['age'];
        $sexe = $_POST['sexe'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $adresse = $_POST['adresse'];
    
        try {
            $conn = getConnection();
            $sql = "INSERT INTO Patient (nom, âge, sexe, email, telephone, adress) VALUES (:nom, :age, :sexe, :email, :telephone, :adresse)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':age', $age);
            $stmt->bindParam(':sexe', $sexe);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':adresse', $adresse);
            $stmt->execute();
            echo "Patient ajouté avec succès!";
        } catch(PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
        $conn = null; // Close connection
    }
    ?>    

    <footer>
        <div class="container">
            <p>&copy; 2024 Centre Hospitalier. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
