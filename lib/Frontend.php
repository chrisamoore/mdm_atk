<?php
/*
   Commonly you would want to re-define ApiFrontend for your own application.
 */
class Frontend extends ApiFrontend {
	function init(){ 
		parent::init(); 
			
			$this->addLocation('atk4-addons',array( 
			'php'=>array( 
			'mvc', 
			'misc/lib', 
				) 
			)) 
			
		->setParent($this->pathfinder->base_location); 
			$this->add('jUI'); 
			$this->js()->_load('atk4_univ'); 
			$this->dbConnect(); 
			/* $this->initLayout(); */
		
		}// End init()
		function page_index($page)
				{
					
					$this->add('BasicAuth')->allow('admin','demo')->check();
					
					$tabs = $page->add('Tabs');
						$crud = $tabs->addTab('pages')->add('CRUD');
							$crud->setModel('page', array('id', 'active', 'name', 'subhead', 'copy', 'photo'));
								if($crud->grid)$crud->grid->addFormatter('copy','html');
								if($crud->grid)$crud->grid->addFormatter('copy','shorttext');
						$crud = $tabs->addTab('projects')->add('CRUD');
							$crud->setModel('project', array('id','active','name', 'subhead', 'caption', 'category_id', 'photo'));
								if($crud->grid)$crud->grid->addFormatter('caption','html');
								if($crud->grid)$crud->grid->addFormatter('caption','shorttext');	
						$crud = $tabs->addTab('categories')->add('CRUD');
							$crud->setModel('category', array('id','name'));
								
							
					$menu = $this->add('Menu',null,'Menu');
        			$menu->addMenuItem('portfolio','portfolio');							
					}	
		}// end API frontend