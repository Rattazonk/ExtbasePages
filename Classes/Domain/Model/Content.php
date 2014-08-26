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
class Content extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var Rattazonk\Extbasepages\Domain\Model\Page
	 * @lazy
	 **/
	protected $page;

	/** @var string **/
	protected $type;

	/** @var string **/
	protected $header;

	/** @var string **/
	protected $bodyText = '';

	/**
	 * @return Rattazonk\Extbasepages\Domain\Model\Page
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * @param Rattazonk\Extbasepages\Domain\Model\Page $page
	 * @return void
	 */
	public function setPage(Page $page) {
		$this->page = $page;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @return string
	 * @api
	 */
	public function getCType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 * @api
	 * @return void
	 */
	public function setCType($type) {
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getHeader() {
		return $this->header;
	}

	/**
	 * @param string $header
	 * @return void
	 */
	public function setHeader($header) {
		$this->header = $header;
	}

	/**
	 * @return string
	 */
	public function getBodyText() {
		return $this->bodyText;
	}

	/**
	 * @param string $bodyText
	 * @return void
	 */
	public function setBodyText($bodyText) {
		$this->bodyText = $bodyText;
	}
}
