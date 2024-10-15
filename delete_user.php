<?php
session_start();
include 'var_globale.php';

// Vérifie que l'utilisateur est admin
$userId = $_SESSION['user_id'];
$result = $mysqli->query("SELECT role FROM users WHERE id = '$userId'");
$user = $result->fetch_assoc();

if ($user['role'] != 'admin') {
    header('Location: wall.php');
    exit();
}

// Récupère l'ID de l'utilisateur à supprimer
if (isset($_POST['user_id'])) {
    $userIdToDelete = $_POST['user_id'];
    $query = "DELETE FROM users WHERE id = '$userIdToDelete'";
    
    if ($mysqli->query($query)) {
        // Redirige vers admin.php après la suppression
        header('Location: admin.php');
    } else {
        echo "Erreur : " . $mysqli->error;
    }
} else {
    // Si aucun user_id n'est passé, redirige vers la page admin
    header('Location: admin.php');
}
?>
