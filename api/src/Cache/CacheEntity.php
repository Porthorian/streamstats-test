<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Cache;

use Porthorian\EntityOrm\Entity;
use Porthorian\EntityOrm\EntityException;
use Porthorian\Utility\Json\JsonWrapper;

abstract class CacheEntity extends Entity
{
	/**
	 * The amount of time in seconds the Entity is going to live in the cache.
	 */
	abstract public function getEntityCacheTime() : int;

	/**
	 * Houses the namespace path to the model for the entity
	 */
	abstract public function getModelPath() : string;

	/**
	 * The collection that we will be manipulating
	 */
	abstract public function getCollectionTable() : string;

	/**
	 * Primary key for the collection table
	 */
	abstract public function getCollectionPrimaryKey() : string;

	/**
	 * The cache collection
	 */
	public function getCollectionName() : string
	{
		return 'streamstats';
	}

	/**
	 * Takes all current values inside the model and inserts them into the cache entity
	 * @throws EntityException - if the insertion query fails some how.
	 * @return ModelInterface
	 */
	public function store() : ModelInterface
	{
		$this->initializeModelIfNotSet();

		$model = $this->getModel();

		$cache = Cache::setArray($this->getCacheKey(), $model->toArray(), $this->getEntityCacheTime());
		if ($cache === false)
		{
			$message = 'Failed to store cache entity '.get_class($this).' for model '.get_class($model);
			if (JsonWrapper::hasError())
			{
				$message .= JsonWrapper::getLastError();
			}
			throw new EntityException($message);
		}

		$model->setInitializedFlag(true);
		$this->setModel($model);

		if ($this->useEntityCache())
		{
			self::setCacheItem($this->getCacheKey(), $model);
		}

		return $model;
	}

	/**
	 * Update the cache entity based on the primary key of the model.
	 * @param $params - These are the fields that will be pulled from the model
	 * Ex ['registration_time', 'date_of_birth']
	 * @throws InvalidArgumentException - If the column does not exist inside the model.
	 * @throws EntityException
	 * @return void
	 */
	public function update(array $params = []) : void
	{
		$this->initializeModelIfNotSet();

		$model = $this->getModel();
		if (!$model->isInitialized())
		{
			throw new EntityException('Unable to update model ' . get_class($model) . ' as it is not initialized');
		}

		$update_values = $model->toArray();

		$update_params = [];
		foreach ($params as $column)
		{
			if (!isset($update_values[$column]))
			{
				throw new InvalidArgumentException('That column: ' . $column . ' does not exist inside the model ' . get_class($model));
			}
			$update_params[$column] = $update_values[$column];
		}

		$array = Cache::getArray($this->getCacheKey());

		if ($array === null)
		{
			throw new EntityException('Cache entity no longer exists, entity '.get_class($this).' model '.get_class($model));
		}

		foreach ($update_params as $param => $value)
		{
			$array[$param] = $value;
		}

		$cache = Cache::setArray($this->getCacheKey(), $array, $this->getEntityCacheTime());
		if ($cache === false && JsonWrapper::hasError())
		{
			throw new EntityException('Failed to update entity '.get_class($this).' on model '.get_class($model).' with json error: '.JsonWrapper::getLastError());
		}

		if ($this->useEntityCache())
		{
			self::setCacheItem($this->getCacheKey(), $model);
		}
	}

	/**
	 * Delete the entity based on the primary key of the model.
	 * @throws EntityException if the model object is not initialized
	 * @return void
	 */
	public function delete() : void
	{
		$this->initializeModelIfNotSet();
		$model = $this->getModel();
		if (!$model->isInitialized())
		{
			throw new EntityException('Unable to delete model '.get_class($model).' as it is not initialized.');
		}

		if (!Cache::delete($this->getCacheKey()))
		{
			return;
		}

		$this->resetModel();

		if ($this->useEntityCache())
		{
			self::deleteCacheItem($this->getCacheKey());
		}
	}

	/**
	 * @param pk_value - Primary key value, mixed types
	 * @return ModelInterface
	*/
	public function find(string|int $pk_value) : ModelInterface
	{
		$this->initializeModelIfNotSet();

		if ($this->useEntityCache())
		{
			$cache_item = self::getCacheItem($this->getCacheKey($pk_value));
			if ($cache_item instanceof ModelInterface)
			{
				return $cache_item;
			}
		}

		$record = Cache::getArray($this->getCacheKey($pk_value));

		$this->resetModel();
		if ($record === null)
		{
			return $this->getModel();
		}

		$this->setModelProperties($record);

		$model = $this->getModel();
		if ($this->useEntityCache())
		{
			self::setCacheItem($this->getCacheKey(), $model);
		}

		return $model;
	}

	protected function setModelProperties(array $record) : void
	{
		$this->resetModel();
		$model = $this->getModel();

		$model->setModelProperties($record);
		$model->setInitializedFlag(true);
		$this->setModel($model);
	}

	private function initializeModelIfNotSet() : void
	{
		if (isset($this->model))
		{
			return;
		}

		$path = $this->getModelPath();
		$this->setModel(new $path);
	}
}
