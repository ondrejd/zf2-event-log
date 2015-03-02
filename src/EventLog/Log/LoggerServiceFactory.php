<?php

/**
 * Renbo.cz
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Log;

use EventLog\Options;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of LoggerFactory
 *
 * @package EventLog
 * @subpackage Log
 */
class LoggerServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$options = $serviceLocator->get('EventLog\Options');

		$loggerOptions = array(
			'processor_plugin_manager' => $this->_getProcessorPluginManager($options, $serviceLocator),
			'writer_plugin_manager' => $this->_getWriterPluginManager($options, $serviceLocator),
			'processors' => $options->getProcessors(),
			'writers' => $options->getWriters()
		);

		$logger = new Logger($loggerOptions);

		return $logger;
	}

	protected function _getProcessorPluginManager(Options $options, ServiceLocatorInterface $serviceLocator)
	{
		$processorPlugins = $options->getProcessorPluginManager();
		if (is_string($processorPlugins) && $serviceLocator->has($processorPlugins)) {
			$processorPlugins = $serviceLocator->get($processorPlugins);
		}

		if (! $processorPlugins instanceof AbstractPluginManager && $serviceLocator->has('LogProcessorManager')) {
			$processorPlugins = $serviceLocator->get('LogProcessorManager');
		}

		return $processorPlugins;
	}

	protected function _getWriterPluginManager(Options $options, ServiceLocatorInterface $serviceLocator)
	{
		$writerPlugins = $options->getWriterPluginManager();
		if (is_string($writerPlugins) && $serviceLocator->has($writerPlugins)) {
			$writerPlugins = $serviceLocator->get($writerPlugins);
		}

		if (! $writerPlugins instanceof AbstractPluginManager && $serviceLocator->has('LogWriterManager')) {
			$writerPlugins = $serviceLocator->get('LogWriterManager');
		}

		return $writerPlugins;
	}
}
