<?php

namespace FuelElasticsearch;

class Index_Query
{
	protected $_query = array();
	protected $_request;
	protected $_client;
	protected $_index_name;
	protected $_results;

	public static function forge($index)
	{
		return new static($index);
	}

	public function __construct($index_name)
	{
		$this->_index_name = $index_name;
		$this->_client = \FuelElasticsearch\Db::forge();
	}

	public function request($type, $id = null)
	{
		$this->_request = $this->_client->request($type, 'siouz', $this->_index_name, $id);
		return $this;
	}

	public function search_type($type)
	{
		$this->_request->search_type($type);
		return $this;
	}

	public function sort($by)
	{
		$this->_request->sort($by);
		return $this;
	}

	public function limit($limit, $from = 0)
	{
		$this->_request->limit($limit, $from);
		return $this;
	}

	public function min_score($score)
	{
		$this->_request->min_score($score);
		return $this;
	}

	public function dynamic_min_score($rate)
	{
		$this->_request->return_source(false);
		$this->_request->limit(1);
		$max_score = $this->get_max_score();
		$this->min_score($max_score * $rate);
		return $this;
	}

	public function filter_term($field, $value)
	{
		$this->_request->filter_term($field, $value);
		return $this;
	}

	public function filter_terms($filters)
	{
		$this->_request->filter_terms($filters);
		return $this;
	}

	public function terms_aggregation($name, $terms)
	{
		$this->_request->terms_aggregation($name, $terms);
		return $this;
	}

	public function return_source($bool)
	{
		$this->_request->return_source($bool);
		return $this;
	}

	public function fuzzy_like_this($fields, $term, $fuzziness = 'AUTO')
	{
		$this->_request->query(array(
			'filtered' => array(
				'query' => array(
					'bool' => array(
						'must' => array(
							array(
								'fuzzy_like_this' => array(
									'fields' => $fields,
									'like_text' => $term,
									'fuzziness' => $fuzziness,
								),
							),
						),
					),
				),
			),
		));
		return $this;
	}

	public function suggest($field, $q)
	{
		$this->_request->suggest(array(
			$this->_index_name.'-suggest' => array(
				'text' => $q,
				'term' => array(
					'field' => $field,
				)
			),
		));
		return $this;
	}

	public function completion($field, $q, $fuzziness = 'AUTO')
	{
		$this->_request->query(array(
			'text' => $q,
			'completion' => array(
				'field' => $field,
				'fuzzy' => array(
					'fuzziness' => $fuzziness,
				)
			),
		));
		return $this;
	}

	public function match_all()
	{
		$this->_request->query(array(
			'filtered' => array(
				'query' => array(
					'match_all' => array(),
				),
			),
		));
		return $this;
	}

	public function bool($options)
	{
		$this->_request->query(array(
			'filtered' => array(
				'query' => array(
					'bool' => $options,
				),
			),
		));
		return $this;
	}

	public function execute()
	{
		$this->_results = $this->_request->execute();
		return $this->_results;
	}

	public function get()
	{
		$this->execute();
		return $this->_results;
	}

	public function debug()
	{
		return $this->_request->debug();
	}

	public function get_max_score()
	{
		$this->execute();
		if(empty($this->_results))
		{
			return null;
		}
		return (float) $this->_results['hits']['max_score'];
	}

	public function get_hits()
	{
		$this->execute();
		if(empty($this->_results))
		{
			return null;
		}
		return $this->_results['hits']['hits'];
	}

	public function get_options()
	{
		$this->execute();
		if(empty($this->_results))
		{
			return null;
		}
		$options = array();
		$suggests = array();
		isset($this->_results[$this->_index_name.'-suggest']) and $suggests = $this->_results[$this->_index_name.'-suggest'];
		empty($suggests) and $suggests = $this->_results['suggest'][$this->_index_name.'-suggest'];
		foreach($suggests as $suggest)
		{
			foreach($suggest['options'] as $option)
			{
				$options[$option['text']] = $option['text'];
			}
		}
		array_unique($options);
		return $options;
	}

	public function get_total()
	{
		$this->execute();
		$this->search_type('query_then_fetch');
		if(empty($this->_results))
		{
			return null;
		}
		return $this->_results['hits']['total'];
	}

	public function get_ids()
	{
		$this->execute();
		if(empty($this->_results))
		{
			return null;
		}
		return \Arr::pluck($this->_results['hits']['hits'], '_id');
	}

	public function get_aggregation($name)
	{
		if(empty($this->_results))
		{
			return null;
		}
		return $this->_results['aggregations'][$name];
	}
}
