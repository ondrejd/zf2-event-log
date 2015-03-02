<?php

/**
 * Renbo.cz
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Log\Processor;

use Zend\Http\PhpEnvironment\RemoteAddress;
use Zend\Log\Processor\ProcessorInterface;

/**
 * Description of IpAddress
 *
 * @package EventLog
 * @subpackage Processor
 */
class IpAddress implements ProcessorInterface
{
	public function process(array $event)
	{
		$remoteAddress = new RemoteAddress;
		$event['extra']['ipAddress'] = $remoteAddress->getIpAddress();

		return $event;
	}
}
