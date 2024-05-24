<!-- maPage.php -->

<?php
require_once("../database/database.php");
require_once("../classe/user.php");

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit();
}

$user = $_SESSION['user'];

// Récupérer les demandes de véhicules de l'utilisateur connecté
$stmt = $pdo->prepare("SELECT * FROM `Demande_Vehicule` WHERE `iduser`=:iduser");
$stmt->bindParam(':iduser', $user->getId());
$stmt->execute();
$vehicule_demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les messages liés aux demandes de véhicules
$demande_ids = array_column($vehicule_demandes, 'id'); // Récupérer les IDs des demandes
if (!empty($demande_ids)) {
    $placeholders = implode(',', array_fill(0, count($demande_ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM `Message` WHERE `id_demande` IN ($placeholders)");
    $stmt->execute($demande_ids);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Associer les messages aux demandes
    $messages_by_demande = [];
    foreach ($messages as $message) {
        $messages_by_demande[$message['id_demande']] = $message['contenu'];
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
    <title>Ma page</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap Icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic"
        rel="stylesheet" type="text/css" />
    <!-- SimpleLightbox plugin CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../css/styles.css" rel="stylesheet" />
</head>

<body id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="../index.html">Barriere</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <li class="nav-item"><a class="nav-link" href="../index.html">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.html#about">A propos</a></li>
                    <li class="nav-item"><a class="nav-link" href="../Con+Ins/inscription.php">Inscription</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Masthead-->
    <header class="masthead">
        <div class="container px-4 px-lg-5 h-100">
            <div class="Contenant">
        <h1>Bienvenue <?php echo htmlspecialchars($user->getLogin(), ENT_QUOTES, 'UTF-8'); ?> sur votre compte personnel</h1>
    <p><strong>Votre nom d'utilisateur :</strong> <?php echo htmlspecialchars($user->getLogin(), ENT_QUOTES, 'UTF-8'); ?></p>
    <p><strong>Votre adresse email :</strong> <?php echo htmlspecialchars($user->getMail(), ENT_QUOTES, 'UTF-8'); ?></p>

    <?php
    // Compter le nombre de demandes
    $demande_count = count($vehicule_demandes);
    ?>

    <?php if ($demande_count < 3) : ?>
        <p>Faire votre <a href="../demande/maDemande.php">demande.</a></p>
    <?php else : ?>
        <p>Vous avez déjà effectué trois demandes.</p>
    <?php endif; ?>

    <h2>Vos demandes de véhicules :</h2>
    <p>
    <table border="1" class="tableau">
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Mail</th>
            <th>Immatriculation</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Message</th>
        </tr>
        <?php foreach ($vehicule_demandes as $demande) : ?>
            <tr>
                <td><?php echo htmlspecialchars($demande['nom'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($demande['prenom'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($demande['mail'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($demande['immatriculation'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($demande['date'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($demande['statut'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo isset($messages_by_demande[$demande['id']]) ? htmlspecialchars($messages_by_demande[$demande['id']], ENT_QUOTES, 'UTF-8') : ''; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    </p>

    <!-- Lien pour se déconnecter -->
    <form action="" method="post">
        <input type="submit" name="deconnexion" value="Se déconnecter">
    </form>

    <?php
    // Gestion de la déconnexion
    if (isset($_POST['deconnexion'])) {
        // Détruire toutes les données de session
        session_unset();
        session_destroy();

        // Rediriger vers la page de connexion
        header("Location: ../Con+Ins/connexion.php");
        exit();
    }
    ?>
            </div>
        </div>
    </header>
    
    <!-- Footer-->
    <footer class="bg-light py-5">
        <div class="container px-4 px-lg-5">
            <div class="small text-center text-muted">Copyright &copy; 2023 - La Providence</div>
        </div>
    </footer>
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