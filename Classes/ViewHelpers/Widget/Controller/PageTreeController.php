<?php
namespace Rattazonk\Extbasepages\ViewHelpers\Widget\Controller;

class PageTreeController extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController {

	/** @var int **/
	protected $startPid = 0;
	/** @var string **/
	protected $treeName;

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
		$tree = $this->filterDoktype( $tree );
		return $tree;
	}

	protected function initWrappers( $tree ) {
		$wrappedTree = array();

		foreach( $tree as $element ) {
			$wrappedElement = $this->objectManager->get('Rattazonk\Extbasepages\Domain\Model\TreeWrapper', $element);
			$wrappedChildren = $this->initWrappers(
				$element->getChildren(),
				$level + 1
			);

			$wrappedElement->setChildren( $wrappedChildren );

			$wrappedTree[] = $wrappedElement;
		}

		return $wrappedTree;
	}

	protected function filterDoktype( $tree ) {
		if( $this->widgetConfiguration['excludeDoktypesOver199'] ) {
			$tree = $this->excludeDoktypesOver199( $tree );
		}

		if( !empty($this->widgetConfiguration['onlyDoktype']) ) {
			$tree = $this->onlyDoktype( $tree );
		}

		if( !empty($this->widgetConfiguration['excludeDoktype']) ) {
			$tree = $this->excludeDoktype( $tree );
		}

		return $tree;
	}

	protected function excludeDoktypesOver199( $tree ) {
		$filtered = array();
		foreach( $tree as $element ) {
			if( $element->getDoktype() < 200 ) {
				$filteredChildren = $this->excludeDoktypesOver199( $element->getChildren() );
				$element->setChildren( $filteredChildren );
				$filtered[] = $element;
			}
		}
		return $filtered;
	}

	protected function onlyDoktype( $tree ) {
		$filtered = array();
		foreach( $tree as $element ) {
			if( in_array($element->getDoktype(), $this->widgetConfiguration['onlyDoktype']) ) {
				$filteredChildren = $this->onlyDoktype( $element->getChildren() );
				$element->setChildren( $filteredChildren );
				$filtered[] = $element;
			}
		}
		return $filtered;
	}

	protected function excludeDoktype( $tree ) {
		$filtered = array();

		foreach( $tree as $element ) {
			if( !in_array($element->getDoktype(), $this->widgetConfiguration['excludeDoktype']) ) {
				$filteredChildren = $this->excludeDoktype( $element->getChildren() );
				$element->setChildren( $filteredChildren );
				$filtered[] = $element;
			} else if ( $this->widgetConfiguration['renderChildrenOfSkipped'] ) {
				$container = $this->objectManager->get('Rattazonk\Extbasepages\Domain\Model\TreeContainer');
				$filteredChildren = $this->excludeDoktype( $element->getChildren() );
				$container->setChildren( $filteredChildren );
				$filtered[] = $container;
			}
		}
		return $filtered;
	}
}
?>
