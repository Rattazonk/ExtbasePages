<?php
namespace Rattazonk\Extbasepages\ViewHelpers;
use \Rattazonk\Extbasepages\Tree\PageTree;

class RenderTreeViewHelper
	extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
	implements \TYPO3\CMS\Fluid\Core\ViewHelper\Facets\ChildNodeAccessInterface {

	// maybe \TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface

	/**
	 * @var array<\TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\AbstractNode> $childNodes
	 */
	protected $childNodes;

	/**
	 * @var array<Rattazonk\Extbasepages\ViewHelpers\RenderLevels>
	 */
	protected $renderLevelsNodes = NULL;

	/**
	 * @var int
	 */
	protected $currentLevel = 0;

	/**
	 * @var array
	 */
	protected $currentLevelElements = array();

	/**
	 * @param Rattazonk\Extbasepages\Tree\PageTree $tree
	 * @return string
	 */
	public function render(PageTree $tree) {
		$this->initRecursionViewHelpers( $this->childNodes );
		return $this->renderLevel( $tree->getFirstLevelPages() );
	}

	/**
	 * initialize the RenderSubLevelViewHelpers
	 *
	 * @param TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\AbstractNode $nodes
	 * @return void
	 */
	protected function initRecursionViewHelpers( $nodes ) {
		foreach( $nodes as $node ) {
			if( !$node INSTANCEOF \TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\ViewHelperNode ) {
				continue;
			}
			if( $node->getUninitializedViewHelper() INSTANCEOF RenderSubLevelViewHelper ) {
				$node->getUninitializedViewHelper()->setRenderTreeViewHelper( $this );
			}
			$this->initRecursionViewHelpers( $node->getChildNodes() );
		}
	}

	/**
	 * has to be public to get called from the RenderSublevelViewHelper
	 * @param array<\TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\ViewHelperNode> $elements
	 * @param int $level
	 * @return string
	 */
	public function renderLevel( $elements ) {
		$this->currentLevel++;
		$output = '';
		$renderingContext = $this->getNewRenderingContext();

		foreach( $this->getRenderLevelsNodes() as $node ) {
			// we dont set them because we can reach the viewhelper after the first evaluation
			$this->currentLevelElements = $elements;
			$output .= $node->evaluate($renderingContext);
		}

		$this->currentLevel--;

		return $output;
	}

	protected function getNewRenderingContext() {
		// dont use a new rendering context, because we cant render partials without a set view
		// why should we create a new one?
		//return $this->renderingContext;
		// we need a new ine because of the variable conflicts
		// but we need the view in viewhelpervariablecontainer
		$renderingContext = $this->renderingContext->getObjectManager()->get('\TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface');
		$renderingContext->setControllerContext( $this->renderingContext->getControllerContext() );
		$renderingContext->getViewhelperVariableContainer()->setView(
			$this->renderingContext->getViewhelperVariableContainer()->getView()
		);
		return $renderingContext;
	}

	protected function getRenderLevelsNodes() {
		if( $this->renderLevelsNodes === NULL ){
			$this->initRenderLevelsNodes();
		}
		return $this->renderLevelsNodes;
	}

	/**
	 * caches the renderLevels ViewHelpers (only direct children)
	 */
	protected function initRenderLevelsNodes() {
		$this->renderLevelsNodes = array();
		foreach( $this->childNodes as $node ) {
			if( !$node INSTANCEOF \TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\ViewHelperNode ) {
				continue;
			}
			if( $node->getUninitializedViewHelper() INSTANCEOF RenderLevelsViewHelper ) {
				$viewHelper = $node->getUninitializedViewHelper();
				$viewHelper->setRenderTreeViewHelper( $this );
				$this->renderLevelsNodes[] = $node;
			}
		}
	}

	/**
	 * @return int
	 */
	public function getCurrentLevel() {
		return $this->currentLevel;
	}

	/**
	 * @param array<\TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\AbstractNode> $childNodes
	 * @return void
	 * @api
	 */
	public function setChildNodes(array $childNodes) {
		$this->childNodes = $childNodes;
	}

	public function getCurrentLevelElements() {
		return $this->currentLevelElements;
	}
}
