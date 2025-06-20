<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer ou Modifier une Urgence - Gestion des Urgences</title>
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
            <h2>Modifier ou Supprimer une Urgence</h2>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $idUrgence = $_POST['idUrgence'];
                $nouvelEtat = $_POST['état'];

                $conn = getConnection();

                // Mettre à jour l'état de l'urgence
                $sqlUpdate = "UPDATE Urgence SET état = ? WHERE idUrgence = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->execute([$nouvelEtat, $idUrgence]);

                // Vérifier l'état après mise à jour
                if ($nouvelEtat === 'validé' || $nouvelEtat === 'décédé') {
                    // Supprimer le patient associé
                    $sql = "SELECT idPatient FROM Urgence WHERE idUrgence = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$idUrgence]);
                    $urgence = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($urgence) {
                        $idPatient = $urgence['idPatient'];

                        // Supprimer le patient
                        $sqlDeletePatient = "DELETE FROM Patient WHERE idPatient = ?";
                        $stmtDeletePatient = $conn->prepare($sqlDeletePatient);
                        $stmtDeletePatient->execute([$idPatient]);

                        // Supprimer l'urgence
                        $sqlDeleteUrgence = "DELETE FROM Urgence WHERE idUrgence = ?";
                        $stmtDeleteUrgence = $conn->prepare($sqlDeleteUrgence);
                        $stmtDeleteUrgence->execute([$idUrgence]);

                        echo "<p>Urgence et patient associés supprimés avec succès.</p>";
                    } else {
                        echo "<p>Aucune urgence trouvée avec cet ID.</p>";
                    }
                } else {
                    echo "<p>Urgence mise à jour avec succès. Aucun patient supprimé car l'état n'est ni 'validé' ni 'décédé'.</p>";
                }
                $conn = null;
            } elseif (isset($_GET['id'])) {
                $idUrgence = $_GET['id'];

                $conn = getConnection();

                // Obtenir les détails de l'urgence
                $sql = "SELECT * FROM Urgence WHERE idUrgence = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$idUrgence]);
                $urgence = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($urgence) {
                    echo '<form method="post" action="">
                            <input type="hidden" name="idUrgence" value="' . htmlspecialchars($idUrgence) . '">
                            <label for="état">État de l\'Urgence:</label>
                            <select id="état" name="état">
                                <option value="en attente" ' . ($urgence['état'] === 'en attente' ? 'selected' : '') . '>En Attente</option>
                                <option value="validé" ' . ($urgence['état'] === 'validé' ? 'selected' : '') . '>Validé</option>
                                <option value="décédé" ' . ($urgence['état'] === 'décédé' ? 'selected' : '') . '>Décédé</option>
                            </select>
                            <button type="submit">Mettre à Jour</button>
                          </form>';
                } else {
                    echo "<p>Aucune urgence trouvée avec cet ID.</p>";
                }

                $conn = null;
            } else {
                echo "<p>ID de l'urgence manquant.</p>";
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
