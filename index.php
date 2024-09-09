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

$router->add('home', ArticleController::class, 'showHome');
$router->add('apropos', ArticleController::class, 'showApropos');
$router->add('showArticle', ArticleController::class, 'showArticle');
$router->add('addComment', CommentController::class, 'addComment');
$router->add('deleteComment', AdminController::class, 'deleteComment');
$router->add('admin', AdminController::class, 'showAdminMonitoring');
$router->add('connectionForm', AdminController::class, 'displayConnectionForm');
$router->add('connectUser', AdminController::class, 'connectUser');
$router->add('disconnectUser', AdminController::class, 'disconnectUser');
$router->add('showUpdateArticleForm', AdminController::class, 'showUpdateArticleForm');
$router->add('updateArticle', AdminController::class, 'updateArticle');
$router->add('deleteArticle', AdminController::class, 'deleteArticle');

$action = Utils::request('action', 'home');

try {
	$router->dispatch($action);
} catch (Exception $e) {
	$errorView = new App\Views\View('Erreur');
	$errorView->render('errorPage', ['errorMessage' => $e->getMessage()]);
}