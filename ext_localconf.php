<?php
$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
	'TYPO3\CMS\Extbase\SignalSlot\Dispatcher'
);


$doktypeFilters = array('ExcludeFilter', 'OnlyFilter', 'Under200Filter');
	
foreach( $doktypeFilters as $filterName ) {
	$signalSlotDispatcher->connect(
		'Rattazonk\Extbasepages\ViewHelpers\Widget\Controller\PageTreeController',
		'initTreeFilters',
		"Rattazonk\\Extbasepages\\Tree\\Filter\\Doktype\\{$filterName}",
		'registerIfResponsible'
	);
}
