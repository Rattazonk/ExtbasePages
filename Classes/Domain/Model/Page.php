<?php
namespace Rattazonk\Extbasepages\Domain\Model;


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

/**
 * Page
 */
class Page
	extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var Rattazonk\Extbasepages\Domain\Model\Page
	 * @lazy
	 **/
	protected $parent;
	
	/**
	 * @var TYPO3\CMS\Extbase\Persistence\ObjectStorage<Rattazonk\Extbasepages\Domain\Model\Page>
	 **/
	protected $subPages;

	/**
	 * @var TYPO3\CMS\Extbase\Persistence\ObjectStorage<Rattazonk\Extbasepages\Domain\Model\Content>
	 * @lazy
	 **/
	protected $content;

	/** @var string **/
	protected $title;

	/** @var string **/
	protected $subTitle;
	
	/** @var string **/
	protected $doktype;

	/** @var int  **/
	protected $startTime = 0;

	/** @var int  **/
	protected $creationDate;

	/**
	 * @param TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function injectSubpages(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $emptySubpages ) {
		$this->subPages = $emptySubpages;
	}

	/**
	 * @param TYPO3\CMS\Extbase\Persistence\ObjectStorage<Rattazonk\Extbasepages\Domain\Model\Page>
	 */
	public function setSubPages( $subPages ) {
		$this->subPages = $subPages;
	}

	public function getSubPages() {
		return $this->subPages;
	}

	/**
	 * @param TYPO3\CMS\Extbase\Persistence\ObjectStorage<Rattazonk\Extbasepages\Domain\Model\Content>
	 */
	public function setContent( $content ) {
		$this->content = $content;
	}

	/**
	 * @return TYPO3\CMS\Extbase\Persistence\ObjectStorage<Rattazonk\Extbasepages\Domain\Model\Content>
	 */
	public function getContent() {
		return $this->content;
	}

	public function getFirstContent() {
		$content = $this->getContent();
		if( $content INSTANCEOF \TYPO3\CMS\Extbase\Persistence\ObjectStorage ) {
			$content->rewind();
			return $content->current();
		} else if ( is_array( $content ) ) {
			return arra_shift( array_values( $content ) );
		} else {
			return NULL;
		}
	}

	/**
	 * @api
	 * @param TYPO3\CMS\Extbase\Persistence\ObjectStorage<Rattazonk\Extbasepages\Domain\Model\Page>
	 */
	public function setChildren( $children ) {
		return $this->setSubPages( $children );
	}

	/**
	 * @param string
	 * @return void
	 */
	public function setTitle( $title ) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string
	 * @return void
	 */
	public function setSubTitle( $subTitle ) {
		$this->subTitle = $subTitle;
	}

	/**
	 * @return string
	 */
	public function getSubTitle() {
		return $this->subTitle;
	}

	/**
	 * @return string
	 */
	public function setDoktype( $doktype ) {
		$this->doktype = $doktype;
	}

	/**
	 * @return string
	 */
	public function getDoktype() {
		return $this->doktype;
	}

	/**
	 * @param DateTime
	 * @return void
	 */
	public function setStartTime( $startTime ) {
		if( $startTime INSTANCEOF \DateTime ) {
			$this->startTime = $startTime->getTimestamp();
		} else {
			$this->startTime = $startTime;
		}
	}

	/**
	 * @return DateTime
	 */
	public function getStartTime() {
		return $this->dateTimeFromTimestamp( $this->startTime );
	}

	protected function dateTimeFromTimestamp( $timestamp ) {
		if( $timestamp === 0 ) {
			$dateTime = NULL;
		} else {
			$dateTime = new \DateTime();
			$dateTime->setTimestamp( $timestamp );
		}
		return $dateTime;
	}

	/**
	 * @param DateTime
	 * @return void
	 */
	public function setCreationDate( $creationDate ) {
		if( $creationDate INSTANCEOF \DateTime ) {
			$this->creationDate = $creationDate->getTimestamp();
		} else {
			$this->creationDate = $creationDate;
		}
	}

	/**
	 * @return DateTime
	 */
	public function getCreationDate() {
		return $this->dateTimeFromTimestamp( $this->creationDate );
	}

	/**
	 * @api
	 * @return TYPO3\CMS\Extbase\Persistence\ObjectStorage<Rattazonk\Extbasepages\Domain\Model\Page>
	 */
	public function getChildren() {
		return $this->getSubPages();
	}

	/**
	 * @api
	 * @return boolean
	 */
	public function hasChildren() {
		return !empty($this->getChildren());
	}

}
