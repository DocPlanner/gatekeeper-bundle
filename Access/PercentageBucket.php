<?php

namespace GateKeeperBundle\Access;

use GateKeeper\Access\AbstractAccess;
use Symfony\Component\HttpFoundation\Session\Session;

class PercentageBucket extends AbstractAccess
{
	const KEY_ACCESS = 'percentage-bucket-access-%d';

	/**
	 * @var Session
	 */
	private $session;

	public function __construct(Session $session)
	{
		$this->session = $session;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return 'percentage-bucket';
	}

	/**
	 * @return bool
	 */
	public function hasAccess()
	{
		$requiredBucket = isset($this->attributes['bucket']) ? (float) $this->attributes['bucket'] : 0;

		if (0 >= $requiredBucket)
		{
			return false;
		}

		if (false === $this->session->isStarted())
		{
			return false;
		}

		$keyName = sprintf(self::KEY_ACCESS, $requiredBucket * 100);
		if ($this->session->has($keyName))
		{
			return (bool) $this->session->get($keyName);
		}

		$access = $requiredBucket > mt_rand() / mt_getrandmax();
		$this->session->set($keyName, $access);

		return $access;
	}
}