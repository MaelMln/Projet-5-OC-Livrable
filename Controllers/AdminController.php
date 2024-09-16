<?php

namespace App\Controllers;

use App\Models\ArticleManager;
use App\Models\UserManager;
use App\Models\CommentManager;
use App\Services\Utils;
use App\Views\View;

class AdminController
{
	/**
	 * Affiche le tableau de gestion des articles pour l'administrateur.
	 * @return void
	 */
	public function showAdminPanel(): void
	{
		$this->checkIfUserIsConnected();

		$articleManager = new ArticleManager();
		$articles = $articleManager->getAllArticles();

		$view = new View("Administration");
		$view->render("admin", ['articles' => $articles]);
	}

	/**
	 * Affiche le tableau de monitoring des articles avec des statistiques.
	 * @return void
	 */
	public function showAdminMonitoring(): void
	{
		$this->checkIfUserIsConnected();

		$sort = Utils::request('sort', 'date_creation');
		$order = Utils::request('order', 'asc');

		$articleManager = new ArticleManager();
		$articles = $articleManager->getArticlesForAdmin($sort, $order);

		$view = new View("Monitoring Admin");
		$view->render("adminMonitoring", ['articles' => $articles, 'sort' => $sort, 'order' => $order]);
	}

	/**
	 * Vérifie si un utilisateur est connecté.
	 * Redirige vers la page de connexion si non connecté.
	 * @return void
	 */
	private function checkIfUserIsConnected(): void
	{
		if (!isset($_SESSION['user'])) {
			Utils::redirect("connectionForm");
		}
	}

	/**
	 * Affiche le formulaire de connexion pour les utilisateurs.
	 * @return void
	 */
	public function displayConnectionForm(): void
	{
		$view = new View("Connexion");
		$view->render("connectionForm");
	}

	/**
	 * Gère la connexion d'un utilisateur.
	 * @return void
	 * @throws \Exception si les identifiants sont incorrects.
	 */
	public function connectUser(): void
	{
		$login = Utils::request("login");
		$password = Utils::request("password");

		if (empty($login) || empty($password)) {
			throw new \Exception("Tous les champs sont obligatoires.");
		}

		$userManager = new UserManager();
		$user = $userManager->getUserByLogin($login);
		if (!$user) {
			throw new \Exception("L'utilisateur demandé n'existe pas.");
		}

		if (!password_verify($password, $user->getPassword())) {
			throw new \Exception("Le mot de passe est incorrect.");
		}

		$_SESSION['user'] = $user;
		$_SESSION['idUser'] = $user->getId();

		Utils::redirect("admin");
	}

	/**
	 * Déconnecte l'utilisateur actuellement connecté.
	 * Redirige vers la page d'accueil après déconnexion.
	 * @return void
	 */
	public function disconnectUser(): void
	{
		unset($_SESSION['user']);
		Utils::redirect("home");
	}

	/**
	 * Affiche le formulaire pour modifier ou créer un article.
	 * @return void
	 */
	public function showUpdateArticleForm(): void
	{
		$this->checkIfUserIsConnected();

		$id = Utils::request("id", -1);

		$articleManager = new ArticleManager();
		$article = $articleManager->getArticleById($id);

		if (!$article) {
			$article = new \App\Models\Article();
		}

		$view = new View("Edition d'un article");
		$view->render("updateArticleForm", ['article' => $article]);
	}

	/**
	 * Met à jour ou ajoute un nouvel article.
	 * @return void
	 * @throws \Exception si des champs requis sont manquants.
	 */
	public function updateArticle(): void
	{
		$this->checkIfUserIsConnected();

		$id = Utils::request("id", -1);
		$title = Utils::request("title");
		$content = Utils::request("content");

		if (empty($title) || empty($content)) {
			throw new \Exception("Tous les champs sont obligatoires.");
		}

		$idUser = $_SESSION['idUser'] ?? null;
		if ($idUser === null) {
			throw new \Exception("L'utilisateur n'est pas connecté ou l'ID utilisateur est manquant.");
		}

		$article = new \App\Models\Article([
			'id' => $id,
			'title' => $title,
			'content' => $content,
			'id_user' => $idUser
		]);

		$articleManager = new ArticleManager();

		if ($id > 0) {
			$articleManager->updateArticle($article);
		} else {
			$articleManager->addArticle($article);
		}

		Utils::redirect("admin");
	}

	/**
	 * Supprime un article par son ID.
	 * Redirige vers la page d'administration après suppression.
	 * @return void
	 */
	public function deleteArticle(): void
	{
		$this->checkIfUserIsConnected();

		$id = Utils::request("id", -1);

		$articleManager = new ArticleManager();
		$articleManager->deleteArticle($id);

		Utils::redirect("admin");
	}

	/**
	 * Supprime un commentaire par son ID.
	 * Redirige vers l'article ou la page d'accueil après suppression.
	 * @return void
	 */
	public function deleteComment(): void
	{
		$this->checkIfUserIsConnected();

		$idComment = Utils::request('idComment', -1);

		if ($idComment > 0) {
			$commentManager = new CommentManager();
			$commentManager->deleteComment($idComment);
		}

		$idArticle = Utils::request('idArticle', -1);
		if ($idArticle > 0) {
			Utils::redirect("showArticle&id=" . $idArticle);
		} else {
			Utils::redirect("home");
		}
	}
}
