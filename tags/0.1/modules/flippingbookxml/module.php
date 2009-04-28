<?php

$Module = array( 'name' => 'eZ flipping book',
                 'variable_params' => true );

$ViewList = array();

$ViewList['getGallery'] = array(
    'script' => 'get_gallery.php',
    'functions' => array( 'get_gallery' ),
    'params' => array( 'NodeID' ) );

$FunctionList = array();
$FunctionList['get_gallery'] = array();

?>
