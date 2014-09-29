<?php

namespace Rattazonk\Extbasepages\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Frederik Vosberg <frederik.vosberg@rattazonk.com>, Rattazonk
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class \Rattazonk\Extbasepages\Domain\Model\Page.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Frederik Vosberg <frederik.vosberg@rattazonk.com>
 */
class PageTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \Rattazonk\Extbasepages\Domain\Model\Page
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
			'TYPO3\CMS\Extbase\Object\ObjectManager'
		);
		$this->subject = $this->objectManager->get('Rattazonk\Extbasepages\Domain\Model\Page');
	}

	protected function tearDown() {
		unset($this->subject);
	}

	protected function _testGetterSetter( $name, $value ) {
		$setter = 'set' . ucfirst( $name );
		$getter = 'get' . ucfirst( $name );

		$this->assertNotSame(
			$value,
			$this->subject->$getter()
		);
		$this->subject->$setter( $value );
		$this->assertSame(
			$value,
			$this->subject->$getter()
		);
	}

	/**
	 * Need the getter setter for other extensions, so I can test them implicitly
	 *
	 * @test
	 */
	public function getSetTitle() {
		$this->_testGetterSetter( 'title', 'fooBar' );
	}

	/**
	 * Need the getter setter for other extensions, so I can test them implicitly
	 *
	 * @test
	 */
	public function getSetSubTitle() {
		$this->_testGetterSetter( 'subTitle', 'fooBar' );
	}

	/**
	 * Need the getter setter for other extensions, so I can test them implicitly
	 *
	 * @test
	 */
	public function getSetDoktype() {
		$this->_testGetterSetter( 'doktype', 'fooBar' );
	}

	/**
	 * Need the getter setter for other extensions, so I can test them implicitly
	 *
	 * @test
	 */
	public function getSetChildren() {
		$this->assertInstanceOf(
			'TYPO3\CMS\Extbase\Persistence\ObjectStorage',
			$this->subject->getChildren()
		);

		$objectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->_testGetterSetter( 'children', $objectStorage );
	}

	/**
	 * @test
	 */
	public function getFirstContentFromObjectStorage() {
		$objectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$contentMockOne = $this->getMock( 'Rattazonk\Extbasepages\Domain\Model\Content' );
		$contentMockTwo = $this->getMock( 'Rattazonk\Extbasepages\Domain\Model\Content' );

		$objectStorage->attach( $contentMockOne );
		$objectStorage->attach( $contentMockTwo );

		$this->subject->setContent( $objectStorage );

		$this->assertSame(
			$contentMockOne,
			$this->subject->getFirstContent()
		);

		return $objectStorage;
	}

	/**
	 * @test
	 */
	public function getSetStartTime() {
		$startTimeTimestamp = 1411847530;
		$startTimeDateTime = new \DateTime();
		$startTimeDateTime->setTimestamp( $startTimeTimestamp );

		$this->assertNotEquals(
			$startTimeDateTime,
			$this->subject->getStartTime()
		);
		$this->subject->setStartTime( $startTimeDateTime );
		$this->assertEquals(
			$startTimeDateTime,
			$this->subject->getStartTime()
		);

		$this->subject->setStartTime( $startTimeTimestamp );
		$this->assertEquals(
			$startTimeDateTime,
			$this->subject->getStartTime()
		);
	}
}
