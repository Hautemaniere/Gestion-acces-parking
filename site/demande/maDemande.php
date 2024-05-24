<!-- maDemande.php -->


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../classe/user.php';
require_once '../classe/demande.php'; // Inclure la classe Vehicule
require_once '../database/database.php';

session_start();

// Fonction pour récupérer l'utilisateur connecté
function getLoggedInUser()
{
    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['user'])) {
        return $_SESSION['user'];
    } else {
        // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
        header("Location: ../Con+Ins/connexion.html");
        exit();
    }
}

// Récupérer l'utilisateur connecté
$user = getLoggedInUser();

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $immatriculation = $_POST['immatriculation'];
    // Traitement de l'image - vous devez gérer l'upload d'image
    $image = file_get_contents($_FILES['image']['tmp_name']);
    $status = $_POST['status']; // Utiliser le statut provenant du formulaire
    $id_user = $_POST['id_user']; // Utiliser l'ID utilisateur provenant du formulaire
    $date = $_POST['date']; // Utiliser la date provenant du formulaire

    // Créer un objet DemandeVehicule avec les données du formulaire
    $demandeVehicule = new DemandeVehicule($nom, $prenom, $email, $immatriculation, $image, $status, $id_user, $date);

    // Insérer la demande de véhicule dans la base de données (à adapter selon votre structure de base de données)
    // Supposons que $pdo est votre connexion à la base de données
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO Demande_Vehicule (nom, prenom, mail, cartegrise, statut, immatriculation, iduser, date) 
                            VALUES (:nom, :prenom, :email, :cartegrise, :statut, :immatriculation, :iduser, :date)");
        $stmt->execute([
            ':nom' => $demandeVehicule->getNom(),
            ':prenom' => $demandeVehicule->getPrenom(),
            ':email' => $demandeVehicule->getEmail(),
            ':cartegrise' => $demandeVehicule->getImage(),
            ':statut' => $demandeVehicule->getStatut(),
            ':immatriculation' => $demandeVehicule->getImmatriculation(),
            ':iduser' => $demandeVehicule->getIdUser(),
            ':date' => $demandeVehicule->getDate()
        ]);
        $pdo->commit();

        // Redirection après l'insertion (à adapter selon vos besoins)
        header("Location: ../Compte/maPage.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erreur : " . $e->getMessage();
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
    <title>Ma demande</title>
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
                    <li class="nav-item"><a class="nav-link" href="../Con+Ins/inscription.php">Inscription</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Masthead-->
    <header class="masthead">
        <div class="container px-4 px-lg-5 h-100">
            <div class="Contenant">
                <h1>Formulaire de demande de véhicule</h1>

                <p><a href="../Compte/maPage.php"><input type="submit" value="Retour"></a></p>


                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <label for="nom">Nom:</label><br>
                    <input type="text" id="nom" name="nom" placeholder="ex : Martin" required><br><br>

                    <label for="prenom">Prénom:</label><br>
                    <input type="text" id="prenom" name="prenom" placeholder="ex : Gabriel" required><br><br>

                    <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email" placeholder="ex : user@gmail.com" required value="<?php echo $user->getMail(); ?>"><br><br>

                    <label for="image">Image (JPG uniquement):</label><br>
                    <input type="file" id="image" name="image" accept=".jpg" required><br><br>

                    <label for="immatriculation">Immatriculation:</label><br>
                    <input type="text" id="immatriculation" name="immatriculation" placeholder="ex : AA-000-AA" required><br><br>

                    <!-- Champ de statut déjà pré-rempli -->
                    <input type="hidden" name="status" value="Traitement en cours">

                    <!-- Champ de l'ID utilisateur déjà pré-rempli -->
                    <input type="hidden" name="id_user" value="<?php echo $user->getId(); ?>">

                    <!-- Champ de date pré-rempli -->
                    <input type="hidden" name="date" value="<?php echo date('Y-m-d H:i:s'); ?>">

                    <input type="submit" value="Envoyer">
                </form>
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