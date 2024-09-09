<?php

namespace App\Models;

class ArticleManager extends AbstractEntityManager
{
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


	public function getArticleById(int $id): ?Article
	{
		$sql = "SELECT * FROM article WHERE id = :id";

		$stmt = $this->db->query($sql, ['id' => $id]);
		$articleData = $stmt->fetch();

		if ($articleData) {
			$article = new Article($articleData);
			$this->incrementViews($article);
			return $article;
		}
		return null;
	}


	private function incrementViews(Article $article): void
	{
		$article->setViews($article->getViews() + 1);

		$sql = "UPDATE article SET views = :views WHERE id = :id";

		$this->db->query($sql, [
			'views' => $article->getViews(),
			'id' => $article->getId()
		]);
	}

	public function addArticle(Article $article): void
	{
		$sql = "INSERT INTO article (title, content, id_user) VALUES (:title, :content, :id_user)";
		$params = [
			'title' => $article->getTitle(),
			'content' => $article->getContent(),
			'id_user' => $article->getIdUser()
		];

		$stmt = $this->db->prepare($sql);
		$stmt->execute($params);
	}

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
}