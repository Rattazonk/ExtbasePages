<?php

namespace Rattazonk\Extbasepages\Tests\Unit\Tree\Filter;

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
 * Test case for class \Rattazonk\Extbasepages\Tree\Filter\AbstractFilter.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Frederik Vosberg <frederik.vosberg@rattazonk.com>
 */
class AbstractFilterTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var Rattazonk\Extbasepages\Tree\Filter\AbstractFilter
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMockForAbstractClass(
			'Rattazonk\Extbasepages\Tree\Filter\AbstractFilter'
		);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function registerResponsible() {
		$pageTreeMock = $this->getMock('Rattazonk\Extbasepages\Tree\PageTree');

		$this->subject->expects($this->once())
			->method('isResponsible')
			->will($this->returnValue(TRUE));

		$pageTreeMock->expects($this->once())
			->method('addFilter')
			->with($this->equalTo($this->subject));

		$this->subject->registerIfResponsible( $pageTreeMock );
	}

	/**
	 * @test
	 */
	public function notRegisterNotResponsible() {
		$pageTreeMock = $this->getMock('Rattazonk\Extbasepages\Tree\PageTree');

		$this->subject->expects($this->once())
			->method('isResponsible')
			->will($this->returnValue(FALSE));

		$pageTreeMock->expects($this->never())
			->method('addFilter');

		$this->subject->registerIfResponsible( $pageTreeMock );
	}
}
