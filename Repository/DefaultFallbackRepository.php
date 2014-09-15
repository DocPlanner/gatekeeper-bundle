<?php

namespace GateKeeperBundle\Provider;

use GateKeeper\Model\ModelInterface;
use GateKeeper\Repository\RepositoryInterface;
use GateKeeperBundle\Service\GateKeeperConfiguration;

class DefaultFallbackRepository implements RepositoryInterface
{
	/**
	 * @var RepositoryInterface
	 */
	private $repository;
	/**
	 * @var GateKeeperConfiguration
	 */
	private $configuration;

	public function __construct(RepositoryInterface $repository, GateKeeperConfiguration $configuration)
	{
		$this->repository = $repository;
		$this->configuration = $configuration;
	}


	/**
	 * @param ModelInterface $gateKeeperModel
	 *
	 * @return bool
	 */
	public function save(ModelInterface $gateKeeperModel)
	{
		return $this->repository->save($gateKeeperModel);
	}

	/**
	 * @param ModelInterface $gateKeeperModel
	 *
	 * @return bool
	 */
	public function update(ModelInterface $gateKeeperModel)
	{
		return $this->repository->update($gateKeeperModel);
	}

	/**
	 * @param ModelInterface $gateKeeperModel
	 *
	 * @return bool
	 */
	public function delete(ModelInterface $gateKeeperModel)
	{
		return $this->repository->delete($gateKeeperModel);
	}

	/**
	 * @param string $name
	 *
	 * @return ModelInterface|null
	 */
	public function get($name)
	{
		return $this->repository->get($name) ?: new DynamicGateModel($name, $this->configuration->getDefaultAccess($name));
	}
}