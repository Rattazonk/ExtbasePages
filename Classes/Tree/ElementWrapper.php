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

/**
 * ElementWrapper
 * to wrap objects in the tree so we dont have to pollute them
 * e. g. when we manipulate the children
 */
class ElementWrapper {

	/** object **/
	protected $wrappedObject;

	/**
	 * boolen whether the wrapped object is hidden from the outside or not
	 * When the object is hidden, only the RecursiveIterator methods are available
	 * @var boolean
	 */
	protected $hiddenWrappedObject = FALSE;

	/**
	 * overriden attributes of the wrapped object, so we dont have to pollute the original one
	 * the key is the attribute name
	 * @var array
	 */
	protected $overridenAttributes = array();

	/**
	 * @param \RecursiveIterator	$elementToWrap
	 * @return void
	 */
	public function __construct($elementToWrap) {
		$this->wrappedObject = $elementToWrap;
	}

	/**
	 * the name is not only 'hide' to reduce conflicts with the wrapped object
	 * it is used to hide elements in a tree but let the children be accessible
	 *
	 * @return void
	 */
	public function hideWrappedElement() {
		$this->hiddenWrappedObject = TRUE;
	}

	/**
	 * the name is so long to reduce conflicts with the wrapped objects
	 *
	 * @return boolean
	 */
	public function wrappedElementIsHidden() {
		return $this->hiddenWrappedObject;
	}

	/**
	 * for fluid
	 * @see wrappedElementIsHidden
	 *
	 * @return boolean
	 */
	public function getWrappedElementIsHidden() {
		return $this->wrappedElementIsHidden();
	}

	/**
	 * __call checks whether the method is a setter or getter
	 * when it is one, it checks if the attribute was overridden in this wrapper
	 * @param string $methodName
	 * @param array $args
	 */
	public function __call($methodName, $args) {
		if( $this->hiddenWrappedObject ) {
			return NULL;
		}

		$methodType = substr($methodName, 0, 3);
		$attributeName = lcfirst(substr($methodName, 3));

		if( $methodType == 'set' ) {
			$this->overridenAttributes[$attributeName] = array_shift($args);
		} else if( $methodType == 'get' ) {
			return $this->callGetter( $attributeName );
		} else {
			return call_user_func(array($this->wrappedObject, $methodName), $args);
		}
	}

	/**
	 * @param string $attributeName
	 * @return mixed
	 */
	protected function callGetter($attributeName) {
		if( $this->attributeIsOverridden($attributeName) ) {
			return $this->overridenAttributes[$attributeName];
		} else {
			$getter = 'get' . ucfirst($attributeName);
			return call_user_func(array($this->wrappedObject, $getter));
		}
	}

	/**
	 * checks whether an attribute of the wrapped element was overriden by a setter.
	 * The overridden value doesnt get set in the wrapped element to let it in a clean state.
	 * @param string $attributeName
	 * @return boolean
	 */
	protected function attributeIsOverridden( $attributeName ) {
		return array_key_exists( $attributeName, $this->overridenAttributes);
	}

	/**
	 * @return TYPO3\CMS\Extbase\Persistence\ObjectStorage<Rattazonk\Extbasepages\Domain\Model\Page>
	 */
	public function getChildren() {
		return $this->callGetter('children');
	}

	/**
	 * override it if you need another check
	 * @return boolean
	 */
	public function hasChildren() {
		return !empty($this->callGetter('children'));
	}
}
