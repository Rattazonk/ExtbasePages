<?php
namespace Rattazonk\Extbasepages\Tree\Filter;

use \Rattazonk\Extbasepages\Tree\ElementWrapper;
use \Rattazonk\Extbasepages\ViewHelpers\Widget\Controller\PageTreeController;

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

abstract class AbstractFilter {
	/** @var array **/
	protected $widgetConfiguration = array();
	/** @var Rattazonk\Extbasepages\ViewHelpers\Widget\Controller\PageTreeController **/
	protected $pageTreeController;

	/**
	 * @param Rattazonk\Extbasepages\ViewHelpers\Widget\Controller\PageTreeController
	 * @param array $widgetConfiguration
	 * @return void
	 */
	public function registerIfResponsible( PageTreeController $pageTreeController) {
		$this->pageTreeController = $pageTreeController;
		$this->widgetConfiguration = $pageTreeController->getWidgetConfiguration();

		if( $this->isResponsible($pageTreeController, $widgetConfiguration) ) {
			$pageTreeController->addTreeFilter( $this );
		}
	}

	/**
	 * @param Rattazonk\Extbasepages\Tree\ElementWrapper $element
	 * @return void
	 */
	public function filter( ElementWrapper $element ) {
		if( !$this->elementIsAllowed($element) ) {
			$element->hideWrappedElement();
		}
	}

	/**
	 * @return boolean
	 */
	abstract protected function isResponsible();

	/**
	 * @param Rattazonk\Extbasepages\Tree\ElementWrapper $element
	 * @return boolean
	 */
	abstract protected function elementIsAllowed( ElementWrapper $element );
}
