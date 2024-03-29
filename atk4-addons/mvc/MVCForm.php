<?php
/**
 * @copyright Agile Technologies Limited
 */
class MVCForm extends Form{
	protected $type_correspondence='form';

	function setController($controller_classname){
		parent::setController($controller_classname);
		// initializing form
		$this->controller->initForm();
		//$this->dq=$this->controller->getModel()->edit_dsql();
		return $this;
	}
	function setTitle($txt){
		$this->template->trySet('form_title',$txt);
		return $this;
	}
	function update($additional_data=array()){
		if(!$this->getController())return parent::update($additional_data);
		return $this->getController()->update(array_merge($this->getAllData(),$additional_data));
	}
	protected function getFieldType($field,$field_name=null){
		return $this->getController()->formatType($field->datatype(),$this->type_correspondence,$field_name);
	}
	function addField($field_name,$chunk=null,$label=null){
		// Normally label is not specified, but if it does, we use addFieldPlain
		if(!$this->getController()){
			return $this->addFieldPlain($field_name,$chunk,$label);
		}

		$field=$this->getController()->getModel()->getField($field_name);
		if(is_null($field))throw new Exception_InitError("Field '$field_name' is not defined in the ".
			get_class($this->getController()->getModel())." model");
		// readonly fields are skipped
		if($field->readonly()===true)return $this;
        if ($field->display(null, 'form')=='file'){
            $field->datatype('file');
        }
		$field_type=$this->getFieldType($field,$field_name);
		$r=$this->addFieldPlain($field_type,$field_name,$field->caption());

		if($field_type=='checkbox')$r->setDefault('N');
        if($field->datatype()=='list')$r->setValueList($field->listData());
        if($field->datatype()=='radio')$r->setValueList($field->listData());
        if($field->datatype()=='reference_id')$r->setValueList($field->refModel(),$field);
		if($field->datatype()=='image')$r->setController($field->refModel());
		if($field->datatype()=='file')$r->setController($field->refModel());
		/*
		   when adding fields for reference fields - 'reference' field type (or field type based on it) should
		   be used. You should also call if possible:

		   last_field->setController($ctl)	- this will be used for adding new entries
		   last_field->setAddURL() 			- alternatively show form from this URL for adding new entries
		   last_field->setValueList()		- model or array.

		   further you are able to control behavor of the field by using functions

		   last_field->allowAdd(bool)		- by default if field have sufficient info, it will provide ways to add entries.
		   last_value->emptyValue(str)		- specify label for when no selection is made
		   */


		// get default from Model
		if($field->defaultValue()!=='**not_set**' && !is_null($field->defaultValue())){
			if($field->datatype()=='boolean')$r->set($field->defaultValue()===true?'Y':'N');
			else $r->set($field->defaultValue());
		}
		// mandatory flag
		if($field->mandatory()!==false)$r->setNotNull($field->mandatory());
        if($field->datatype()=='list')$r->validateField('$this->get()');
		return $r;
	}
	/**
	 * Generic addField()
	 */
	function addFieldPlain($type,$name,$caption=null,$attr=null){
		$r=parent::addField($type,$name,$caption,$attr);
		return $r;
	}
	function addCondition($field,$value=null){
		if(!$this->getController())return parent::addCondition($field,$value);
		$this->getController()->getModel()->setCondition('edit_dsql',$field,$value);
		// TODO: make it work with arrays of values
		$this->conditions[$field]=$value;
		return $this;
	}
	function setCondition($field,$value=null){
		return $this->addCondition($field,$value);
	}
	function loadData(){
		if($this->bail_out)return;
		// loading from controller/model
		// if controller is not set, use parent
		if(!$this->getController())return parent::loadData();
		if(empty($this->conditions))$this->addCondition('id',null);
		try{
			$data=$this->getController()->get();//->getModel()->edit_dsql()->do_getHash();
		}catch(Exception $e){
			// data was not loaded, it is new record
		}
		if(isset($data)){
			$this->set($data);
			$this->loaded_from_db=true;
		}
	}
	function hasField($name){
		return isset($this->elements[$name])?$this->elements[$name]:false;
	}
}
