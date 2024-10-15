<?php
// Connexion à la base de données
$host = 'localhost';  // Hôte
$user = 'root';       // Utilisateur de la base de données (à modifier si nécessaire)
$pass = '';           // Mot de passe de la base de données (à modifier si nécessaire)
$db = 'ma_base';      // Nom de la base de données (à modifier si nécessaire)

$mysqli = new mysqli($host, $user, $pass, $db);

// Vérification de la connexion
if ($mysqli->connect_error) {
    die("Échec de la connexion : " . $mysqli->connect_error);
}

// 1. Ajouter la colonne "role" dans la table "users"
$sqlAddRoleColumn = "ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'user';";
if ($mysqli->query($sqlAddRoleColumn) === TRUE) {
    echo "Colonne 'role' ajoutée avec succès à la table 'users'.<br>";
} else {
    echo "Erreur lors de l'ajout de la colonne 'role' : " . $mysqli->error . "<br>";
}

// 2. Mettre à jour le rôle d'un utilisateur (exemple avec l'ID 1)
$sqlUpdateRole = "UPDATE users SET role = 'admin' WHERE id = 1;";
if ($mysqli->query($sqlUpdateRole) === TRUE) {
    echo "Rôle mis à jour avec succès pour l'utilisateur avec ID 1.<br>";
} else {
    echo "Erreur lors de la mise à jour du rôle : " . $mysqli->error . "<br>";
}

// Fermeture de la connexion à la base de données
$mysqli->close();
?>
