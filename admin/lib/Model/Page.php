<?php
class Model_page extends Model_Table {
	public $entity_code='page';

	function init(){
		parent::init();
		$this->addField('active');// enum ('y', 'n')
		$this->addField('name')->caption('pages');
		$this->addField('subhead');
		$this->addField('copy')->type('text');// text area
		$this->addField('photo')->refModel("Model_Filestore_File")->display("file");
	}
}
