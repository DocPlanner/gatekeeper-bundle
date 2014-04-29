<?php
/**
 * Author: Łukasz Barulski
 * Date: 29.04.14 14:17
 */
namespace GateKeeperBundle\Twig;

use GateKeeper\GateKeeper;

class TwigExtension extends \Twig_Extension
{
	/**
	 * @var GateKeeper
	 */
	private $gateKeeper;

	/**
	 * @param GateKeeper $gateKeeper
	 */
	public function __construct(GateKeeper $gateKeeper)
	{
		$this->gateKeeper = $gateKeeper;
	}

	/**
	 * @return array
	 */
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('gate', [$this->gateKeeper, 'hasAccess']),
		];
	}

	/**
	 * Returns the name of the extension.
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'gatekeeper';
	}
}