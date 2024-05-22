<?php
require_once '../classe/user.php';

// Fonction pour afficher les résultats de test
function assertEqual($expected, $actual, $message = '') {
    if ($expected === $actual) {
        echo "<p style='color: green;'>PASS: $message</p>";
    } else {
        echo "<p style='color: red;'>FAIL: $message</p>";
        echo "<p>Expected: " . var_export($expected, true) . "</p>";
        echo "<p>Actual: " . var_export($actual, true) . "</p>";
    }
}

// Fonction pour afficher un en-tête de section
function printSectionHeader($section) {
    echo "<h2>$section</h2>";
}

// Test des getters
printSectionHeader('Test des Getters');
$user = new User(1, 'testuser', 'password123', 'admin', 'testuser@example.com');
assertEqual(1, $user->getId(), 'getId()');
assertEqual('testuser', $user->getLogin(), 'getLogin()');
assertEqual('password123', $user->getPassword(), 'getPassword()');
assertEqual('admin', $user->getType(), 'getType()');
assertEqual('testuser@example.com', $user->getMail(), 'getMail()');

// Test des setters
printSectionHeader('Test des Setters');
$user->setId(2);
assertEqual(2, $user->getId(), 'setId()');

$user->setLogin('newuser');
assertEqual('newuser', $user->getLogin(), 'setLogin()');

$user->setPassword('newpassword');
assertEqual('newpassword', $user->getPassword(), 'setPassword()');

$user->setType('user');
assertEqual('user', $user->getType(), 'setType()');

$user->setMail('newuser@example.com');
assertEqual('newuser@example.com', $user->getMail(), 'setMail()');

echo "<p>Tous les tests sont terminés.</p>";
?>
