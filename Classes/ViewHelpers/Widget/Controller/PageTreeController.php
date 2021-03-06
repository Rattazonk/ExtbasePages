<?php
namespace Rattazonk\Extbasepages\ViewHelpers\Widget\Controller;

use Rattazonk\Extbasepages\Tree\Filter\AbstractFilter
	as AbstractTreeFilter;
use \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController;

class PageTreeController extends AbstractWidgetController {

	/**
	 * @var Rattazonk\Extbasepages\Tree\PageTree
	 * @inject
	 */
	protected $pageTree;

	public function indexAction() {
		$this->initPageTree();

		$this->view->assign(
			'treeAlias',
			array($this->widgetConfiguration['as'] => $this->pageTree)
		);
	}

	protected function initPageTree() {
		foreach( $this->widgetConfiguration as $name => $value ) {
			$this->pageTree->addConfiguration($name, $value);
		}
	}
}
?>
