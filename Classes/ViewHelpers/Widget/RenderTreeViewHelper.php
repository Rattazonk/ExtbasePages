<?php
namespace Rattazonk\Extbasepages\ViewHelpers\Widget;

class RenderTreeViewHelper
	extends \TYPO3\CMS\Fluid\Core\Widget\AbstractViewHelper
	implements \TYPO3\CMS\Fluid\Core\ViewHelper\Facets\ChildNodeAccessInterface {
	
	// maybe \TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface

	/**
	 * @param int $underPid
	 * @return string
	 */
	public function render($underPid = NULL) {
		var_dump($this->viewHelperNode);
		$this->initSubLevelViewHelpers();
		return $this->renderLevel( $elements );
	}

	protected function initSubLevelViewHelpers() {
		foreach( $this->childNodes as $childNode ) {
			var_dump(get_class($childNode));
			var_dump(get_class($childNode->getUninitializedViewHelper()));
			var_dump($childNode->getViewHelperClassName());
		}
		die();
	}

	/**
	 * has to be public to get called from the RenderSublevelViewHelper
	 * @param array<\TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\ViewHelperNode> $elements
	 * @param int $level
	 * @return string
	 */
	public function renderLevel($elements, $level = 1) {
		$nodes = $this->getFluidChildNodesForLevel( $level );
		$output = ''
		foreach( $nodes as $node ) {
			$nodeAlias = TODO;
			$this->templateVariableContainer->add($nodeAlias, $elements);
			$output .= $node->evaluate($this->renderingContext);
			$this->templateVariableContainer->remove($nodeAlias, $elements);
		}
		return $output;
	}
}
/*
elseViewHelperEncountered = FALSE;
		foreach ($this->childNodes as $childNode) {
			if ($childNode instanceof \TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\ViewHelperNode
				&& $childNode->getViewHelperClassName() === 'TYPO3\\CMS\\Fluid\\ViewHelpers\\ThenViewHelper') {
				$data = $childNode->evaluate($this->renderingContext);
				return $data;
			}
			if ($childNode instanceof \TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\ViewHelperNode
				&& $childNode->getViewHelperClassName() === 'TYPO3\\CMS\\Fluid\\ViewHelpers\\ElseViewHelper') {
				$elseViewHelperEncountered = TRUE;
			}
		}
 */
