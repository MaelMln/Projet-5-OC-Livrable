<?php

namespace App\Services;

use DateTime;
use IntlDateFormatter;

class Utils
{
	/**
	 * Convertit une date en un format français lisible (ex: "lundi 1 janvier 2023").
	 * @param DateTime $date La date à formater.
	 * @return string La date formatée en français.
	 */
	public static function convertDateToFrenchFormat(DateTime $date): string
	{
		$dateFormatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::FULL);
		$dateFormatter->setPattern('EEEE d MMMM Y');
		return $dateFormatter->format($date);
	}

	/**
	 * Redirige l'utilisateur vers une autre page.
	 * @param string $action L'action à exécuter.
	 * @param array $params Paramètres supplémentaires à ajouter à l'URL.
	 */
	public static function redirect(string $action, array $params = []): void
	{
		$url = "index.php?action=$action";
		foreach ($params as $paramName => $paramValue) {
			$url .= "&$paramName=$paramValue";
		}
		header("Location: $url");
		exit();
	}

	/**
	 * Génère une confirmation JavaScript pour certaines actions (comme la suppression).
	 * @param string $message Le message de confirmation.
	 * @return string Le code JavaScript pour la confirmation.
	 */
	public static function askConfirmation(string $message): string
	{
		return "onclick=\"return confirm('$message');\"";
	}

	/**
	 * Formate une chaîne en la transformant en HTML sécurisé et bien structuré.
	 * Chaque ligne de texte est enveloppée dans des balises <p>.
	 * @param string $string La chaîne à formater.
	 * @return string La chaîne formatée en HTML sécurisé.
	 */
	public static function format(string $string): string
	{
		$finalString = htmlspecialchars($string, ENT_QUOTES);
		$lines = explode("\n", $finalString);
		$finalString = "";
		foreach ($lines as $line) {
			if (trim($line) != "") {
				$finalString .= "<p>$line</p>";
			}
		}
		return $finalString;
	}

	/**
	 * Récupère la valeur d'une variable dans la requête HTTP ($_REQUEST).
	 * Retourne une valeur par défaut si la variable n'est pas présente.
	 * @param string $variableName Le nom de la variable à récupérer.
	 * @param mixed $defaultValue La valeur par défaut à retourner si la variable n'existe pas.
	 * @return mixed La valeur de la variable ou la valeur par défaut.
	 */
	public static function request(string $variableName, mixed $defaultValue = null): mixed
	{
		return $_REQUEST[$variableName] ?? $defaultValue;
	}
}
