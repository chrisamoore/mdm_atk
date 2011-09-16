<?php
class page_about extends Page {
    function init(){
        parent::init();
        $page = $_GET['page'];
        
       	$menu = $this->add('Menu');
	      	$menu->addMenuItem('portfolio','portfolio');
	      	$menu->addMenuItem('about','about');
	      	$menu->addMenuItem('home','index');
	      	$menu->addMenuItem('contact','contact');
      
      // DB queries
      	// MAybe i can get this down to one query and iterate through
      	$subhead = $this->api->db->dsql()->table('page')->where('name', $page)->field('subhead')->do_getHash(); 
      	$name = $this->api->db->dsql()->table('page')->where('name', $page)->field('name')->do_getHash();
      	$copy = $this->api->db->dsql()->table('page')->where('name', $page)->field('copy')->do_getHash();
      	$active = $this->api->db->dsql()->table('page')->where('name', $page)->field('active')->do_getHash();
      	$id = $this->api->db->dsql()->table('page')->where('name', $page)->field('id')->do_getHash();
      	$photo = $this->api->db->dsql()->table('page')->where('name', $page)->field('photo')->do_getHash();
      	
		$this->add('h1')->set($name);
		$this->add('h2')->set($subhead);
		$this->add('p')->set($copy);
		$this->add('p')->set($active);
		$this->add('p')->set($id);
		$this->add('p')->set($photo);
      }
}
