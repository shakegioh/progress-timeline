<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://www.giovanneafonso.net
 * @since      1.0.0
 *
 * @package    Progress_Timeline
 * @subpackage Progress_Timeline/public
 * @author     Giovanne <giovanneafonso@gmail.com>
 */
class Progress_Timeline_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
    
    /**
     * The timeline object
     * 
     * @since   1.0.0
     * @access  privte
     * @var     Progress_Timeline_Object  $timeline  The timeline Object
     */
    private $timeline;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->timeline = new Progress_Timeline_Object();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Progress_Timeline_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Progress_Timeline_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/progress-timeline-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Progress_Timeline_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Progress_Timeline_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        
        // Except the wp_enqueue_script, the script is based on this
        // http://www.billerickson.net/infinite-scroll-in-wordpress/
        //global $wp_query;
        
        $args = array(
            'nonce' => wp_create_nonce( 'ptl-load-more-nonce' ),
            'url'   => admin_url( 'admin-ajax.php' ),
        );
        
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/progress-timeline-public.js', array( 'jquery' ), $this->version, false );
        
        wp_localize_script( $this->plugin_name, 'ptlLoadMore', $args );
        
	}
    
    /**
     * Generate shortcode html code
     *
     * @since    1.0.0
     * @param    array     $atts    Attributes passed through the shortcode
     * @return   string             The HTML code
     */
    public function progress_timeline_shortcode( $atts ) {
        
        return $this->timeline->getFullHTML();
        
    }
    
    /**
     * AJAX Load More posts from timeline
     */
    public function ptl_ajax_load_more() {
        
        check_ajax_referer( 'ptl-load-more-nonce', 'nonce' );
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        
        $data = $this->timeline->getPageHTML($page);
        
        wp_send_json_success( $data );
        
    }

}