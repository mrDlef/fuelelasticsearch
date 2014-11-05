<?php

namespace FuelElasticsearch;

class Observer_Delete extends \Orm\Observer
{
	protected $_index;
	protected $_type;
	protected $_id_field = 'id';
	protected $_to_delete;

	public function __construct($class)
	{
		$props = $class::observers(get_class($this));
		$this->_index  = $props['index'];
		$this->_type  = $props['type'];
		isset($props['id_field']) and $this->_id_field = $props['id_field'];
	}

	public function before_delete(\Orm\Model $model)
	{
		$this->_to_delete = $model->{$this->_id_field};
	}

	public function after_delete(\Orm\Model $model)
	{
		if(! empty($this->_to_delete))
		{
			$client = Db::forge();
			$client->request('delete', $this->_index, $this->_type, $this->_to_delete)->execute();
		}
	}

}
