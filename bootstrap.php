<?php

Autoloader::add_classes(array(
	'FuelElasticsearch\\Db'        => __DIR__.'/classes/db.php',

	'FuelElasticsearch\\Index'     => __DIR__.'/classes/index.php',
	'FuelElasticsearch\\Index_Abstract'     => __DIR__.'/classes/index/abstract.php',
	'FuelElasticsearch\\Index_Query'     => __DIR__.'/classes/index/query.php',

	'FuelElasticsearch\\Exception' => __DIR__.'/classes/exception.php',

	'FuelElasticsearch\\Request_Abstract' => __DIR__.'/classes/request/abstract.php',
	'FuelElasticsearch\\Request_Count' => __DIR__.'/classes/request/count.php',
	'FuelElasticsearch\\Request_Delete' => __DIR__.'/classes/request/delete.php',
	'FuelElasticsearch\\Request_Exists' => __DIR__.'/classes/request/exists.php',
	'FuelElasticsearch\\Request_Get' => __DIR__.'/classes/request/get.php',
	'FuelElasticsearch\\Request_Index' => __DIR__.'/classes/request/index.php',
	'FuelElasticsearch\\Request_Search' => __DIR__.'/classes/request/search.php',
	'FuelElasticsearch\\Request_Suggest' => __DIR__.'/classes/request/suggest.php',
	'FuelElasticsearch\\Request_Update' => __DIR__.'/classes/request/update.php',

	'FuelElasticsearch\\Observer_Delete' => __DIR__.'/classes/observer/delete.php',
));
