<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Urgence - Gestion des Urgences</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function showErrors(errors) {
            if (errors.length > 0) {
                let message = "Erreur(s) :\n";
                errors.forEach(function(error) {
                    message += "- " + error + "\n";
                });
                alert(message);
            }
        }

        function checkId(idType, idValue) {
            const infoElement = document.getElementById(idType + 'Info');
            const statusElement = document.getElementById(idType + 'Status');

            if (idValue) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'verifieID.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.exists) {
                            infoElement.textContent = `Nom du ${idType}: ${response.nom}`;
                            statusElement.textContent = 'ID valide';
                            statusElement.style.color = 'green';
                        } else {
                            infoElement.textContent = '';
                            statusElement.textContent = 'ID invalide';
                            statusElement.style.color = 'red';
                        }
                    }
                };
                xhr.send(`idType=${idType}&id=${idValue}`);
            } else {
                infoElement.textContent = '';
                statusElement.textContent = '';
            }
        }

        function validateForm() {
            const dateEntré = document.getElementById('dateEntré').value;
            const dateSortie = document.getElementById('dateSortie').value;
            const errors = [];

            // Vérification que la date de sortie est après la date d'entrée
            if (dateEntré && dateSortie) {
                const dateEntréObj = new Date(dateEntré);
                const dateSortieObj = new Date(dateSortie);
                if (dateSortieObj <= dateEntréObj) {
                    errors.push("La date de sortie doit être supérieure à la date d'entrée.");
                }
            }

            // Affichage des erreurs si présentes
            if (errors.length > 0) {
                showErrors(errors);
                return false; // Empêche l'envoi du formulaire
            }

            return true; // Permet l'envoi du formulaire
        }
    </script>
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
            <div class="form-title">
                <h2>Ajouter une Urgence</h2>
            </div>
            <form action="" method="post" class="form-common" onsubmit="return validateForm()">
                <label for="idMedecin">Médecin ID:</label>
                <input type="number" id="idMedecin" name="idMedecin" oninput="checkId('Medecin', this.value)" required>
                <span id="MedecinStatus"></span><br>
                <div id="MedecinInfo"></div><br>

                <label for="idPatient">Patient ID:</label>
                <input type="number" id="idPatient" name="idPatient" oninput="checkId('Patient', this.value)" required>
                <span id="PatientStatus"></span><br>
                <div id="PatientInfo"></div><br>

                <label for="symptome">Symptôme:</label>
                <textarea id="symptome" name="symptome" required></textarea><br>

                <label for="etat">État:</label>
                <select id="etat" name="etat" required>
                    <option value="encore">Encore</option>
                    <option value="validé">Validé</option>
                    <option value="décédé">Décédé</option>
                </select><br>

                <label for="dateEntré">Date d'Entrée:</label>
                <input type="datetime-local" id="dateEntré" name="dateEntré" required><br>

                <label for="dateSortie">Date de Sortie:</label>
                <input type="datetime-local" id="dateSortie" name="dateSortie"><br>

                <input type="submit" value="Ajouter Urgence">
                <input type="reset" value="Annuler">
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $errors = [];
                $idMedecin = $_POST['idMedecin'];
                $idPatient = $_POST['idPatient'];
                $symptome = $_POST['symptome'];
                $etat = $_POST['etat'];
                $dateEntré = $_POST['dateEntré'];
                $dateSortie = $_POST['dateSortie'] ?? null;
                $dateAdmission = date('Y-m-d H:i:s'); // Date et heure actuelles pour la date d'admission

                // Vérifier que les IDs sont positifs
                if ($idMedecin <= 0) {
                    $errors[] = "L'ID du médecin doit être positif.";
                }
                if ($idPatient <= 0) {
                    $errors[] = "L'ID du patient doit être positif.";
                }

                // Vérifier que les IDs existent dans la base de données
                $conn = getConnection();
                $stmt = $conn->prepare("SELECT COUNT(*) FROM Médecin WHERE idMédecin = ?");
                $stmt->execute([$idMedecin]);
                if ($stmt->fetchColumn() == 0) {
                    $errors[] = "L'ID du médecin n'existe pas.";
                }

                $stmt = $conn->prepare("SELECT COUNT(*) FROM Patient WHERE idPatient = ?");
                $stmt->execute([$idPatient]);
                if ($stmt->fetchColumn() == 0) {
                    $errors[] = "L'ID du patient n'existe pas.";
                }

                // Vérification que la date de sortie est après la date d'entrée
                if ($dateEntré && $dateSortie) {
                    if ($dateSortie <= $dateEntré) {
                        $errors[] = "La date de sortie doit être supérieure à la date d'entrée.";
                    }
                }

                // Afficher les erreurs dans une fenêtre de dialogue si des erreurs existent
                if (empty($errors)) {
                    $sql = "INSERT INTO Urgence (idMédecin, idPatient, date_admission, symptôme, état, dateEntré, dateSortie) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$idMedecin, $idPatient, $dateAdmission, $symptome, $etat, $dateEntré, $dateSortie]);
                    echo "<p>Urgence ajoutée avec succès.</p>";
                } else {
                    echo "<script>window.onload = function() { showErrors(" . json_encode($errors) . "); }</script>";
                }

                $conn = null;
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
