<?php
/**
 * Provides CMS Administration of {@link: RedirectedURL} objects
 *
 * @package redirectedurls
 * @author sam@silverstripe.com
 * @author scienceninjas@silverstripe.com
 */

use SilverStripe\Admin\ModelAdmin;

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
	private static $menu_icon_class = 'font-icon-switch';

	/**
	 * @var array
	 */
	private static $managed_models = array(
		'RedirectedURL'
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
		$importer = new SilverStripe\Dev\CsvBulkLoader("RedirectedURL");
		$importer->duplicateChecks = array(
			'FromBase' => array('callback' => 'findByFrom'),
		);
		return array(
			'RedirectedURL' => $importer
		);
	}

	/**
	 * Overriden so that the CSV column headings have the exact field names of the DataObject
	 *
	 * To prevent field name conversion in DataObject::summaryFields() during export
	 * e.g. 'FromBase' is output as 'From Base'
	 *
	 * @return array
	 */
	public function getExportFields() {
		$fields = array();
		foreach(singleton($this->modelClass)->config()->db as $field => $spec) {
			$fields[$field] = $field;
		}
		return $fields;
	}
}
