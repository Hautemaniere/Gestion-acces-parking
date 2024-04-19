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
$stmt = $pdo->prepare("SELECT COUNT(*) AS demande_count FROM `Demande_Vehicule` WHERE `iduser`=:iduser");
$stmt->bindParam(':iduser', $user->getId());
$stmt->execute();
$demande_count = $stmt->fetch(PDO::FETCH_ASSOC)['demande_count'];

// Récupérer les demandes de véhicules de l'utilisateur connecté
$stmt = $pdo->prepare("SELECT * FROM `Demande_Vehicule` WHERE `iduser`=:iduser");
$stmt->bindParam(':iduser', $user->getId());
$stmt->execute();
$vehicule_demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma page</title>
</head>
<body>
    <h1>Bienvenue <?php echo $user->getLogin(); ?> sur votre compte personnel</h1>
    <p><strong>Nom d'utilisateur :</strong> <?php echo $user->getLogin(); ?></p>
    <p><strong>Email :</strong> <?php echo $user->getMail(); ?></p>
    <p><strong>Type :</strong> <?php echo $user->getType(); ?></p>

    <?php if ($demande_count < 3) : ?>
        <p>Faire votre <a href="../demande/maDemande.php">demande.</a></p>
    <?php else: ?>
        <p>Vous avez déjà effectué trois demandes.</p>
    <?php endif; ?>

    <h2>Vos demandes de véhicules :</h2>
    <p><table border="1">
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
                <td><?php echo $demande['nom']; ?></td>
                <td><?php echo $demande['prenom']; ?></td>
                <td><?php echo $demande['mail']; ?></td>
                <td><?php echo $demande['statut']; ?></td>
                <td><?php echo $demande['immatriculation']; ?></td>
                <td><?php echo $demande['date']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table></p>

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
</body>
</html>
