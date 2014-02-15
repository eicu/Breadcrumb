<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Breadcrumb
 *
 * @author Andre Salmon (eicu)
 */
class Breadcrumb {
    const WRAPPER_HTML = 'html';
    const WRAPPER_TEXT = 'txt';
    
    /**
     *
     * @var string
     */
    protected $htmlRootWrapper = '<ul>%s</ul>';
    
    /**
     *
     * @var string
     */
    protected $htmlElementWrapper = '<li>%s</li>';
    
    /**
     *
     * @var string
     */
    protected $htmlActiveElementWrapper = '<li calss"active">%s</li>';
    
    /**
     *
     * @var string
     */
    protected $htmlElementLinkWrapper = '<a href="%s">%s</a>';
    
    /**
     * 
     * @var boolean
     */
    protected $htmlWithLink = false;


    /**
     *
     * @var string
     */
    protected $textDelimiter = ' > ';


    /**
     *
     * @var string
     */
    protected $wrapperSelection;
    
    /**
     *
     * @var array
     */
    protected $elementList;
    
    /**
     * 
     * @var \Breadcrumb\Element
     */
    protected $elementSelected;


    /**
     * 
     */
    public function __construct() {
        $this->setWrapperSelection(self::WRAPPER_TEXT);
        
        spl_autoload_register(array($this, 'classLoader'));
    }
    
    /**
     * 
     * @param string $class
     * @return void
     */
    public function classLoader($class) {
        $file = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $class);
    
        $filename = __DIR__ . DIRECTORY_SEPARATOR . $file . '.php';
        if(is_file($filename)) {
            include_once $filename;
            return;
        }
    }
    
    /**
     * 
     * @return bool
     */
    public function getHtmlWithLink() {
        return $this->htmlWithLink;
    }
    
    /**
     * 
     * @param bool $htmlWithLink
     */
    public function setHtmlWithLink($htmlWithLink) {
        $this->htmlWithLink = (bool) $htmlWithLink;
    }

    
    /**
     * 
     * @return string
     */
    public function getTextDelimiter() {
        return $this->textDelimiter;
    }
    
    /**
     * 
     * @param type $textDelimiter
     * @return \Breadcrumb
     */
    public function setTextDelimiter($textDelimiter) {
        $this->textDelimiter = $textDelimiter;
        return $this;
    }
    
    /**
     * 
     * @return Breadcrumb\Element\Repository
     */
    public function getElementRepository() {
        return Breadcrumb\Element\Repository::getInstance();
    }
    
    /**
     * 
     * @param \Breadcrumb\Element $element
     */
    public function addElement(\Breadcrumb\Element $element) {
        $this->getElementRepository()->addElement($element);
    }


    /**
     * 
     * @return \Breadcrumb\Element
     */
    protected function getElementSelected() {
        return $this->elementSelected;
    }    
    
    /**
     * 
     * @param \Breadcrumb\Element $elementSelected
     * @return \Breadcrumb
     */
    protected function setElementSelected(\Breadcrumb\Element $elementSelected) {
        $this->elementSelected = $elementSelected;
        return $this;
    }
        
    /**
     * 
     * @return string
     */
    public function getWrapperSelection() {
        return $this->wrapperSelection;
    }
    
    /**
     * 
     * @param string $wrapperSelection
     * @return Breadcrumb
     */
    public function setWrapperSelection($wrapperSelection) {
        $allowedWrapper = array(self::WRAPPER_HTML, self::WRAPPER_TEXT);
        if(in_array($wrapperSelection, $allowedWrapper)) {
            $this->wrapperSelection = $wrapperSelection;
        }
        
        return $this;
    }
    
    /**
     * 
     * @param string $text
     */
    public function setActiveElement($text) {
        $element = $this->getElementRepository()->find($text);
        $this->setElementSelected($element);
    }
    
    /**
     * 
     * @param string $text
     * @param string $link
     * @return \Breadcrumb\Element
     */
    public function createElement($text, $link = '') {
        $element = new \Breadcrumb\Element();
        $element->setText($text)
                ->setLink($link);
        
        $this->addElement($element);
        
        return $element;
    }

    /**
     * 
     * @param array $list
     */
    protected function _createTextList($list) {
        $textList = array();
        foreach ($list as $element) {
            $textList[] = $element->getText();
        }
        
        $string = implode($this->getTextDelimiter(), $textList);
        
        return $string;
    }
    
    /**
     * 
     * @param array $list
     */
    protected function _createHtmlList($list) {
        $listString = '';
        foreach ($list as $element) {
            $wrapper = ($element === $this->getElementSelected()) ? $this->htmlActiveElementWrapper : $this->htmlElementWrapper;
            $text = (($element !== $this->getElementSelected()) && $this->getHtmlWithLink()) ? sprintf($this->htmlElementLinkWrapper, $element->getLink(), $element->getText())  : $element->getText();
            
            $listString .= sprintf($wrapper, $text);
        }
        
        $completeString = sprintf($this->htmlRootWrapper, $listString);
        
        return $completeString;
    }

    /**
     * 
     */
    public function toString() {
        $list = array();
        $currentElement = $this->getElementSelected();
        $retVal = '';
        
        do {
            $list[] = $currentElement;
            $parent = $currentElement->getParent();
            if(empty($parent)) {
                break;
            } else {
                $currentElement = $parent;
            }
        } while (1);
        
        $list = array_reverse($list);
        
        if($this->getWrapperSelection() === self::WRAPPER_TEXT) {
            $retVal = $this->_createTextList($list);
        }
        
        if($this->getWrapperSelection() == self::WRAPPER_HTML) {
            $retVal = $this->_createHtmlList($list);
        }
        
        return $retVal;
    }
}
