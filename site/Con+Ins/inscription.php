<!-- inscription.php -->


<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once("../database/database.php");
require_once("../classe/user.php");

if (isset($_POST['inscription'])) {
    // Récupérer les données du formulaire
    $login = $_POST['login'];
    $password = $_POST['password'];
    $type = $_POST['type']; // Assurez-vous de récupérer le type d'utilisateur correctement
    $mail = $_POST['mail'];

    // Insérer un nouvel utilisateur dans la base de données
    $stmt = $pdo->prepare('INSERT INTO `User` (`login`, `password`, `type`, `mail`) VALUES (?, ?, ?, ?)');
    $stmt->execute([$login, $password, 'demandeur', $mail]);

    // Rediriger vers la page de connexion ou afficher un message de succès
    header("Location: connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" type="text/css" href="../style/styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'inscription</title>
</head>

<body>

<div class="form-container">
    <h1 class="form-title">Inscrivez-vous</h1>
    <form action="" method="post" class="form">
        <input type="text" name="login" placeholder="Nom d utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="email" name="mail" placeholder="Adresse e-mail" required>
        <input type="submit" name="inscription" value="S inscrire">
    </form>
    <div class="form-separator">
        <strong>OU</strong>
        <hr class="separator">
    </div>
    <div class="signin-message">
        <span>Déjà un compte ?</span>
        <a href="connexion.php">Connectez-vous.</a>
    </div>
</div>


    </div>

</body>

</html>