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
			
			// Connect to the DB 
			$this->dbConnect(); 
			/* $this->initLayout(); */ // Legacy 4.1.1
		
		}// End init()
		function page_index($page)
				{
					 	$menu = $this->add('Menu');
					      	$menu->addMenuItem('portfolio','portfolio');
					      	$menu->addMenuItem('about','about');
					      	$menu->addMenuItem('home','index');
					      	$menu->addMenuItem('contact','contact');
			      	
			      	$this->add('p')->set('home');
			   
					/*
						$this->dbConnect(); 
						// Query Pages that are active and get the tables 
						$title = $this->api->db->dsql()->table('page')->where('active', 'Y')->field('name')->do_getAllHash(); 
						
						$menu = $this->add('Menu');
      					//foreach to populate Nav from active pages in DB 
      					foreach ($title as $t)
      						{
      							$menu->addMenuItem('$t', '$t');
      						}
      					*/
					
					// Add Canvas Application here		
					
				}
	}// end API frontend