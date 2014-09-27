<?php

namespace Rattazonk\Extbasepages\Tests\Unit\Tree;

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
 * Test case for class \Rattazonk\Extbasepages\Tree\ElementWrapper.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Frederik Vosberg <frederik.vosberg@rattazonk.com>
 */
class ElementWrapperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \Rattazonk\Extbasepages\Tree\ElementWrapper
	 */
	protected $subject = NULL;

	protected function setUp() {
	}

	protected function tearDown() {
	}

	/**
	 * @test
	 */
	public function getWrappedElement() {
		$page = $this->getMock('Rattazonk\Extbasepages\Domain\Model\Page');
		$this->subject = new \Rattazonk\Extbasepages\Tree\ElementWrapper( $page );

		$this->assertSame(
			$page,
			$this->subject->getWrappedElement()
		);
	}

	/**
	 * @test
	 */
	public function passCallsThrough() {
		$page = $this->getMock('Rattazonk\Extbasepages\Domain\Model\Page', array('getFooBar'));
		$page->expects($this->any())
			->method( 'getFooBar' )
			->will( $this->returnValue('bazFoo') );

		$this->subject = new \Rattazonk\Extbasepages\Tree\ElementWrapper( $page );

		$this->assertSame(
			'bazFoo',
			$this->subject->getFooBar()
		);
	}

	/**
	 * @test
	 */
	public function overWriteChildrenWithoutTouchingWrappedElement() {
		$page = $this->getMock('Rattazonk\Extbasepages\Domain\Model\Page', array('setChildren', 'getChildren'));
		$page->expects($this->any())
			->method( 'getChildren' )
			->will( $this->returnValue('bazFoo') );

		$page->expects($this->never())
			->method( 'setChildren' );

		$this->subject = new \Rattazonk\Extbasepages\Tree\ElementWrapper( $page );

		$this->assertSame(
			'bazFoo',
			$this->subject->getChildren()
		);

		$this->subject->setChildren( 'fooBar' );

		$this->assertSame(
			'fooBar',
			$this->subject->getChildren()
		);

		$this->assertSame(
			'bazFoo',
			$this->subject->getWrappedElement()->getChildren()
		);
	}
}
