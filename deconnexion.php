<?php
session_start();
session_unset();
session_destroy();
header('Location: connexion_medecin.php');
exit;
?>
