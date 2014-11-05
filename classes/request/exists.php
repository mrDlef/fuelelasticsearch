<?php

namespace FuelElasticsearch;

class Request_Exists extends Request_Abstract
{

	protected static $_method = 'exists';

	public function __construct($db, $index, $type, $id)
	{
		parent::__construct($db, $index, $type, array(
		   'id' => $id,
		));
	}

}
