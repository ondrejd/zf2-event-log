<?php

/**
 * zf2-event-logger
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

/**
 * Description of EventLogMapper
 *
 * @package EventLog
 * @subpackage Model
 */
class LogMapper
{
	const TABLE = 'event_log';

	/**
	 * @var AdapterInterface
	 */
	protected $dbAdapter;

	/**
	 * @var Sql
	 */
	protected $sql;

	/**
	 * @var LogEntity
	 */
	protected $entityPrototype;

	/**
	 * Constructor
	 * 
	 * @param AdapterInterface $dbAdapter
	 * @param string $tableName
	 */
	public function __construct(AdapterInterface $dbAdapter, $tableName = self::TABLE)
	{
		$this->dbAdapter = $dbAdapter;

		$this->sql = new Sql($dbAdapter);

		if ($tableName) {
			$this->sql->setTable($tableName);
		}
	}

	/**
	 * Set entity used as array object prototype for result set
	 * 
	 * @param LogEntity $entity
	 */
	public function setEntityPrototype(LogEntity $entity)
	{
		$this->entityPrototype = $entity;
	}

	/**
	 * Get entity used as array object prototype for result set
	 * 
	 * @return LogEntity
	 */
	public function getEntityPrototype()
	{
		if (! $this->entityPrototype instanceof LogEntity) {
			$this->setEntityPrototype(new LogEntity());
		}

		return $this->entityPrototype;
	}

	/**
	 * Get all logs
	 * 
	 * @param array $options (Optional)
	 * @return ResultSet|Paginator
	 */
	public function getAll($options = array())
	{
		$select = $this->sql->select();

		$resultSet = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, $this->getEntityPrototype());

		if (isset($options['order']) && $options['order']) {
			$select->order($options['order']);
		} else {
			$select->order('timestamp DESC');
		}

		if (isset($options['paginate']) && $options['paginate']) {
			$paginatorAdapter = new DbSelect($select, $this->dbAdapter, $resultSet);
			$paginator = new Paginator($paginatorAdapter);
			return $paginator;
		}

		$stmt = $this->sql->prepareStatementForSqlObject($select);
		$results = $stmt->execute();
		$resultSet->initialize($results);

		return $resultSet;
	}
}
