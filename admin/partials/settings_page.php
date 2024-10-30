<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://clinicsoftware.com
 * @since      1.0.0
 *
 * @package    Clinicsoftwarecom_crm
 * @subpackage Clinicsoftwarecom_crm/admin/partials
 */
?>

<div class="wrap">
    <h1>ClinicSoftware.com CRM Settings</h1>
    <hr class="wp-header-end">

    <?php if(!empty($data['saved_now'])): ?>
        <div class="notice notice-success is-dismissible">
            <p>Settings saved.</p>
            <button class="notice-dismiss" type="button"></button>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo admin_url('admin.php?page=clinicsoftwarecom-admin'); ?>">
        <input type="hidden" name="action" value="save_clinicsoftwarecom_settings"/>
        <?php $nonce = wp_create_nonce( 'save_clinicsoftwarecom_settings' ); ?>
        <input type="hidden" name="nonce" value="<?php echo $nonce ?>" />

        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">Client Key:</th>
                <td>
                    <input type="text" name="clinicsoftwarecom_client_key" id="clinicsoftwarecom_client_key"
                           class="regular-text" value="<?php echo esc_html($data['clinicsoftwarecom_client_key']); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">Client Secret:</th>
                <td>
                    <input type="text" name="clinicsoftwarecom_client_secret" id="clinicsoftwarecom_client_secret"
                           class="regular-text" value="<?php echo esc_html($data['clinicsoftwarecom_client_secret']); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">Client Alias:</th>
                <td>
                    <input type="text" name="clinicsoftwarecom_client_alias" id="clinicsoftwarecom_client_alias"
                           class="regular-text" value="<?php echo esc_html($data['clinicsoftwarecom_client_alias']); ?>" placeholder="demo">
                </td>
            </tr>
            <tr>
                <th scope="row">Client Server:</th>
                <td>
                    <input type="text" name="clinicsoftwarecom_client_server" id="clinicsoftwarecom_client_server"
                           class="regular-text" value="<?php echo esc_html($data['clinicsoftwarecom_client_server']); ?>" placeholder="server3.clinicsoftware.com">
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" value="Save changes" class="button button-primary">
        </p>
    </form>
</div>
