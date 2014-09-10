<?php
namespace Rattazonk\Extbasepages\ViewHelpers;

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
	 * @param array $tree
	 * @return string
	 */
	public function render($tree = array()) {
		$this->initRecursionViewHelpers( $this->childNodes );
		return $this->renderLevel( $tree );
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
			$node->getUninitializedViewHelper()->setLevelElements( $elements );
			$output .= $node->evaluate($renderingContext);
		}

		$this->currentLevel--;

		return $output;
	}

	protected function getRenderLevelsNodes() {
		if( $this->renderLevelsNodes === NULL ){
			$this->initRenderLevelsNodes();
		}
		return $this->renderLevelsNodes;
	}

	protected function getNewRenderingContext() {
			$renderingContext = $this->renderingContext->getObjectManager()->get('\TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface');
			$renderingContext->setControllerContext( $this->renderingContext->getControllerContext() );
			return $renderingContext;
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
}
