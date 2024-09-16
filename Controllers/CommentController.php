<?php

namespace App\Controllers;

use App\Models\ArticleManager;
use App\Models\Comment;
use App\Models\CommentManager;
use App\Services\Utils;
use Exception;

class CommentController
{
	/**
	 * Ajoute un commentaire à un article.
	 * Récupère les données du formulaire, vérifie que l'article existe,
	 * crée un objet Comment et l'ajoute à la base de données.
	 * Redirige ensuite vers l'article.
	 * @return void
	 * @throws Exception si des champs sont manquants ou si l'article n'existe pas.
	 */
	public function addComment(): void
	{
		$pseudo = Utils::request("pseudo");
		$content = Utils::request("content");
		$idArticle = Utils::request("idArticle");

		if (empty($pseudo) || empty($content) || empty($idArticle)) {
			throw new Exception("Tous les champs sont obligatoires.");
		}

		$articleManager = new ArticleManager();
		$article = $articleManager->getArticleById($idArticle);
		if (!$article) {
			throw new Exception("L'article demandé n'existe pas.");
		}

		$comment = new Comment([
			'pseudo' => $pseudo,
			'content' => $content,
			'idArticle' => $idArticle
		]);

		$commentManager = new CommentManager();
		$result = $commentManager->addComment($comment);

		if (!$result) {
			throw new Exception("Une erreur est survenue lors de l'ajout du commentaire.");
		}

		Utils::redirect("showArticle", ['id' => $idArticle]);
	}
}
