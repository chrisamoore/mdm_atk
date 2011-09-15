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
			$this->add('jui'); 
			$this->js()->_load('atk4_univ'); 
			$this->dbConnect(); 
			/* $this->initLayout(); */
		
		}// End init()
		function page_index($page)
				{
					$menu = $this->add('Menu');
      				$menu->addMenuItem('portfolio','portfolio');
      				$menu->addMenuItem('about','about');
      				$menu->addMenuItem('index','index');
					
					$this->add('p')->set('hello World!!');
					$this->add('button')->set('pushme');
					
					
							
				}
	}// end API frontend