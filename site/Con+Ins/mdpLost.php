<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../database/database.php");
require_once("../classe/user.php");

$redirect = false;
$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email1 = $_POST['email1'];
    $email2 = $_POST['email2'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    if ($email1 == $email2 && $password1 == $password2) {
        // Vérifier que l'email existe dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM User WHERE mail = :email");
        $stmt->bindParam(':email', $email1);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            // Mettre à jour le mot de passe
            $hashedPassword = password_hash($password1, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE User SET password = :password WHERE mail = :email");
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $email1);
            $stmt->execute();
            $success_message = "Votre mot de passe a été réinitialisé avec succès.";
            $redirect = true;
        } else {
            $error_message = "Aucun utilisateur trouvé avec cet e-mail.";
        }
    } else {
        $error_message = "Les e-mails ou les mots de passe ne correspondent pas.";
    }
}

if ($redirect) {
    header("refresh:5;url=connexion.php");
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
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
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
                    <li class="nav-item"><a class="nav-link" href="inscription.php">Inscription</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Masthead-->
    <header class="masthead">
        <div class="form-container">
            <h1 class="form-title">Mot de passe oublié</h1>
            <form action="" method="post" class="form">
                <input type="email" name="email1" id="email1" placeholder="Email" required>
                <input type="email" name="email2" id="email2" placeholder="Confirmer Email" required>
                <input type="password" name="password1" id="password1" placeholder="Nouveau mot de passe" required>
                <input type="password" name="password2" id="password2" placeholder="Confirmer nouveau mot de passe" required>
                <?php if (!empty($error_message)) { ?>
                    <p class="error-message"><?php echo $error_message; ?></p>
                <?php } ?>
                <?php if (!empty($success_message)) { ?>
                    <p class="success-message"><?php echo $success_message; ?></p>
                <?php } ?>
                <input type="submit" value="Réinitialiser le mot de passe">
            </form>
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
