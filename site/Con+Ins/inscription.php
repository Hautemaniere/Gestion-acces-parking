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

    // Vérifier si l'utilisateur est déjà inscrit
    $stmt = $pdo->prepare('SELECT * FROM `User` WHERE `login` = ? OR `mail` = ?');
    $stmt->execute([$login, $mail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Utilisateur déjà inscrit, rediriger vers la page de connexion avec un message
        header("Location: connexion.php?message=Utilisateur déjà inscrit. Veuillez vous connecter.");
        exit();
    } else {
        // Insérer un nouvel utilisateur dans la base de données
        $stmt = $pdo->prepare('INSERT INTO `User` (`login`, `password`, `type`, `mail`) VALUES (?, ?, ?, ?)');
        $stmt->execute([$login, $password, 'demandeur', $mail]);

        // Rediriger vers la page de connexion ou afficher un message de succès
        header("Location: connexion.php?message=Inscription réussie. Veuillez vous connecter.");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Demande d'accès à La Providence Amiens</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap Icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- SimpleLightbox plugin CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../styleFormulaire/styles.css">
</head>

<body id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="../index.html">Barriere</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <li class="nav-item"><a class="nav-link" href="../index.html">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.html#about">A propos</a></li>
                    <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Masthead-->
    <header class="masthead">
        <div class="form-container">
            <h1 class="form-title">Inscrivez-vous</h1>
            <form action="" method="post" class="form">
                <input type="text" name="login" placeholder="Nom d'utilisateur" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="email" name="mail" placeholder="Adresse e-mail" required>
                <input type="submit" name="inscription" value="S'inscrire">
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
    </header>

    <!-- Footer-->
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SimpleLightbox plugin JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
    <!-- Core theme JS-->
    <script src="../js/scripts.js"></script>
    <!-- SB Forms JS-->
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>

</html>
