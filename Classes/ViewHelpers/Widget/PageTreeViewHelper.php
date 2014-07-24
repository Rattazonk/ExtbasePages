<?php
namespace Rattazonk\Extbasepages\ViewHelpers\Widget;

class PageTreeViewHelper extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper {
	/**
	 * @var \Rattazonk\Extbasepages\ViewHelpers\Widget\Controller\PageTreeController
	 * @inject
	 */
	protected $controller;

	/**
	 * @param string $as
	 * @param int $underPid
	 * @param mixed $onlyDoktype
	 * @param mixed $excludeDoktype
	 * @param boolean $renderChildrenOfSkipped
	 * @return string
	 */
	public function render(
		$as, 
		$underPid = NULL, 
		$onlyDoktype = array(), 
		$excludeDoktype = array(),
		$renderChildrenOfSkipped = FALSE
	) {
		return $this->initiateSubRequest();
	}

	protected function getWidgetConfiguration() {
		$config = parent::getWidgetConfiguration();
		$config['onlyDoktype'] = (array) $config['onlyDoktype'];
		$config['excludeDoktype'] = (array) $config['excludeDoktype'];

		return $config;
	}
}
