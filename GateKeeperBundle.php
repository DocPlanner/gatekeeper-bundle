<?php

namespace GateKeeperBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use GateKeeperBundle\DependencyInjection\Compiler\GateKeeperCompilerPass;

class GateKeeperBundle extends Bundle
{
	/**
	 * @param ContainerBuilder $container
	 */
	public function build(ContainerBuilder $container)
	{
		$container->addCompilerPass(new GateKeeperCompilerPass());
	}
}
