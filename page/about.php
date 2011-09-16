<?php
class page_about extends Page {
    function init(){
        parent::init();
        $page = $_GET['page'];
        
        $this->add('H2')->set('Welcome to the porfolio');
      	
      	$menu = $this->add('Menu');
      	$menu->addMenuItem('portfolio','portfolio');
      	$menu->addMenuItem('about','about');
      	$menu->addMenuItem('home','index');
      
      	$subhead = $this->api->db->dsql()->table('page')->where('name', $page)->field('subhead')->do_getHash(); 
      	$name = $this->api->db->dsql()->table('page')->where('name', $page)->field('name')->do_getHash();
      	$copy = $this->api->db->dsql()->table('page')->where('name', $page)->field('copy')->do_getHash();
      	$active = $this->api->db->dsql()->table('page')->where('name', $page)->field('active')->do_getHash();
      	$id = $this->api->db->dsql()->table('page')->where('name', $page)->field('id')->do_getHash();
      	$photo = $this->api->db->dsql()->table('page')->where('name', $page)->field('photo')->do_getHash();
      	
      //	print_r($data);
      	
      	/*
			$g=$this->add('Grid');
			$g->addColumn('name');
			$g->addColumn('subhead');
			$g->setStaticSource($data);
		*/
		//$this->template->set($data);
		
		$this->add('h1')->set($name);
		$this->add('h2')->set($subhead);
		$this->add('p')->set($copy);
		$this->add('p')->set($active);
		$this->add('p')->set($id);
		$this->add('p')->set($photo);
      }
}
