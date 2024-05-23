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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma page</title>
</head>
<body>
    <h1>Bienvenue <?php echo htmlspecialchars($user->getLogin(), ENT_QUOTES, 'UTF-8'); ?> sur votre compte personnel</h1>
    <p><strong>Nom d'utilisateur :</strong> <?php echo htmlspecialchars($user->getLogin(), ENT_QUOTES, 'UTF-8'); ?></p>
    <p><strong>Email :</strong> <?php echo htmlspecialchars($user->getMail(), ENT_QUOTES, 'UTF-8'); ?></p>

    <?php
    // Compter le nombre de demandes
    $demande_count = count($vehicule_demandes);
    ?>

    <?php if ($demande_count < 3) : ?>
        <p>Faire votre <a href="../demande/maDemande.php">demande.</a></p>
    <?php else: ?>
        <p>Vous avez déjà effectué trois demandes.</p>
    <?php endif; ?>

    <h2>Vos demandes de véhicules :</h2>
    <table border="1">
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
