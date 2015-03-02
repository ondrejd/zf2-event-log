<?php

/**
 * Renbo.cz
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog;

use Zend\Config\Exception\RuntimeException;
use Zend\Serializer\Adapter\AdapterInterface as SerializerInterface;
use Zend\Stdlib\AbstractOptions;

/**
 * Description of Options
 *
 * @package EventLog
 */
class Options
{
	/**
	 * @var array
	 */
	protected $options;

	/**
	 * @var string[]
	 */
	protected $defaultSeparateFromExtra = array();

	/**
	 * @var string[]
	 */
	protected $defaultSerializeProperties = array();

	/**
	 * @var string|SerializerInterface
	 */
	protected $defaultSerializer = 'PhpSerialize';

	/**
	 * @var array
	 */
	protected $defaultColumnMap = array();

	public function __construct($options)
	{
		$this->options = $options;
	}

	public function getDb()
	{
		if (! $this->_option('db')) {
			throw new RuntimeException('Missing database configuration for EventLog module.');
		}

		return $this->_option('db');
	}

	public function getTableName()
	{
		if (! $this->_option('table_name')) {
			throw new RuntimeException('Missing table name configuration for EventLog module.');
		}

		return $this->_option('table_name');
	}

	public function getSeparateFromExtra()
	{
		if (! $this->_option('separate_from_extra')) {
			return $this->defaultSeparateFromExtra;
		}

		return $this->_option('separate_from_extra');
	}

	public function getSerializeProperties()
	{
		if (! $this->_option('serialize_properties')) {
			return $this->defaultSerializeProperties;
		}

		return $this->_option('serialize_properties');
	}

	public function getSerializer()
	{
		if (! $this->_option('serializer')) {
			return $this->defaultSerializer;
		}

		return $this->_option('serializer');
	}

	public function getColumnMap()
	{
		if (! $this->_option('column_map')) {
			return $this->defaultColumnMap;
		}

		return $this->_option('column_map');
	}

	public function getProcessorPluginManager()
	{
		return $this->_option('processor_plugin_manager');
	}

	public function getWriterPluginManager()
	{
		return $this->_option('writer_plugin_manager');
	}

	public function getProcessors()
	{
		return $this->_option('processors');
	}

	public function getWriters()
	{
		return $this->_option('writers');
	}

	protected function _option($key) {
		return isset($this->options[$key]) ? $this->options[$key] : null;
	}
}
