<?php
/**
 * Author: Łukasz Barulski
 * Date: 23.04.14 16:30
 */

namespace GateKeeperBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GateKeeperCompilerPass implements CompilerPassInterface
{
	/**
	 * You can modify the container here before it is dumped to PHP code.
	 *
	 * @param ContainerBuilder $container
	 *
	 * @api
	 */
	public function process(ContainerBuilder $container)
	{
		if (!$container->hasDefinition('gatekeeper'))
		{
			return;
		}

		$definition = $container->getDefinition('gatekeeper');
		$definition->addArgument(new Reference($container->getParameter('gatekeeper.repository.service')));

		$taggedServices = $container->findTaggedServiceIds('gatekeeper.access');

		foreach ($taggedServices as $id => $attributes)
		{
			$definition->addMethodCall('addAccess', [
				new Reference($id)
			]);
		}
	}

} 