<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        include_once 'Up/Upper/Breadcrumb.php';
        
        $breadcrumb = new Breadcrumb();
        $breadcrumb->createElement('Test')->createChild('Test2')->createChild('Test3')->createChild('Test4');
        $breadcrumb->setActiveElement('Test4');
        $breadcrumb->setWrapperSelection(Breadcrumb::WRAPPER_HTML);
        print $breadcrumb->toString();
        ?>
    </body>
</html>
