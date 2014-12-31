<?php

namespace FuelElasticsearch;

class Request_DeleteByQuery extends Request_Abstract
{

	protected static $_method = 'deleteByQuery';

	public function query($params)
	{
		$this->_request['body']['query'] = $params;
	}

}
