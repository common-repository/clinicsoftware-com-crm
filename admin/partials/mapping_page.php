<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://clinicsoftware.com
 * @since      1.1.0
 *
 * @package    Clinicsoftwarecom_crm
 * @subpackage Clinicsoftwarecom_crm/admin/partials
 */
?>

<style>
    #marketing_lists {
        background: #fff;
        border: 1px solid #e1e1e1;
        padding: 5px 12px;
    }
</style>

<div class="wrap">
    <h1>ClinicSoftware.com CRM Mapping Helper</h1>
    <hr class="wp-header-end">

    <p>Mapping helper allows you to check certain options from ClinicSoftware.com CRM and generate code to insert them into your forms.</p>

    <?php if(!empty($data['marketing_lists'])) : ?>
        <div id="marketing_lists">
            <h2>Marketing Lists</h2>
            <p>Connect your form to a certain Marketing List inside ClinicSoftware.com CRM, the lead created from your website form will be assigned to the selected Marketing list, this way any automation provided on that list will be triggerred. </p>
            <p>
                <a href="<?php echo admin_url('admin.php?page=clinicsoftwarecom-mapping&resync=1'); ?>" class="button button-primary">Sync with ClinicSoftware.com CRM</a>
            </p>

            <p>*Place the content of <strong>Code</strong> column into your form. </p>

            <table class="wp-list-table widefat fixed striped table-view-list">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Code</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['marketing_lists'] as $marketing_list) :?>
                        <tr>
                            <td><?php echo esc_html($marketing_list['id']); ?></td>
                            <td><?php echo esc_html($marketing_list['name']); ?></td>
                            <td>
                                <code>[hidden marketing_list_name "<?php echo esc_html($marketing_list['id']); ?>"]</code>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
