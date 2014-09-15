<?php

namespace GateKeeperBundle\Service;

class GateKeeperConfiguration
{
	private $configuration = [];

	public function __construct($configuration)
	{
		foreach ($configuration as $gate)
		{
			$this->configuration[$gate['name']] = $gate;
		}
	}

	public function getDefaultAccess($gate)
	{
		return $this->configuration[(string)$gate]['default'];
	}

	public function getAttributes($gate)
	{
		return $this->configuration[(string)$gate]['attributes'];
	}

}