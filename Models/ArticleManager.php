<?php

namespace App\Models;

class ArticleManager extends AbstractEntityManager
{
	/**
	 * Récupère tous les articles de la base de données, triés par date de création décroissante.
	 * @return array Un tableau d'objets Article.
	 */
	public function getAllArticles(): array
	{
		$sql = "SELECT * FROM article ORDER BY date_creation DESC";
		$result = $this->db->query($sql);
		$articles = [];

		while ($article = $result->fetch()) {
			$articles[] = new Article($article);
		}

		return $articles;
	}

	/**
	 * Récupère les articles pour le tableau de monitoring, avec possibilité de trier
	 * par différents critères (titre, nombre de vues, nombre de commentaires, date de création).
	 * @param string $sort Le critère de tri.
	 * @param string $order L'ordre de tri (ascendant ou descendant).
	 * @return array Un tableau associatif avec les informations sur les articles.
	 */
	public function getArticlesForAdmin(string $sort = 'date_creation', string $order = 'asc'): array
	{
		$allowedSort = ['title', 'views', 'comment_count', 'date_creation'];
		if (!in_array($sort, $allowedSort)) {
			$sort = 'title';
		}

		$order = strtolower($order) === 'desc' ? 'desc' : 'asc';

		$sql = "
        	SELECT a.id, a.title, a.views, a.date_creation, 
        	(SELECT COUNT(c.id) FROM comment c WHERE c.id_article = a.id) AS comment_count
        	FROM article a
        	ORDER BY $sort $order";

		$result = $this->db->query($sql);
		$articles = [];

		while ($article = $result->fetch()) {
			$articles[] = $article;
		}

		return $articles;
	}

	/**
	 * Récupère un article par son identifiant.
	 * Incrémente le nombre de vues de l'article lors de la récupération.
	 * @param int $id L'identifiant de l'article.
	 * @return Article|null L'article récupéré ou null s'il n'existe pas.
	 */
	public function getArticleById(int $id): ?Article
	{
		$sql = "SELECT * FROM article WHERE id = :id";

		$stmt = $this->db->prepare($sql);
		$stmt->execute(['id' => $id]);
		$articleData = $stmt->fetch();

		if ($articleData) {
			$article = new Article($articleData);
			$this->incrementViews($article);
			return $article;
		}
		return null;
	}

	/**
	 * Incrémente le nombre de vues d'un article.
	 * @param Article $article L'article dont le nombre de vues doit être incrémenté.
	 */
	private function incrementViews(Article $article): void
	{
		$article->setViews($article->getViews() + 1);

		$sql = "UPDATE article SET views = :views WHERE id = :id";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			'views' => $article->getViews(),
			'id' => $article->getId()
		]);
	}

	/**
	 * Ajoute un nouvel article à la base de données.
	 * @param Article $article L'article à ajouter.
	 */
	public function addArticle(Article $article): void
	{
		$sql = "INSERT INTO article (title, content, id_user, date_creation) VALUES (:title, :content, :id_user, NOW())";
		$params = [
			'title' => $article->getTitle(),
			'content' => $article->getContent(),
			'id_user' => $article->getIdUser()
		];

		$stmt = $this->db->prepare($sql);
		$stmt->execute($params);
	}

	/**
	 * Met à jour un article existant dans la base de données.
	 * @param Article $article L'article à mettre à jour.
	 */
	public function updateArticle(Article $article): void
	{
		$sql = "UPDATE article SET title = :title, content = :content, id_user = :id_user WHERE id = :id";
		$params = [
			'id' => $article->getId(),
			'title' => $article->getTitle(),
			'content' => $article->getContent(),
			'id_user' => $article->getIdUser()
		];

		$stmt = $this->db->prepare($sql);
		$stmt->execute($params);
	}

	/**
	 * Supprime un article de la base de données.
	 * @param int $id L'identifiant de l'article à supprimer.
	 */
	public function deleteArticle(int $id): void
	{
		$sql = "DELETE FROM article WHERE id = :id";

		$stmt = $this->db->prepare($sql);

		$stmt->execute(['id' => $id]);
	}
}
