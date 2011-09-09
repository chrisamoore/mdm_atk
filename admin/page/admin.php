<?php
class page_mgr extends Page {
    function init(){
        parent::init();

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
    }
}
