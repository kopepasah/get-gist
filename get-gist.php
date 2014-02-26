<?php
/*
Plugin Name: Get Gist
Plugin URI: https://github.com/kopepasah/get-gist
Description: A simple plugin that adds a gist shortcode for getting a single gist and the files within. It uses the Gist V3 API.
Version: 1.1.3
Author: Justin Kopepasah
Author URI: http://kopepasah.com/ 
Text Domain: get-gist
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
	
	public $version = '1.1.3';
	public $text_domain = 'get-gist';
	
	function __construct() {
		add_action( 'admin_menu', array( $this, 'settings_submenu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_shortcode( 'gist', array( $this, 'shortcode' ) );
	}
	
	function settings_submenu() {
		add_submenu_page( 'options-general.php', __( 'Get Gist', $this->text_domain ), __( 'Get Gist', $this->text_domain ), 'manage_options', $this->text_domain, array( $this, 'options_page' ) );
	}
	
	function options_page() {
		?>
			<div id="get-gist-wrap" class="wrap">
	 			<h2><?php _e( 'Get Gist', $this->text_domain ); ?></h2>
				
				<div id="get-gist-primary">
					<form method="post" action="options.php">
						<?php wp_nonce_field('update-options'); ?>
					
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><?php _e( 'Personal Access Token', $this->text_domain ); ?></th>
								<td>
									<input type="text" name="get_gist_github_access_token" value="<?php echo get_option( 'get_gist_github_access_token' ); ?>" />
									<p class="description"><?php _e( 'By default, unauthenticated requests will be limited to 60 per hour. To get the normal 5000 views per hour, enter your personal Github API Token here.', $this->text_domain ) ?></p>
								</td>
							</tr>
						</table>
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="page_options" value="get_gist_github_access_token" />
					
						<p class="submit">
							<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
						</p>
					</form>
				</div>
				
				<div id="get-gist-secondary">
					<div class="get-gist-inner">
						<div class="author-profile">
							<figure class="author-avatar">
								<?php echo get_avatar( 'justin@kopepasah.com', '250' ); ?>
							</figure>
							<div class="author-info">
								<h3 class="info-heading"><?php _e( 'Created &amp; maintained by', 'LION' ); ?></h3>
								<h2 class="info-name">Justin Kopepasah</h2>
								<p class="info-links"><a href="http://kopepasah.com" target="_blank" class="button"><?php _e( 'Website', 'LION' ); ?></a><a class="wepay-widget-button button" id="wepay_widget_anchor_52cc5c7dd6ac5" href="https://www.wepay.com/donations/1203508425">Donate</a><script type="text/javascript">var WePay = WePay || {};WePay.load_widgets = WePay.load_widgets || function() { };WePay.widgets = WePay.widgets || [];WePay.widgets.push( {object_id: 1203508425,widget_type: "donation_campaign",anchor_id: "wepay_widget_anchor_52cc5c7dd6ac5",widget_options: {list_suggested_donations: true,allow_cover_fee: true,enable_recurring: true,allow_anonymous: true,reference_id: ""}});if (!WePay.script) {WePay.script = document.createElement('script');WePay.script.type = 'text/javascript';WePay.script.async = true;WePay.script.src = 'https://static.wepay.com/min/js/widgets.v2.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(WePay.script, s);} else if (WePay.load_widgets) {WePay.load_widgets();}</script></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
	}
	
	function admin_scripts() {
		if ( 'settings_page_get-gist' == get_current_screen()->id ) {
			wp_enqueue_style( 'get-gist-css', plugins_url( 'assets/get-gist-admin.css', __FILE__ ), false, $this->version );
		}
	}
	
	function get_data( $url ) {
		$timeout = 5;
		
		$token = get_option( 'get_gist_github_access_token' );
		
		$ch = curl_init();
		if ( ! empty( $token ) ) {
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Authorization: token ' . $token ) );
		}
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
