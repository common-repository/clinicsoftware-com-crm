<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://clinicsoftware.com
 * @since      1.0.0
 *
 * @package    Clinicsoftwarecom_crm
 * @subpackage Clinicsoftwarecom_crm/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Clinicsoftwarecom_crm
 * @subpackage Clinicsoftwarecom_crm/admin
 * @author     ClinicSoftware.com <a@clinicsoftware.com>
 */
class Clinicsoftwarecom_crm_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * All settings keys needed by the plugin
     * @since 1.0.0
     */
    private $settings_keys;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version, $settings_keys)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->settings_keys = $settings_keys;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Clinicsoftwarecom_crm_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Clinicsoftwarecom_crm_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/clinicsoftwarecom_crm-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Clinicsoftwarecom_crm_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Clinicsoftwarecom_crm_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/clinicsoftwarecom_crm-admin.js', array('jquery'), $this->version, false);

    }

    /**
     * Register admin side menus
     *
     * @since 1.0.0
     */
    public function admin_menu()
    {
        add_menu_page('ClinicSoftware.com CRM Settings', 'ClinicSoftware', 'manage_options', 'clinicsoftwarecom-admin', [$this, 'settings_page']);
        add_submenu_page('clinicsoftwarecom-admin', 'ClinicSoftware.com CRM Status', 'Settings', 'manage_options', 'clinicsoftwarecom-admin', [$this, 'settings_page']);
        add_submenu_page('clinicsoftwarecom-admin', 'ClinicSoftware.com CRM Status', 'Status', 'manage_options', 'clinicsoftwarecom-status', [$this, 'status_page']);
        add_submenu_page('clinicsoftwarecom-admin', 'ClinicSoftware.com CRM Fields', 'Fields', 'manage_options', 'clinicsoftwarecom-fields', [$this, 'fields_page']);
        add_submenu_page('clinicsoftwarecom-admin', 'ClinicSoftware.com CRM Mapping', 'Mapping', 'manage_options', 'clinicsoftwarecom-mapping', [$this, 'mapping_page']);
        add_submenu_page('clinicsoftwarecom-admin', 'ClinicSoftware.com CRM FAQ', 'FAQ', 'manage_options', 'clinicsoftwarecom-faq', [$this, 'faq_page']);
    }

    /**
     * Process admin side posts
     **/
    public function save_settings_page($data)
    {
        if ( !current_user_can( 'manage_options' ) ) {
            include_once __DIR__ . '/partials/permission_denied.php';
            exit();
        }
        if (empty($data)) {
            // do nothing
            return;
        }

        foreach ($data as $key => $value) {
            // ignore some keys
            if ($key == 'action') {
                continue;
            }
            update_option($key, $value);
        }

        return $this->load_settings();
    }

    public function load_settings()
    {
        $data = [];
        if (empty($this->settings_keys)) {
            return [];
        }
        foreach ($this->settings_keys as $defined_setting) {
            $data[$defined_setting] = get_option($defined_setting);
        }
        return $data;
    }

    public function save_fields_page($data)
    {
        if ( !current_user_can( 'manage_options' ) ) {
            include_once __DIR__ . '/partials/permission_denied.php';
            exit();
        }
        update_option('clinicsoftwarecom_form_fields', serialize($data['fields']));
        return $data;
    }

    /**
     * Pages functions
     *
     * @since 1.0.0
     */
    public function settings_page()
    {
        if ( !current_user_can( 'manage_options' ) ) {
            include_once __DIR__ . '/partials/permission_denied.php';
            exit();
        }
        if (isset($_POST['action']) && $_POST['action'] == 'save_clinicsoftwarecom_settings') {
            $verify = wp_verify_nonce($_POST['nonce'], $_POST['action']);
            if($verify){
                $data = $this->save_settings_page($_POST);
                $data['saved_now'] = time();
            }else{
                include_once __DIR__ . '/partials/permission_denied.php';
                exit();
            }
        } else {
            $data = $this->load_settings();
        }
        include_once __DIR__ . '/partials/settings_page.php';
    }

    public function status_page()
    {
        if ( !current_user_can( 'manage_options' ) ) {
            include_once __DIR__ . '/partials/permission_denied.php';
            exit();
        }
        $data = $this->load_settings();
        if (empty($data)) {
            $data['status'] = 'disconnected';
        } else {
            $api = new clinicsoftwarecom_api($data['clinicsoftwarecom_client_key'], $data['clinicsoftwarecom_client_secret'], $data['clinicsoftwarecom_client_alias'], $data['clinicsoftwarecom_client_server']);
            $result = $api->getStatus();
            if(is_null($result)){
                $data['status'] = 'disconnected';
                $data['message'] = $api->getLastResult();
            }else{
                $data['status'] = $result['status'];
            }
        }
        include_once __DIR__ . '/partials/status_page.php';
    }

    public function fields_page()
    {
        if ( !current_user_can( 'manage_options' ) ) {
            include_once __DIR__ . '/partials/permission_denied.php';
            exit();
        }
        if (isset($_POST['action']) && $_POST['action'] == 'save_clinicsoftwarecom_fields') {
            $verify = wp_verify_nonce($_POST['nonce'], $_POST['action']);
            if($verify){
                $this->save_fields_page($_POST);
                $data['saved_now'] = time();
            }else{
                include_once __DIR__ . '/partials/permission_denied.php';
                exit();
            }
        }

        $fields = get_option('clinicsoftwarecom_form_fields');
        if (empty($fields)) {
            $fields = [
                [
                    "cs-name" => "name",
                    "local-name" => "",
                    "label" => "Name",
                ],
                [
                    "cs-name" => "surname",
                    "local-name" => "",
                    "label" => "Surname",
                ],
                [
                    "cs-name" => "email",
                    "local-name" => "",
                    "label" => "Email",
                ],
                [
                    "cs-name" => "phone",
                    "local-name" => '',
                    "label" => "Phone",
                ],
                [
                    "cs-name" => "description",
                    "local-name" => "",
                    "label" => "Description",
                ],
                [
                    "cs-name" => "marketing_source_id",
                    "local-name" => "",
                    "label" => "Marketing Source ID",
                ],
                [
                    "cs-name" => "postcode",
                    "local-name" => "",
                    "label" => "Postcode",
                ],
                [
                    "cs-name" => "address",
                    "local-name" => "",
                    "label" => "Address",
                ],
                [
                    "cs-name" => "phone_work",
                    "local-name" => "",
                    "label" => "Phone work",
                ],
                [
                    "cs-name" => "sex",
                    "local-name" => "",
                    "label" => "Sex",
                ],
                [
                    "cs-name" => "dob",
                    "local-name" => "",
                    "label" => "Date of Birth",
                ],
                [
                    "cs-name" => "salon_id",
                    "local-name" => "",
                    "label" => "Location ID",
                ],
                [
                    "cs-name" => "email_appointments_optin",
                    "local-name" => "",
                    "label" => "Email Appointments opt in",
                ],
                [
                    "cs-name" => "email_purchases_optin",
                    "local-name" => " ",
                    "label" => "Email Purchases opt in",
                ],
                [
                    "cs-name" => "email_marketing_optin",
                    "local-name" => " ",
                    "label" => "Marketing Emails opt in",
                ],
                [
                    "cs-name" => "email_other_optin",
                    "local-name" => " ",
                    "label" => "Other Emails opt in",
                ],
                [
                    "cs-name" => "sms_appointments_optin",
                    "local-name" => " ",
                    "label" => "SMS Appointments opt in",
                ],
                [
                    "cs-name" => "sms_purchases_optin",
                    "local-name" => " ",
                    "label" => "SMS Purchases opt in",
                ],
                [
                    "cs-name" => "sms_marketing_optin",
                    "local-name" => " ",
                    "label" => "SMS Marketing opt in",
                ],
                [
                    "cs-name" => "sms_other_optin",
                    "local-name" => " ",
                    "label" => "SMS Other opt in",
                ],
            ];

            $settings = $this->load_settings();
            $api = new clinicsoftwarecom_api($settings['clinicsoftwarecom_client_key'], $settings['clinicsoftwarecom_client_secret'], $settings['clinicsoftwarecom_client_alias'], $settings['clinicsoftwarecom_client_server']);
            $data = $api->getLeadCustomFields();

            if(!empty($data)){
                foreach ($data as $custom_field) {
                    $fields[] = [
                        "cs-name" => "custom_field{$custom_field['id']}",
                        "local-name" => "",
                        "label" => $custom_field['field_label'],
                    ];
                }
            }
        } else {
            $fields = unserialize($fields);
        }
        include_once __DIR__ . '/partials/fields_page.php';
    }

    /**
     * @since 1.1.0
     */
    public function mapping_page()
    {
        if ( !current_user_can( 'manage_options' ) ) {
            include_once __DIR__ . '/partials/permission_denied.php';
            exit();
        }

        $api = false;
        $data = $this->load_settings();

        if(empty($data)){
            wp_redirect('admin.php?page=clinicsoftwarecom-status');
        }

        $api = new clinicsoftwarecom_api($data['clinicsoftwarecom_client_key'], $data['clinicsoftwarecom_client_secret'], $data['clinicsoftwarecom_client_alias'], $data['clinicsoftwarecom_client_server']);
        $api->getStatus();
        $api_status = $api->getLastResult();

        if(empty($api_status) || $api_status['status'] == 'error'){
            wp_redirect('admin.php?page=clinicsoftwarecom-status');
        }

        $getMapping = [];
        $getMapping['data'] = unserialize(get_option('clinicsoftwarecom_mapping_fields'));

        if((isset($_GET['resync']) && $_GET['resync'] == 1) || empty($getMapping['data'])){
            $res = $api->getMapping();
            $getMapping = $api->getLastResult();
            if(!empty($getMapping['data'])){
                update_option('clinicsoftwarecom_mapping_fields', serialize($getMapping['data']));
            }
        }

        if(!empty($getMapping['data']['marketing_lists'])){
            $data['marketing_lists'] = $getMapping['data']['marketing_lists'];
        }

        include_once __DIR__ . '/partials/mapping_page.php';
    }

    public function faq_page()
    {
        if ( !current_user_can( 'manage_options' ) ) {
            include_once __DIR__ . '/partials/permission_denied.php';
            exit();
        }

        include_once __DIR__ . '/partials/faq_page.php';
    }

    public function debug($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit();
    }
}
