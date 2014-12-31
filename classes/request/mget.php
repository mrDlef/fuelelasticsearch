<?php

namespace FuelElasticsearch;

class Request_Mget extends Request_Abstract
{

	protected static $_method = 'mget';

	public function ids($ids)
	{
		foreach($ids as $id)
		{
			$this->_request['body']['docs'][]['_id'] = $id;
		}
	}

	public function fields($fields)
	{
		foreach($this->_request['body']['docs'] as &$doc)
		{
			$doc['fields'] = $fields;
		}
	}

}
