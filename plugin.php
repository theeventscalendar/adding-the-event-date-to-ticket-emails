<?php
/**
 * Plugin Name: The Events Calendar Extension: Adding the Event Date to Ticket Emails
 * Description:  Add event date to tickets emails.
 * Version: 1.0.0
 * Author: Modern Tribe, Inc.
 * Author URI: http://m.tri.be/1971
 * License: GPLv2 or later
 */

defined( 'WPINC' ) or die;

class Tribe__Extension__Adding_the_Event_Date_to_Ticket_Emails {

    /**
     * The semantic version number of this extension; should always match the plugin header.
     */
    const VERSION = '1.0.0';

    /**
     * Each plugin required by this extension
     *
     * @var array Plugins are listed in 'main class' => 'minimum version #' format
     */
    public $plugins_required = array(
        'Tribe__Tickets__Main' => '4.2',
        'Tribe__Events__Main'  => '4.2'
    );

    /**
     * The constructor; delays initializing the extension until all other plugins are loaded.
     */
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ), 100 );
    }

    /**
     * Extension hooks and initialization; exits if the extension is not authorized by Tribe Common to run.
     */
    public function init() {

        // Exit early if our framework is saying this extension should not run.
        if ( ! function_exists( 'tribe_register_plugin' ) || ! tribe_register_plugin( __FILE__, __CLASS__, self::VERSION, $this->plugins_required ) ) {
            return;
        }

        add_filter( 'woocommerce_order_item_name', array( $this, 'add_date_to_order_title' ), 100, 2 );
    }

    /**
     * Adds event start date to ticket order titles in email and checkout screens.
     *
     * @return string
     */
    public function add_date_to_order_title( $title, $item ) {
     
        $event = tribe_events_get_ticket_event( $item['product_id'] );
        
        if ( $event !== false ) {
            $title .= ' - ' . tribe_get_start_date( $event );
        }
        
        return $title;
    }
}

new Tribe__Extension__Adding_the_Event_Date_to_Ticket_Emails();
