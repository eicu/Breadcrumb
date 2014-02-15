<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Breadcrumb\Element;

/**
 * Description of ElementRepository
 *
 * @author eicu
 */
class Repository {
    
    /**
     *
     * @var Repository 
     */
    private static $_instance;
    
    /**
     *
     * @var array
     */
    protected $list;
    
    /**
     * 
     */
    private function __construct() {
        
    }
    
    /**
     * 
     * @return Repository
     */
    public static function getInstance() {
        if (!isset(self::$_instance)) {
            $className = __CLASS__;
            self::$_instance = new $className();
        }
        return self::$_instance;
    }
    
    /**
     * 
     * @param string $text
     * @return \Breadcrumb\Element
     */
    public function find($text) {
        $foundedElement = null;
        foreach ($this->getList() as $element) {
            if($element->getText() === $text) {
                $foundedElement = $element;
            }
        }        
        return $foundedElement;
    }
    
    /**
     * 
     * @param \Breadcrumb\Element $element
     */
    public function addElement(\Breadcrumb\Element $element) {
        $list = $this->getList();
        $list[] = $element;
        $this->setList($list);
    }
    
    /**
     * 
     * @param type $list
     * @return Repository
     */
    protected function setList($list) {
        $this->list = $list;
        return $this;
    }

    /**
     * 
     * @return array
     */    
    protected function getList() {
        return $this->list;
    }    
}
