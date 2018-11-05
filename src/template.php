<?php

class Template {
    
    private $properties;

    public function __construct() {
        $this->properties = array();
    }

    public function render($filename) {
        ob_start();
        $path = VIEW_DIR . '/' . $filename;
        if (file_exists($path)) {
            include($path);
        } else {
            throw new Exception("File $path non esistente");
        }            
        return ob_get_clean();
    }

    public function __set($k, $v) {
        $this->properties[$k] = $v;
    }

    public function __get($k) {
        return isset($this->properties[$k]) ? $this->properties[$k] : null;
    }

}
