<?php

namespace FuelElasticsearch;

class Request_Delete extends Request_Abstract
{

	protected static $_method = 'delete';

	public function __construct($db, $index, $type, $id)
	{
		parent::__construct($db, $index, $type, array(
		   'id' => $id,
		));
	}

}
