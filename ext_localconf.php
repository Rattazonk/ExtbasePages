<?php
$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
	'TYPO3\CMS\Extbase\SignalSlot\Dispatcher'
);

$doktypeFilter = array('Under200', 'Only', 'Exclude');

foreach( $doktypeFilter as $filterName ) {
	$signalSlotDispatcher->connect(
		'Rattazonk\Extbasepages\ViewHelpers\Widget\Controller\PageTreeController',
	 	'initTreeFilters',
		"Rattazonk\\Extbasepages\\Tree\\Filter\\Doktype\\{$filterName}Filter",
		'registerIfResponsible'
	);	
}
