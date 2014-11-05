<?php

namespace FuelElasticsearch;

class Request_Get extends Request_Abstract
{

	protected static $_method = 'get';

	public function __construct($db, $index, $type, $id)
	{
		parent::__construct($db, $index, $type, array(
			'id' => $id,
		));
	}

	public function execute()
	{
		try
		{
			return parent::execute();
		}
		catch (\Elasticsearch\Common\Exceptions\Missing404Exception $ex) {
			return false;
		}
	}

}
