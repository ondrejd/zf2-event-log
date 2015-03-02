<?php

/**
 * Renbo.cz
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Log\Formatter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for Db formatter
 *
 * @package EventLog
 * @subpackage Log
 */
class DbServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$options = $serviceLocator->get('EventLog\Options');

		$formatter = new Db(
			$options->getSeparateFromExtra(),
			$options->getSerializeProperties(),
			$options->getSerializer()
		);

		return $formatter;
	}
}
