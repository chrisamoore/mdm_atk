<?php
class page_Contact extends Page {
    function init(){
        parent::init();

		 $menu = $this->add('Menu');
	      	$menu->addMenuItem('portfolio','portfolio');
	      	$menu->addMenuItem('about','about');
	      	$menu->addMenuItem('home','index');
	      	$menu->addMenuItem('contact','contact');
      	
        $form = $this->add('Form');
        $form->setFormClass('vertical');
     	   $form->addField('line','name');
      	   $form->addField('line','email');
       	   $form->addField('text','message');
       	   $form->addSubmit('send');

        if($form->isSubmitted()){
            $form->js()->html('Thank You')->execute();
            // add data to DB + send email to Admin
        }
    }
   /*
 function defaultTemplate(){
        return array('page/Contact');
    }
*/
}
