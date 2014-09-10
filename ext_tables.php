<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Extbase Pages');

// extend page attributes
\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA('pages');


// add category page doktype

// TODO to constant
$categoryPageDoktype = '48';
$GLOBALS['PAGES_TYPES'][$categoryPageDoktype] = array(
	'type' => 'web',
	'allowedTables' => '*'
);

// Add the new doktype to the page type selector
$categoryPageDoktypeConfig = array(
        'LLL:EXT:slimblog/Resources/Private/Language/locallang.xlf:category_page_type',
        $categoryPageDoktype
);

$GLOBALS['TCA']['pages']['columns']['doktype']['config']['items'][] = $categoryPageDoktypeConfig;
$GLOBALS['TCA']['pages_language_overlay']['columns']['doktype']['config']['items'][] = $categoryPageDoktypeConfig;

// TODO to configuration
$newColumns = array(
	'tx_slimblog_author' => array(
		'exclude' => 0,
		'label'	=> 'LLL:EXT:slimblog/Resources/Private/Language/locallang_db.xlf:author',
		'config' => array(
			'type' => 'select',
			'size' => 10,
			'foreign_table' => 'be_users',
			'minitems' => 0,
			'maxitems' => 999,
		)
	)
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $newColumns, 1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', 'tx_slimblog_author;;;;1-1-1');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages_language_overlay', $newColumns, 1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages_language_overlay', 'tx_slimblog_author;;;;1-1-1');
