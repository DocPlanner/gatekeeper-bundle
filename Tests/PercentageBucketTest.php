<?php

namespace GateKeeperBundle\Tests;

use GateKeeperBundle\Access\PercentageBucket;
use Symfony\Component\HttpFoundation\Session\Session;

class PercentageBucketTest extends \PHPUnit_Framework_TestCase
{

	public function testNoConfigurationDeniesAccess()
	{
		$session = $this->getSessionMock();
		$access = new PercentageBucket($session);

		$this->assertEquals(false, $access->hasAccess());
	}

	public function testBucketAttributeMustBeGreaterThanZero()
	{
		$session = $this->getSessionMock();
		$access = new PercentageBucket($session);
		$access->setAttributes(['bucket' => 0]);

		$this->assertEquals(false, $access->hasAccess());
	}

	public function testSessionMustBeStartedForAccessToBeGranted()
	{
		$session = $this->getSessionMock();
		$session->expects($this->once())->method('isStarted')->will($this->returnValue(false));
		$access = new PercentageBucket($session);
		$access->setAttributes(['bucket' => 1]);

		$this->assertEquals(false, $access->hasAccess());

	}

	/**
	 * @param bool $expected
	 * @dataProvider grandedAndDeniedProvider
	 */
	public function testAccessIsFetchedFromSession($expected)
	{
		$bucketValue = 1;
		$key = sprintf(PercentageBucket::KEY_ACCESS, 100 * $bucketValue);
		$session = $this->getSessionMock();
		$session->expects($this->once())->method('isStarted')->will($this->returnValue(true));
		$session->expects($this->once())->method('has')->with($this->equalTo($key))->will($this->returnValue(true));
		$session->expects($this->once())->method('get')->with($this->equalTo($key))->will($this->returnValue($expected));

		$access = new PercentageBucket($session);
		$access->setAttributes(['bucket' => 1]);

		$this->assertEquals($expected, $access->hasAccess());

	}

	public function testResultIsStoredInSession()
	{
		$bucketValue = 1;
		$key = sprintf(PercentageBucket::KEY_ACCESS, 100 * $bucketValue);
		$session = $this->getSessionMock();
		$session->expects($this->once())->method('isStarted')->will($this->returnValue(true));
		$session->expects($this->once())->method('has')->with($this->equalTo($key))->will($this->returnValue(false));
		$session->expects($this->once())->method('set')->with($this->equalTo($key), $this->equalTo(true));

		$access = new PercentageBucket($session);
		$access->setAttributes(['bucket' => 1]);

		$this->assertEquals(true, $access->hasAccess());

	}


	public function grandedAndDeniedProvider()
	{
		return [
			'granted' => [true],
			'denied' => [false],
		];
	}


	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|Session
	 */
	private function getSessionMock()
	{
		return $this->getMock(Session::class);
	}

}
 