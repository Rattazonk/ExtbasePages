<?php
namespace Rattazonk\Extbasepages\ViewHelpers\Widget\Controller;

use Rattazonk\Extbasepages\Tree\Filter\AbstractFilter
	as AbstractTreeFilter;
use \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController;

class PageTreeController extends AbstractWidgetController {

	/** @var int **/
	protected $startPid = 0;
	/** @var string **/
	protected $treeName;
	/** @var array **/
	protected $treeFilters = array();

	/**
	 * @var Rattazonk\Extbasepages\Tree\PageTree
	 * @inject
	 */
	protected $pageTree;

	public function indexAction() {
		$tree = $this->getTree();
		$this->view->assign('treeAlias', array($this->treeName => $tree) );
	}

	protected function getTree() {
		foreach( $this->widgetConfiguration as $name => $value ) {
			$this->pageTree->addConfiguration($name, $value);
		}
		return $tree;
	}
}
?>
