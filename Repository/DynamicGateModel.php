<?php

namespace GateKeeperBundle\Provider;

use GateKeeper\Model\ModelInterface;

class DynamicGateModel implements ModelInterface
{
	/**
	 * @var string
	 */
	private $access;
	/**
	 * @var string
	 */
	private $gate;

	/**
	 * @param string $gate
	 * @param string $access
	 */
	public function __construct($gate, $access)
	{
		$this->gate = $gate;
		$this->access = $access;
	}


	/**
	 * @return string
	 */
	public function getAccess()
	{
		return $this->access;
	}

	/**
	 * @return string
	 */
	public function getGate()
	{
		return $this->gate;
	}
}