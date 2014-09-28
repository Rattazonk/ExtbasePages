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
	 * @var Rattazonk\Extbasepages\Domain\Repository\PageRepository
	 * @inject
	 */
	protected $pageRepository;


	public function indexAction() {
		$this->initConfiguration();
		$tree = $this->getTree();
		
		$this->view->assign('treeAlias', array($this->treeName => $tree) );
	}

	protected function initConfiguration() {
		$this->startPid = $this->widgetConfiguration['underPid'];
		$this->treeName = $this->widgetConfiguration['as'];
	}

	protected function getTree() {
		$tree = $this->pageRepository->findByParent( $this->startPid );
		$tree = $this->initWrappers( $tree );
		$tree = $this->filterTree( $tree );

		return $tree;
	}

	protected function filterTree( $tree, $init = TRUE ) {
		if( $init ) {
			$this->initTreeFilters();
		}

		foreach( $tree as $element ) {
			foreach( $this->treeFilters as $filter ){
				$filter->filter( $element );

				if(	$this->widgetConfiguration['renderChildrenOfSkipped']
					|| !$element->wrappedElementIsHidden() ) {

					$element->setChildren( 
						$this->filterTree( $element->getChildren(), FALSE )
					);
				}

				if( $element->wrappedElementIsHidden() ) {
					break;
				}
			}
		}

		return $tree;
	}

	protected function initTreeFilters() {
		// should call addTreeFilter, if it is responsible
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__, array($this));
	}

	protected function initWrappers( $tree ) {
		$wrappedTree = array();

		foreach( $tree as $element ) {
			$wrappedElement = $this->objectManager->get(
				'Rattazonk\Extbasepages\Tree\ElementWrapper', 
				$element
			);
			$wrappedChildren = $this->initWrappers(
				$element->getChildren()
			);

			$wrappedElement->setChildren( $wrappedChildren );

			$wrappedTree[] = $wrappedElement;
		}

		return $wrappedTree;
	}

	public function getWidgetConfiguration() {
		return $this->widgetConfiguration;
	}

	/**
	 * @param Rattazonk\Extbasepages\Tree\Filter\AbstractFilter $treeFilter
	 * @return void
	 */
	public function addTreeFilter(AbstractTreeFilter $treeFilter) {
		$this->treeFilters[] = $treeFilter;
	}

}
?>
