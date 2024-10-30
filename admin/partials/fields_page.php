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
    <h1>ClinicSoftware.com CRM Fields</h1>
    <hr class="wp-header-end">

    <?php if(!empty($data['saved_now'])): ?>
        <div class="notice notice-success is-dismissible">
            <p>Settings saved.</p>
            <button class="notice-dismiss" type="button"></button>
        </div>
    <?php endif; ?>

    <p>Left column contains fields provided by your connection to ClinicSoftware.com CRM, on the right side you must connect the Contact Forms fields names you have on the contact form setup.</p>
    <p>Only the assigned fields will be sent to ClinicSoftware.com</p>

    <form method="post" action="<?php echo admin_url('admin.php?page=clinicsoftwarecom-fields'); ?>">
        <input type="hidden" name="action" value="save_clinicsoftwarecom_fields"/>
        <?php $nonce = wp_create_nonce( 'save_clinicsoftwarecom_fields' ); ?>
        <input type="hidden" name="nonce" value="<?php echo $nonce ?>" />

        <table class="form-table" role="presentation">
            <tbody>
            <?php foreach ($fields as $key => $field): ?>
                <tr>
                    <th role="row">
                        <?php echo esc_html($field['label']); ?>
                        <input type="hidden" name="fields[<?php echo esc_html($key); ?>][label]" value="<?php echo esc_html($field['label']); ?>" />
                    </th>
                    <td style="max-width: 100px;">
                        <input type="text" name="fields[<?php echo esc_html($key); ?>][cs-name]"
                               class="regular-text" value="<?php echo esc_html($field['cs-name']); ?>" readonly>
                    </td>
                    <td>
                        <input type="text" name="fields[<?php echo esc_html($key); ?>][local-name]"
                               class="regular-text" value="<?php echo (!empty($field['local-name']) ? esc_html($field['local-name']) : $field['cs-name']); ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" value="Save fields" class="button button-primary">
        </p>
    </form>
</div>
