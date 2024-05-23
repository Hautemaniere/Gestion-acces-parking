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
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
      <meta name="description" content="" />
      <meta name="author" content="" />
      <title>Creative - Start Bootstrap Theme</title>
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
                       <li class="nav-item"><a class="nav-link" href="inscription.php">Inscription</a></li>
                       <li class="nav-item"><a class="nav-link" href="../index.html#about">A propos</a></li>

                  </ul>
              </div>
          </div>
      </nav>
      <!-- Masthead-->
      <header class="masthead">
          <div class="form-container"> 
              <h1 class="form-title">Connectez-vous</h1>
              <form action="" method="post" class="form">
                  <input type="text" name="nom" placeholder="Nom" required>
                  <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
                  <input type="submit" name="connexion" value="Se connecter">
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

      </header>


      <!-- Footer-->
      <!-- Bootstrap core JS-->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
      <!-- SimpleLightbox plugin JS-->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
      <!-- Core theme JS-->
      <script src="../js/scripts.js"></script>
      <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
      <!-- * *                               SB Forms JS                               * *-->
      <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
      <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
      <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
  </body>

  </html>