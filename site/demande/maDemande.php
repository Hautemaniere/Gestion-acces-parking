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
function getLoggedInUser() {
    // Vérifier si l'utilisateur est connecté
    if(isset($_SESSION['user'])) {
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
    $image = file_get_contents ($_FILES['image']['tmp_name']);
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Demande</title>
    <!-- Lien vers votre fichier CSS -->
    <link rel="stylesheet" href="style/style.css">
</head>
<body>

<h1>Formulaire de demande de véhicule</h1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <label for="nom">Nom:</label><br>
    <input type="text" id="nom" name="nom" placeholder="ex : Martin" required ><br><br>

    <label for="prenom">Prénom:</label><br>
    <input type="text" id="prenom" name="prenom" placeholder="ex : Gabriel" required ><br><br>

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

</body>
</html>
