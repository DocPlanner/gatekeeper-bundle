<?php
/**
 * Author: Łukasz Barulski
 * Date: 23.04.14 16:07
 */

namespace GateKeeperBundle\Access;

use GateKeeper\Access\AbstractAccess;

class Deny extends AbstractAccess
{
	/**
	 * @return string
	 */
	public function getName()
	{
		return 'deny-all';
	}
}