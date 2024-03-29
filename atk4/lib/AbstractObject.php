<?php // vim:ts=4:sw=4:et:fdm=marker
/**
 * A base class for all objects/classes in Agile Toolkit.
 * Do not directly inherit from this class, instead use one of
 * AbstractModel, AbstractController or AbstractView
 * 
 * @link http://agiletoolkit.org/learn/intro
*//*
==ATK4===================================================
   This file is part of Agile Toolkit 4 
    http://agiletoolkit.org/
  
   (c) 2008-2011 Romans Malinovskis <atk@agiletech.ie>
   Distributed under Affero General Public License v3
   
   See http://agiletoolkit.org/about/license
 =====================================================ATK4=*/
abstract class AbstractObject {
    public $settings=array('extension'=>'.html');

    /** Configuration passed as a 2nd argument/array to add. Useful for dependency injection */
    public $di_config = array();

    // {{{ Object hierarchy management: http://agiletoolkit.org/learn/understand/base/adding

    /** Unique object name */
    public $name;
    /** Name of the object in owner's element array */
    public $short_name;

    /** short_name => object hash of children objects */ 
    public $elements = array ();

    /** Link to object into which we added this object */
    public $owner;
    /** Always points to current API */
    public $api;

    public $_initialized=false;
    /** Initialize object. Always call parent */
    function init() {
        /**
         * This method is called for initialization
         */
        $this->_initialized=true;
    }
    function __clone(){
        // fix short name and add ourselves to the parent
        $this->short_name=$this->_unique($this->owner->elements,$this->short_name);
        $this->owner->add($this);
    }
    function __toString() {
        return "Object " . get_class($this) . "(" . $this->name . ")";
    }
    /** Removes object from parent and prevents it from renedring */
    function destroy(){
        foreach($this->elements as $el){
            $el->destroy();
        }
        unset($this->elements);
        $this->owner->_removeElement($this->short_name);
    }
    /** Remove child element if it exists */
    function _removeElement($short_name){
        unset($this->elements[$short_name]);
        return $this;
    }
    /** Creates new object and adds it as a child. Returns new object
     * http://agiletoolkit.org/learn/understand/base/adding */
    function add($class, $short_name = null, $template_spot = null, $template_branch = null) {

        if(is_array($short_name)){
            $di_config=$short_name;
            $short_name=@$di_config['name'];
        }else $di_config=array();

        if (is_object($class)) {
            // Object specified, just add the object, do not create anything
            if (!($class instanceof AbstractObject)) {
                throw $this->exception('You may only add objects based on AbstractObject');
            }
            if (!$class->short_name) {
                throw $this->exception('Cannot add existing object, without short_name');
            }
            if (isset($this->elements[$class->short_name]))
                return $this->elements[$class->short_name];
            $this->elements[$class->short_name] = $class;
            $class->owner = $this;
            $class->di_config = array_merge($class->di_config,$di_config);
            return $class;
        }
        if (!$short_name)
            $short_name = strtolower($class);

        $short_name=$this->_unique($this->elements,$short_name);

        if (isset ($this->elements[$short_name])) {
            if ($this->elements[$short_name] instanceof AbstractView) {
                // AbstractView classes shouldn't be created with the same name. If someone
                // would still try to do that, it should generate error. Obviously one of
                // those wouldn't be displayed or other errors would occur
                $this->warning("Element with name $short_name already exists in " . ($this->__toString()));
            }
            if ($this->elements[$short_name] instanceof AbstractController) {
                return $this->elements[$short_name];
            }
            // Model classes may be created several times and we are actually don't care about those.
        }

        if(!is_string($class) || !$class)throw new BaseException("Class is not valid");
        $element = new $class ();

        if (!($element instanceof AbstractObject)) {
            throw new BaseException("You can add only classes based on AbstractObject (called from " . caller_lookup(1, true) . ")");
        }

        $element->owner = $this;
        $element->api = $this->api;
        $this->elements[$short_name] = $element;

        $element->name = $this->name . '_' . $short_name;
        $element->short_name = $short_name;
        $element->di_config=$di_config;

        if ($element instanceof AbstractView) {
            $element->initializeTemplate($template_spot, $template_branch);
        }

        $element->init();
        return $element;
    }
    /** Find child element by their short name. Use in chaining. Exception if not found. */
    function getElement($short_name, $obligatory = true) {
        if (!isset ($this->elements[$short_name]))
            if ($obligatory)
                throw $this->exception("Child element not found")
                    ->addMoreInfo('element',$short_name);
            else
                return null;
        return $this->elements[$short_name];
    }
    /** Find child element. Use in condition. */ 
    function hasElement($name){
        return isset($this->elements[$name])?$this->elements[$name]:false;
    }

    // }}} 

    // {{{ Session management: http://agiletoolkit.org/doc/session
    /** Remember object-relevant session data */
    function memorize($name, $value) {
        if (!isset ($value))
            return $this->recall($name);
        $this->api->initializeSession();
        return $_SESSION['o'][$this->name][$name] = $value;
    }
    /** Remember one of the supplied arguments, which is not-null */
    function learn($name, $value1 = null, $value2 = null, $value3 = null) {
        if (isset ($value1))
            return $this->memorize($name, $value1);
        if (isset ($value2))
            return $this->memorize($name, $value2);
        return $this->memorize($name, $value3);
    }
    /** Forget session data for arg $name. Null forgets all data relevant to this object */
    function forget($name = null) {
        $this->api->initializeSession();
        if (isset ($name)) {
            unset ($_SESSION['o'][$this->name][$name]);
        } else {
            unset ($_SESSION['o'][$this->name]);
        }
    }
    /** Returns session data for this object. If not set, $default is returned */
    function recall($name, $default = null) {
        $this->api->initializeSession(false);
        if (!isset ($_SESSION['o'][$this->name][$name])||is_null($_SESSION['o'][$this->name][$name])) {
            return $default;
        } else {
            return $_SESSION['o'][$this->name][$name];
        }
    }
    // }}}

    // {{{ Exception handling: http://agiletoolkit.org/doc/exception
    public $default_exception='BaseException';

    function exception($message,$type=null){
        if(!$type){
            $type=$this->default_exception;
        }else{
            $type='Exception_'.$type;
        }

        // Localization support
        if($this->api->hasMethod('_'))
            $message=$this->api->_($message);

        $e=new $type($message);
        $e->owner=$this;
        $e->api=$this->api;
        $e->init();
        return $e;
    }
    // }}}

    // {{{ Code which can be potentially obsoleted
    /** @obsolete */
    function fatal($error, $shift = 0) {
        /**
         * If you have fatal error in your object use the following code:
         *
         * return $this->fatal("Very serious problem!");
         *
         * This line will notify parent about fatal error and return null to
         * the caller. Caller don't have to handle error messages, just throw
         * everything up.
         *
         * Fatal calls are intercepted by API. Or if you want you can intercept
         * them yourself.
         *
         * TODO: record debug_backtrace depth so we could point acurately at
         * the function/place where fatal is called from.
         */

        return $this->upCall('outputFatal', array (
                    $error,
                    $shift
                    ));
    }
    /** @obsolete */
    function info($msg) {
        /**
         * Call this function to send some information to API. Example:
         *
         * $this->info("User tried buying traffic without enough money in bank");
         */

        if(!$this->api->hook('outputInfo',array($msg,$this)))
            $this->upCall('outputInfo', $msg);
    }
    /** @obsolete */
    function debug($msg, $file = null, $line = null) {
        /**
         * Use this function to send debug information. Information will only
         * be sent if you enable debug localy (per-object) by setting
         * $this->debug=true or per-apllication by setting $api->debug=true;
         *
         * You also may enable debug globaly:
         * $this->api->debug=true;
         * but disable for object
         * $object->debug=false;
         */
        if ((isset ($this->debug) && $this->debug) || (isset ($this->api->debug) && $this->api->debug)) {
            $this->upCall('outputDebug', array (
                        $msg,
                        $file,
                        $line
                        ));
        }
    }
    /** @obsolete */
    function warning($msg, $shift = 0) {
        $this->upCall('outputWarning', array (
                    $msg,
                    $shift
                    ));
    }

    /////////////// C r o s s   c a l l s ///////////////////////
    function upCall($type, $args = array ()) {
        /**
         * Try to handle something on our own and in case we are not
         * able, pass to parent. Such as messages, notifications and request
         * for additional info or descriptions are passed this way.
         */
        if (method_exists($this, $type)) {
            return call_user_func_array(array (
                        $this,
                        $type
                        ), $args);
        }
        if (!$this->owner)
            return false;
        return $this->owner->upCall($type, $args);
    }
    function downCall($type, $args = array()) {
        /**
         * Unlike upCallHandler, this will pass call down to all childs. This
         * one is useful for a "render" or "submitted" calls.
         */
        foreach (array_keys($this->elements) as $key) {
            if (!($this->elements[$key] instanceof AbstractController)) {
                $this_result = $this->elements[$key]->downCall($type, $args);
                if ($this_result === false)
                    return false;
            }
        }
        if (method_exists($this, $type)) {
            return call_user_func_array(array (
                        $this,
                        $type
                        ), $args);
        }
        return null;
    }
    // }}} 

    // {{{ Hooks: http://agiletoolkit.org/doc/hooks
    public $hooks = array ();

    function addHook($hook_spot, $callable, $arguments=array(), $priority = 5) {
        if(!is_array($arguments)){
            // Backwards compatibility
            $priority=$arguments;
            $arguments=array();
        }
        $this->hooks[$hook_spot][$priority][] = array($callable,$arguments);
        return $this;
    }
    function removeHook($hook_spot) {
        unset($this->hooks[$hook_spot]);
        return $this;
    }
    function hook($hook_spot, $arg = array ()) {
        $return=array();
        try{
            if (isset ($this->hooks[$hook_spot])) {
                if (is_array($this->hooks[$hook_spot])) {
                    foreach ($this->hooks[$hook_spot] as $prio => $_data) {
                        foreach ($_data as $data) {

                            // Our extentsion.
                            if (is_string($data[0]) && !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $data[0])) {
                                $result = eval ($data[0]);
                            } elseif (is_callable($data[0])) {
                                $result = call_user_func_array($data[0], array_merge($arg,$data[1]));
                            } else {
                                if (!is_array($data[0]))
                                    $data[0] = array (
                                            'STATIC',
                                            $data[0]
                                            );
                                throw $this->exception("Cannot call hook. Function might not exist")
                                    ->addMoreInfo('hook',$hook_spot)
                                    ->addMoreInfo('arg1',$data[0][0])
                                    ->addMoreInfo('arg2',$data[0][1]);
                            }
                            $return[]=$result;
                        }
                    }
                }
            }
        }catch(Exception_Hook $e){
            return $e->return_value;
        }
        return $return;
    }
    function breakHook($return){
        $e=$this->exception(null,'Hook');
        $e->return_value=$return;
        throw $e;
    }
    // }}}

    // {{{ Dynamic Methods: http://agiletoolkit.org/learn/dynamic
    function __call($method,$arguments){
        if($ret=$this->tryCall($method,$arguments))return $ret[0];
        throw $this->exception("Method is not defined for this object")
            ->addMoreInfo("method",$method)
            ->addMoreInfo("arguments",$arguments);
    }
    /** [private] attempts to call method, returns array containing result or false */
    function tryCall($method,$arguments){
        array_unshift($arguments,$this);
        if($ret=$this->hook('method-'.$method,$arguments))return $ret;
        if($ret=$this->api->hook('global-method-'.$method,$arguments))return $ret;
    }
    /** Add new method for this object */
    function addMethod($name,$callable){
        if($this->hasMethod($name))
            throw $this->exception('Registering method twice');
        $this->addHook('method-'.$name,$callable);
    }
    /** Return if this object have specified method */
    function hasMethod($name){
        return method_exists($this,$name)
            || isset($this->hooks['method-'.$name])
            || isset($this->api->hooks['global-method-'.$name]);
    }
    function removeMethod($name){
        $this->removeHook('method-'.$name);
    }
    
    // }}}

    // {{{ Logger: to be moved out 
    function logVar($var,$msg=""){
        $this->api->getLogger()->logVar($var,$msg);
    }
    function logInfo($info,$msg=""){
        $this->api->getLogger()->logLine($msg.' '.$info."\n");
    }
    function logError($error,$msg=""){
        if(is_object($error)){
            // we got exception object obviously
            $error=$error->getMessage();
        }
        $this->api->getLogger()->logLine($msg.' '.$error."\n",null,'error');
    }
    // }}}
    /**
     *  @private
     *  DO NOT USE THIS FUNCTION, it might relocate
     *
     * This funcion given the associative $array and desired new key will return
     * the best matching key which is not yet in the arary. For example if you have
     * array('foo'=>x,'bar'=>x) and $desired is 'foo' function will return 'foo_2'. If 
     * 'foo_2' key also exists in that array, then 'foo_3' is returned and so on.
     */
    function _unique(&$array,$desired=null){
        $postfix=1;$attempted_key=$desired;
        while(isset($array[$attempted_key])){
            // already used, move on
            $attempted_key=($desired?$desired:'undef').'_'.(++$postfix);
        }       
        return $attempted_key;
    }
}
