<?php
/*
Plugin Name: Ultimate Nofollow
Plugin URI: http://wikiduh.com/plugins/nofollow
Description: A suite of tools that gives you complete control over the rel=nofollow tag on an individual link basis.
Version: 0.1.0
Author: bitacre
Author URI: http://wikiduh.com
License: GPLv2 
	Copyright 2012 bitacre (plugins@wikiduh.com)

*/

/********************
* NOFOLLOW SHORTCODES *
********************/

/* define additional plugin meta links */
function set_plugin_meta_ultnofo( $links, $file ) { 
	$plugin = plugin_basename( __FILE__ ); // '/nofollow/nofollow.php' by default
    if ( $file == $plugin ) { // if called for THIS plugin then:
		$newlinks = array( 
			'<a href="options-general.php?page=ultimate-nofollow">Settings</a>',
			'<a href="http://wikiduh.com/plugins/nofollow/help">Help Page</a>' 
		); // array of links to add
		return array_merge( $links, $newlinks ); // merge new links into existing $links
	}
return $links; // return the $links (merged or otherwise)
}

/* valid href starting substring? */
function ultnofo_valid_url( $href ) {
	$start_strs = array( // list of accepted url protocols
		'/',
		'http://',
		'https://',
		'ftp://',
		'mailto:',
		'magnet:',
		'svn://',
		'irc:',
		'gopher://',
		'telnet://',
		'nntp://', 
		'worldwind://',
		'news:',
		'git://',
		'mms://'
	);
	
	foreach( $start_strs as $start_str )
		if( substr( $href, 0, strlen( $start_str ) ) == $start_str ) return TRUE;

	return FALSE;
}

/* return nofollow link html or html error comment */
function ultnofo_nofollow_link( $atts, $content = NULL ) {
	extract( 
		shortcode_atts( 
			array( 
				'href' => NULL, 
				'title' => NULL,
				'target' => NULL 
			), 
			$atts
		)
	);
	
	// href
	if( !ultnofo_valid_url( $href ) ) return '<!-- Ultimate Nofollow Plugin | shortcode insertion failed | given href resource not valid, href must begin with: ' . print_r( $start_strs, TRUE ) . ' -->'; // if url doesn't starts with valid string
	else $href_chunk = ' href="' . $href . '"'; // else add href=''
	
	// title
	if( empty( $title ) ) $title_chunk = NULL; // if no $title, omit HTML
	else $title_chunk = ' title="' . trim( htmlentities( strip_tags( $title ), ENT_QUOTES ) ) . '"'; // else add title='' 

	// target
	if( empty( $target ) ) $target_chunk = NULL; // if no $target, omit HTML
	else $target_chunk = ' target="' . trim( htmlentities( strip_tags( $target ), ENT_QUOTES ) ) . '"'; // else add target='' 
	
	// content
	if( empty( $content ) ) return '<!-- Ultimate Nofollow Plugin | shortcode insertion failed | no link text given -->'; // if url doesn't starts with valid string
	else $content_chunk = trim( htmlentities( strip_tags( $content ), ENT_QUOTES ) ); // else add $content
	
	return '<a' . $href_chunk . $target_chunk . $title_chunk . '" rel="nofollow">' . $content_chunk . '</a>';
}

/* add hooks */
// add meta links to plugin's section on 'plugins' page (10=priority, 2=num of args)
add_filter( 'plugin_row_meta', 'set_plugin_meta_ultnofo', 10, 2 ); 

// add shortcodes
$shortcodes = array(
	'relnofollow',
	'nofollow',
	'nofol',
	'nofo',
	'nf'
);
foreach( $shortcodes as $shortcode ) add_shortcode( $shortcode, 'ultnofo_nofollow_link' );

/****************************
* BLOGROLL NOFOLLOW SECTION *
*****************************/

/**********************************************
* ADD LINK DIALOGUE NOFOLLOW CHECKBOX SECTION *
***********************************************/

/*******************************
* NOFOLLOW ON COMMENTS SECTION *
********************************/

?>