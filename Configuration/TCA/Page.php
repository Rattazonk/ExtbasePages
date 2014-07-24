<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TCA']['pages']['columns']['sub_pages'] = array(
	'exclude' => 0,
	'label' => 'LLL:EXT:extbasepages/Resources/Private/Language/locallang_db.xlf:tx_extbasepages_domain_model_page.sub_pages',
	'config' => array(
		'type' => 'inline',
		'foreign_table' => 'pages',
		'foreign_field' => 'pid',
		'maxitems'      => 9999,
		'appearance' => array(
			'collapseAll' => 1,
			'levelLinksPosition' => 'top',
			'showSynchronizationLink' => 1,
			'showPossibleLocalizationRecords' => 1,
			'showAllLocalizationLink' => 1
		),
	)
);
