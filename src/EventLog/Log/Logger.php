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
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\I18n\Translator\TranslatorAwareTrait;
use Zend\Log\Logger as ZendLogger;

/**
 * Description of Logger
 *
 * @package EventLog
 * @subpackage Log
 */
class Logger extends ZendLogger implements EventManagerAwareInterface, TranslatorAwareInterface
{
	use EventManagerAwareTrait;
	use TranslatorAwareTrait;

	protected $eventLoggers = array();

	/**
	 * Register logging system as an logger for specified event
	 * 
	 * Params $id and $event has the same specification as in SharedEventManagerInterface::attach method.
	 * 
	 * The last argument is a callback that would by called before the event is logged. 
	 * It accepts the event as an argument. The callback must return TRUE of FALSE to
	 * check if the event should be logged. You can also modify the event here, 
	 * but logger processor may be more suitable for this (@see Logger::addProcessor).
	 * 
	 * @param string|array $id Identifier(s) for event emitting component(s)
	 * @param string|array|EventInterface $event Name of the event
	 * @param callable $processCallback (Optional)
	 */
	public function registerEventLogger($id, $event, $processCallback = null)
	{
		$ids = is_array($id) ? $id : array($id);
		$events = is_array($event) ? $event : array($event);

		$sharedEventManager = $this->getEventManager()->getSharedManager();

		foreach($ids as $id) {
			foreach($events as $event) {
				if ($event instanceof EventInterface) {
					$event = $event->getName();
				}

				// register only once for particular event per id
				if (isset($this->eventLoggers[$id][$event])) {
					continue;
				}

				$listener = $sharedEventManager->attach($id, $event, function($event) use($processCallback) {
					if ($processCallback) {
						$shouldLog = call_user_func($processCallback, $event);

						if(! $shouldLog) {
							return;
						}
					}

					if (! $event instanceof EventInterface) {
						return; // log only events
					}

					if (! $event instanceof LoggableEventInterface) {
						$event = new LoggableEventWrapper($event);
					}

					// TODO set translator elsewhere
					$event->setTranslator($this->getTranslator(), $this->getTranslatorTextDomain());

					$extra = array(
						'event' => $event
					);

					$this->log(
						$event->getLogPriority(),
						$event->getMessage(),
						$extra
					);
				});

				$this->eventLoggers[$id][$event] = $listener;
			}
		}
	}

	/**
	 * Unregister event logger
	 * 
	 * @param string $id
	 * @param string|EventInterface $event
	 * @return bool Returns true if id and event found, and unregistered; returns false if either id or event not found
	 */
	public function unregisterEventLogger($id, $event)
	{
		if ($event instanceof EventInterface) {
			$event = $event->getName();
		}

		if (! isset($this->eventLoggers[$id][$event])) {
			return false;
		}

		$listener = $this->eventLoggers[$id][$event];
		return $this->getEventManager()->getSharedManager()->detach($id, $listener);
	}
}
