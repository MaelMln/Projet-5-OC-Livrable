<?php

namespace App\Models;

/**
 * Classe qui permet de se connecter à la base de données.
 * Cette classe est un singleton. Cela signifie qu'il n'est pas possible de créer plusieurs instances de cette classe.
 * Pour récupérer une instance de cette classe, il faut utiliser la méthode getInstance().
 */
class DBManager
{
	private static $instance;

	private \PDO $db;

	/**
	 * Constructeur de la classe DBManager.
	 * Initialise la connexion à la base de données.
	 * Ce constructeur est privé. Pour récupérer une instance de la classe, il faut utiliser la méthode getInstance().
	 * @see DBManager::getInstance()
	 */
	private function __construct()
	{
		$this->db = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
		$this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
	}

	/**
	 * Méthode qui permet de récupérer l'instance de la classe DBManager.
	 * @return DBManager
	 */
	public static function getInstance(): DBManager
	{
		if (!self::$instance) {
			self::$instance = new DBManager();
		}
		return self::$instance;
	}

	/**
	 * Méthode qui permet de récupérer l'objet PDO qui permet de se connecter à la base de données.
	 * @return \PDO
	 */
	public function getPDO(): \PDO
	{
		return $this->db;
	}

	/**
	 * Méthode qui permet d'exécuter une requête SQL.
	 * Si des paramètres sont passés, on utilise une requête préparée.
	 * @param string $sql : la requête SQL à exécuter.
	 * @param array|null $params : les paramètres de la requête SQL.
	 * @return \PDOStatement : le résultat de la requête SQL.
	 */
	public function query(string $sql, ?array $params = null): \PDOStatement
	{
		if ($params == null) {
			return $this->db->query($sql);
		} else {
			$stmt = $this->db->prepare($sql);
			$stmt->execute($params);
			return $stmt;
		}
	}

	/**
	 * Méthode qui permet de préparer une requête SQL sans l'exécuter (pour des requêtes préparées).
	 * @param string $sql : la requête SQL à préparer.
	 * @return \PDOStatement : la requête SQL préparée.
	 */
	public function prepare(string $sql): \PDOStatement
	{
		return $this->db->prepare($sql);
	}
}
