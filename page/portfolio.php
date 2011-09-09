<?php
class page_portfolio extends Page {
    function init(){
        parent::init();
        $this->add('H2')->set('Welcome to the porfolio');
      	
      	$menu = $this->add('Menu');
      	$menu->addMenuItem('portfolio','portfolio');
      	$menu->addMenuItem('about','about');
      	$menu->addMenuItem('admin','index');
      	
      }
}
