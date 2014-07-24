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
 * TreeWrapper
 * to wrap objects in the tree so we dont have to pollute them
 * e. g. when we manipulate the children
 */
class TreeWrapper {

	/** object **/
	protected $wrappedObject;

	/**
	 * overriden attributes of the wrapped object, so we dont have to pollute the original one
	 * the key is the attribute name
	 * @var array
	 */
	protected $overridenAttributes = array();

	public function __construct($wrappedObject) {
		$this->wrappedObject = $wrappedObject;
	}

	/**
	 * @api
	 */
	public function getChildren() {
		return $this->__call('getChildren', array());
	}

	public function __call($methodName, $args) {
		$methodType = substr($methodName, 0, 3);
		$attributeName = lcfirst(substr($methodName, 3));

		if( $methodType == 'set' ) {
			$this->overridenAttributes[$attributeName] = array_shift(array_values($args));
		} else if( $methodType == 'get' && array_key_exists($attributeName, $this->overridenAttributes) ) {
			return $this->overridenAttributes[$attributeName];
		} else {
			return call_user_func(array($this->wrappedObject, $methodName), $args);
		}
	}
}
