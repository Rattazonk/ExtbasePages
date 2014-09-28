<?php
namespace Rattazonk\Extbasepages\Tree;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Frederik Vosberg <frederik.vosberg@rattazonk.com>, Rattazonk
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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

class PageTree {

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 * @inject
	 */
	protected $firstLevelPages;

	/**
	 * @var TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 * @inject
	 */
	protected $objectManager;

	/** @var boolean **/
	protected $initialized = FALSE;

	/** @var array **/
	protected $filters = array();

	/**
	 * @var Rattazonk\Extbasepages\Domain\Repository\PageRepository
	 * @inject
	 */
	protected $pageRepository;

	public function getFirstLevelPages() {
		if( !$this->initialized ) { $this->initialize(); }
		return $this->firstLevelPages;
	}

	protected function initialize() {
		$firstLevelPages = $this->pageRepository->findByParent(
			(int) $GLOBALS['TSFE']->id
		);
		$firstLevelPages = $this->wrapTree( $firstLevelPages );
		$this->firstLevelPages = $this->filterTree( $firstLevelPages );
		$this->initialized = TRUE;
	}

	protected function wrapTree( $currentLevel ) {
		$wrappedLevel = $this->objectManager->get( 'TYPO3\CMS\Extbase\Persistence\ObjectStorage' );

		foreach( $currentLevel as $page ) {
			$wrappedPage = $this->objectManager->get(
				'Rattazonk\Extbasepages\Tree\ElementWrapper',
				$page
			);
			$wrappedLevel->attach( $wrappedPage );

			// the wrapped children are stored in the wrapper of the page, the page itself stays clean
			$wrappedPage->setChildren(
				$this->wrapTree( $wrappedPage->getChildren() )
			);
		}

		return $wrappedLevel;
	}

	public function addFilter($filter) {
		$this->filters[] = $filter;
	}

	public function filterTree( $level ) {
		foreach( $level as $page ) {
			foreach( $this->filters as $filter ) {
				$filter->filter( $page );
				$this->filterTree( $page->getChildren() );

				if( $page->wrappedElementIsHidden() ) {
					break;
				}
			}
		}
		return $level;
	}
}
