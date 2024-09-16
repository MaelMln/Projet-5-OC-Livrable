<?php

namespace App\Models;

use DateTime;

class Article extends AbstractEntity
{
	private int $idUser;
	private string $title = "";
	private string $content = "";
	private ?DateTime $dateCreation = null;
	private ?DateTime $dateUpdate = null;
	private int $views = 0;

	/**
	 * Définit l'ID de l'utilisateur associé à l'article.
	 * @param int $idUser
	 */
	public function setIdUser(int $idUser): void
	{
		$this->idUser = $idUser;
	}

	/**
	 * Retourne l'ID de l'utilisateur associé à l'article.
	 * @return int
	 */
	public function getIdUser(): int
	{
		return $this->idUser;
	}

	/**
	 * Définit le titre de l'article.
	 * @param string $title
	 */
	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	/**
	 * Retourne le titre de l'article.
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * Définit le contenu de l'article.
	 * @param string $content
	 */
	public function setContent(string $content): void
	{
		$this->content = $content;
	}

	/**
	 * Retourne le contenu de l'article, avec une limite de longueur si précisé.
	 * @param int $length (facultatif) La longueur maximale du contenu retourné.
	 * @return string Le contenu tronqué si une longueur est définie, ou le contenu complet.
	 */
	public function getContent(int $length = -1): string
	{
		if ($length > 0) {
			$content = mb_substr($this->content, 0, $length);
			if (strlen($this->content) > $length) {
				$content .= "...";
			}
			return $content;
		}
		return $this->content;
	}

	/**
	 * Définit la date de création de l'article.
	 * Si une chaîne est fournie, elle est convertie en DateTime.
	 * @param string|DateTime $dateCreation La date de création de l'article.
	 * @param string $format Le format de la date si une chaîne est utilisée.
	 */
	public function setDateCreation(string|DateTime $dateCreation, string $format = 'Y-m-d H:i:s'): void
	{
		if (is_string($dateCreation)) {
			$dateCreation = DateTime::createFromFormat($format, $dateCreation);
		}
		$this->dateCreation = $dateCreation;
	}

	/**
	 * Retourne la date de création de l'article.
	 * @return DateTime
	 */
	public function getDateCreation(): DateTime
	{
		return $this->dateCreation;
	}

	/**
	 * Définit la date de mise à jour de l'article.
	 * Si aucune date n'est fournie, la date actuelle est utilisée.
	 * @param string|DateTime|null $dateUpdate La date de mise à jour de l'article.
	 * @param string $format Le format de la date si une chaîne est utilisée.
	 */
	public function setDateUpdate(string|DateTime|null $dateUpdate = null, string $format = 'Y-m-d H:i:s'): void
	{
		if ($dateUpdate === null) {
			$this->dateUpdate = new DateTime();
		} elseif (is_string($dateUpdate)) {
			$this->dateUpdate = DateTime::createFromFormat($format, $dateUpdate);
		} else {
			$this->dateUpdate = $dateUpdate;
		}
	}

	/**
	 * Retourne la date de mise à jour de l'article, ou null si non définie.
	 * @return ?DateTime
	 */
	public function getDateUpdate(): ?DateTime
	{
		return $this->dateUpdate;
	}

	/**
	 * Définit le nombre de vues de l'article.
	 * @param int $views
	 */
	public function setViews(int $views): void
	{
		$this->views = $views;
	}

	/**
	 * Retourne le nombre de vues de l'article.
	 * @return int
	 */
	public function getViews(): int
	{
		return $this->views;
	}
}
