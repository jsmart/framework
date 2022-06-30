<?php

return [
	'default'	=> 'mysql',
	'connections'	=> [
		'mysql'	=> [
			'driver'	        => 'mysql',
            'url'               => '',
            'host'              => 'localhost',
            'port'              => '3306',
			'database'	        => 'jsmart',
			'username'	        => 'root',
			'password'	        => 'root',
            'unix_socket'       => '',
			'charset'	        => 'utf8mb4',
            'collation'         => 'utf8mb4_unicode_ci',
            'prefix'            => '',
            'prefix_indexes'    => true,
            'strict'            => true,
            'engine'            => null,
		],
	],
];