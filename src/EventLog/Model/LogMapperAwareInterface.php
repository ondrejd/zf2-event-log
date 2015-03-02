<?php

/**
 * Renbo.cz
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Model;

/**
 * Log mapper aware interface
 *
 * @package EventLog
 * @subpackage Model
 */
interface LogMapperAwareInterface
{
	/**
	 * Set log mapper
	 * 
	 * @param LogMapper $mapper
	 */
	public function setLogMapper(LogMapper $mapper);
}
