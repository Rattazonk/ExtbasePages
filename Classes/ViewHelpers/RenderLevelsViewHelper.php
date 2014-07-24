<?php
namespace Rattazonk\Extbasepages\ViewHelpers;


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

class RenderLevelsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var Rattazonk\Extbasepages\ViewHelpers\RenderTreeViewHelper
	 */
	protected $renderTreeViewHelper;

	/**
	 * @var array
	 */
	protected $levelElements = array();

	/**
	 * @param int $equal
	 * @param int $lowerThan
	 * @param int $greaterThan
	 * @param boolean $orEqual
	 * @param string $as
	 * @return string the rendered string
	 **/
	public function render($equal = NULL, $greaterThan = NULL, $lowerThan = NULL, $orEqual = FALSE, $as = 'subTree') {
		$this->currentLevel = $this->renderTreeViewHelper->getCurrentLevel();

		$output = '';
		if( $this->isResponsible() ) {
			$output = $this->renderChildrenWithAlias();
		}

		return $output;
	}

	/**
	 * @return boolean
	 */
	protected function isResponsible() {
		$level = $this->currentLevel;

		if( $this->arguments['greaterThan'] !== NULL
			&& $this->arguments['lowerThan'] !== NULL) {
			
			return $this->arguments['orEquals'] === TRUE;
		}

		if( $this->arguments['equal'] !== NULL ) {
			return $this->currentLevel === (int)$this->arguments['equal'];

		} else if( $this->arguments['greaterThan'] !== NULL ) {
			// decrement level when orEqual
			!$this->arguments['orEqual'] || $level++;
			return $level > (int)$this->arguments['greaterThan'];

		} else if( $this->arguments['lowerThan'] !== NULL ) {
			// increment level when orEqual
			!$this->arguments['orEqual'] || $level--;
			return $level < (int)$this->arguments['lowerThan'];
		} else {
			// no condition
			return TRUE;
		}
	}

	protected function renderChildrenWithAlias() {

		$this->templateVariableContainer->add($this->arguments['as'], $this->levelElements);
		$output = $this->renderChildren();
		$this->templateVariableContainer->remove($this->arguments['as']);

		return $output;
	}

	/**
	 * @param Rattazonk\Extbasepages\ViewHelpers\RenderTreeViewHelper $renderTreeViewHelper
	 * @return void
	 */
	public function setRenderTreeViewHelper( RenderTreeViewHelper $renderTreeViewHelper ) {
		$this->renderTreeViewHelper = $renderTreeViewHelper;
	}

	public function setLevelElements( $elements ) {
		$this->levelElements = $elements;
	}
}
