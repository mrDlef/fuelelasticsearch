<?php

namespace FuelElasticsearch;

class Request_Search extends Request_Abstract
{

	protected static $_method = 'search';

	public function limit($limit, $from = 0)
	{
		$this->_request['body']['from'] = $from;
		$this->_request['body']['size'] = $limit;
	}

	public function min_score($value)
	{
		$this->_request['body']['min_score'] = $value;
	}

	public function query($params)
	{
		$this->_request['body']['query'] = $params;
	}

	public function suggest($params)
	{
		$this->_request['body']['suggest'] = $params;
	}

	public function rescore($params)
	{
		$this->_request['body']['rescore'] = $params;
	}

	public function sort($params)
	{
		$this->_request['body']['sort'] = $params;
	}

	public function search_type($value)
	{
		$this->_request['search_type'] = $value;
	}

	public function filter_term($field, $value)
	{
		$this->_request['body']['query']['filtered']['filter']['and'][] = array(
			'term' => array($field => $value),
		);
	}

	public function terms_aggregation($name, $terms)
	{
		$this->_request['body']['aggs'][$name]['terms'] = $terms;
	}

	public function filter_terms($filters)
	{
		foreach ($filters as $term => $values)
		{
			$this->_request['body']['query']['filtered']['filter']['and'][] = array(
				'terms' => array($term => $values),
			);
		}
	}

	public function filter_or($filters)
	{
		$or = array();
		foreach($filters as $filter)
		{
			$or['or'][] = $filter;
		}
		$this->_request['body']['query']['filtered']['filter']['and'][] = $or;
	}

}
