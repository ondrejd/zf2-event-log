<?php

/**
 * Renbo.cz
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Log\Writer;

use EventLog\Log\Formatter\Db as DbFormatter;
use Zend\Log\Writer\Db;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for Db writer
 *
 * @package EventLog
 * @subpackage Writer
 */
class DbServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $writerPluginManager)
	{
		$serviceLocator = $writerPluginManager->getServiceLocator();

		$options = $serviceLocator->get('EventLog\Options');
		$dbAdapter = $serviceLocator->get('EventLog\Db\Adapter');

		$writer = new Db(
			$dbAdapter,
			$options->getTableName(),
			$options->getColumnMap()
		);

		$formatter = new DbFormatter(
			$options->getSeparateFromExtra(),
			$options->getSerializeProperties(),
			$options->getSerializer()
		);

		$writer->setFormatter($formatter);
		
		return $writer;
	}
}
