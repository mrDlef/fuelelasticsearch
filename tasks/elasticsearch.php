<?php

namespace Fuel\Tasks;

class Elasticsearch
{

	protected static function _index_config($name)
	{
		\Config::load('elasticsearch', true);
		$config = \Config::get('elasticsearch.index.'.$name);

		if(! $config)
		{
			\Cli::write('Can\'t find any index '.$name);
			exit;
		}

		return $config;
	}

	public static function run()
	{
		\Cli::write();
		\Cli::write('Available elasticsearch tasks:');
		\Cli::write();
		\Cli::write('* create_index [index]: '.\Cli::color('Create the given index', 'dark_gray'));
		\Cli::write('* get_index_settings [index]: '.\Cli::color('Return the settings of the given index', 'dark_gray'));
		\Cli::write('* get_index_mapping [index]: '.\Cli::color('Return the mapping of the given index', 'dark_gray'));
		\Cli::write('* delete_index [index]: '.\Cli::color('Delete the given index', 'dark_gray'));
		\Cli::write('* index_all [type]:    '.\Cli::color('Index all type given from MySQL to Elasticsearch index', 'dark_gray'));
		\Cli::write('* exists [type] [id]:  '.\Cli::color('Check if a document of the given type and id exists', 'dark_gray'));
		\Cli::write('* get [type] [id]:     '.\Cli::color('Display document of the given type and id', 'dark_gray'));
		\Cli::write('* delete [type] [id]:  '.\Cli::color('Delete document of the given type and id', 'dark_gray'));
		\Cli::write('* stats:        '.\Cli::color('Display some statistics', 'dark_gray'));
		\Cli::write();
	}

	public static function delete_index($index = 'default')
	{
		$config = static::_index_config($index);

		$client = \FuelElasticsearch\Db::forge();
		$client->index($config['name'])->delete();
	}

	public static function create_index($index = 'default')
	{
		$config = static::_index_config($index);

		$client = \FuelElasticsearch\Db::forge();
		try
		{
			$client->index($config['name'])->create($config['settings'], $config['mappings']);
		}
		catch (\FuelElasticsearch\Exception $ex)
		{
			\Cli::write($ex->getMessage());
		}
	}

	public static function get_index_settings($index = 'default')
	{
		$config = static::_index_config($index);

		$client = \FuelElasticsearch\Db::forge();

		$response = $client->index($config['name'])->get_settings();
		\Cli::write(var_export($response, null));
	}

	public static function get_index_mapping($index = 'default')
	{
		$config = static::_index_config($index);

		$client = \FuelElasticsearch\Db::forge();

		$response = $client->index($config['name'])->get_mapping();
		\Cli::write(var_export($response, null));
	}

	public static function exists($type, $id)
	{
		$class = '\\Index_'.ucfirst($type);

		$result = $class::query()
			->request('exists', $id)
			->execute();

		if ($result)
		{
			\Cli::write(ucfirst($type).' '.$id.' exists');
		}
		else
		{
			\Cli::write(ucfirst($type).' '.$id.' doesn\'t exists');
		}
	}

	public static function get($type, $id)
	{
		$class = '\\Index_'.ucfirst($type);

		$document = $class::query()
			->request('get', $id)
			->get();

		if ($document)
		{
			\Cli::write(var_export($document, null));
		}
		else
		{
			\Cli::write(ucfirst($type).' '.$id.' doesn\'t exists');
		}
	}

	public static function delete($type, $id)
	{
		$class = '\\Index_'.ucfirst($type);

		$result = $class::query()
			->request('delete', $id)
			->execute();

		if ($result)
		{
			\Cli::write(ucfirst($type).' '.$id.' deleted');
		}
		else
		{
			\Cli::write(ucfirst($type).' '.$id.' doesn\'t exists');
		}
	}

	public static function index_all($type)
	{
		$model_class = '\\Model\\'.ucfirst($type);
		$index_class = '\\Index_'.ucfirst($type);

		$objects = $model_class::query()->get();

		\Cli::write(count($objects).' to index');
		foreach ($objects as $object)
		{
			\Cli::write('Indexing '.ucfirst($type).' #'.$object->id);
			$index_class::refresh($object);
			\Cli::write(ucfirst($type).' #'.$object->id.' indexed');
		}
	}

	public static function stats()
	{
		$client = \FuelElasticsearch\Db::forge();
		$response = $client->stats();
		\Cli::write(var_export($response, null));
	}

}
