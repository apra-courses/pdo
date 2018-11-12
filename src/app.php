<?php

class App {
    
    const DEFAULT_CONTROLLER_ACTION = 'index';
    
    private $config;
    private $pathInfo;
    private $action;        
    
    private function __construct() {  
        $this->readConfig();
        $this->readPathInfo();
        $this->readAction();                
    }
    
    public static function getInstance() {
        static $instance = null;
        if (!$instance) {
            $instance = new Static();
        }
        return $instance;
    }
    
    public function getConfig($key, $default = null) {
        return isset($this->config[$key]) ? $this->config[$key] : $default;
    }
    
    public function getPathInfo() {
        return $this->pathInfo;
    }
     
    public function getAction() {        
        return $this->action ?: self::DEFAULT_CONTROLLER_ACTION;
    }
    
    private function readConfig() {
        $this->config = require_once(CFG_DIR . '/config.php');
    }
    
    private function readPathInfo() {
        $this->pathInfo = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : '/';        
    }
    
    private function readAction() {
        $this->action = isset($_GET['action']) ? $_GET['action'] : '';
    }
          
  
    
}