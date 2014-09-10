<?php
$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
	'TYPO3\CMS\Extbase\SignalSlot\Dispatcher'
);

$signalSlotDispatcher->connect(
	'Rattazonk\Extbasepages\Domain\Model\Page',
	'initializePageTypeInstanceSignal',
	'Rattazonk\Slimblog\Domain\Model\BlogEntry',
	'instantiatePageTypeInstance'
);
