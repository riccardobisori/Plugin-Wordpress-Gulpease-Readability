<?php
/*
Plugin Name: Wordpress Readability Plugin With Gulpease
Description: Rileva la leggibilità di un testo attraverso l'uso dell'indice Gulpease
Author: Riccardo Bisori
Version: 1.0
License: GPLv2 or later
*/ 

include_once( plugin_dir_path( __FILE__ ) . 'includes/ReadabilityFunctions.php');

include_once( plugin_dir_path( __FILE__ ) . 'includes/MetaBox.php');

add_action('add_meta_boxes', 'readabilityAddMetaBox');

function readabilityAddMetaBox() {
	add_meta_box('readability', 'Analisi Leggibilità del Testo', 'readabilityMetaBox', 'post','normal','high'); //this is for the articles
	add_meta_box('readability', 'Analisi Leggibilità del Testo', 'readabilityMetaBox', 'page','normal','high'); //this is for the pages
}

add_action('admin_enqueue_scripts','enqueue_scripts');

function enqueue_scripts(){
    
    // Il jQuery c'è già in Wordpress, se lo carico anche io si crea un conflitto
    //wp_enqueue_script( 'jquery341min', plugins_url('/js/jquery-3.4.1.min.js', __FILE__ ));
    
    wp_enqueue_script("ajaxHandle", plugins_url('/js/realtimeScript.js', __FILE__ ), array('jquery'));
	wp_localize_script("ajaxHandle", "ajax_object", array('ajax_url' => admin_url('admin-ajax.php')));
    wp_enqueue_style( 'metaboxStyle', plugins_url('/css/MetaBoxStyle.css', __FILE__));
}

// documentazione wordpress su mce_external_plugins molto utile 

add_filter('admin_init', 'tinymce_init');

function tinymce_init() {
    
    add_filter( 'mce_external_plugins', 'tinymce_plugin' );
}

function tinymce_plugin($plugins) {
    // "keyup_event" è un plugin di tinymce che noi creiamo nel file js
    $plugins['keyup_event'] = plugins_url('/js/realtimeScript.js', __FILE__);
    return $plugins;
}

?>