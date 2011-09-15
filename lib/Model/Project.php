<?php
class Model_project extends Model_Table {
	public $entity_code='project';

	function init(){
		parent::init();
		$this->addField('active');// enum ('y', 'n')
		$this->addField('name')->caption('projects');
		$this->addField('subhead');
		$this->addField('caption')->type('text');// text area
		$this->addField('category_id');// can have many
		$this->addField('photo')->refModel("Model_Filestore_File")->display("file");
		$this->debug();
	}
}
