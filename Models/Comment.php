<?php

namespace App\Models;

class Comment extends AbstractEntity
{
	private int $idArticle;
	private string $pseudo;
	private string $content;
	private \DateTime $dateCreation;

	/**
	 * Retourne l'identifiant de l'article associé au commentaire.
	 * @return int
	 */
	public function getIdArticle(): int
	{
		return $this->idArticle;
	}

	/**
	 * Définit l'identifiant de l'article associé au commentaire.
	 * @param int $idArticle
	 */
	public function setIdArticle(int $idArticle): void
	{
		$this->idArticle = $idArticle;
	}

	/**
	 * Retourne le pseudonyme de l'auteur du commentaire.
	 * @return string
	 */
	public function getPseudo(): string
	{
		return $this->pseudo;
	}

	/**
	 * Définit le pseudonyme de l'auteur du commentaire.
	 * @param string $pseudo
	 */
	public function setPseudo(string $pseudo): void
	{
		$this->pseudo = $pseudo;
	}

	/**
	 * Retourne le contenu du commentaire.
	 * @return string
	 */
	public function getContent(): string
	{
		return $this->content;
	}

	/**
	 * Définit le contenu du commentaire.
	 * @param string $content
	 */
	public function setContent(string $content): void
	{
		$this->content = $content;
	}

	/**
	 * Retourne la date de création du commentaire.
	 * @return \DateTime
	 */
	public function getDateCreation(): \DateTime
	{
		return $this->dateCreation;
	}

	/**
	 * Définit la date de création du commentaire.
	 * Si une chaîne est fournie, elle est convertie en DateTime.
	 * @param string|\DateTime $dateCreation La date de création du commentaire.
	 * @param string $format Le format de la date si une chaîne est utilisée.
	 */
	public function setDateCreation(string|\DateTime $dateCreation, string $format = 'Y-m-d H:i:s'): void
	{
		if (is_string($dateCreation)) {
			$dateCreation = \DateTime::createFromFormat($format, $dateCreation);
		}
		$this->dateCreation = $dateCreation;
	}
}
