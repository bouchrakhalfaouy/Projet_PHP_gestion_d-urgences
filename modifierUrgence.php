<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'function.php';

if (!isset($_GET['idUrgence'])) {
    echo "ID d'urgence manquant.";
    exit;
}

$idUrgence = $_GET['idUrgence'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nouvelEtat = $_POST['etat'];

    try {
        $conn = getConnection();

        // Mettre à jour l'état de l'urgence
        $sql = "UPDATE Urgence SET état = :etat WHERE idUrgence = :idUrgence";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':etat', $nouvelEtat);
        $stmt->bindParam(':idUrgence', $idUrgence);
        $stmt->execute();

        // Vérifier si l'état est 'validé' ou 'décédé'
        if ($nouvelEtat == 'validé' || $nouvelEtat == 'décédé') {
            // Mettre à jour la date de sortie à aujourd'hui
            $dateSortie = date('Y-m-d H:i:s');
            $sql = "UPDATE Urgence SET dateSortie = :dateSortie WHERE idUrgence = :idUrgence";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':dateSortie', $dateSortie);
            $stmt->bindParam(':idUrgence', $idUrgence);
            $stmt->execute();

            // Obtenir les informations de l'urgence et du patient associé
            $sql = "SELECT Urgence.*, Patient.nom, Patient.sexe, Patient.âge, Patient.email, Patient.telephone, Patient.adress
                    FROM Urgence 
                    INNER JOIN Patient ON Urgence.idPatient = Patient.idPatient 
                    WHERE Urgence.idUrgence = :idUrgence";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idUrgence', $idUrgence);
            $stmt->execute();
            $urgence = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($urgence) {
                // Calculer la durée passée dans l'urgence en jours, heures et minutes
                $dateEntree = new DateTime($urgence['dateEntré']);
                $dateSortie = new DateTime($urgence['dateSortie']);
                $interval = $dateEntree->diff($dateSortie);
                $duree = $interval->format('%a jours %h heures %i minutes');

                // Préparer les données à sauvegarder
                $urgenceData = [
                    'idUrgence' => htmlspecialchars($urgence['idUrgence']),
                    'idMédecin' => htmlspecialchars($urgence['idMédecin']),
                    'idPatient' => htmlspecialchars($urgence['idPatient']),
                    'nom' => htmlspecialchars($urgence['nom']),
                    'sexe' => htmlspecialchars($urgence['sexe']),
                    'âge' => htmlspecialchars($urgence['âge']),
                    'email' => htmlspecialchars($urgence['email']),
                    'telephone' => htmlspecialchars($urgence['telephone']),
                    'adresse' => htmlspecialchars($urgence['adress']),
                    'symptôme' => htmlspecialchars($urgence['symptôme']),
                    'état' => htmlspecialchars($nouvelEtat),
                    'dateEntré' => htmlspecialchars($urgence['dateEntré']),
                    'dateSortie' => htmlspecialchars($dateSortie->format('Y-m-d H:i:s')),
                    'duree' => htmlspecialchars($duree)
                ];

                // Lire les urgences supprimées existantes à partir du fichier JSON
                $file = 'urgencesSupprimees.json';
                if (file_exists($file)) {
                    $jsonData = file_get_contents($file);
                    $urgencesSupprimees = json_decode($jsonData, true);
                } else {
                    $urgencesSupprimees = [];
                }

                // Ajouter la nouvelle urgence supprimée
                $urgencesSupprimees[] = $urgenceData;

                // Sauvegarder les urgences supprimées dans le fichier JSON
                file_put_contents($file, json_encode($urgencesSupprimees, JSON_PRETTY_PRINT));

                // Supprimer le patient
                $idPatient = $urgence['idPatient'];
                $sql = "DELETE FROM Patient WHERE idPatient = :idPatient";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':idPatient', $idPatient);
                $stmt->execute();

                // Stocker les données dans une variable pour les afficher plus tard
                $result = $urgenceData;
            } else {
                $result = ['error' => 'Aucune urgence trouvée pour l\'ID fourni.'];
            }

            $conn = null; // Fermer la connexion
        } else {
            $result = ['error' => 'L\'état de l\'urgence ne permet pas d\'effectuer cette opération.'];
        }
    } catch (PDOException $e) {
        $result = ['error' => 'Erreur : ' . $e->getMessage()];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'État d'une Urgence</title>
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
            <div class="form-container">
                <h2>Modifier l'État d'une Urgence</h2>
                <form method="post" action="modifierUrgence.php?idUrgence=<?= htmlspecialchars($idUrgence); ?>" class="form-common">
                    <label for="etat">Nouvel État:</label>
                    <select id="etat" name="etat" required>
                        <option value="encore">Encore</option>
                        <option value="validé">Validé</option>
                        <option value="décédé">Décédé</option>
                    </select>
                    <input type="submit" value="Modifier">
                </form>
            </div>

            <?php if (isset($result)): ?>
                <?php if (isset($result['error'])): ?>
                    <p><?= htmlspecialchars($result['error']); ?></p>
                <?php else: ?>
                    <h2>Informations de l'Urgence Supprimée</h2>
                    <table>
                        <tr><th>ID Urgence</th><td><?= $result['idUrgence']; ?></td></tr>
                        <tr><th>ID Médecin</th><td><?= $result['idMédecin']; ?></td></tr>
                        <tr><th>ID Patient</th><td><?= $result['idPatient']; ?></td></tr>
                        <tr><th>Nom Patient</th><td><?= $result['nom']; ?></td></tr>
                        <tr><th>Sexe Patient</th><td><?= $result['sexe']; ?></td></tr>
                        <tr><th>Âge Patient</th><td><?= $result['âge']; ?></td></tr>
                        <tr><th>Email Patient</th><td><?= $result['email']; ?></td></tr>
                        <tr><th>Téléphone Patient</th><td><?= $result['telephone']; ?></td></tr>
                        <tr><th>Adresse Patient</th><td><?= $result['adresse']; ?></td></tr>
                        <tr><th>Symptôme</th><td><?= $result['symptôme']; ?></td></tr>
                        <tr><th>État</th><td><?= $result['état']; ?></td></tr>
                        <tr><th>Date d'Entrée</th><td><?= $result['dateEntré']; ?></td></tr>
                        <tr><th>Date de Sortie</th><td><?= $result['dateSortie']; ?></td></tr>
                        <tr><th>Durée</th><td><?= $result['duree']; ?></td></tr>
                    </table>
                <?php endif; ?>
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
