<?php

/**
 * Renbo.cz
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Log;

use Zend\EventManager\EventInterface;

/**
 * Description of LoggableEventWrapper
 *
 * @package EventLog
 * @subpackage Log
 */
class LoggableEventWrapper extends LoggableEvent
{
	/**
	 * @var EventInterface
	 */
	protected $originalEvent;

	public function __construct(EventInterface $originalEvent, $message = null, $priority = null)
	{
		parent::__construct(
			$originalEvent->getName(),
			$originalEvent->getTarget(),
			$originalEvent->getParams(),
			$message,
			$priority
		);
	}

	/**
	 * Get original event
	 * 
	 * @return EventInterface
	 */
	public function getOriginalEvent()
	{
		return $this->originalEvent;
	}
}
