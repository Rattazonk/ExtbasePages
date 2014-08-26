<?php
namespace Rattazonk\Extbasepages\ViewHelpers\Widget\Controller;

use Rattazonk\Extbasepages\Utility\PageTree\Filter\AbstractFilter as AbstractTreeFilter;

class PageTreeController extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController {

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
					|| !$element->treeWrapperIsCleared() ) {

					$element->setChildren( 
						$this->filterTree( $element->getChildren(), FALSE )
					);
				}

				if( $element->treeWrapperIsCleared() ) {
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
			$wrappedElement = $this->objectManager->get('Rattazonk\Extbasepages\Domain\Model\TreeWrapper', $element);
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
	 * @param Rattazonk\Extbasepages\Utility\PageTree\Filter\AbstractFilter $treeFilter
	 * @return void
	 */
	public function addTreeFilter(AbstractTreeFilter $treeFilter) {
		$this->treeFilters[] = $treeFilter;
	}

	protected function debug( $tree ) {
		echo '<div style="padding-left: 20px">';
		foreach( $tree as $element ) {
			echo get_class( $element );
			echo '::' . $element->getUid();
			echo '<br>children:<hr>';

			$this->debug( $element->getChildren() );
		}
		echo '</div>';
	}
}
?>
