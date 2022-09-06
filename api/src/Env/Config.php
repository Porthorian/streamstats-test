<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Env;

use Porthorian\Utility\Json\JsonWrapper;
use Porthorian\PDOWrapper\Models\DatabaseModel;

class Config
{
	private static $instance = null;
	private static ?array $databases = null;

	private array $config;
	private string $file_path;

	private function __construct(string $file_path)
	{
		$json = @file_get_contents($file_path);

		if ($json === false)
		{
			throw new EnvironmentException('Unable to find config at path: ' . $file_path);
		}

		$decode = JsonWrapper::decode($json);

		if ($decode === '' || JsonWrapper::hasError())
		{
			throw new EnvironmentException('There has been an error parsing the input. JSON Error: ' . JsonWrapper::getLastError());
		}
		$this->config = $decode;
		$this->file_path = $file_path;
	}

	public function get(string $config_option)
	{
		if (!isset($this->config[$config_option]))
		{
			throw new EnvironmentException('Config Option: ' . $config_option . ' does not exist inside the config! See file_path: ' . $this->file_path);
		}
		return $this->config[$config_option];
	}

	public static function getConfig() : self
	{
		return self::$instance;
	}

	public static function setConfig(string $file_path) : void
	{
		if (self::$instance !== null)
		{
			throw new EnvironmentException('Config Instance already exists! Only one instance of a config can exist.');
		}
		self::$instance = new Config($file_path);
	}

	public static function isInitialized() : bool
	{
		return self::$instance !== null;
	}

	/**
	* @return []DatabaseModel
	*/
	public static function getDatabases() : array
	{
		if (self::$databases !== null)
		{
			return self::$databases;
		}
		$databases = self::getConfig()->get('databases');

		$out = [];
		foreach ($databases as $database => $attr)
		{
			$model = new DatabaseModel($database, $attr['host'], $attr['user'], $attr['password'], $attr['charset'] ?? 'UTF8');
			$model->setPort($attr['port']);
			$out[$database] = $model;
		}

		self::$databases = $out;
		return self::$databases;
	}

	public static function getTwitchClientId() : string
	{
		return self::getConfig()->get('twitch')['client_id'];
	}

	public static function getTwitchClientSecret() : string
	{
		return self::getConfig()->get('twitch')['client_secret'];
	}
}
