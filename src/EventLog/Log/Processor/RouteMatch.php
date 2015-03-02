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
use Zend\Mvc\Application;
use Zend\Mvc\Router;

/**
 * Description of RouteMatch
 *
 * @package EventLog
 * @subpackage Log
 */
class RouteMatch implements ProcessorInterface
{
	/**
	 * @var Application
	 */
	protected $application;

	public function __construct(Application $application)
	{
		$this->application = $application;
	}

	public function process(array $event)
	{
		$routeMatch = $this->application->getMvcEvent()->getRouteMatch();

		if ($routeMatch instanceof Router\RouteMatch) {
			$routeMatchParams = array();
			foreach ($routeMatch->getParams() as $key => $value) {
				$routeMatchParams[$key] = $this->_valueToString($value);
			}

			$event['extra']['routeMatch'] = array(
				'name' => $routeMatch->getMatchedRouteName(),
				'params' => $routeMatchParams
			);
		}

		return $event;
	}

	protected function _valueToString($value)
	{
		// TODO

		if (is_object($value)) {
			return get_class($value);
		} elseif (is_array($value)) {
			return '[Array]';
		}
			
		return (string) $value;
	}
}
