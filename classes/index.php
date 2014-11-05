<?php

namespace FuelElasticsearch;

class Index
{

	protected $_name;
	protected $_db;
	protected $_connection;
	protected $_params = array();
	protected $_mappings = array();
	protected $_settings = array();

	public function __construct($db, $name)
	{
		$this->_db = $db;
		$this->_connection = $this->_db->get_connection();
		$this->_name = $name;

		$this->_params = array(
		   'index' => $this->_name,
		);
	}

	public function exists()
	{
		return $this->_connection->indices()->exists($this->_params);
	}

	public function create($settings = null, $mappings = null)
	{
		if (!$this->exists())
		{
			$settings and $this->_settings = $settings;
			$mappings and $this->_mappings = $mappings;

			$params = \Arr::merge($this->_params, array(
				'body' => array(
				   'settings' => $this->_settings,
				   'mappings' => $this->_mappings,
				),
			));

			return $this->_connection->indices()->create($params);
		}
		throw new Exception('Index already exists');
	}

	public function delete()
	{
		if ($this->exists())
		{
			$this->_connection->indices()->delete($this->_params);
		}
	}

	public function get_settings()
	{
		if ($this->exists())
		{
			return $this->_connection->indices()->getSettings($this->_params);
		}
	}

	public function get_mapping()
	{
		if ($this->exists())
		{
			return $this->_connection->indices()->getMapping($this->_params);
		}
	}

	public function get_name()
	{
		return $this->_name;
	}

}
