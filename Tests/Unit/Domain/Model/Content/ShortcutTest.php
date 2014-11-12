<?php

namespace Rattazonk\Extbasepages\Tests\Unit\Domain\Model\Content;

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
class ShortcutTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \Rattazonk\Extbasepages\Domain\Model\Content\Shortcut
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = new \Rattazonk\Extbasepages\Domain\Model\Content\Shortcut();
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getRecordsFromTtContent() {
    $contentRepositoryMock = $this->getMockBuilder(
      'Rattazonk\Extbasepages\Domain\Repository\ContentRepository'
    )->disableOriginalConstructor()
    ->setMethods(array('findByUid'))
    ->getMock();

    $this->inject( $this->subject, 'contentRepository', $contentRepositoryMock );
    $this->inject( $this->subject, 'recordsStorage', new \TYPO3\CMS\Extbase\Persistence\ObjectStorage() );
    $this->inject( $this->subject, 'records', 'tt_content_101,tt_content_131' );

    $zeroMock = $this->getMock('Rattazonk\Extbasepages\Domain\Model\Content');
    $contentRepositoryMock->expects($this->at(0))
      ->method('findByUid')
      ->with($this->equalTo( 101 ))
      ->will($this->returnValue( $zeroMock ));
    $threeMock = $this->getMock('Rattazonk\Extbasepages\Domain\Model\Content');
    $contentRepositoryMock->expects($this->at(1))
      ->method('findByUid')
      ->with($this->equalTo( 131 ))
      ->will($this->returnValue( $threeMock ));

    $records = $this->subject->getRecords();
    $this->assertInstanceOf(
      'TYPO3\CMS\Extbase\Persistence\ObjectStorage',
      $records,
      'We are expecting an object storage for the records'
    );
    $this->assertCount(
      2,
      $records
    );
    $this->assertEquals(
      $zeroMock,
      $records->current()
    );
    $records->next();
    $this->assertEquals(
      $threeMock,
      $records->current()
    );
	}
}
