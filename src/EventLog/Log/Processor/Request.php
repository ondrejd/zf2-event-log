<?php

/**
 * Renbo.cz
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Log\Processor;

use Zend\Console\Request as ConsoleRequest;
use Zend\Http\PhpEnvironment\Request as HttpRequest;
use Zend\Log\Processor\ProcessorInterface;
use Zend\Stdlib\Message;

/**
 * Description of Request
 *
 * @package EventLog
 * @subpackage Log
 */
class Request implements ProcessorInterface
{
	/**
	 * @var HttpRequest|ConsoleRequest
	 */
	protected $request;

	/**
	 * @param HttpRequest|ConsoleRequest $request
	 */
	public function __construct($request)
	{
		$this->request = $request;
	}

	public function process(array $event)
	{
		if ($this->request instanceof HttpRequest) {
			$request = clone $this->request;
			$request->setContent(null);

			$event['extra']['request'] = $this->request->toString();
		} elseif ($this->request instanceof ConsoleRequest) {
			$event['extra']['request'] = $this->request->toString();
		}
		
		return $event;
	}
}
