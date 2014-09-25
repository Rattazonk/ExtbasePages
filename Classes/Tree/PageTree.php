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

class PageTree implements \RecursiveIterator {

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function __construct( $children ) {

	}

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 * @inject
	 */
	protected $children;

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $children
	 */
	public function setChildren($children) {
		$this->children = $children;
	}

	/**
	 * @api \RecursiveIterator
	 * @return \RecursiveIterator
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * @api \RecursiveIterator
	 * @return boolean
	 */
	public function hasChildren() {
		return !empty($this->getChildren());
	}

	/**
	 * @api
	 * @return mixed
	 */
	public function current() {
		return $this->getChildren()->current();
	}

	/**
	 * @api
	 * @return scalar
	 */
	public function key() {
		return $this->getChildren()->key();
	}

	/**
	 * @api
	 * @return void
	 */
	public function next() {
		return $this->getChildren()->next();
	}

	/**
	 * @api
	 * @return void
	 */
	public function rewind() {
		return $this->getChildren()->rewind();
	}

	/**
	 * @api
	 * @return boolean
	 */
	public function valid() {
		return $this->getChildren()->valid();
	}
}
