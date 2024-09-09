<?php
/**
 * Ce fichier est le template principal qui "contient" ce qui aura été généré par les autres vues.
 *
 * Les variables qui doivent impérativement être définies sont :
 *      $title string : le titre de la page.
 *      $content string : le contenu de la page.
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emilie Forteroche</title>
    <link rel="stylesheet" href="./Css/style.css">
</head>

<body>
<header>
    <nav>
        <a href="index.php">Articles</a>
        <a href="index.php?action=apropos">À propos</a>
		<?php
		if (isset($_SESSION['user'])) {
			echo '<a href="index.php?action=admin">Espace admin</a>';
			echo '<a href="index.php?action=disconnectUser">Déconnexion</a>';
		} else {
			echo '<a href="index.php?action=connectionForm">Connexion</a>';
		}
		?>
    </nav>
    <h1>Emilie Forteroche</h1>
</header>

<main>
	<?= $content ?>
</main>

<footer>
    <p>Copyright © Emilie Forteroche 2023 - Openclassrooms - <a href="index.php?action=adminMonitoring">Admin</a></p>
</footer>

</body>
</html>
