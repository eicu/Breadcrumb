<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Breadcrumb;

/**
 * Description of Element
 *
 * @author Andre Salmon (eicu)
 */
class Element {
    /**
     *
     * @var Element
     */
    protected $parent;
    
    /**
     *
     * @var array
     */
    protected $childs;
    
    /**
     *
     * @var string
     */
    protected $text;
    
    /**
     *
     * @var string
     */
    protected $link;


    /**
     * 
     * @return Element
     */
    
    public function getParent() {
        return $this->parent;
    }
    
    /**
     * 
     * @return arary
     */
    public function getChilds() {
        return $this->childs;
    }
    
    /**
     * 
     * @return string
     */
    public function getText() {
        return $this->text;
    }
    
    /**
     * 
     * @return string
     */
    public function getLink() {
        return $this->link;
    }
    
    /**
     * 
     * @return Element\Repository
     */
    protected function getRepository() {
        return Element\Repository::getInstance();
    }

    /**
     * 
     * @param Element $parent
     * @return Element
     */
    public function setParent(Element $parent) {
        $this->parent = $parent;
        $parent->addChild($this);
        return $this;
    }
    
    /**
     * 
     * @param string $text
     * @param string $link
     * @return Element
     */
    public function createChild($text, $link = '') {
        $child = new self();
        $child->setText($text)
              ->setLink($link)
              ->setParent($this);
        
        $this->getRepository()->addElement($child);
        return $child;
    }
    
    /**
     * 
     * @param Element $child
     * @return Element 
     */
    public function addChild(Element $child) {
        if($child->getParent() !== $this) {
            $child->setParent($this);

            $childs = $this->getChilds();
            $childs[] = $child;
            $this->setChilds($childs);
        }
        
        return $this;
    }
    
    /**
     * 
     * @param array $childs
     * @return Element
     */
    protected function setChilds($childs) {
        $this->childs = $childs;
        return $this;
    }
    
    /**
     * 
     * @param string $text
     * @return Element
     */
    public function setText($text) {
        $this->text = $text;
        return $this;
    }
    
    /**
     * 
     * @param string $link
     * @return Element
     */
    public function setLink($link) {
        $this->link = $link;
        return $this;
    }


}
