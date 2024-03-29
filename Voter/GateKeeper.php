<?php
/**
 * Author: Łukasz Barulski
 * Date: 29.04.14 14:55
 */

namespace GateKeeperBundle\Voter;

use GateKeeper\GateKeeper as Keeper;
use GateKeeper\Item\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class GateKeeper implements VoterInterface
{
	/**
	 * @var \GateKeeper\GateKeeper
	 */
	private $gateKeeper;

	/**
	 * @param Keeper                 $gateKeeper
	 */
	public function __construct(Keeper $gateKeeper)
	{
		$this->gateKeeper = $gateKeeper;
	}

	/**
	 * Checks if the voter supports the given attribute.
	 *
	 * @param string $attribute An attribute
	 *
	 * @return Boolean true if this Voter supports the attribute, false otherwise
	 */
	public function supportsAttribute($attribute)
	{
		return strpos($attribute, 'GATE_') === 0;
	}

	/**
	 * Checks if the voter supports the given class.
	 *
	 * @param string $class A class name
	 *
	 * @return Boolean true if this Voter can process the class
	 */
	public function supportsClass($class)
	{
		return false;
	}

	/**
	 * Returns the vote for the given parameters.
	 * This method must return one of the following constants:
	 * ACCESS_GRANTED, ACCESS_DENIED, or ACCESS_ABSTAIN.
	 *
	 * @param TokenInterface $token      A TokenInterface instance
	 * @param object         $object     The object to secure
	 * @param array          $attributes An array of attributes associated with the method being invoked
	 *
	 * @return integer either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
	 */
	public function vote(TokenInterface $token, $object, array $attributes)
	{
		if (false === $this->supportsAttribute($attributes[0]))
		{
			return self::ACCESS_ABSTAIN;
		}

		if (1 !== count($attributes))
		{
			throw new \InvalidArgumentException('Only one attribute is allowed');
		}

		$user = $token->getUser() instanceof ItemInterface ? $token->getUser() : null;
		$object = is_array($object) ? $object : [];

		if ($this->gateKeeper->hasAccess($attributes[0], $user, $object))
		{
			return self::ACCESS_GRANTED;
		}

		return self::ACCESS_DENIED;
	}
}
