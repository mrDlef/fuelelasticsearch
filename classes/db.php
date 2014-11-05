<?php

namespace FuelElasticsearch;

class Db
{

	protected static $instances = array();
	protected $_connection;
	protected $_indexes = array();

	public static function instance($name = 'default')
	{
		if (!array_key_exists($name, static::$instances))
		{
			// call forge() if a new instance needs to be created, this should throw an error
			return static::forge($name);
		}

		return static::$instances[$name];
	}

	public static function forge($name = 'default', $config = array())
	{
		empty(static::$instances) and \Config::load('db', true);

		if (!($conf = \Config::get('db.elasticsearch.'.$name)))
		{
			throw new Exception('Invalid instance name given.');
		}
		$config = \Arr::merge($conf, $config);

		static::$instances[$name] = new static($config);

		return static::$instances[$name];
	}

	protected function __construct(array $config = array())
	{
		$this->_connection = new \Elasticsearch\Client($config);
	}

	public function index($name)
	{
		if (!isset($this->_indexes[$name]))
		{
			$this->_indexes[$name] = new Index($this, $name);
		}
		return $this->_indexes[$name];
	}

	public function get_connection()
	{
		return $this->_connection;
	}

	public function request($request, $index, $type, $params = null)
	{
		$class_name = 'FuelElasticsearch\\Request_'.ucfirst($request);
		if(! class_exists($class_name))
		{
			throw new Exception('Can\'t find the requested class "'.$class_name.'"');
		}
		return new $class_name($this, $index, $type, $params);
	}

	public function execute($method, $params)
	{
		return $this->_connection->$method($params);
	}
	
	public function stats()
	{
		return $this->_connection->indices()->stats();
	}

}
