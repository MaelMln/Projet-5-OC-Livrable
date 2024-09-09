<?php

namespace App\Config;

spl_autoload_register(function ($className) {
	$classPath = str_replace("\\", DIRECTORY_SEPARATOR, $className) . '.php';
	$rootDir = __DIR__ . '/../';

	$directories = ['Services', 'Models', 'Controllers', 'Views'];

	foreach ($directories as $directory) {
		$filePath = $rootDir . "$directory/" . basename($classPath);
		error_log("Chemin recherché : " . $filePath);

		if (file_exists($filePath)) {
			require_once $filePath;
			return;
		}
	}

	error_log("Classe non trouvée : $classPath");
});