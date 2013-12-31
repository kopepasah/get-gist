<?php
/*
Plugin Name: Get Gist
Plugin URI: http://kopepasah.com/get-gist
Description: A simple plugin that adds a gist shortcode for getting a single gist and the files within. It uses the Gist V3 API.
Version: 1.0.0
Author: Justin Kopepasah
Author URI: http://kopepasah.com/ 
*/

/**
 * Copyright (c) 2013 Justin Kopepasah. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
*/

class Get_Gist {
	
	function __construct() {
		add_shortcode( 'gist', array( $this, 'shortcode' ) );
	}
	
	function get_data( $url ) {
		$timeout = 5;
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
		curl_setopt( $ch, CURLOPT_USERAGENT, 'Get-Gist' );
		$data = curl_exec( $ch );
		curl_close( $ch );
		
		return $data;
	}
	
	function get_gist( $id ) {
		$data = $this->get_data( 'https://api.github.com/gists/' . $id );
		
		$data = json_decode( $data );
		
		return $data;
	}
	
	function shortcode( $atts ) {
		extract( shortcode_atts( array(
			'id'   => '',
			'file' => null,
			'synhi' => '',
			'wrap'  => ''
		), $atts ) );
		
		if ( empty( $id ) )
			return;
		
		$gist = $this->get_gist( $id );
		
		if ( isset( $gist->message ) ) {
			return '<p class="notice">' . $gist->message . '</p>';
		}
		
		if ( null === $file ) {
			$output = '';
			
			foreach ( $gist->files as $name ) {

				if ( true == $synhi ) {
					$language = strtolower( $name->language );
					
					if ( 'less' == $language ) {
						$language = 'css';
					}
					
					if ( true == $wrap ) {
						$output .= '<div class="syntax-wrapper">';
						$output .= '<span class="filename">' . $name->filename . '</span>';
						$output .= '[' . $language . ']' . $name->content . '[/' . $language . ']';
						$output .= '</div>';
					} else {
						$output .= '<span class="filename">' . $name->filename . '</span>';
						$output .= '[' . $language . ']' . $name->content . '[/' . $language . ']';
					}
					
				} else {
					$output .= $name->content;
				}
			}
			
			if ( true == $synhi ) {
				$output = apply_filters( 'the_content', $output );
			}
		} else {
			if ( empty( $gist->files->{$file}->content ) ) {
				$output = '<p class="error">There was an error reading \'<strong>' . $file . '</strong>\'. This is most likely caused by an incorrect spelling of the file name in the shortcode.</p>.';
			} else {
				if ( true == $synhi ) {
					$language = strtolower( $gist->files->{$file}->language );
					
					if ( 'less' == $language ) {
						$language = 'css';
					}
					
					if ( true == $wrap ) {
						$output = '<div class="syntax-wrapper">';
						$output .= '[' . $language . ']' . $gist->files->{$file}->content . '[/' . $language . ']';
						$output .= '</div>';
					} else {
						$output = '[' . $language . ']' . $gist->files->{$file}->content . '[/' . $language . ']';
					}
					
					$output = apply_filters( 'the_content', $output );
				} else {
					$output = $gist->files->{$file}->content;
				}
			}
		}
		
		return $output;
	}
}

new Get_Gist();