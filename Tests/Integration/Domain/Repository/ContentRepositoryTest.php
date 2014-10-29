<?php

namespace Rattazonk\Extbasepages\Tests\Integration\Domain\Repository;

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
 * Test case for class \Rattazonk\Extbasepages\Tree\PageTree.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Frederik Vosberg <frederik.vosberg@rattazonk.com>
 */
class ContentRepositoryTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \Rattazonk\Extbasepages\Tree\PageTree
	 */
	protected $subject = NULL;

	/**
	 * @var Tx_Phpunit_Framework
	 */
	protected $testingFramework;

	/** @var int **/
	protected $currentPageUid;
	
	protected $pageRepositoryMock;

	protected function setUp() {
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
			'TYPO3\CMS\Extbase\Object\ObjectManager'
		);
		$this->subject = $this->objectManager->get('Rattazonk\Extbasepages\Domain\Repository\ContentRepository');
    // foobar because the tables we want to test are allowed sys tables 
		$this->testingFramework = new \Tx_Phpunit_Framework('foobar');
		$this->currentPageUid = $this->testingFramework->createFakeFrontEnd();
	}

	protected function tearDown() {
		unset( $this->subject );
    if( is_object($this->testingFramework) ){
      $this->testingFramework->cleanUp();
    }
		unset( $this->testingFramework );
	}

	/**
	 * @test
	 */
	public function instantiateShortcuts() {
    $textUid = $this->testingFramework->createContentElement(
      $this->currentPageUid, // pid
      array( 'CType' => 'text' )
    );
    $shortcutUid = $this->testingFramework->createContentElement(
      $this->currentPageUid, // pid
      array( 'CType' => 'shortcut' )
    );
    $this->assertInstanceOf(
      'Rattazonk\Extbasepages\Domain\Model\Content',
      $this->subject->findByUid( $textUid ),
      'The record with the CType text must be an instance of Content'
    );
    $this->assertInstanceOf(
      'Rattazonk\Extbasepages\Domain\Model\Content\Shortcut',
      $this->subject->findByUid( $shortcutUid ),
      'The record with the CType shortcut must be an instance of Shortcut'
    );
	}
}

