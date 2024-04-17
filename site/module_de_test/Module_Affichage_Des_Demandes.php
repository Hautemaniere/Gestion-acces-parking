<!-- Module_Affichage_Des_Demandes.php -->


<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Inclusion des fichiers nécessaires
    require_once '../classe/user.php';
    require_once '../database/database.php';

    
    // Récupérer les demandes de véhicules
    $vehicule_demandes = getVehicleDemands();

    // Fonction pour récupérer l'utilisateur connecté (à adapter selon votre système de connexion)
    function getLoggedInUser() {}


    // Fonction pour récupérer les demandes de véhicules depuis la base de données
    function getVehicleDemands() {
        global $pdo; // Accès à la connexion PDO définie dans database.php

        $query = "SELECT * FROM Demande_Vehicule";
        $stmt = $pdo->query($query);
        $vehicule_demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $vehicule_demandes;
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


<h2>Toutes les demandes de véhicules dans la base de données :</h2>
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
