<?php

/**
 * Renbo.cz
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog;

use Zend\Config\Exception\RuntimeException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of OptionsServiceFactory
 *
 * @package EventLog
 */
class OptionsServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$config = $serviceLocator->get('Config');

		if (! isset($config['event_log'])) {
			throw new RuntimeException('Missing configuration for EventLog module.');
		}

		$options = new Options($config['event_log']);
		return $options;
	}
}
