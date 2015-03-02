<?php

/**
 * zf2-event-logger
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

return array(
	'router' => array(
		'routes' => array(
			'event_log' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/logs',
					'defaults' => array(
						'controller' => 'EventLog\Controller\Index',
						'action' => 'index',
					),
				),
			),
		),
	),
	'event_log' => array(
		'db' => array(
			'driver' => 'Pdo_Sqlite',
			'database' => __DIR__ . '/../data/event-log.sqlite',
		),
		'table_name' => 'event_log',
		'separate_from_extra' => array(
			'event',
			'eventName',
			'ipAddress',
			'requestId'
		),
		'serialize_properties' => array(
			'event',
			'extra'
		),
		'column_map' => array(
			'timestamp' => 'timestamp',
			'priority' => 'priority',
			'priorityName' => 'priority_name',
			'message' => 'message',
			'event' => 'event',
			'eventName' => 'event_name',
			'ipAddress' => 'ip_address',
			'requestId' => 'request_id',
			'extra' => 'extra'
		),
		'processors' => array(
			array('name' => 'EventLog\EventName'),
			array('name' => 'EventLog\IpAddress'),
			array('name' => 'EventLog\Request'),
			array('name' => 'EventLog\RouteMatch'),
			array('name' => 'requestId'),
		),
		'writers' => array(
			array('name' => 'EventLog\Db'),
		)
	),
	'view_manager' => array(
		'template_path_stack' => array(
			'event_log' => __DIR__ . '/../view',
		),
	),
);
