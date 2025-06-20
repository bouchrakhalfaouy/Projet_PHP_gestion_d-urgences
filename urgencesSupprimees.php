<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urgences Supprimées</title>
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
                    <li><a href="urgencesSupprimees.php">Urgences Supprimées</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <!-- Formulaire de recherche -->
            <div class="search-container">
                <form method="get" action="">
                    <input type="text" id="nomPatient" name="nomPatient" placeholder="Entrez le nom du patient" value="<?php echo isset($_GET['nomPatient']) ? htmlspecialchars($_GET['nomPatient']) : ''; ?>">
                    <button type="submit">Rechercher</button>
                </form>
            </div>
            <br>
            <?php
            $file = 'urgencesSupprimees.json';
            $nomPatientFilter = isset($_GET['nomPatient']) ? $_GET['nomPatient'] : '';

            if (file_exists($file)) {
                $jsonData = file_get_contents($file);
                $urgencesSupprimees = json_decode($jsonData, true);

                if (!empty($urgencesSupprimees)) {
                    if ($nomPatientFilter) {
                        $urgencesSupprimees = array_filter($urgencesSupprimees, function($urgence) use ($nomPatientFilter) {
                            return stripos($urgence['nom'], $nomPatientFilter) !== false;
                        });
                    }

                    if (!empty($urgencesSupprimees)) {
                        echo '<table>';
                        echo '<tr><th>ID Urgence</th><th>ID Médecin</th><th>ID Patient</th><th>Nom Patient</th><th>Sexe Patient</th><th>Âge Patient</th><th>Email Patient</th><th>Téléphone Patient</th><th>Adresse Patient</th><th>Symptôme</th><th>État</th><th>Date d\'Entrée</th><th>Date de Sortie</th><th>Durée</th></tr>';
                        foreach ($urgencesSupprimees as $urgence) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($urgence['idUrgence']) . '</td>';
                            echo '<td>' . htmlspecialchars($urgence['idMédecin']) . '</td>';
                            echo '<td>' . htmlspecialchars($urgence['idPatient']) . '</td>';
                            echo '<td>' . htmlspecialchars($urgence['nom']) . '</td>';
                            echo '<td>' . htmlspecialchars($urgence['sexe']) . '</td>';
                            echo '<td>' . htmlspecialchars($urgence['âge']) . '</td>';
                            echo '<td>' . htmlspecialchars($urgence['email']) . '</td>';
                            echo '<td>' . htmlspecialchars($urgence['telephone']) . '</td>';
                            echo '<td>' . htmlspecialchars($urgence['adress']) . '</td>';
                            echo '<td>' . htmlspecialchars($urgence['symptôme']) . '</td>';
                            echo '<td>' . htmlspecialchars($urgence['état']) . '</td>';
                            echo '<td>' . htmlspecialchars($urgence['dateEntré']) . '</td>';
                            echo '<td>' . htmlspecialchars($urgence['dateSortie']) . '</td>';
                            echo '<td>' . htmlspecialchars($urgence['duree']) . '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    } else {
                        echo '<p>Aucune urgence supprimée n\'a été trouvée pour ce nom de patient.</p>';
                    }
                } else {
                    echo '<p>Aucune urgence supprimée n\'a été trouvée.</p>';
                }
            } else {
                echo '<p>Aucune urgence supprimée n\'a été trouvée.</p>';
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
