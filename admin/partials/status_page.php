<?php

/**
 *
 * @link       https://clinicsoftware.com
 * @since      1.0.0
 *
 * @package    Clinicsoftwarecom_crm
 * @subpackage Clinicsoftwarecom_crm/admin/partials
 */
?>

<div class="wrap">
    <h1>ClinicSoftware.com CRM Status</h1>
    <hr class="wp-header-end">

    <div class="status" style="margin-top: 25px;">
        <?php if (isset($data['status']) && $data['status'] == 'connected'): ?>
            <span style="background: #00a32a; padding: 8px 12px; color: #fff;">Connected</span>
        <?php else: ?>
            <span style="background: #e74c3c; padding: 8px 12px; color: #fff;">
                Not Connected
            <?php if (!empty($data['message'])) : ?>
                - Reason: <?php _e($data['message']['message'], 'clinicsoftwarecom_crm'); ?>
            <?php endif; ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="status-message" style="margin-top: 25px;">
        <?php if (isset($data['status']) && $data['status'] == 'connected'): ?>
            <p style="font-weight: bold;">Your connection to ClinicSoftware.com CRM is done, now all your connected
                forms are sending data to your CRM account.</p>
            <p>In order to connect a Contact Form 7 form to your CRM, please add <code>[hidden api_connected "1"]</code>
                inside your form fields.</p>
            <p>Remember to check the plugin Fields page in order to sync fields from your side to ClinicSoftware.com CRM
                fields.</p>

        <?php else: ?>
            <p>Your connection to ClinicSoftware.com CRM failed. Please review added credentials on the ClinicSoftware
                Settings page.</p>
        <?php endif; ?>
    </div>
</div>
