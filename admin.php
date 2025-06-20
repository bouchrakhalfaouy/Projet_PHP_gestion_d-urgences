<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Admin - Gestion des Urgences</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<?php include 'function.php'; ?>
    <?php
    // Fonction pour obtenir le nombre d'éléments dans une table
    function getCount($table) {
        $conn = getConnection();
        $sql = "SELECT COUNT(*) AS count FROM $table";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null; // Fermer la connexion

        return $result['count'];
    }

    // Fonction pour obtenir le nombre d'urgences actuelles
    function getCurrentEmergenciesCount() {
        $conn = getConnection();
        $sql = "SELECT COUNT(*) AS count FROM Urgence WHERE état = 'encore'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null; // Fermer la connexion

        return $result['count'];
    }
    ?>
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
                    <li><a href="deconnexionPatient.php">Déconnexion</a></li>
                </ul>
            </nav>
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <!-- Tableau de Bord -->
            <section class="dashboard">
                <h2>Tableau de Bord</h2>
                <div class="dashboard-cards">
                    <div class="card">
                        <h3>Nombre de Patients</h3>
                        <p><?php echo getCount('Patient'); ?></p>
                    </div>
                    <div class="card">
                        <h3>Urgences Actuelles</h3>
                        <p><?php echo getCurrentEmergenciesCount(); ?></p>
                    </div>
                    <div class="card">
                        <h3>Nombre de Médecins</h3>
                        <p><?php echo getCount('Médecin'); ?></p>
                    </div>
                </div>
            </section>

            <!-- Gestion des Patients -->
            <section class="manage-section">
                <h2>Gestion des Patients</h2>
                <a href="ajouterPatient.php" class="button">Ajouter un Patient</a><br>
                <a href="listePatients.php" class="button">Liste des Patients</a>
            </section>

            <!-- Gestion des Urgences -->
            <section class="manage-section">
                <h2>Gestion des Urgences</h2>
                <a href="ajouterUrgence.php" class="button">Ajouter une Urgence</a><br>
                
                <a href="listeUrgences.php" class="button">Liste des Urgences</a>
            </section>

            <!-- Gestion des Médecins -->
            <section class="manage-section">
                <h2>Gestion des Médecins</h2>
                <a href="ajouterMédecin.php" class="button">Ajouter un Médecin</a><br>
                <a href="listeMedecins.php" class="button">Liste des Médecins</a>
            </section>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Centre Hospitalier. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
