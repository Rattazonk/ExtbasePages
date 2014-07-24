#
# Table structure for table 'tx_extbasepages_domain_model_page'
#
#	now I know why extbase creates the relation columns. The tca
# goes from the columns to the objects attributes and does not
# support multiple attributes for one attribute
CREATE TABLE pages (
	sub_pages int(11) unsigned DEFAULT '0' NOT NULL,
);
