<?php

namespace FuelElasticsearch;

class Request_Count extends Request_Abstract
{

	protected static $_method = 'count';

	public function query($params)
	{
		$this->_request['body']['query'] = $params;
	}

	public function rescore($params)
	{
		$this->_request['body']['rescore'] = $params;
	}

	public function filter_terms($filters)
	{
		foreach($filters as $term=>$values)
		{
			$this->_request['body']['query']['filtered']['filter']['and'][] = array(
				'terms' => array($term => $values),
			);
		}
	}

}
