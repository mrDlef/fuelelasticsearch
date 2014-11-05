<?php

namespace FuelElasticsearch;

class Request_Suggest extends Request_Abstract
{

	protected static $_method = 'suggest';

	public function __construct($db, $index, $type, $request = null)
	{
		parent::__construct($db, $index, $type, $request);
		unset($this->_request['type']);
	}

	public function query($params)
	{
		$this->_request['body'][$this->_type.'-suggest'] = $params;
	}

}
