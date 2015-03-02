<?php

/**
 * zf2-event-logger
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Controller;

use EventLog\Model\LogMapper;
use EventLog\Model\LogMapperAwareInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController implements LogMapperAwareInterface
{
	const LOG_ITEM_COUNT_PER_PAGE = 30;

	/**
	 * @var LogMapper
	 */
	protected $logMapper;

	public function indexAction()
	{
		$pageNumber = $this->params()->fromQuery('page');

		$logs = $this->logMapper->getAll(array('paginate' => true));
		$logs->setCurrentPageNumber($pageNumber);
		$logs->setItemCountPerPage(self::LOG_ITEM_COUNT_PER_PAGE);

		$view = new ViewModel;
		$view->logs = $logs;

		return $view;
	}

	public function setLogMapper(LogMapper $mapper)
	{
		$this->logMapper = $mapper;
	}
}
