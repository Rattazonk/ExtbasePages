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
 * Test case for class \Rattazonk\Extbasepages\Tree\PageTree.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Frederik Vosberg <frederik.vosberg@rattazonk.com>
 */
class PageTreeTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
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
		$this->subject = $this->objectManager->get('Rattazonk\Extbasepages\Tree\PageTree');
		$this->testingFramework = new \Tx_Phpunit_Framework('foobar');

		$this->currentPageUid = $this->testingFramework->createFakeFrontEnd();
		$this->pageRepositoryMock = $this->getMockBuilder(
				'Rattazonk\Extbasepages\Domain\Repository\PageRepository'
			)->disableOriginalConstructor()
			->setMethods(array('findByParent'))
			->getMock();

		$this->inject( 
			$this->subject,
			'pageRepository',
			$this->pageRepositoryMock
		);
	}

	protected function tearDown() {
		unset( $this->subject );
		$this->testingFramework->cleanUp();
		unset( $this->testingFramework );
	}

	/**
	 * @test
	 */
	public function initializedUnderCurrentPageUid() {
		$this->pageRepositoryMock->expects($this->once())
			->method('findByParent')
			->with( $this->equalTo( $this->currentPageUid ) )
			->will( $this->returnValue(array()));

		$this->subject->getFirstLevelPages();
	}

	/**
	 * @test
	 */
	public function wrapPagesRecursively() {
		$pageMocks = $this->initTestTree();

		$firstLevel = $this->subject->getFirstLevelPages();

		$this->assertInstanceOf('\TYPO3\CMS\Extbase\Persistence\ObjectStorage', $firstLevel, 'Page tree returns not an object storage for the first level');
		$this->assertCount(2, $firstLevel, 'The first level hasnt 2 children');
		$firstLevel->rewind();
		$wrappedOne = $firstLevel->current();
		$this->isWrappedPage( $wrappedOne, $pageMocks['one'], 'One isnt wrapped' );

		$wrappedOneChildren = $wrappedOne->getChildren();
		$this->assertInstanceOf(
			'\TYPO3\CMS\Extbase\Persistence\ObjectStorage',
			$wrappedOneChildren,
			'Children of wrapped one are not in an object storage'
		);
		$this->assertCount(2, $wrappedOneChildren);
		$wrappedOneChildren->rewind();
		$wrappedOneOne = $wrappedOneChildren->current();
		$this->isWrappedPage( $wrappedOneOne, $pageMocks['oneOne'] );

		$wrappedOneOneChildren = $wrappedOneOne->getChildren();
		$this->assertInstanceOf('\TYPO3\CMS\Extbase\Persistence\ObjectStorage', $wrappedOneOneChildren);
		$this->assertCount(0, $wrappedOneOneChildren);

		$wrappedOneChildren->next();
		$wrappedOneTwo = $wrappedOneChildren->current();
		$this->isWrappedPage( $wrappedOneTwo, $pageMocks['oneTwo'] );

		$wrappedOneTwoChildren = $wrappedOneTwo->getChildren();
		$this->assertInstanceOf('\TYPO3\CMS\Extbase\Persistence\ObjectStorage', $wrappedOneTwoChildren);
		$this->assertCount(1, $wrappedOneTwoChildren);
		$wrappedOneTwoChildren->rewind();
		$this->isWrappedPage( $wrappedOneTwoChildren->current(), $pageMocks['oneTwoOne'] );


		$firstLevel->next();
		$wrappedTwo = $firstLevel->current();
		$this->isWrappedPage( $wrappedTwo, $pageMocks['two'] );

		$wrappedTwoChildren = $wrappedTwo->getChildren();
		$this->assertInstanceOf('\TYPO3\CMS\Extbase\Persistence\ObjectStorage', $wrappedTwoChildren);
		$this->assertCount(1, $wrappedTwoChildren);
		$wrappedTwoChildren->rewind();
		$this->isWrappedPage( $wrappedTwoChildren->current(), $pageMocks['twoOne'] );
	}

	/**
	 * initializes a test tree of pageMocks
	 * one
	 * 	oneOne
	 * 	oneTwo
	 * 		oneTwoOne
	 * two
	 * 	twoOne
	 */
	protected function initTestTree() {
		$pageMocks['one'] = $this->getMock(
			'Rattazonk\Extbasepages\Domain\Model\Page',
			array('getChildren')
		);
		$pageMocks['oneOne'] = $this->getMock(
			'Rattazonk\Extbasepages\Domain\Model\Page',
			array('getChildren')
		);
		$pageMocks['oneTwo'] = $this->getMock(
			'Rattazonk\Extbasepages\Domain\Model\Page',
			array('getChildren')
		);
		$pageMocks['oneTwoOne'] = $this->getMock(
			'Rattazonk\Extbasepages\Domain\Model\Page',
			array('getChildren')
		);
		$pageMocks['two'] = $this->getMock(
			'Rattazonk\Extbasepages\Domain\Model\Page',
			array('getChildren')
		);
		$pageMocks['twoOne'] = $this->getMock(
			'Rattazonk\Extbasepages\Domain\Model\Page',
			array('getChildren')
		);
		$this->pageCount = 6;

		$this->returnsAsChildren( $pageMocks['one'], array($pageMocks['oneOne'], $pageMocks['oneTwo']) );
		$this->returnsAsChildren( $pageMocks['oneOne'] );
		$this->returnsAsChildren( $pageMocks['oneTwo'], array($pageMocks['oneTwoOne']) );
		$this->returnsAsChildren( $pageMocks['oneTwoOne'] );
		$this->returnsAsChildren( $pageMocks['two'], array($pageMocks['twoOne']) );
		$this->returnsAsChildren( $pageMocks['twoOne'] );

		$this->pageRepositoryMock->expects($this->once())
			->method('findByParent')
			->with($this->equalTo( $this->currentPageUid ))
			->will($this->returnValue( array($pageMocks['one'], $pageMocks['two']) ));

		return $pageMocks;
	}

	protected function returnsAsChildren( $page, $children = array() ) {
		$page->expects($this->any())
			->method('getChildren')
			->will($this->returnValue( $children ));
	}

	protected function isWrappedPage( $wrapped, $page, $message = '' ) {
		$this->assertInstanceOf( '\Rattazonk\Extbasepages\Tree\ElementWrapper', $wrapped, $message );
		$this->assertSame( $page, $wrapped->getWrappedElement(), $message );
	}

	/**
	 * @test
	 */
	public function forEachElement() {
		$pageMocks = $this->initTestTree();

		$counter = 0;
		$this->subject->forEachElement( function($page) use (&$counter) {
			$counter++;
		});

		$this->assertEquals(
			$this->pageCount,
			$counter,
			'Foreach traversed not through all pages'
		);

		$counter = 0;
		$this->subject->forEachElement( function($page) use (&$counter, $pageMocks) {
			$counter++;
			if( $page->getWrappedElement() === $pageMocks['one'] ) {
				return FALSE;
			}
		});

		$this->assertEquals(
			$this->pageCount - 3,
			$counter,
			'Foreach didnt ignore the children of one'
		);

	}

	/**
	 * @test
	 */
	public function treeFilter() {
		$pageMocks = $this->initTestTree();

		$filter = $this->getMockForAbstractClass(
			'Rattazonk\Extbasepages\Tree\Filter\AbstractFilter',
			array(), '', TRUE, TRUE, TRUE,
			array('filter')
		);

		$filter->expects($this->exactly( $this->pageCount ))
			->method('filter');

		$this->subject->addFilter( $filter );
		$this->subject->getFirstLevelPages();
	}

	/**
	 * @test
	 */
	public function flattenedPages() {
		$this->initTestTree();

		$this->assertCount(
			$this->pageCount,
			$this->subject->getFlattenedPages()
		);
	}

	/**
	 * @test
	 */
	public function childrenOfHiddenAreHiddenByDefault() {
		$pageMocks = $this->initTestTree();

		// default is to hide children of hidden, so when we hide oneTwo, oneTwoOne should be hidden to
		$filter = $this->getMockForAbstractClass(
			'Rattazonk\Extbasepages\Tree\Filter\AbstractFilter',
			array(), '', TRUE, TRUE, TRUE,
			array('filter')
		);

		$filter->expects($this->any())
			->method('filter')
			->will($this->returnCallback(
				function() use ($pageMocks) {
					$currentWrapper = array_shift(func_get_args());
					$currentPage = $currentWrapper->getWrappedElement();
					if( $currentPage === $pageMocks['oneTwo'] ) {
						$currentWrapper->hideWrappedElement();
					}
				}));
		$this->subject->addFilter( $filter );
		$this->subject->getFirstLevelPages();

		foreach( $this->subject->getFlattenedPages() as $wrappedPage ) {
			if( $wrappedPage->getWrappedElement() === $pageMocks['oneTwo'] ) {
				$this->assertTrue( $wrappedPage->wrappedElementIsHidden(), 'This element should be directly hidden' );
			} elseif( $wrappedPage->getWrappedElement() === $pageMocks['oneTwoOne'] ) {
				$this->assertTrue( $wrappedPage->wrappedElementIsHidden(), 'This element should be hidden by default, because its parent was hidden, too' );
			} else {
				$this->assertFalse( $wrappedPage->wrappedElementIsHidden() );
			}
		}
	}

	/**
	 * @test
	 */
	public function configuration() {
		$this->assertNull(
			$this->subject->getConfiguration('foo')
		);

		$this->subject->addConfiguration('foo', 'bazFoo');
		$this->assertEquals(
			'bazFoo',
			$this->subject->getConfiguration('foo')
		);
		$this->assertContains(
			'bazFoo',
			$this->subject->getConfiguration()
		);
	}

	/**
	 * @test
	 */
	public function dontHideChildrenOfHidden() {
		$pageMocks = $this->initTestTree();

		$this->subject->addConfiguration('hideChildrenOfHidden', FALSE);

		$filter = $this->getMockForAbstractClass(
			'Rattazonk\Extbasepages\Tree\Filter\AbstractFilter',
			array(), '', TRUE, TRUE, TRUE,
			array('filter')
		);

		$filter->expects($this->any())
			->method('filter')
			->will($this->returnCallback(
				function() use ($pageMocks) {
					$currentWrapper = array_shift(func_get_args());
					$currentPage = $currentWrapper->getWrappedElement();
					if( $currentPage === $pageMocks['oneTwo'] ) {
						$currentWrapper->hideWrappedElement();
					}
				}));
		$this->subject->addFilter( $filter );
		$this->subject->getFirstLevelPages();

		foreach( $this->subject->getFlattenedPages() as $wrappedPage ) {
			if( $wrappedPage->getWrappedElement() === $pageMocks['oneTwo'] ) {
				$this->assertTrue( $wrappedPage->wrappedElementIsHidden(), 'This element should be directly hidden' );
			} else {
				$this->assertFalse( $wrappedPage->wrappedElementIsHidden(), 'This element shouldnt be hidden, because it wasnt be hidden directly.' );
			}
		}
	}

	/**
	 * @test
	 */
	public function filterSignalSlot() {
		$pageMocks = $this->initTestTree();
		// check that signalSlotDispatcher is called at initialization
		// and that the pageTree is submitted as argument, to add a filter
		$signalSlotDispatcherMock = $this->getMock(
			'TYPO3\CMS\Extbase\SignalSlot\Dispatcher',
			array('dispatch')
		);
		$signalSlotDispatcherMock->expects($this->once())
			->method('dispatch')
			->with(
				$this->equalTo('Rattazonk\Extbasepages\Tree\PageTree'),
				$this->equalTo('initFilters'),
				$this->equalTo(array($this->subject))
			);
		
		$this->inject(
			$this->subject,
			'signalSlotDispatcher',
			$signalSlotDispatcherMock
		);
		$this->subject->getFirstLevelPages();
	}
}

