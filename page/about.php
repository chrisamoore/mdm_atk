<?php
class page_about extends Page {
    function init(){
        parent::init();
        $this->add('H2')->set('Welcome to the About');
      	
      	$menu = $this->add('Menu');
      	$menu->addMenuItem('portfolio','portfolio');
      	$menu->addMenuItem('about','about');
      	$menu->addMenuItem('home','index');
      	
      	
      	
      }
}
