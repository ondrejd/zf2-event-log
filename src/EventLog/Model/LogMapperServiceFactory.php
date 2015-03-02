<?php

/**
 * Renbo.cz
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of LogMapperServiceFactory
 *
 * @package EventLog
 * @subpackage Model
 */
class LogMapperServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$options = $serviceLocator->get('EventLog\Options');
		$dbAdapter = $serviceLocator->get('EventLog\Db\Adapter');

		$mapper = new LogMapper($dbAdapter, $options->getTableName());

		$entityPrototype = new LogEntity();

		$entityPrototype->setColumnMap($options->getColumnMap());
		$entityPrototype->setSerializeProperties($options->getSerializeProperties());
		$entityPrototype->setSerializer($options->getSerializer());

		$mapper->setEntityPrototype($entityPrototype);

		return $mapper;
	}
}
