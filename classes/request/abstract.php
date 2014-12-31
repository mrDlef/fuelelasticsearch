<?php

namespace FuelElasticsearch;

abstract class Request_Abstract
{

	protected static $_method;
	protected $_db;
	protected $_index;
	protected $_type;
	protected $_request = array();

	public function __construct($db, $index, $type, $request = null)
	{
		$this->_db = $db;
		$this->_index = $this->_db->index($index);
		$this->_type = $type;
		$this->_request = $request;
		$this->_request['index'] = $this->_index->get_name();
		$this->_request['type'] = $this->_type;
	}

	public function params($params)
	{
		$this->_request = \Arr::merge($this->_request, $params);
	}

	public function debug()
	{
		\Debug::dump("\n".json_encode($this->_request, JSON_PRETTY_PRINT)."\n");
	}


	public function execute()
	{
		try
		{
			return $this->_db->execute(static::$_method, $this->_request);
		}
		catch (\Elasticsearch\Common\Exceptions\Missing404Exception $ex)
		{
			return false;
		}
		catch (\Elasticsearch\Common\Exceptions\Curl\CouldNotConnectToHost $ex)
		{
			throw $ex;
		}
		catch (\Exception $ex)
		{
			$error = \Format::forge($ex->getMessage(), 'json')->to_array();
			if(isset($error['status']))
			{
				\Log::error('Elasticsearch error '.$error['status'].' in '.__FILE__.'#'.__LINE__.': '.$error['error']);
			}
			else
			{
				\Log::error('Elasticsearch error in '.__FILE__.'#'.__LINE__.': '.$ex->getMessage());
			}
		}
	}

	public function return_source($params)
	{
		$this->_request['_source'] = $params;
	}

}
