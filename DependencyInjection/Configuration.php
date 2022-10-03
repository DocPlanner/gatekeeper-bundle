<?php

namespace GateKeeperBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /** @var string */
    protected $alias;

    public function __construct($alias = 'gate_keeper')
    {
        $this->alias = $alias;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder($this->alias);

        $rootNode = method_exists($treeBuilder, 'getRootNode') ?
            $treeBuilder->getRootNode()
            :
            $treeBuilder->root($this->alias); // BC layer for symfony/config 4.1 and older

		$rootNode
			->children()
				->scalarNode('repository_service')
					->defaultValue('gatekeeper.repository.dummy')
				->end()
			    ->scalarNode('provider_service')
					->defaultValue('gatekeeper.gates_provider.dummy')
				->end()
			->end();

        return $treeBuilder;
    }
}
