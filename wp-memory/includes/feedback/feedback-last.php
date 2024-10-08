<?php  namespace wpmemory_last_feedback{
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}
	if ( is_multisite() ) {
		return;
	}
	if ( __NAMESPACE__ == 'wpmemory_last_feedback' ) {
		define( __NAMESPACE__ . '\PRODCLASS', 'wp_memory' );
		define( __NAMESPACE__ . '\VERSION', WPMEMORYVERSION );
		define( __NAMESPACE__ . '\PLUGINHOME', 'https://wpmemory.com' );
		define( __NAMESPACE__ . '\PRODUCTNAME', 'WP Memory Plugin' );
		define( __NAMESPACE__ . '\LANGUAGE', 'wp-memory'  );
		define( __NAMESPACE__ . '\PAGE', 'settings' );
		define( __NAMESPACE__ . '\OPTIN', 'wp_memor_optin' );
		define( __NAMESPACE__ . '\LAST', 'wp_memory_last_feedback' );
		define( __NAMESPACE__ . '\URL', WPMEMORYURL );
	}
	if ( __NAMESPACE__ == 'wptools_last_feedback' ) {
		define( __NAMESPACE__ . '\PRODCLASS', 'wptools' );
		define( __NAMESPACE__ . '\VERSION', WPTOOLSVERSION );
		// define( __NAMESPACE__ . '\PLUGINHOME', 'https://wptoolsplugin.com' );
		define( __NAMESPACE__ . '\PRODUCTNAME', 'WP Tools Plugin' );
		define( __NAMESPACE__ . '\LANGUAGE', 'wptools'  );
		define( __NAMESPACE__ . '\PAGE', 'settings' );
		define( __NAMESPACE__ . '\OPTIN', 'wp_tools_optin' );
		define( __NAMESPACE__ . '\LAST', 'wp_tools_last_feedback' );
		define( __NAMESPACE__ . '\URL', WPTOOLSURL );
	}
    $last_feedback =  (int) sanitize_text_field(get_site_option(LAST, '0'));
	if($last_feedback == 0){
	  $delta = 0;
	  $last_feedback = time();
	}
	else{
	   $delta = (1 * 24 * 3600);
	}



	// debug
// $delta = 0;



		if ( $last_feedback + $delta <= time() ) {
			// return;
			define( __NAMESPACE__ . '\WPMSHOW', true );
		}
		else
			define( __NAMESPACE__ . '\WPMSHOW', false );	
	class Bill_mConfig {
		protected static $namespace          = __NAMESPACE__;
		protected static $bill_plugin_url    = URL;
		protected static $bill_class         = PRODCLASS;
		protected static $bill_prod_veersion = VERSION;
		//protected static $sbb_show_or_not = SBBNOTSHOW;
		function __construct() {
			add_action( 'load-plugins.php', array( __CLASS__, 'init' ) );
			add_action( 'wp_ajax_bill_feedback', array( __CLASS__, 'feedback' ) );
		}
		public static function init() {
			add_action( 'admin_notices', array( __CLASS__, 'message' ) );
			add_action( 'admin_head', array( __CLASS__, 'register' ) );
			add_action( 'admin_footer', array( __CLASS__, 'enqueue' ) );
		}
		public static function register() {
			  wp_enqueue_style( PRODCLASS, URL . 'includes/feedback/feedback-last-plugin.css' );
			  if(WPMSHOW)
			    wp_register_script( PRODCLASS, URL . 'includes/feedback/feedback-last.js', array( 'jquery' ), VERSION, true );
		}
		public static function enqueue() {
			  wp_enqueue_style( PRODCLASS );
			  wp_enqueue_script( PRODCLASS );
			  // var_dump(__LINE__);
		}
		public static function message() {
				if ( ! update_option( LAST, time() ) ) {
					add_option( LAST, time() );
				}
				?>  
			   <div class="<?php echo esc_attr( PRODCLASS ); ?>-wrap-deactivate" style="display:none">
				  <div class="bill-vote-gravatar"><a href="https://profiles.wordpress.org/sminozzi" target="_blank"><img src="https://en.gravatar.com/userimage/94727241/31b8438335a13018a1f52661de469b60.jpg?size=100" alt="Bill Minozzi" width="70" height="70"></a></div>
					<div class="bill-vote-message">
				   <?php
				   echo '<h2 style="color:blue;">';
				   echo esc_attr(PRODUCTNAME).' - ';
				   echo esc_attr__( "We're sorry to hear that you're leaving.", 'wpmemory' );
				   echo '</h2>'; 
				   esc_attr_e( 'Hello,', 'wpmemory' );
				   echo '<br />'; 
				   echo '<br />'; 
				   esc_attr_e( "We'd like to thank you for trying our products.", 'wpmemory' );
				   echo '<br />';

				   esc_attr_e("We offer 20+ free plugins and 6 themes to supercharge your website's security, functionality, and backups. Trusted by over 50,000 users.", 'wpmemory' );
				   // echo '<br />'; 
					 ?>
					 <br /><br />             
					 <strong><?php esc_attr_e( 'Best regards!', 'wpmemory' ); ?></strong>
					 <br /><br /> 
					 Bill Minozzi<br /> 
					 Plugin Developer
					 <br /> <br /> 

						
							<a href="<?php echo esc_url(admin_url('tools.php?page=wp_memory_admin_page&tab=tools')); ?>" class="button button-primary <?php echo esc_attr(PRODCLASS); ?>-close-submit">
							<?php esc_html_e('Discover New FREE Plugins', 'wpmemory'); ?>
						</a>
						<a href="<?php echo esc_url('https://BillMinozzi.com/dove/'); ?>" class="button button-primary <?php echo esc_attr(PRODCLASS); ?>-close-dialog">
							<?php esc_html_e('Support Page', 'wpmemory'); ?>
						</a>
						<a href="#" class="button <?php echo esc_attr(PRODCLASS); ?>-close-dialog">
							<?php esc_html_e('Cancel', 'wpmemory'); ?>
						</a>
						<a href="#" class="button <?php echo esc_attr(PRODCLASS); ?>-deactivate">
							<?php esc_html_e('Just Deactivate', 'wpmemory'); ?>
						</a>
	
						
						
						
						
						
							<br /><br />
					 <input type="hidden" id="prodclass" name="prodclass" value="<?php echo esc_attr(PRODCLASS);?>">
				   </div>
			 </div> 
					<?php
			}
		} //end class
		new Bill_mConfig();
		$stringtime = strval(time());
		if ( ! update_option( LAST, $stringtime ) ) {
			   add_option( LAST, $stringtime );
		   }
	} // End Namespace ...
	?>