<?php

namespace App\Views;

class View
{
	private string $title;

	/**
	 * Constructeur de la classe View.
	 * Définit le titre de la vue.
	 * @param string $title Le titre de la vue.
	 */
	public function __construct(string $title)
	{
		$this->title = $title;
	}

	/**
	 * Rendu de la vue spécifiée avec les paramètres donnés.
	 * @param string $viewName Le nom de la vue à afficher.
	 * @param array $params Les données à transmettre à la vue.
	 */
	public function render(string $viewName, array $params = []): void
	{
		$viewPath = $this->buildViewPath($viewName);

		$content = $this->_renderViewFromTemplate($viewPath, $params);
		$title = $this->title;
		ob_start();
		require(MAIN_VIEW_PATH);
		echo ob_get_clean();
	}

	/**
	 * Exécute le rendu de la vue et retourne le contenu en tant que chaîne.
	 * @param string $viewPath Le chemin vers le fichier de vue.
	 * @param array $params Les données à passer à la vue.
	 * @return string Le contenu rendu de la vue.
	 * @throws \Exception Si le fichier de vue est introuvable.
	 */
	private function _renderViewFromTemplate(string $viewPath, array $params = []): string
	{
		if (file_exists($viewPath)) {
			extract($params);
			ob_start();
			require($viewPath);
			return ob_get_clean();
		} else {
			throw new \Exception("La vue '$viewPath' est introuvable.");
		}
	}

	/**
	 * Construit le chemin complet vers le fichier de vue correspondant au nom donné.
	 * @param string $viewName Le nom de la vue.
	 * @return string Le chemin complet vers le fichier de vue.
	 */
	private function buildViewPath(string $viewName): string
	{
		return TEMPLATE_VIEW_PATH . $viewName . '.php';
	}
}
