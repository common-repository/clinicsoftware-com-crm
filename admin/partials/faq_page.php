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

$plugin_dir_url = plugin_dir_url('clinicsoftware-com-crm/assets');
$faq_assets_url = $plugin_dir_url . 'assets/faq/';
?>

<style>
    #faq_list {

    }

    #faq_list ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: block;
        width: 100%;
    }

    #faq_list ul li {
        display: block;
        width: 100%;
        background: #3582c4;
        color: #fff;
    }

    #faq_list ul li span.question {
        display: block;
        width: 100%;
        font-size: 16px;
        font-weight: bold;
        padding: 8px;
        cursor: pointer;
    }

    #faq_list ul li span.response.d-none {
        display: none;
    }

    #faq_list ul li span.response {
        display: block;
        width: 100%;
        background: #f0f0f1;
        padding: 10px;
        font-size: 16px;
        line-height: 22px;
        color: #000;
    }

    #faq_list .img-fluid {
        width: auto;
        max-width: 100%;
        border: 2px dashed #3a2c4d;
        margin-top: 5px;
        margin-bottom: 5px;
    }
</style>

<div class="wrap">
    <h1>ClinicSoftware.com CRM FAQ</h1>
    <hr class="wp-header-end">

    <p></p>

    <div id="faq_list">
        <ul>
            <li>
                <span class="question">How to connect ClinicSoftware.com CRM?</span>
                <span class="response d-none">
                    1. Go to <a href="<?php echo admin_url('admin.php?page=clinicsoftwarecom-admin'); ?>"
                                target="_blank">ClinicSoftware.com CRM Settings</a><br/>
                    <img src="<?php echo $faq_assets_url . 'settings_page.png'; ?>" alt="settings-page"
                         class="img-fluid"/> <br/>
                    2. Complete settings fields with data from your CRM account (see API Clients inside Admin Area) <br/>
                    <img src="<?php echo $faq_assets_url . 'crm_api_clients.png'; ?>" alt="crm-api-clients-page"
                         class="img-fluid"/> <br/>
                    3. Save settings and check <a
                            href="<?php echo admin_url('admin.php?page=clinicsoftwarecom-status'); ?>" target="_blank">Status</a> page. <br/>
                    <br/>
                    If the connection is not ready the status page will display the reason connection failed.<br/>
                    <img src="<?php echo $faq_assets_url . 'connection_failed.png'; ?>" alt="invalid-connection"
                         class="img-fluid"/> <br/>
                </span>
            </li>
            <li>
                <span class="question">ClinicSoftware.com CRM Fields</span>
                <span class="response d-none">
                    This page will load and show the mandatory fields from ClinicSoftware.com CRM + all the custom fields you set for leads inside your account. <br/>
                    By default after plugin activation and connection to API, the right side will autocomplete the ClinicSoftware.com CRM names, that can be changed based on your forms input name. <br/>
                    <br/>
                    For example: <br/>
                    In CRM the input "name" can be assigned to a form input name like "your-name", "first-name", "fname", etc.
                    <br/>
                    <img src="<?php echo $faq_assets_url . 'fields_example.png'; ?>" alt="field_example"
                         class="img-fluid"/> <br/>
                    <br/>
                    You can make any changes on the right side as long as they are added and exists in your forms. <strong>Don't forget to save the changes</strong>.
                    <br/>
                    <br/>
                    <strong>You don't have to add all the fields listed here to your forms, as long as you don't need that relation to be saved in your leads/deals section inside CRM.</strong>
                </span>
            </li>
            <li>
                <span class="question">ClinicSoftware.com CRM Mapping Helper</span>
                <span class="response d-none">
                       Mapping helper allows you to check certain options from ClinicSoftware.com CRM and generate code to insert them into your forms. <br/>
                       Example Marketing Lists in our case: <br/>
                        <img src="<?php echo $faq_assets_url . 'marketing_lists.png'; ?>" alt="marketing_lists"
                             class="img-fluid"/> <br/>
                        <br/>
                        Example of generated code that can be added to form: <br/>
                        <img src="<?php echo $faq_assets_url . 'marketing_list_name_code.png'; ?>"
                             alt="marketing_list_name_code"
                             class="img-fluid"/> <br/>
                </span>
            </li>
            <li>
                <span class="question">How to connect a form to ClinicSoftware.com CRM ?</span>
                <span class="response d-none">
                   Go to your form edit page and add : <code>[hidden api_connected "1"]</code> <br/>
                    <br/>
                     <img src="<?php echo $faq_assets_url . 'api_connected.png'; ?>"
                          alt="api_connected"
                          class="img-fluid"/> <br/>
                    All submitted forms will now send the data to your connected account.
                </span>
            </li>
            <li>
                <span class="question">How to add a specific location to a form?</span>
                <span class="response d-none">
                    1. Go to <a href="<?php echo admin_url('admin.php?page=clinicsoftwarecom-fields'); ?>"
                                target="_blank">ClinicSoftware.com CRM Fields</a><br/>
                    2. Search for Location ID row and get the name from the right column input.(default case salon_id) <br/>
                    3. Go to your form edit page and add it : <code>[hidden salon_id "1"]</code> (where "1" is the ID of the location inside ClinicSoftware.com CRM)<br/>
                    <br/>
                    <img src="<?php echo $faq_assets_url . 'salon_example.png'; ?>"
                         alt="salon_example"
                         class="img-fluid"/> <br/>
                </span>
            </li>
        </ul>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#faq_list ul li span.question').on('click', function () {
            $('span.response').each(function (){
                $(this).addClass('d-none');
            });

            if ($(this).hasClass('active')) {
                $(this).siblings('span.response').addClass('d-none');
                $(this).removeClass('active');
            } else {
                $(this).siblings('span.response').removeClass('d-none');
                $(this).addClass('active');
            }
        });
    });
</script>