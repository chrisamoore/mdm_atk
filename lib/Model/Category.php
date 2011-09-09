<?php
class Model_category extends Model_Table {
	public $entity_code='category';

	function init(){
		parent::init();
		$this->addField('name')->caption('category');
	}
}