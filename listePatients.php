<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Patients - Gestion des Urgences</title>
    <link rel="stylesheet" href="style.css">
    </style>
</head>
<body>
    <?php include 'function.php'; ?>
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
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Liste des Patients</h2>
            <button onclick="printPage()">Imprimer</button>
            <?php
            // Connexion à la base de données
            $conn = getConnection();

            // Requête SQL pour obtenir tous les patients
            $sql = "SELECT * FROM Patient";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Affichage des données
            if ($patients) {
                echo "<table border='1'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Âge</th>
                                <th>Sexe</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Adresse</th>
                            </tr>
                        </thead>
                        <tbody>";
                foreach ($patients as $patient) {
                    echo "<tr>
                            <td>{$patient['idPatient']}</td>
                            <td>{$patient['nom']}</td>
                            <td>{$patient['âge']}</td>
                            <td>{$patient['sexe']}</td>
                            <td>{$patient['email']}</td>
                            <td>{$patient['telephone']}</td>
                            <td>{$patient['adress']}</td>
                        </tr>";
                }
                echo "  </tbody>
                    </table>";
            } else {
                echo "<p>Aucun patient trouvé.</p>";
            }
            ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Centre Hospitalier. Tous droits réservés.</p>
        </div>
    </footer>

    <script>
    function printPage() {
        window.print();
    }
    </script>
</body>
</html>
