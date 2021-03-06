<?php
/**
 * Core functionalities.
 *
 * @package  WordPress
 * @subpackage  Boot
 */

/**
 * Load vendors.
 */
require_once 'vendor/autoload.php';

/**
 * Initialise Timber.
 */
$timber              = new Timber\Timber();
$timber::$dirname    = array( 'views' );
$timber::$autoescape = false;

/**
 * Subclass of Timber\Site to init the theme.
 */
class bootSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'init', array( $this, 'register_menus' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'remove_gutenberg_panel' ) );
		add_action( 'admin_head', array( $this, 'remove_gutenberg_tips' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'disable_editor_fullscreen' ) );
		parent::__construct();
	}

	/** General context.
	 *
	 * @return string
	 */
	public function add_to_context( $context ) {
		global $timber;

		$context['menu']             = new Timber\Menu( 'primary' );
		$context['menu_footer']      = new Timber\Menu( 'footer' );
		$context['site']             = $this;
		$context['site']->login_url  = wp_login_url( get_permalink() );
		$context['site']->logout_url = wp_logout_url( $context['site']->url );
		$context['logged_in']        = is_user_logged_in();
		$context['current_user']     = new Timber\User();
		$context['sidebar']          = $timber::get_widgets( 'sidebar' );

		return $context;
	}

	/**
	 *
	 */
	public function theme_supports() {
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support(
				'html5',
				array(
						'search-form',
						'comment-form',
						'comment-list',
						'gallery',
						'caption',
						'style',
						'script',
				)
		);
	}

	/**
	 * Register main and footer menu.
	 */
	public function register_menus() {
		register_nav_menus(
				[
						'primary' => 'Main',
						'footer'  => 'Footer',
				]
		);
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts() {
		// Styles.
		wp_enqueue_style( 'boot-main', get_template_directory_uri() . '/assets/vendor/twbs/bootstrap/dist/css/bootstrap.css', [], filemtime( get_template_directory() . '/assets/vendor/twbs/bootstrap/dist/css/bootstrap.css' ) );
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/main.min.css', [], filemtime( get_template_directory() . '/assets/css/main.css' ) );

		// Scripts.
		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.js', [ 'jquery' ], filemtime( get_template_directory() . '/assets/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.js' ), true );
		wp_enqueue_script( 'boot-main', get_template_directory_uri() . '/assets/js/main.min.js', [ 'jquery' ], filemtime( get_template_directory() . '/assets/js/main.js' ), true );

		if ( is_singular() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

	}

	/**
	 * Register widget areas and custom widgets.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
	 */
	public function widgets_init() {

		register_sidebar( array(
				'name'          => esc_html__( 'Sidebar', 'boot' ),
				'id'            => 'sidebar',
				'description'   => esc_html__( 'Sidebar', 'boot' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s p-4 mb-3 rounded-0">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="font-italic pb-2">',
				'after_title'   => '</h4>',
		) );
	}

	/**
	 * Removes the very annoying Try Gutenberg Panel.
	 */
	public function remove_gutenberg_panel() {
		remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );
	}


	/**
	 * Hides the very annoying Welcome Tips popup for Gutenberg.
	 */
	public function remove_gutenberg_tips() {
		?>
		<style>
			.components-modal__frame.components-guide {
				display: none !important;
			}

			.components-modal__screen-overlay {
				display: none !important;
			}
		</style>
		<?php
	}

	/**
	 * Disable the very annoying fullscreen mode for Gutenberg.
	 */
	public function disable_editor_fullscreen() {
		$script = "window.onload = function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } }";
		wp_add_inline_script( 'wp-blocks', $script );
	}

}

new bootSite();

/**
 * Custom fields.
 */
require_once 'inc/custom-fields.php';

/**
 * Custom Post types and Taxonomies.
 */
require_once 'inc/custom-post-taxonomies.php';

/**
 * Custom user roles.
 */
require_once 'inc/custom-user-roles.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require_once 'inc/template-functions.php';

/**
 * Theme options.
 */
require_once 'inc/template-options.php';

/**
 * Custom template tags.
 */
require_once 'inc/template-tags.php';

