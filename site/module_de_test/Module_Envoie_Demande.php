<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fichiers nécessaires
require_once '../classe/user.php';
require_once '../database/database.php';
require_once '../classe/demande.php';  // Assurez-vous que le chemin est correct

// Fonction pour récupérer l'utilisateur connecté (à adapter selon votre système de connexion)
function getLoggedInUser() {
    // Exemple de récupération d'utilisateur fictif pour démonstration
    return new User(28, 'Gabriel', 'password', 'Traitement en cours', 'gabmar@gmail.com');
}

// Fonction pour générer une plaque d'immatriculation aléatoire au format français
function generateLicensePlate() {
    $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $numbers = "0123456789";
    $plate = "";
    // Générer deux lettres aléatoires
    $plate .= $letters[rand(0, strlen($letters) - 1)];
    $plate .= $letters[rand(0, strlen($letters) - 1)];
    // Ajouter un tiret
    $plate .= "-";
    // Générer trois chiffres aléatoires
    for ($i = 0; $i < 3; $i++) {
        $plate .= $numbers[rand(0, strlen($numbers) - 1)];
    }
    // Ajouter un tiret
    $plate .= "-";
    // Générer deux lettres aléatoires
    $plate .= $letters[rand(0, strlen($letters) - 1)];
    $plate .= $letters[rand(0, strlen($letters) - 1)];
    return $plate;
}

// Fonction pour récupérer les demandes de véhicules depuis la base de données
function getVehicleDemands() {
    global $pdo; // Accès à la connexion PDO définie dans database.php

    $query = "SELECT * FROM Demande_Vehicule";
    $stmt = $pdo->query($query);
    $vehicule_demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $vehicule_demandes;
}

// Récupérer l'utilisateur connecté
$user = getLoggedInUser();

// Récupérer les demandes de véhicules
$vehicule_demandes = getVehicleDemands();

// Définir une variable pour stocker le message à afficher
$message = '';

// Gérer l'envoi de la demande lorsque le bouton est cliqué
if(isset($_POST['submit'])) {
    // Générer une plaque d'immatriculation aléatoire
    $immatriculation = generateLicensePlate();

    // Créer une nouvelle demande de véhicule
    $demande = new DemandeVehicule(
        $user->getLogin(),    // Utiliser le login comme nom
        $user->getLogin(),    // Utiliser le login comme prénom (vous pouvez ajuster selon vos besoins)
        $user->getMail(),
        $immatriculation,
        'image.jpg', // Vous pouvez adapter cette partie pour gérer le téléchargement d'images
        'Traitement en cours',
        $user->getId(),
        date('Y-m-d H:i:s') // Format de date pour l'insertion dans la base de données
    );

    // Préparer et exécuter la requête d'insertion
    $query = "INSERT INTO Demande_Vehicule (nom, prenom, mail, cartegrise, statut, immatriculation, iduser, date) 
              VALUES (:nom, :prenom, :mail, :cartegrise, :statut, :immatriculation, :iduser, :date)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':nom' => $demande->getNom(),
        ':prenom' => $demande->getPrenom(),
        ':mail' => $demande->getEmail(),
        ':cartegrise' => $demande->getImage(),
        ':statut' => $demande->getStatut(),
        ':immatriculation' => $demande->getImmatriculation(),
        ':iduser' => $demande->getIdUser(),
        ':date' => $demande->getDate()
    ]);

    // Mettre à jour le message
    $message = "La demande a été envoyée avec succès.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module de test Envoie de demande</title>
</head>
<body>
<p><a href="indexModule.html"><input type="submit" value="Retour"></a></p>

<h1>Envoyer une demande pré-remplie</h1>

<!-- Ajouter du code HTML pour afficher le message -->
<?php if (!empty($message)): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<!-- Formulaire d'envoi de demande -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="submit" name="submit" value="Envoyer une demande">
</form>

</body>
</html>
