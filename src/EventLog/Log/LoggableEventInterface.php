<?php

/**
 * zf2-event-logger
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Log;

use Zend\EventManager\EventInterface;

/**
 * Loggabe event interface
 *
 * @package EventLog
 * @subpackage Log
 */
interface LoggableEventInterface extends EventInterface
{
	/**
	 * Get message briefly describing the event
	 * 
	 * @return string
	 */
	public function getMessage();

	/**
	 * Get priority to be used when the event is logged
	 * 
	 * @see \Zend\Log\Logger constants
	 * 
	 * @return int
	 */
	public function getLogPriority();
}
