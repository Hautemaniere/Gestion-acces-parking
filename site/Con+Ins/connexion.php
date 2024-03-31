    <!-- connexion.php -->


    <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);


    require_once("../database/database.php");
    require_once("../classe/user.php");

    if (isset($_POST['connexion'])) {
        $login = $_POST['nom'];
        $password = $_POST['mot_de_passe'];

        // Vérification des informations de connexion dans la base de données
        $stmt = $pdo->prepare('SELECT * FROM `User` WHERE `login` = :login AND `password` = :password');
        $stmt->execute(['login' => $login, 'password' => $password]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Utilisateur trouvé, connectez-vous ou effectuez d'autres actions nécessaires
            // Par exemple, démarrer une session
            session_start();
            $_SESSION['user'] = new User($user['id'], $user['login'], $user['password'], $user['type'], $user['mail']);
            // Rediriger vers une page d'accueil ou autre
            header("Location: ../Compte/maPage.php ");
            exit();
        } else {
            // Afficher un message d'erreur si les informations de connexion sont incorrectes
            $error_message = "Mauvais nom d'utilisateur ou mot de passe.";
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <link rel="stylesheet" type="text/css" href="../style/styles.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Page de connexion</title>
    </head>

    <body>
        <div class="form-container">
            <h1 class="form-title">Connectez-vous</h1>
            <form action="" method="post" class="form">
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
                <input type="submit" name="connexion" value="Envoyer">
                <?php if (isset($error_message)) { ?>
                    <p class="error-message"><?php echo $error_message; ?></p>
                <?php } ?>
            </form>
            <div class="form-separator">
                <strong>OU</strong>
                <hr class="separator">
            </div>
            <div class="signin-message">
                <span>Pas encore de compte ?</span>
                <a href="inscription.php">Inscrivez-vous.</a>
            </div>
        </div>

    </body>

    </html>
