<?php
/**
 * Author: Łukasz Barulski
 * Date: 23.04.14 16:07
 */

namespace GateKeeperBundle\Access;

use GateKeeper\Access\AbstractAccess;

class Allow extends AbstractAccess
{
	/**
	 * @return string
	 */
	public function getName()
	{
		return 'allow-all';
	}

	/**
	 * @return bool
	 */
	public function hasAccess()
	{
		return true;
	}
}