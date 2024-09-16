<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'config/config.php';
require_once 'config/autoload.php';
require_once 'Router.php';

use App\Router;
use App\Controllers\ArticleController;
use App\Controllers\AdminController;
use App\Controllers\CommentController;
use App\Services\Utils;

$router = new Router();

// Définir les routes disponibles dans l'application
$router->add('home', ArticleController::class, 'showHome'); // Affiche la page d'accueil
$router->add('apropos', ArticleController::class, 'showApropos'); // Affiche la page "à propos"
$router->add('showArticle', ArticleController::class, 'showArticle'); // Affiche le détail d'un article
$router->add('addComment', CommentController::class, 'addComment'); // Ajoute un commentaire à un article
$router->add('deleteComment', AdminController::class, 'deleteComment'); // Supprime un commentaire (admin)
$router->add('admin', AdminController::class, 'showAdminPanel'); // Affiche le tableau de gestion des articles pour l'admin
$router->add('adminMonitoring', AdminController::class, 'showAdminMonitoring'); // Affiche le monitoring des articles pour l'admin
$router->add('connectionForm', AdminController::class, 'displayConnectionForm'); // Affiche le formulaire de connexion
$router->add('connectUser', AdminController::class, 'connectUser'); // Gère la connexion de l'utilisateur
$router->add('disconnectUser', AdminController::class, 'disconnectUser'); // Gère la déconnexion de l'utilisateur
$router->add('showUpdateArticleForm', AdminController::class, 'showUpdateArticleForm'); // Affiche le formulaire pour modifier ou ajouter un article
$router->add('updateArticle', AdminController::class, 'updateArticle'); // Gère la mise à jour ou l'ajout d'un article
$router->add('deleteArticle', AdminController::class, 'deleteArticle'); // Supprime un article

// Récupère l'action demandée par l'utilisateur, par défaut l'action est 'home'
$action = Utils::request('action', 'home');

try {
	$router->dispatch($action);
} catch (Exception $e) {
	$errorView = new App\Views\View('Erreur');
	$errorView->render('errorPage', ['errorMessage' => $e->getMessage()]);
}
