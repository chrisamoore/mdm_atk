<?php
class page_portfolio extends Page {
    function init(){
        parent::init();
        $this->add('H2')->set('Welcome to the porfolio');
      	
      	$menu = $this->add('Menu');
      	$menu->addMenuItem('portfolio','portfolio');
      	$menu->addMenuItem('about','about');
      	$menu->addMenuItem('home','index');
      
      	$data = $this->api->db->dsql()->table('page')->where('id', 2)->field('subhead')->do_getHash(); 
      //	print_r($data);
      	
      	/*
			$g=$this->add('Grid');
			$g->addColumn('name');
			$g->addColumn('subhead');
			$g->setStaticSource($data);
		*/
		//$this->template->set($data);
		$this->add('p')->set($data);
      }
}
