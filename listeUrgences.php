<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Urgences - Gestion des Urgences</title>
    <link rel="stylesheet" href="style.css">
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
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Liste des Urgences</h2>
            <button onclick="printPage()">Imprimer</button>
            <?php
            $conn = getConnection();
            $sql = "SELECT * FROM Urgence";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $urgences = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($urgences) {
                echo "<table border='1'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Médecin ID</th>
                                <th>Patient ID</th>
                                <th>Symptôme</th>
                                <th>État</th>
                                <th>Date d'Entrée</th>
                                <th>Date de Sortie attendu</th>
                            </tr>
                        </thead>
                        <tbody>";
                foreach ($urgences as $urgence) {
                    echo "<tr>
                            <td>{$urgence['idUrgence']}</td>
                            <td>{$urgence['idMédecin']}</td>
                            <td>{$urgence['idPatient']}</td>
                            <td>{$urgence['symptôme']}</td>
                            <td>{$urgence['état']}</td>
                            <td>{$urgence['dateEntré']}</td>
                            <td>{$urgence['dateSortie']}</td>
                        </tr>";
                }
                echo "  </tbody>
                    </table>";
            } else {
                echo "<p>Aucune urgence trouvée.</p>";
            }
            $conn = null;
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
