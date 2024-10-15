<?php
include 'var_globale.php';

// Vérification de la connexion et des permissions
redirect_login();

echo $head;

if ($mysqli->connect_errno) {
    echo "Échec de la connexion : " . $mysqli->connect_error;
    exit();
}

// Vérification si l'utilisateur est un administrateur
$sqlAdminCheck = "SELECT role FROM users WHERE id = ?";
$stmt = $mysqli->prepare($sqlAdminCheck);
$stmt->bind_param("i", $_SESSION['connected_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || $user['role'] !== 'admin') {
    // Si l'utilisateur n'est pas admin, redirection vers la page d'accueil ou autre page protégée
    echo "Vous n'avez pas les droits d'accès à cette page.";
    exit();
}
?>

<div id="wrapper" class="admin">
    <aside>
        <h2>Mots-clés</h2>
        <?php
        $laQuestionEnSql = "SELECT * FROM tags LIMIT 50";
        $lesInformations = $mysqli->query($laQuestionEnSql);

        if (!$lesInformations) {
            echo "Échec de la requête : " . $mysqli->error;
            exit();
        }

        while ($tag = $lesInformations->fetch_assoc()) {
            ?>
            <article>
                <h3><?php echo $tag['label']; ?></h3>
                <p><?php echo $tag['id']; ?></p>
                <nav>
                    <a href="tags.php?tag_id=<?php echo $tag['id']; ?>">Messages</a>
                </nav>
            </article>
        <?php } ?>
    </aside>

    <main>
        <h2>Utilisateurs</h2>
        <?php
        // Récupération de la liste des utilisateurs
        $laQuestionEnSql = "SELECT id, alias, role FROM users LIMIT 50";
        $lesInformations = $mysqli->query($laQuestionEnSql);

        if (!$lesInformations) {
            echo "Échec de la requête : " . $mysqli->error;
            exit();
        }

        while ($user = $lesInformations->fetch_assoc()) {
            ?>
            <article>
                <h3><a href="wall.php?user_id=<?php echo $user['id']; ?>"><?php echo $user['alias']; ?></a></h3>
                <p>Rôle: <?php echo $user['role'] == 'admin' ? 'Administrateur' : 'Utilisateur'; ?></p>
                <p>ID: <?php echo $user['id']; ?></p>

                <nav>
                    <a href="wall.php?user_id=<?php echo $user['id']; ?>">Mur</a>
                    <a href="feed.php?user_id=<?php echo $user['id']; ?>">Flux</a>
                    <a href="settings.php?user_id=<?php echo $user['id']; ?>">Paramètres</a>
                    <a href="followers.php?user_id=<?php echo $user['id']; ?>">Suiveurs</a>
                    <a href="subscriptions.php?user_id=<?php echo $user['id']; ?>">Abonnements</a>

                    <!-- Si l'utilisateur est un admin, on affiche un bouton pour supprimer l'utilisateur -->
                    <?php if ($user['role'] == 'admin') { ?>
                        <a href="admin.php?action=delete_user&id=<?php echo $user['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                    <?php } ?>
                </nav>
            </article>
        <?php } ?>

        <!-- Vérifier si l'utilisateur a appuyé sur le bouton pour supprimer un utilisateur -->
        <?php
        if (isset($_GET['action']) && $_GET['action'] === 'delete_user' && isset($_GET['id'])) {
            $userIdToDelete = intval($_GET['id']);

            // Supprime l'utilisateur de la base de données
            $deleteSql = "DELETE FROM users WHERE id = ?";
            $deleteStmt = $mysqli->prepare($deleteSql);
            $deleteStmt->bind_param("i", $userIdToDelete);

            if ($deleteStmt->execute()) {
                echo "<p>L'utilisateur a été supprimé avec succès.</p>";
            } else {
                echo "<p>Erreur lors de la suppression de l'utilisateur.</p>";
            }
        }
        ?>
    </main>
</div>

</body>
</html>
