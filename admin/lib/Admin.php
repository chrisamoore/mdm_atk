<?php
class Admin extends ApiFrontend {

    public $is_admin=true;
    
    function init(){
        parent::init();
        $this->dbConnect();

        $this->addLocation('..',array(
                    'php'=>array(
                        'lib',
                        'atk4-addons/mvc',
                        'atk4-addons/billing/lib',
                        'atk4-addons/misc/lib',
                        )
                    ))
            ->setParent($this->pathfinder->base_location);

        $this->add('jUI');
        $this->js()
            ->_load('atk4_univ')
            ->_load('ui.atk4_notify')
            ;

        // Allow user: "admin", with password: "demo" to use this application
        $this->add('BasicAuth')->allow('admin','demo')->check();

        $menu=$this->add('Menu',null,'Menu');
        $menu->addMenuItem('Schema Generator','sg');
        $menu->addMenuItem('Manager','mgr');

    }
    function page_index($p){
        $this->api->redirect('admin');
    }
}
