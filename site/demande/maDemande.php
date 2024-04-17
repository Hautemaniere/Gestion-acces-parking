<!-- maDemande.php -->


<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

require_once '../classe/user.php';
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
    $status = "Traitement en cours";
    // Récupérer l'id de l'utilisateur connecté
    $id_user = $user->getId();
    // Date actuelle
    $date = date('Y-m-d H:i:s');

    // Préparation de la requête SQL
    $stmt = $pdo->prepare("INSERT INTO Demande_Vehicule (nom, prenom, mail, cartegrise, statut, immatriculation, iduser, date) 
                            VALUES (:nom, :prenom, :email, :cartegrise, :statut, :immatriculation, :iduser, :date)");
    // Liaison des paramètres de la requête
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':cartegrise', $image); // Changer pour correspondre à votre base de données
    $stmt->bindParam(':statut', $status);
    $stmt->bindParam(':immatriculation', $immatriculation);
    $stmt->bindParam(':iduser', $id_user);
    $stmt->bindParam(':date', $date);

    // Exécution de la requête
    $stmt->execute();

    // Redirection après l'insertion (à adapter selon vos besoins)
    header("Location: ../Compte/maPage.php");
    exit();
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
    <input type="text" id="nom" name="nom" placeholder="ex : Gabriel" required><br><br>

    <label for="prenom">Prénom:</label><br>
    <input type="text" id="prenom" name="prenom" placeholder="ex : Martin" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" placeholder="ex : user@gmail.com" required><br><br>

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