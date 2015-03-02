<?php

/**
 * zf2-event-logger
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Model;

use DateTime;
use EventLog\Log\Formatter\Db as DbFormatter;
use Zend\Serializer\Serializer;
use Zend\Serializer\Adapter\AdapterInterface as SerializerInterface;

/**
 * Description of EventLogEntity
 *
 * @package EventLog
 * @subpackage Model
 */
class LogEntity
{
	/**
	 * @var array $data
	 */
	protected $data;

	/**
	 * @var string[]
	 */
	protected $serializeProperties = array();

	/**
	 * @var SerializerInterface
	 */
	protected $serializer;

	/**
	 * @var array
	 */
	protected $columnMap = array();

	public function __construct($data = array())
	{
		$this->exchangeArray($data);
	}

	public function exchangeArray($data = array())
	{
		$this->data = $data;
	}

	public function __call($name, $arguments)
	{
		if (method_exists($this, $name)) {
			return call_user_func_array(array($this, $name), $arguments);
		}

		$modifier = substr($name, 0, 3);
		$property = lcfirst(substr($name, 3));

		$column = null;
		if ($modifier === 'get' && isset($this->columnMap[$property])) {
			$column = $this->columnMap[$property];
		}

		if (! $column) {
			trigger_error(sprintf( 'Call to undefined method %s::%s()', __CLASS__, $name), E_USER_ERROR);
		}
			
		$value = isset($this->data[$column]) ? $this->data[$column] : null;

		if (in_array($property, $this->serializeProperties)) {
			$value = $this->getSerializer()->unserialize($value);
		}

		return $value;
	}

	public function getId()
	{
		return $this->data['id'];
	}

	public function getTimestamp()
	{
		$dateTime = DateTime::createFromFormat(DbFormatter::DATETIME_FORMAT, $this->data['timestamp']);
		return $dateTime;
	}

	public function getColumnMap()
	{
		return $this->columnMap;
	}

	public function setColumnMap($columnMap)
	{
		$this->columnMap = $columnMap;
	}

	/**
	 * Set properties to be serialized using $this->serializer
	 * 
	 * @param string[] $properties
	 */
	public function setSerializeProperties($properties)
	{
		$this->serializeProperties = $properties;
	}

	/**
	 * Get properties to be serialized using $this->serializer
	 * 
	 * @return string[]
	 */
	public function getSerializeProperties()
	{
		return $this->serializeProperties;
	}

	/**
	 * Get serializer for data unserialization
	 * 
	 * @return SerializerInterface
	 */
	public function getSerializer()
	{
		return $this->serializer;
	}

	/**
	 * Set serializer for data unserialization
	 * 
	 * @param string|SerializerInterface $serializer
	 */
	public function setSerializer($serializer)
	{
		$this->serializer = Serializer::factory($serializer);
	}

	protected function _getRaw($name)
	{
		return isset($this->data[$name])
			? $this->data[$name]
			: null;
	}
}
