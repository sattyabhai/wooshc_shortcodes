<?php 

/*
    Plugin Name: Woo Shortcode and Widget
    Plugin URI: 
    Author: Satyajit Ghosh
    Author URI: 
    Version: 1.0.0
    Description: Shortcode and Widget for WooCommerce.
    Text Domain: wooshc
*/
if ( ! defined( 'ABSPATH' ) ) 
{
  exit;
}
if ( ! defined( 'WOOSHC_VERSION' ) ) {
    define( 'WOOSHC_VERSION', '1.0.0' );
}
if ( ! defined( 'WOOSHC_TEMPLATE_PATH' ) ) {
    define( 'WOOSHC_TEMPLATE_PATH', plugin_dir_path( __FILE__).'templates/' );
}
if ( ! defined( 'WOOSHC_INCLUDES_PATH' ) ) {
    define( 'WOOSHC_INCLUDES_PATH', plugin_dir_path( __FILE__).'includes/' );
}
if ( ! defined( 'WOOSHC_CSS_URI' ) ) {
    define( 'WOOSHC_CSS_URI', plugins_url( 'css/',__FILE__ ) );
}
Class WOOSHC
{
    private $actived_plugins;
    function __construct()
    {
        $this->actived_plugins = (array) get_option('active_plugins', array());
        add_shortcode('wooshc_show_products',array($this,'wooshc_AllProducts'));
        register_activation_hook(__FILE__, array($this, 'wooshc_activatePlugin'));
        add_action('widgets_init', array($this,'register_custom_recent_products_widget'));
        add_action( 'wp_enqueue_scripts', array($this,'wooshc_assets') );
        add_filter( 'use_block_editor_for_post', '__return_false' ); 
        add_filter( 'use_widgets_block_editor', '__return_false' );
    }//end of function
     function wooshc_isWooCommerceActive()
    {
        return in_array('woocommerce/woocommerce.php', $this->actived_plugins) || array_key_exists('woocommerce/woocommerce.php', $this->active_plugins);
    }//end of function
     function wooshc_activatePlugin()
    {
        if(!$this->wooshc_isWooCommerceActive())
        {
            wp_die('Sorry, but this plugin requires WooCommerce to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
        }
    }//end of function

     function register_custom_recent_products_widget() {
        require WOOSHC_INCLUDES_PATH."wooshc-widgets.php";
        register_widget('Wooshc_Recent_Products_Widget');
        register_sidebar(array(
            'name' => 'Customsidebar-1',
            'id'   => 'customsidebar-id',
            'before_widget' => '<div class="sidebar-module">',
            'after_widget' => '</div>',
            'before_title' => '</h4>',
            'after_title' => '</h4>'
        ));
    }
    
     function wooshc_AllProducts($atts)
    {
        extract( shortcode_atts( array(
            'type'=>"product",
            'add_to_cart_button'=>"yes",
            'add_to_cart_button_ajax'=>"yes",
            'number'=>4
        ), $atts,'woo_shortcodes' ) );
        
        $button['add_to_cart_button'] = $add_to_cart_button;
        $button['add_to_cart_button_ajax'] = $add_to_cart_button_ajax;

        switch($type)
        {
            case 'product': 
                            $this->wooshc_getProducts($number,$button);
                            break;
        }

    }//end of function

     function wooshc_getProducts($number,$button)
    {
        $args = array(
            'posts_per_page' => $number,
            'post_type' => 'product'
        );
        $wc_query = new WP_Query($args);

        echo '<div class="product-wrapper">';
            // while ( $wc_query->have_posts() ) : $wc_query->the_post();
                require WOOSHC_TEMPLATE_PATH."wooshc-products.php";
            // endwhile;
        echo '</div>';
    }//end of function
     function wooshc_assets()
    {
        wp_enqueue_style( 'wooshc-frontend-css',WOOSHC_CSS_URI.'wooshc-frontend.css', array(), WOOSHC_VERSION);
    }//end of function

} //end of class
new WOOSHC();