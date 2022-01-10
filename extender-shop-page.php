<?php
/**
 * Plugin Name: Extender Shop Page
 * Plugin URI: https://github.com/masumtaf/extender-shop-page
 * Description: WooCommerce Custom Shop Page, Customize Shop Page as you want to 
 * Author: Abdullah
 * Author URI: https://github.com/masumtaf/
 * Tags: woocommerce product list,woocommerce product table, wc product table, product grid view, inventory, shop product table
 * 
 * Version: 1.0.1
 * Requires at least:    4.0.0
 * Tested up to:         5.8.2
 * WC requires at least: 3.0.0
 * WC tested up to: 	 5.9.0
 * 
 * Text Domain: extender-shop-page
 * Domain Path: /languages/
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Extender Shop Page core class.
 *
 * Register plugin and make instances of core classes
 *
 * @package Extender Shop Page
 * @version 1.0.0
 * @since   1.0.0
 */

class Extender_Shop_Page {

    /**
     * Plugin Version
     *
     * @package Extender Shop Page
     * @version 1.0.0
     * @since   1.0.0
     * @var string
     */
    const EXT_VERSION = '1.0.0';

    /**
     * Minimum PHP Version required for run
     *
     * @package Extender Shop Page
     * @version 1.0.0
     * @since   1.0.0
     * @var string
     */
    const EXT_PHP_VERSION = '7.4.3';

    /**
     * Core Singleton Class 
     *
     * @package Extender Shop Page
     * @version 1.0.0
     * @since   1.0.0
     * @var type
     */
    public static $_instance;

    /**
     * Main Shortcode for use in forntend
     *
     * @package Extender Shop Page
     * @version 1.0.0
     * @since   1.0.0
     */
    public static $shortcode = 'ext-woo-shop';

    /**
     * Store all elments 
     *
     * Register plugin and make instances of core classes
     *
     * @package Extender Shop Page
     * @version 1.0.0
     * @since   1.0.0
     * @return string
     */
    public static $ext_elements = [
        'title'                 => 'title',
        'sale_tag'              => 'sale_tag',
        'thumbnail'             => 'thumbnail',
        'rating'                => 'rating',
        'price'                 => 'price',
        'cart_btn'              => 'cart_btn',
        'additional'            => 'additional',
    ];

    /**
     * User Defined Elemenets to show
     *
     * @package Extender Shop Page
     * @version 1.0.0
     * @since   1.0.0
     * @return array
     * @var array
     */
    public static $ext_enabled_elements = [];
    
    /**
     * Default Enable Elements to show
     *
     * Register plugin and make instances of core classes
     *
     * @package Extender Shop Page
     * @version 1.0.0
     * @since   1.0.0
     * @return array
     * @var array
     */
    public static $ext_default_enabled_elements = [
        'title'                 => 'title',
        'sale_tag'              => 'sale_tag',
        'thumbnail'             => 'thumbnail',
        'rating'                => 'rating',
        'price'                 => 'price',
        'cart_btn'              => 'cart_btn',
    ];

    /**
     * Get instance of the Extender_Shop_Page class
     * 
     * @package Extender Shop Page
     * @version 1.0.0
     * @since   1.0.0
     */
    public static function get_instance(){
        if( null === self::$_instance ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Default Constructor
     * Initialize plugin core and build environment
     *
     * @package Extender Shop Page
     * @version 1.0.0
     * @since   1.0.0
     */
    public function __construct () {

        //all constants define here
        $this->define_constants();

        //all action fired here
        $this->actions();

        //admin file includes here
        $this->admin_includes();

        //includes all file here
        $this->includes();
        // $this->update_cpt();

    }
  
    /**
     * All constant define functions
     *
     * @package Extender Shop Page
     * @version 1.0.0
     * @since   1.0.0
     */
    public function define_constants(){

        // Some Constants for ease of use
        if ( ! defined( 'EXT_VER' ) )
          define( 'EXT_VER', '1.0.0' );
        if ( ! defined( 'EXT_PNAME' ) )
            define( 'EXT_PNAME', basename( dirname( __FILE__ ) ) );
        if ( ! defined( 'EXT_PBNAME' ) )
        define( 'EXT_PBNAME', plugin_basename(__FILE__) );
        if ( ! defined( 'EXT_PATH' ) )
            define( 'EXT_PATH', plugin_dir_path( __FILE__ ) );
        if ( ! defined( 'EXT_ADMIN' ) )
            define( 'EXT_ADMIN', plugin_dir_path( __FILE__ ) . 'admin/' );    
        if ( ! defined( 'EXT_INCLUDES' ) )
            define( 'EXT_INCLUDES', plugin_dir_path( __FILE__ ) . 'includes/' );
        if ( ! defined( 'EXT_URL' ) )
        define( 'EXT_URL', plugins_url( '/', __FILE__ ) );
        if ( ! defined( 'EXT_ASSETS_URL' ) )
            define( 'EXT_ASSETS_URL', EXT_URL . 'assets/' ); 
        if ( ! defined( 'EXT_ADMIN_ASSETS_URL' ) )
            define( 'EXT_ADMIN_ASSETS_URL', EXT_URL . 'admin/assets/' );

    }


    /**
     * All action fire here
     *
     * @package Extender Shop Page
     * @version 1.0.0
     * @since   1.0.0
     */
    public function actions(){
       
       add_action( 'admin_enqueue_scripts', [ $this, 'ext_admin_enqueue_scripts' ], 99 );
    }

    /**
     * All Admin file inludes
     *
     * @package Extender Shop Page
     * @version 1.0.0
     * @since   1.0.0
     */
    public function admin_includes(){

        include_once EXT_ADMIN . 'cpt-shop-page.php';
        include_once EXT_ADMIN . 'register-metaboxes.php';

    } 

    /**
     * All Admin file inludes
     *
     * @package Extender Shop Page
     * @version 1.0.0
     * @since   1.0.0
     */
    public function includes(){

    }


    /**
    * Update Options when Installing
    * This method has update at Version 3.6
    * 
    * @since 1.0.0
    * @updated since 3.6_29.10.2018 d/m/y
    */
   public static function update_cpt() {
        ob_start();
        //check current value

        $role = get_role( 'administrator' );

        $role->add_cap( 'edit_ext_shop_product' );
        $role->add_cap( 'edit_ext_shop_products' );
        $role->add_cap( 'edit_others_ext_shop_products' );
        $role->add_cap( 'publish_ext_shop_products' );
        $role->add_cap( 'read_ext_shop_product' );
        $role->add_cap( 'read_ext_shop_products' );
        $role->add_cap( 'delete_ext_shop_product' );
        $role->add_cap( 'manage_ext_shop_product' );
    }

    public function ext_admin_enqueue_scripts(){
        wp_enqueue_style( 'ext-admin', EXT_ADMIN_ASSETS_URL . 'css/admin.css', array(), EXT_VER, 'all' );
        wp_enqueue_script( 'ext-admin-js', EXT_ADMIN_ASSETS_URL . 'js/admin.js', array('jquery'), EXT_VER, true );
    }

}

/**
* Plugin Install and Uninstall
*/
// register_activation_hook(__FILE__, array( 'Extender_Shop_Page','update_cpt' ) );

Extender_Shop_Page::get_instance();