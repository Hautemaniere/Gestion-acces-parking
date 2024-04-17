<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Inclusion des fichiers nécessaires
    require_once '../classe/user.php';
    require_once '../database/database.php';

    
    // Fonction pour récupérer l'utilisateur connecté (à adapter selon votre système de connexion)
    function getLoggedInUser() {
        // Exemple de récupération d'utilisateur fictif pour démonstration
        return new User(28, 'Gabriel', 'martin', 'Traitement en cours', 'gabmar@gmail.com');
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

    // Gérer l'envoi de la demande lorsque le bouton est cliqué
    if(isset($_POST['submit'])) {
        // Générer une plaque d'immatriculation aléatoire
        $immatriculation = generateLicensePlate();
        // Exemple de requête pré-remplie
        $query = "INSERT INTO Demande_Vehicule (nom, prenom, mail, cartegrise, statut, immatriculation, iduser, date) 
                  VALUES ('Gabriel', 'Martin', 'gabmar@gmail.com', 'image.jpg', 'Traitement en cours', '{$immatriculation}', '{$user->getId()}', NOW())";

        // Exécuter la requête
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        // Redirection après l'insertion (à adapter selon vos besoins)
        header("Location: Module_Envoie_Demande.php");
        exit();
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

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="submit" name="submit" value="Envoyer une demande">
</form>

<h2>Vos demandes de véhicules :</h2>
<table border="1">
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Mail</th>
        <th>Statut</th>
        <th>Immatriculation</th>
        <th>Date</th>
    </tr>
    <?php foreach ($vehicule_demandes as $demande) : ?>
        <tr>
            <td><?php echo htmlspecialchars($demande['nom']); ?></td>
            <td><?php echo htmlspecialchars($demande['prenom']); ?></td>
            <td><?php echo htmlspecialchars($demande['mail']); ?></td>
            <td><?php echo htmlspecialchars($demande['statut']); ?></td>
            <td><?php echo htmlspecialchars($demande['immatriculation']); ?></td>
            <td><?php echo htmlspecialchars($demande['date']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
