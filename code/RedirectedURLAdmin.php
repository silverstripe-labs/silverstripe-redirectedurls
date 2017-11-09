<?php

namespace SilverStripe\RedirectedURLs;

use SilverStripe\Dev\CsvBulkLoader;
use SilverStripe\Admin\ModelAdmin;


/**
 * Provides CMS Administration of {@link: RedirectedURL} objects
 *
 * @package redirectedurls
 * @author sam@silverstripe.com
 * @author scienceninjas@silverstripe.com
 */
class RedirectedURLAdmin extends ModelAdmin {

	/**
	 * @var string
	 */
	private static $url_segment = 'redirects';

	/**
	 * @var string
	 */
	private static $menu_title = 'Redirects';

	/**
	 * @var string
	 */
	private static $menu_icon = 'redirectedurls/images/redirect.svg';

	/**
	 * @var array
	 */
	private static $managed_models = array(
		RedirectedURL::class
	);

	/**
	 * Overridden to add duplicate checking to the bulkloader to prevent
	 * multiple records with the same 'FromBase' value.
	 *
	 * Duplicates are found via callback to {@link: RedirectedURL.findByForm}.
	 *
	 * @return array Map of model class names to importer instances
	 */
	public function getModelImporters() {
		$importer = new CsvBulkLoader(RedirectedURL::class);
		$importer->duplicateChecks = array(
			'FromBase' => array('callback' => 'findByFrom'),
		);
		return array(
			'RedirectedURL' => $importer
		);
	}

	/**
	 * Overridden so that the CSV column headings have the exact field names of the DataObject
	 *
	 * To prevent field name conversion in DataObject::summaryFields() during export
	 * e.g. 'FromBase' is output as 'From Base'
	 *
	 * @return array
	 */
	public function getExportFields() {
		$fields = array();

		$obj = singleton($this->modelClass);
		$schema = $obj::getSchema();
		$dbFields = $schema->fieldSpecs($this->modelClass);

		foreach($dbFields as $field => $spec) {
			$fields[$field] = $field;
		}
		return $fields;
	}
}
