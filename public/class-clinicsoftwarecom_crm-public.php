<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://clinicsoftware.com
 * @since      1.0.0
 *
 * @package    Clinicsoftwarecom_crm
 * @subpackage Clinicsoftwarecom_crm/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Clinicsoftwarecom_crm
 * @subpackage Clinicsoftwarecom_crm/public
 * @author     ClinicSoftware.com <a@clinicsoftware.com>
 */
class Clinicsoftwarecom_crm_Public
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
    protected $settings_keys;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
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
     * Register the stylesheets for the public-facing side of the site.
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
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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
    }

    /**
     * Add method to send contact forms to ClinicSoftware.com CRM
     *
     * @since    1.0.0
     */
    public function send_contact()
    {
        if(class_exists('WPCF7_ContactForm') && class_exists('WPCF7_Submission')){
            $this->process_contact_form_sending();
        }
    }

    protected function load_settings()
    {
        $data = [];
        if(empty($this->settings_keys)){
            return [];
        }

        foreach ($this->settings_keys as $defined_setting){
            $data[$defined_setting] = get_option($defined_setting);
        }
        return $data;
    }

    protected function load_form_fields()
    {
        return unserialize(get_option('clinicsoftwarecom_form_fields'));
    }

    /**
     * @since 1.1.0
     */
    protected function load_mapping()
    {
        return unserialize(get_option('clinicsoftwarecom_mapping_fields'));
    }

    /**
     * @since 1.1.4
     */
    protected function load_mandatory_fields($form_fields){
        $mandatory_fields = [];
        foreach ($form_fields as $form_field){
            if($form_field['cs-name'] == 'email'){
                $mandatory_fields[] = $form_field['local-name'];
            }
            if($form_field['cs-name'] == 'phone'){
                $mandatory_fields[] = $form_field['local-name'];
            }
        }
        return $mandatory_fields;
    }

    /**
     * @since 1.0.0
     * @updated 1.1.4
     */
    protected function process_contact_form_sending()
    {
        $settings = $this->load_settings();
        $form_fields = $this->load_form_fields();
        $mapping = $this->load_mapping();
        $mandatory = $this->load_mandatory_fields($form_fields);

        $wpcf7 = WPCF7_ContactForm::get_current();
        $submission = WPCF7_Submission::get_instance();

        if(!empty($settings)){
            if ($submission) {
                $data = $submission->get_posted_data();
                // data is empty do not send to api
                if (empty($data)) { return $wpcf7; }

                // check if mandatory fields are empty, if empty to do not send to api
                $mandatory_empty = 0;
                foreach ($mandatory as $item){
                    if(!empty($data[$item])){
                        $mandatory_empty++;
                    }
                }
                if($mandatory_empty == 0){
                    return $wpcf7;
                }


                if ( !empty($data['api_connected']) ) {

                    $api = new clinicsoftwarecom_api($settings['clinicsoftwarecom_client_key'], $settings['clinicsoftwarecom_client_secret'], $settings['clinicsoftwarecom_client_alias'], $settings['clinicsoftwarecom_client_server']);

                    $client_notes = 'Added from website contact form on ' . date('d/m/Y h:i A');

                    // Initialize the dataset as empty
                    $cs_dataset = [];

                    foreach( $form_fields as $form_field ) {
                        // Skip special fields.
                        if ( $form_field['cs-name'] == 'description' ) {
                            $client_notes .= "\r\n\r\nForm description: " . $data[$form_field['local-name']];
                            continue;
                        };

                        if ( $form_field['cs-name'] == 'marketing_source_id' ) {
                            $cs_dataset['marketing_source_id'] = $data[$form_field['local-name']];

                            if ( is_array($cs_dataset['marketing_source_id']) ) {
                                $cs_dataset['marketing_source_id'] = $cs_dataset['marketing_source_id'][array_keys($cs_dataset['marketing_source_id'])[0]];
                            }
                            continue;
                        };

                        $data_value = !empty($data[$form_field['local-name']]) ? $data[$form_field['local-name']] : null;
                        $cs_dataset[$form_field['cs-name']] = $data_value;
                    }

                    $blacklist = ['g-recaptcha-response', 'api_connected', 'marketing_list_name'];
                    foreach ( $form_fields as $v ) {
                        $blacklist[] = $v['local-name'];
                    }

                    $client_notes .= "\n\n";
                    foreach( $data as $k => $v ) {
                        if ( in_array(strtolower($k), $blacklist) ) {
                            continue;
                        }
                        if ( gettype($v) != 'string' ) {
                            $v = json_encode($v);
                        }
                        $client_notes .= "$k: $v\n";
                    }

                    $cs_dataset['description'] = trim($client_notes);

                    if(!empty($mapping['marketing_lists']) && !empty($data['marketing_list_name'])){
                        foreach ($mapping['marketing_lists'] as $marketing_list){
                            if($marketing_list['id'] == $data['marketing_list_name']){
                                $data['marketing_list_name'] = $marketing_list['name'];
                                break;
                            }
                        }
                    }
                    $cs_dataset['marketing_list_name'] = $data['marketing_list_name'];
                    $api->addLead($cs_dataset);
                }
            }
        }

        return $wpcf7;
    }

}
