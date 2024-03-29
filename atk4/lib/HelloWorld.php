<?php // vim:ts=4:sw=4:et:fdm=marker
/**
 * Why creating your own HelloWorld class? $this->add('HelloWorld'); will do the trick!
 *
 * @link http://agiletoolkit.org/learn/understand/view/usage
 * @link http://agiletoolkit.org/learn/template
*//*
==ATK4===================================================
   This file is part of Agile Toolkit 4 
    http://agiletoolkit.org/
  
   (c) 2008-2011 Romans Malinovskis <atk@agiletech.ie>
   Distributed under Affero General Public License v3
   
   See http://agiletoolkit.org/about/license
 =====================================================ATK4=*/
class HelloWorld extends AbstractView {
	private $message;
	function init(){
		$this->message = 'Hello world';
	}
	function setMessage($msg){
		$this->message=$msg;
	}
	function render(){
		$this->output('<p>'.$this->message.'</p>');
	}
}
