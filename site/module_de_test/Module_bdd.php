<!-- Module_bdd.php -->


<p><a href="indexModule.html"><input type="submit" value="Retour"></a></p>



<?php

// Importation du fichier de configuration de la base de données
require_once("../database/database.php");

// Fonctions pour afficher du texte en couleur
function colorize($text, $color) {
    $colors = [
        'red'    => "<span style='color: red;'>",
        'green'  => "<span style='color: green;'>",
        'reset'  => "</span>"
    ];
    return $colors[$color] . $text . $colors['reset'];
}

// Vérification de la connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Affichage d'un message en vert si la connexion est établie avec succès
    echo colorize("La connexion à la base de données est établie avec succès.\n", 'green');
} catch (PDOException $e) {
    // Affichage d'un message en rouge en cas d'échec de la connexion
    echo colorize("Erreur lors de la connexion à la base de données: " . $e->getMessage() . "\n", 'red');
}
?>
