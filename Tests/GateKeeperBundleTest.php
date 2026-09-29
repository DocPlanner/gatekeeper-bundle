<?php

namespace GateKeeperBundle\Tests;

use GateKeeper\GateKeeper;
use GateKeeper\Model\ModelInterface;
use GateKeeper\Repository\RepositoryInterface;
use GateKeeperBundle\GateKeeperBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class GateKeeperBundleTest extends TestCase
{
	/** @var ContainerBuilder */
	private $container;

	protected function setUp(): void
	{
		$bundle = new GateKeeperBundle();
		$this->container = new ContainerBuilder();
		$this->container->registerExtension($bundle->getContainerExtension());
		$this->container->loadFromExtension('gate_keeper', ['repository_service' => 'test.repository']);
		$this->container->register('test.repository', InMemoryRepository::class);
		$bundle->build($this->container);
		$this->container->addCompilerPass(new PublicVoterPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION);
		$this->container->compile();
	}

	public function testContainerDumps(): void
	{
		$this->assertStringContainsString('class GateKeeperTestContainer', (new PhpDumper($this->container))->dump(['class' => 'GateKeeperTestContainer']));
	}

	public function testGateKeeperServiceIsPublic(): void
	{
		$gateKeeper = $this->container->get(GateKeeper::class);

		$this->assertInstanceOf(GateKeeper::class, $gateKeeper);
		$this->assertTrue($gateKeeper->hasAccess('GATE_ON'));
		$this->assertFalse($gateKeeper->hasAccess('GATE_OFF'));
		$this->assertFalse($gateKeeper->hasAccess('GATE_MISSING'));
	}

	public function testVoter(): void
	{
		$this->assertArrayHasKey('gatekeeper.voter', $this->container->findTaggedServiceIds('security.voter'));

		$voter = $this->container->get('gatekeeper.voter');
		$token = new TestToken();

		$this->assertSame(VoterInterface::ACCESS_GRANTED, $voter->vote($token, null, ['GATE_ON']));
		$this->assertSame(VoterInterface::ACCESS_DENIED, $voter->vote($token, null, ['GATE_OFF']));
		$this->assertSame(VoterInterface::ACCESS_ABSTAIN, $voter->vote($token, null, ['ROLE_ADMIN']));
	}
}

class InMemoryRepository implements RepositoryInterface
{
	public function save(ModelInterface $gateKeeperModel) {}
	public function update(ModelInterface $gateKeeperModel) {}
	public function delete(ModelInterface $gateKeeperModel) {}

	public function get($name)
	{
		$accesses = ['GATE_ON' => 'allow-all', 'GATE_OFF' => 'deny-all'];

		return isset($accesses[$name]) ? new Model($accesses[$name]) : null;
	}
}

class Model implements ModelInterface
{
	private $access;

	public function __construct($access) { $this->access = $access; }
	public function getAccess() { return $this->access; }
	public function getGate() { return ''; }
}

class TestToken extends AbstractToken
{
	public function __construct() { parent::__construct([]); }
	public function getCredentials() { return ''; }
}

class PublicVoterPass implements CompilerPassInterface
{
	public function process(ContainerBuilder $container)
	{
		$container->getDefinition('gatekeeper.voter')->setPublic(true);
	}
}
