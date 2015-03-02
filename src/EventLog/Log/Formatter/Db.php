<?php

/**
 * Renbo.cz
 *
 * @link https://github.com/renbocz/zf2-event-log for the canonical source repository
 * @copyright Copyright (c) 2014 Richard Jedlička, <jedlicka.r@gmail.com>
 * @license http://www.mozilla.org/MPL/2.0 Mozilla Public License 2.0
 */

namespace EventLog\Log\Formatter;

use Traversable;
use Zend\Log\Formatter\Base;
use Zend\Serializer\Serializer;
use Zend\Serializer\Adapter\AdapterInterface as SerializerInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Description of Db
 *
 * @package EventLog
 * @subpackage Log
 */
class Db extends Base
{
	const DATETIME_FORMAT = 'U';

	/**
	 * @var string[]
	 */
	protected $separateFromExtra = array();

	/**
	 * @var string[]
	 */
	protected $serializeProperties = array();

	/**
	 * @var SerializerInterface
	 */
	protected $serializer;

	/**
	 * Constructor
	 * 
	 * @param array $separateFromExtra @see setSeparateFromExtra
	 * @param array $serializeProperties @see setSerializeProperties
	 * @param string|SerializerInterface $serializer @see setSerializer
	 */
	public function __construct($separateFromExtra = array(), $serializeProperties = array(), $serializer = 'PhpSerialize')
	{
		parent::__construct(self::DATETIME_FORMAT);

		$this->setSeparateFromExtra($separateFromExtra);
		$this->setSerializeProperties($serializeProperties);
		$this->setSerializer($serializer);
	}

	/**
	 * Formats data to be written by the writer.
	 * 
	 * @param array $logEvent
	 * @return array
	 */
	public function format($logEvent)
	{
		// separate extra properties
		$extra = $logEvent['extra'];

		if ($extra instanceof Traversable) {
			$extra = ArrayUtils::iteratorToArray($extra);
		}

		if (is_array($extra)) {
			foreach($this->separateFromExtra as $property) {
				if (isset($extra[$property])) {
					$logEvent[$property] = $extra[$property];
					unset($extra[$property]);
				}
			}
		}

		$logEvent['extra'] = $extra;

		// serialize properties
		foreach($this->serializeProperties as $property) {
			if (isset($logEvent[$property])) {
				$logEvent[$property] = $this->getSerializer()->serialize($logEvent[$property]);
			}
		}

		return parent::format($logEvent);
	}

	/**
	 * Set extra properties to be seperated into individual ones
	 * 
	 * @param string[] $properties
	 */
	public function setSeparateFromExtra($properties)
	{
		$this->separateFromExtra = $properties;
	}

	/**
	 * Get extra properties to be seperated into individual ones
	 * 
	 * @return string[]
	 */
	public function getSeparateFromExtra()
	{
		return $this->separateFromExtra;
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
	 * Get serializer for 'extra' column
	 * 
	 * @return Serializer
	 */
	public function getSerializer()
	{
		return $this->serializer;
	}

	/**
	 * Set serializer for 'extra' column
	 * 
	 * @param string|SerializerInterface $serializer
	 */
	public function setSerializer($serializer)
	{
		$this->serializer = Serializer::factory($serializer);
	}
}
