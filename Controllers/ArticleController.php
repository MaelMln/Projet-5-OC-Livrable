<?php

namespace App\Controllers;

use App\Models\ArticleManager;
use App\Models\CommentManager;
use App\Services\Utils;
use App\Views\View;
use Exception;

class ArticleController
{
	/**
	 * Affiche la page d'accueil.
	 * @return void
	 */
	public function showHome(): void
	{
		$articleManager = new ArticleManager();
		$articles = $articleManager->getAllArticles();

		$view = new View("Accueil");
		$view->render("home", ['articles' => $articles]);
	}

	/**
	 * Affiche le détail d'un article.
	 * @return void
	 */
	public function showArticle(): void
	{
		$id = Utils::request("id", -1);

		$articleManager = new ArticleManager();
		$article = $articleManager->getArticleById($id);

		if (!$article) {
			throw new Exception("L'article demandé n'existe pas.");
		}

		$commentManager = new CommentManager();
		$comments = $commentManager->getAllCommentsByArticleId($id);

		$view = new View($article->getTitle());
		$view->render("detailArticle", ['article' => $article, 'comments' => $comments]);
	}

	/**
	 * Affiche le formulaire d'ajout d'un article.
	 * @return void
	 */
	public function addArticle(): void
	{
		$view = new View("Ajouter un article");
		$view->render("addArticle");
	}

	/**
	 * Affiche la page "à propos".
	 * @return void
	 */
	public function showApropos(): void
	{
		$view = new View("A propos");
		$view->render("apropos");
	}
}