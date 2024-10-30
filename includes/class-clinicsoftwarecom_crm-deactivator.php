<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://clinicsoftware.com
 * @since      1.0.0
 *
 * @package    Clinicsoftwarecom_crm
 * @subpackage Clinicsoftwarecom_crm/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Clinicsoftwarecom_crm
 * @subpackage Clinicsoftwarecom_crm/includes
 * @author     ClinicSoftware.com <a@clinicsoftware.com>
 */
class Clinicsoftwarecom_crm_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        delete_option('clinicsoftwarecom_form_fields');
        delete_option('clinicsoftwarecom_client_key');
        delete_option('clinicsoftwarecom_client_secret');
        delete_option('clinicsoftwarecom_client_alias');
        delete_option('clinicsoftwarecom_client_server');
        /**
         * @since 1.1.0
         */
        delete_option('clinicsoftwarecom_mapping_fields');
	}

}
