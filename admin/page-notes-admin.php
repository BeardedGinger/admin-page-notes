<?php
/**
 * Class to include the meta boxes for edit screens
 */
class Page_Notes_Admin {
	
	/**
	 * Set version #
	 */
	const VERSION = '1.1.0';

	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 */
	protected $plugin_slug = 'gb-page-notes';

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		
		// Load admin stylesheet
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( 'add_meta_boxes', array( $this, 'page_notes_metaboxes' ) );
		add_action( 'save_post', array( $this, 'save' ) );

	}

	/**
	 * Return an instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 */
	public function enqueue_admin_styles() {

		wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), self::VERSION );
	
	}

	/**
	 * Load the metaboxes
	 */
	public function page_notes_metaboxes() {
		
		$post_types = get_post_types();
		if ( current_user_can( 'activate_plugins' ) ) {
			foreach( $post_types as $type ) {
				add_meta_box( 
					'admin-page-notes', 
					__( 'Add Page Notes', 'gb-page-notes' ),
					array( $this, 'admin_page_note_entry' ),
					$type
				);
			}
		}
		
		global $post;
		
		$value = get_post_meta( $post->ID, 'gb_admin_note', true );
		
		if($value) {
			foreach( $post_types as $type) {
				add_meta_box(
					'editor-note-view',
					__( 'Important Information', 'gb-page-notes' ),
					array( $this, 'editor_page_note_view' ),
					$type,
					'side',
					'high'
				);
			}
		}
	}
	
	/**
	 * Save the data from the admin box
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['gb_page_notes_admin_nonce'] ) )
			return $post_id;

		$nonce = $_POST['gb_page_notes_admin_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'gb_page_notes_admin_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions, if they aren't administrator don't let them save this box.
		if ( ! current_user_can( 'activate_plugins' ) ) {

				return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		// Sanitize the user input.
		$admin_note = esc_textarea($_POST['admin_add_note']);

		// Update the meta field.
		update_post_meta( $post_id, 'gb_admin_note', $admin_note );
	}
	
	/**
	 * Callback for the admin addition of page notes
	 */
	public function admin_page_note_entry( $post ) {
		
		// Add an nonce field so we can check for it later.
  		wp_nonce_field( 'gb_page_notes_admin_box', 'gb_page_notes_admin_nonce' );
		
		include_once( 'views/admin.php' );
	}
	
	/**
	 * Callback for the editor view of page notes
	 */
	public function editor_page_note_view( $post ) {
		
		include_once( 'views/editor.php' );
	}
}
