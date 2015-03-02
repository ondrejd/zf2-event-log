<?php

/**
 * zf2-event-logger
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog;

use EventLog\Log\Processor\Request as RequestProcessor;
use EventLog\Log\Processor\RouteMatch as RouteMatchProcessor;
use EventLog\Model\LogMapperAwareInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module
{
	public function onBootstrap(MvcEvent $e)
	{
		// You may not need to do this if you're doing it elsewhere in your
		// application
		$eventManager = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);
	}

	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
				),
			),
		);
	}

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	public function getControllerConfig()
	{
		return array(
			'initializers' => array(
				function($controller, ServiceLocatorInterface $serviceManager) {
					if ($controller instanceof LogMapperAwareInterface) {
						$serviceLocator = $serviceManager->getServiceLocator();
						$logMapper = $serviceLocator->get('EventLog\Model\LogMapper');

						$controller->setLogMapper($logMapper);
					}
				}
			),
			'invokables' => array(
				'EventLog\Controller\Index' => 'EventLog\Controller\IndexController'
			)
		);
	}

	public function getServiceConfig()
	{
		return array(
			'factories' => array(
				'EventLog\Options' => 'EventLog\OptionsServiceFactory',
				'EventLog\Db\Adapter' => 'EventLog\Db\AdapterServiceFactory',
				'EventLog\Log\Formatter\Db' => 'EventLog\Log\Formatter\DbServiceFactory',
				'EventLog\Log\Writer\Db' => 'EventLog\Log\Writer\DbServiceFactory',
				'EventLog\Model\LogMapper' => 'EventLog\Model\LogMapperServiceFactory',
				'EventLog\Log\Logger' => 'EventLog\Log\LoggerServiceFactory',
			),
			'aliases' => array(
				'EventLog' => 'EventLog\Log\Logger'
			)
		);
	}

	public function getLogProcessorConfig()
	{
		return array(
			'invokables' => array(
				'EventLog\EventName' => 'EventLog\Log\Processor\EventName',
				'EventLog\IpAddress' => 'EventLog\Log\Processor\IpAddress',
			),
			'factories' => array(
				'EventLog\Request' => function($serviceManager) {
					$request = $serviceManager->getServiceLocator()->get('request');

					$processor = new RequestProcessor($request);
					return $processor;
				},
				'EventLog\RouteMatch' => function($serviceManager) {
					$application = $serviceManager->getServiceLocator()->get('Application');

					$processor = new RouteMatchProcessor($application);
					return $processor;
				},
			)
		);
	}

	public function getLogWriterConfig()
	{
		return array(
			'factories' => array(
				'EventLog\Db' => 'EventLog\Log\Writer\DbServiceFactory'
			)
		);
	}
}
