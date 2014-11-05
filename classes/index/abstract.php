<?php

namespace FuelElasticsearch;

abstract class Index_Abstract
{

	protected static $_index_name;

	protected static function _get_elasticsearch_data($tag) {}

	public static function refresh($tag, $action = null) {}

	public static function query()
	{
		return Index_Query::forge(static::$_index_name);
	}

}
