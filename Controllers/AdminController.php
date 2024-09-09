<?php

namespace App\Controllers;

use App\Models\ArticleManager;
use App\Models\UserManager;
use App\Models\CommentManager;
use App\Services\Utils;
use App\Views\View;

class AdminController
{
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


	private function checkIfUserIsConnected(): void
	{
		if (!isset($_SESSION['user'])) {
			Utils::redirect("connectionForm");
		}
	}

	public function displayConnectionForm(): void
	{
		$view = new View("Connexion");
		$view->render("connectionForm");
	}

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

	public function disconnectUser(): void
	{
		unset($_SESSION['user']);
		Utils::redirect("home");
	}

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

	public function deleteArticle(): void
	{
		$this->checkIfUserIsConnected();

		$id = Utils::request("id", -1);

		$articleManager = new ArticleManager();
		$articleManager->deleteArticle($id);

		Utils::redirect("admin");
	}

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
