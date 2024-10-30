<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://clinicsoftware.com
 * @since      1.0.0
 *
 * @package    Clinicsoftwarecom_crm
 * @subpackage Clinicsoftwarecom_crm/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Clinicsoftwarecom_crm
 * @subpackage Clinicsoftwarecom_crm/includes
 * @author     ClinicSoftware.com <a@clinicsoftware.com>
 */
class Clinicsoftwarecom_crm {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Clinicsoftwarecom_crm_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * All settings keys needed by the plugin
     * @since 1.0.0
     */
    protected $settings_keys = [
        'clinicsoftwarecom_client_key',
        'clinicsoftwarecom_client_secret',
        'clinicsoftwarecom_client_alias',
        'clinicsoftwarecom_client_server',
    ];

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if ( defined( 'CLINICSOFTWARECOM_CRM_VERSION' ) ) {
            $this->version = CLINICSOFTWARECOM_CRM_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'clinicsoftwarecom_crm';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Clinicsoftwarecom_crm_Loader. Orchestrates the hooks of the plugin.
     * - Clinicsoftwarecom_crm_i18n. Defines internationalization functionality.
     * - Clinicsoftwarecom_crm_Admin. Defines all hooks for the admin area.
     * - Clinicsoftwarecom_crm_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for ClinicSoftware.com API communication
         */
        require_once plugin_dir_path(dirname(__FILE__)). 'includes/class-clinicsoftwarecom_api.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-clinicsoftwarecom_crm-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-clinicsoftwarecom_crm-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-clinicsoftwarecom_crm-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-clinicsoftwarecom_crm-public.php';

        $this->loader = new Clinicsoftwarecom_crm_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Clinicsoftwarecom_crm_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Clinicsoftwarecom_crm_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Clinicsoftwarecom_crm_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_settings_keys());

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action('admin_menu', $plugin_admin, 'admin_menu');

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new Clinicsoftwarecom_crm_Public( $this->get_plugin_name(), $this->get_version(), $this->get_settings_keys());

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        // Contact Form 7 hook
        $this->loader->add_action('wpcf7_mail_sent', $plugin_public, 'send_contact');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Clinicsoftwarecom_crm_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Retrieve settings keys
     * @since 1.0.0
     * @return array
     */
    public function get_settings_keys()
    {
        return $this->settings_keys;
    }

}
