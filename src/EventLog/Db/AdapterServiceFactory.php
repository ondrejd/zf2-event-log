<?php

/**
 * Renbo.cz
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Db;

use Zend\Config\Exception\RuntimeException;
use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of AdapterFactory
 *
 * @package EventLog
 * @subpackage Db
 */
class AdapterServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$options = $serviceLocator->get('EventLog\Options');

		$dbAdapter = new Adapter($options->getDb());
		return $dbAdapter;
	}
}
