<?php
class Admin extends ApiFrontend {

    public $is_admin=true;
    
    function init(){
        parent::init();
        $this->dbConnect();

        $this->addLocation('..',array(
                    'php'=>array(
                        'lib',
                        'atk4-addons/mvc',
                        'atk4-addons/misc/lib',
                        )
                    ))
            ->setParent($this->pathfinder->base_location);

        $this->add('jUI');
        $this->js()
            ->_load('atk4_univ')
            ->_load('ui.atk4_notify')
            ;
    }
       // $this->api->redirect('admin');
       function page_index($page)
				{
					
					// set admin theme
					// add authorization
					// $this->add('BasicAuth')->allow('admin','demo')->check();
					
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
								
							
											
					}	
    
}
