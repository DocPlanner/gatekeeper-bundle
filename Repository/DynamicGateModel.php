<?php

namespace GateKeeperBundle\Provider;

use GateKeeper\Model\ModelInterface;

class DynamicGateModel implements ModelInterface
{
	private $access;
	private $gate;

	function __construct($gate, $access)
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