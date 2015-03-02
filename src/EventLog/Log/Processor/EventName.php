<?php

/**
 * Renbo.cz
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Log\Processor;

use Zend\Log\Processor\ProcessorInterface;

/**
 * Description of EventName
 *
 * @package EventLog
 * @subpackage Processor
 */
class EventName implements ProcessorInterface
{
	public function process(array $logEvent)
	{
		if (isset($logEvent['extra']['event'])) {
			$event = $logEvent['extra']['event'];
			$logEvent['extra']['eventName'] = $event->getName();
		}

		return $logEvent;
	}
}
