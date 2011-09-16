<?php
class page_portfolio extends Page {
    function init(){
        parent::init();
        $page = $_GET['page'];
                
      	///Need to get menu from DB or API  
      	$menu = $this->add('Menu');
	      	$menu->addMenuItem('portfolio','portfolio');
	      	$menu->addMenuItem('about','about');
	      	$menu->addMenuItem('home','index');
	      	$menu->addMenuItem('contact','contact');
      
      	// DB Queries Perhaps a centralized DB access?
      	$subhead = $this->api->db->dsql()->table('page')->where('name', $page)->field('subhead')->do_getHash(); 
      	$name = $this->api->db->dsql()->table('page')->where('name', $page)->field('name')->do_getHash();
      	$copy = $this->api->db->dsql()->table('page')->where('name', $page)->field('copy')->do_getHash();
      	
      //	print_r($data);
      	
      	/*
			$g=$this->add('Grid');
			$g->addColumn('name');
			$g->addColumn('subhead');
			$g->setStaticSource($data);
		*/
		//$this->template->set();
		
		$this->add('h1')->set($name);
		$this->add('h2')->set($subhead);
		$this->add('p')->set($copy);
		
		
      }
    /*
  function defaultTemplate(){
     		  return array('page/Portfolio');
    	}
*/
}
