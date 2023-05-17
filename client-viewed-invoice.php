<?php
/**
 * Plugin Name: Client Viewed Invoice
 * Description: Addon for Sliced Invoices - Secure Invoices extension. Adds custom column to Invoices admin which shows a tick icon when invoice has been viewed via secure link.

 * Author: Pieter Bos
 * Version: 1.0

 * Requires at least:	4.9
 * Tested up to:		6.2

 * License:    			GPL-3.0+
 * License URI:			http://www.gnu.org/licenses/gpl-3.0.txt

 * Text Domain: 		client-viewed-invoice

 * GitHub Plugin URI:	https://github.com/senlin/client-viewed-invoice
 * GitHub Branch:		main

 * @package WordPress
 * @package Sliced Invoices Secure
 * @author Pieter Bos
 * @since 1.0.0
 */


class Client_Viewed_Invoice {
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_filter( 'manage_sliced_invoice_posts_columns', array( $this, 'set_custom_edit_sliced_invoice_columns' ) );
		add_action( 'manage_sliced_invoice_posts_custom_column', array( $this, 'custom_sliced_invoice_column' ), 10, 2 );
	}

	public function enqueue_styles() {
		wp_enqueue_style( 'client-viewed-invoice', plugin_dir_url( __FILE__ ) . 'client-viewed-invoice.css', array(), '1.0' );
	}

	public function set_custom_edit_sliced_invoice_columns( $columns ) {
		$columns['log'] = __( 'Client viewed', 'client-viewed-invoice' );

		return $columns;
	}

	public function custom_sliced_invoice_column( $column, $post_id ) {
		switch ( $column ) {
			case 'log' :
				$log_meta = get_post_meta( $post_id, '_sliced_log', true );
				$displayed_yes = false; // Add a boolean flag to track if "Yes" has already been displayed
				foreach ( $log_meta as $log ) {
					if ( $log['type'] ==  'invoice_viewed' && $log['secured'] == 'yes' ) {
						if ( !$displayed_yes ) { // If "Yes" hasn't been displayed yet, display it
							echo ''; // tick (saved) dashicons
							$displayed_yes = true; // Update the flag to indicate "Yes" has been displayed
						}
						break; // Break out of the loop since we don't want to display "Yes" again
					}
				}
				break;
		}
	}
}

// Initialize the plugin
new Client_Viewed_Invoice();
