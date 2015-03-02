<?php

/**
 * zf2-event-logger
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Log;

use Zend\EventManager\Event;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\I18n\Translator\TranslatorAwareTrait;
use Zend\Log\Exception\InvalidArgumentException;

/**
 * Description of Event
 *
 * @package EventLog
 * @subpackage EventMapper
 */
class LoggableEvent extends Event implements LoggableEventInterface, TranslatorAwareInterface
{
	use TranslatorAwareTrait;

	/**
	 * @var string
	 */
	protected $messageTemplate = "The event %name% occured";

	/**
	 * @var int
	 */
	protected $logPriority = Logger::INFO;

	public function __construct($name = null, $target = null, $params = null, $message = null, $priority = null)
	{
		parent::__construct($name, $target, $params);

		if (! is_null($message)) {
			$this->setMessage($message);
		}

		if (! is_null($priority)) {
			$this->setLogPriority($priority);
		}
	}

	/**
	 * Get message briefly describing the event
	 * 
	 * @return string
	 */
	public function getMessage()
	{
		return $this->_createMessage($this->messageTemplate);
	}

	/**
	 * Set message briefly describing the event
	 * 
	 * In message string can be used message variables %name%
	 * and %target% which would be replaced with event name
	 * and string representation of target.
	 * 
	 * @param string $message
	 * @return LoggableEvent
	 */
	public function setMessage($message)
	{
		$this->messageTemplate = $message;
		return $this;
	}

	/**
	 * Get priority to be used when the event is logged
	 * 
	 * @see Logger constants
	 * 
	 * @return int
	 */
	public function getLogPriority()
	{
		return $this->logPriority;
	}

	/**
	 * Set priority to be used when the event is logged
	 *
	 * @see Logger constants
	 * 
	 * @param int $priority
	 * @return LoggableEvent
	 */
	public function setLogPriority($priority)
	{
		if (! is_int($priority) || ($priority < Logger::EMERG) || ($priority >= Logger::DEBUG)) {
			throw new InvalidArgumentException(sprintf(
			'$priority must be an integer > 0 and < %d; received %s',
			count($this->priorities),
			var_export($priority, 1)
			));
		}

		$this->logPriority = $priority;

		return $this;
	}

	public function __sleep()
	{
		$target = $this->getTarget();
		$this->setTarget(is_object($target) ? get_class($target) : null);

		return array(
			'name',
			'target',
			'params',
			'stopPropagation',
			'messageTemplate',
			'logPriority'
		);
	}

	/**
	 * Constructs and returns the event message.
	 *
	 * If a translator is available and a translation exists for $messageTemplate,
	 * the translation will be used.
	 *
	 * @param string $messageTemplate
	 * @return string
	 */
	protected function _createMessage($messageTemplate, $messageVariables = array())
	{
		$message = $this->_translateMessage($messageTemplate);

		$messageVariables['name'] = strtoupper($this->getName());
		$messageVariables['target'] = $this->getTarget();

		$message = preg_replace_callback('/%([^%]+)%/si', function($match) use($messageVariables) {
			return isset($messageVariables[$match[1]]) ? $messageVariables[$match[1]] : $match[0];
		}, $message);

		return $message;
	}

	/**
	 * Translate the given message message
	 *
	 * @param string $message
	 * @return string
	 */
	protected function _translateMessage($message)
	{
		$translator = $this->getTranslator();
		if (!$translator) {
			return $message;
		}

		return $translator->translate($message, $this->getTranslatorTextDomain());
	}
}