<?php
require PSYEM_PATH . 'packages/vendor/autoload.php';
require PSYEM_PATH . 'packages/phpqrcode/qrlib.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class psyemAdminManager extends psyemEventsManager
{
    var $REQ;

    function __construct()
    {
        global $wpdb, $post;
        $this->REQ  = (isset($_REQUEST) && !empty($_REQUEST)) ? $_REQUEST : array();
        $this->psyem_EventsManagerAdminActions();
    }

    function psyem_EventsManagerAdminActions()
    {
        add_action('admin_head',  array(&$this, PSYEM_PREFIX . 'AddCustomAdminHeadstyles'));

        // plugin settings
        add_action('admin_menu',            array(&$this, PSYEM_PREFIX . 'AdminMenus'));
        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_settings', array(&$this, PSYEM_PREFIX . 'ManageSettingsAjax'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_settings', array(&$this, PSYEM_PREFIX . 'ManageSettingsAjax'));
        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_projectsafe_type', array(&$this, PSYEM_PREFIX . 'ManageProjectsafeTypeAjax'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_projectsafe_type', array(&$this, PSYEM_PREFIX . 'ManageProjectsafeTypeAjax'));

        // admin scripts
        add_action('admin_enqueue_scripts', array(&$this, PSYEM_PREFIX . 'RegisterEventsManagerAdminScripts'));
        add_action('admin_enqueue_scripts', array(&$this, PSYEM_PREFIX . 'EnqueueEventsManagerAdminScripts'));

        // tickets posts type
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'TicketsCustomPostType'));
        add_action('add_meta_boxes',        array(&$this, PSYEM_PREFIX . 'TicketsCustomPostTypeMetaBoxes'));
        add_action('save_post',             array(&$this, PSYEM_PREFIX . 'TicketsCustomPostTypeSaveMetaBoxes'));
        add_filter('manage_edit-psyem-tickets_columns', array(&$this, PSYEM_PREFIX . 'TicketsTableCustomColumns'));
        add_action('manage_psyem-tickets_posts_custom_column',  array(&$this, PSYEM_PREFIX . 'TicketsTableCustomColumnValues'), 10, 2);
        add_filter('manage_edit-psyem-tickets_sortable_columns', array(&$this, PSYEM_PREFIX . 'TicketsTableSortableColumns'));

        // speakers posts type
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'SpeakersCustomPostType'));
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'CreateSpeakerTaxonomy'));
        add_action('add_meta_boxes',        array(&$this, PSYEM_PREFIX . 'SpeakerCustomPostTypeMetaBoxes'));
        add_action('save_post',             array(&$this, PSYEM_PREFIX . 'SpeakerCustomPostTypeSaveMetaBoxes'));
        add_filter('manage_edit-psyem-speakers_columns', array(&$this, PSYEM_PREFIX . 'SpeakersTableCustomColumns'));
        add_action('manage_psyem-speakers_posts_custom_column',  array(&$this, PSYEM_PREFIX . 'SpeakersTableCustomColumnValues'), 10, 2);
        add_filter('manage_edit-psyem-speakers_sortable_columns', array(&$this, PSYEM_PREFIX . 'SpeakersTableSortableColumns'));

        // partners posts type
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'PartnersCustomPostType'));
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'CreatePartnerTaxonomy'));
        add_action('add_meta_boxes',        array(&$this, PSYEM_PREFIX . 'PartnerCustomPostTypeMetaBoxes'));
        add_action('save_post',             array(&$this, PSYEM_PREFIX . 'PartnerCustomPostTypeSaveMetaBoxes'));
        add_filter('manage_edit-psyem-partners_columns', array(&$this, PSYEM_PREFIX . 'PartnersTableCustomColumns'));
        add_action('manage_psyem-partners_posts_custom_column',  array(&$this, PSYEM_PREFIX . 'PartnersTableCustomColumnValues'), 10, 2);
        add_filter('manage_edit-psyem-partners_sortable_columns', array(&$this, PSYEM_PREFIX . 'PartnersTableSortableColumns'));

        // coupons posts type
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'CouponsCustomPostType'));
        add_action('add_meta_boxes',        array(&$this, PSYEM_PREFIX . 'CouponsCustomPostTypeMetaBoxes'));
        add_action('save_post',             array(&$this, PSYEM_PREFIX . 'CouponsCustomPostTypeSaveMetaBoxes'));
        add_filter('manage_edit-psyem-coupons_columns', array(&$this, PSYEM_PREFIX . 'CouponsTableCustomColumns'));
        add_action('manage_psyem-coupons_posts_custom_column',  array(&$this, PSYEM_PREFIX . 'CouponsTableCustomColumnValues'), 10, 2);
        add_filter('manage_edit-psyem-coupons_sortable_columns', array(&$this, PSYEM_PREFIX . 'CouponsTableSortableColumns'));

        // events posts type
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'EventsCustomPostType'));
        add_action('add_meta_boxes',        array(&$this, PSYEM_PREFIX . 'EventsCustomPostTypeMetaBoxes'));
        add_action('save_post',             array(&$this, PSYEM_PREFIX . 'EventsCustomPostTypeSaveMetaBoxes'));
        add_filter('manage_edit-psyem-events_columns', array(&$this, PSYEM_PREFIX . 'EventsTableCustomColumns'));
        add_action('manage_psyem-events_posts_custom_column',  array(&$this, PSYEM_PREFIX . 'EventsTableCustomColumnValues'), 10, 2);
        add_filter('manage_edit-psyem-events_sortable_columns', array(&$this, PSYEM_PREFIX . 'EventsTableSortableColumns'));

        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_copy_event', array(&$this,  PSYEM_PREFIX . 'ManageCopyEventData'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_copy_event', array(&$this,  PSYEM_PREFIX . 'ManageCopyEventData'));
        add_action('admin_post_'            . PSYEM_PREFIX . 'export_event_attendees', array(&$this,  PSYEM_PREFIX . 'ExportEventAttendees'));
        add_action('admin_post_nopriv_'     . PSYEM_PREFIX . 'export_event_attendees', array(&$this,  PSYEM_PREFIX . 'ExportEventAttendees'));


        // order posts type
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'OrdersCustomPostType'));
        add_action('add_meta_boxes',        array(&$this, PSYEM_PREFIX . 'OrdersCustomPostTypeMetaBoxes'));
        add_action('save_post',             array(&$this, PSYEM_PREFIX . 'OrdersCustomPostTypeSaveMetaBoxes'));
        add_filter('manage_edit-psyem-orders_columns', array(&$this, PSYEM_PREFIX . 'OrdersTableCustomColumns'));
        add_action('manage_psyem-orders_posts_custom_column',  array(&$this, PSYEM_PREFIX . 'OrdersTableCustomColumnValues'), 10, 2);
        add_filter('manage_edit-psyem-orders_sortable_columns', array(&$this, PSYEM_PREFIX . 'OrdersTableSortableColumns'));
        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_order_send_tickets', array(&$this, PSYEM_PREFIX . 'ManageOrderSendTicketsAjax'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_order_send_tickets', array(&$this, PSYEM_PREFIX . 'ManageOrderSendTicketsAjax'));
        add_action('admin_post_'            . PSYEM_PREFIX . 'order_print_tickets', array(&$this,  PSYEM_PREFIX . 'ManageOrderPrintTickets'));
        add_action('admin_post_nopriv_'     . PSYEM_PREFIX . 'order_print_tickets', array(&$this,  PSYEM_PREFIX . 'ManageOrderPrintTickets'));

        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_offline_signup', array(&$this, PSYEM_PREFIX . 'ManageOfflineRegistrationAjax'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_offline_signup', array(&$this, PSYEM_PREFIX . 'ManageOfflineRegistrationAjax'));
        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_participants_csv', array(&$this, PSYEM_PREFIX . 'ManageOrderParticipantsCsvAjax'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_participants_csv', array(&$this, PSYEM_PREFIX . 'ManageOrderParticipantsCsvAjax'));

        // participants posts type
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'ParticipantsCustomPostType'));
        add_action('add_meta_boxes',        array(&$this, PSYEM_PREFIX . 'ParticipantsCustomPostTypeMetaBoxes'));
        add_action('save_post',             array(&$this, PSYEM_PREFIX . 'ParticipantsCustomPostTypeSaveMetaBoxes'));
        add_filter('manage_edit-psyem-participants_columns', array(&$this, PSYEM_PREFIX . 'ParticipantsTableCustomColumns'));
        add_action('manage_psyem-participants_posts_custom_column',  array(&$this, PSYEM_PREFIX . 'ParticipantsTableCustomColumnValues'), 10, 2);
        add_filter('manage_edit-psyem-participants_sortable_columns', array(&$this, PSYEM_PREFIX . 'ParticipantsTableSortableColumns'));
        add_action('restrict_manage_posts', array(&$this, PSYEM_PREFIX . 'AddParticipantsExportButtonBeforeTable'));
        add_action('admin_post_'            . PSYEM_PREFIX . 'participants_export', array(&$this,  PSYEM_PREFIX . 'ExportParticipantsCustomPostType'));
        add_action('admin_post_nopriv_'     . PSYEM_PREFIX . 'participants_export', array(&$this,  PSYEM_PREFIX . 'ExportParticipantsCustomPostType'));

        // projectsafes posts type
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'ProjectsafesCustomPostType'));
        add_action('add_meta_boxes',        array(&$this, PSYEM_PREFIX . 'ProjectsafesCustomPostTypeMetaBoxes'));
        add_action('save_post',             array(&$this, PSYEM_PREFIX . 'ProjectsafesCustomPostTypeSaveMetaBoxes'));
        add_filter('manage_edit-psyem-projectsafes_columns', array(&$this, PSYEM_PREFIX . 'ProjectsafesTableCustomColumns'));
        add_action('manage_psyem-projectsafes_posts_custom_column',  array(&$this, PSYEM_PREFIX . 'ProjectsafesTableCustomColumnValues'), 10, 2);
        add_filter('manage_edit-psyem-projectsafes_sortable_columns', array(&$this, PSYEM_PREFIX . 'ProjectsafesTableSortableColumns'));
        add_action('restrict_manage_posts', array(&$this, PSYEM_PREFIX . 'AddProjectSafeExportButtonBeforeTable'));
        add_action('admin_post_'            . PSYEM_PREFIX . 'projectsafe_export', array(&$this,  PSYEM_PREFIX . 'ExportProjectsafesCustomPostType'));
        add_action('admin_post_nopriv_'     . PSYEM_PREFIX . 'projectsafe_export', array(&$this,  PSYEM_PREFIX . 'ExportProjectsafesCustomPostType'));

        // misc
        add_filter('pre_get_posts',         array(&$this, PSYEM_PREFIX . 'ApplyCustomPostTypeSearch'));
        add_filter('posts_search',          array(&$this, PSYEM_PREFIX . 'ExtendCustomPostTypeSearch'), 10, 2);
        add_filter('post_row_actions',      array(&$this, PSYEM_PREFIX . 'AddEventCustomPreviewLink'), 10, 2);

        // donations amounts
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'DonationAmountsCustomPostType'));
        add_action('add_meta_boxes',        array(&$this, PSYEM_PREFIX . 'DonationAmountsCustomPostTypeMetaBoxes'));
        add_action('save_post',             array(&$this, PSYEM_PREFIX . 'DonationAmountsCustomPostTypeSaveMetaBoxes'));
        add_filter('manage_edit-psyem-amounts_columns', array(&$this, PSYEM_PREFIX . 'DonationAmountsTableCustomColumns'));
        add_action('manage_psyem-amounts_posts_custom_column',  array(&$this, PSYEM_PREFIX . 'DonationAmountsTableCustomColumnValues'), 10, 2);
        add_filter('manage_edit-psyem-amounts_sortable_columns', array(&$this, PSYEM_PREFIX . 'DonationAmountsTableSortableColumns'));

        // donations
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'DonationsCustomPostType'));
        add_action('add_meta_boxes',        array(&$this, PSYEM_PREFIX . 'DonationsCustomPostTypeMetaBoxes'));
        add_action('save_post',             array(&$this, PSYEM_PREFIX . 'DonationsCustomPostTypeSaveMetaBoxes'));
        add_filter('manage_edit-psyem-donations_columns', array(&$this, PSYEM_PREFIX . 'DonationsTableCustomColumns'));
        add_action('manage_psyem-donations_posts_custom_column',  array(&$this, PSYEM_PREFIX . 'DonationsTableCustomColumnValues'), 10, 2);
        add_filter('manage_edit-psyem-donations_sortable_columns', array(&$this, PSYEM_PREFIX . 'DonationsTableSortableColumns'));

        // Knowledge hub
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'KnowledgehubCustomPostType'));
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'KnowledgehubCustomPostTaxonomy'));
        add_action('psyem-knowledges-category_add_form_fields', array(&$this, PSYEM_PREFIX . 'ListingCustomPostsTaxonomyAddCustomField'));
        add_action('psyem-knowledges-category_edit_form_fields', array(&$this, PSYEM_PREFIX . 'ListingCustomPostsTaxonomyEditCustomField'));
        add_action('created_psyem-knowledges-category', array(&$this, PSYEM_PREFIX . 'ListingCustomPostsTaxonomySaveCustomField'));
        add_action('edited_psyem-knowledges-category', array(&$this, PSYEM_PREFIX . 'ListingCustomPostsTaxonomySaveCustomField'));
        // add_action('add_meta_boxes',        array(&$this, PSYEM_PREFIX . 'KnowledgehubCustomPostTypeMetaBoxes'));
        // add_action('save_post',             array(&$this, PSYEM_PREFIX . 'KnowledgehubCustomPostTypeSaveMetaBoxes'));
        // add_filter('manage_edit-psyem-donations_columns', array(&$this, PSYEM_PREFIX . 'KnowledgehubTableCustomColumns'));
        // add_action('manage_psyem-donations_posts_custom_column',  array(&$this, PSYEM_PREFIX . 'KnowledgehubTableCustomColumnValues'), 10, 2);
        // add_filter('manage_edit-psyem-donations_sortable_columns', array(&$this, PSYEM_PREFIX . 'KnowledgehubTableSortableColumns'));

        // News
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'NewsCustomPostType'));
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'NewsCustomPostTaxonomy'));
        add_action('psyem-news-category_add_form_fields', array(&$this, PSYEM_PREFIX . 'ListingCustomPostsTaxonomyAddCustomField'));
        add_action('psyem-news-category_edit_form_fields', array(&$this, PSYEM_PREFIX . 'ListingCustomPostsTaxonomyEditCustomField'));
        add_action('created_psyem-news-category', array(&$this, PSYEM_PREFIX . 'ListingCustomPostsTaxonomySaveCustomField'));
        add_action('edited_psyem-news-category', array(&$this, PSYEM_PREFIX . 'ListingCustomPostsTaxonomySaveCustomField'));
        // add_action('add_meta_boxes',        array(&$this, PSYEM_PREFIX . 'NewsCustomPostTypeMetaBoxes'));
        // add_action('save_post',             array(&$this, PSYEM_PREFIX . 'NewsCustomPostTypeSaveMetaBoxes'));
        // add_filter('manage_edit-psyem-donations_columns', array(&$this, PSYEM_PREFIX . 'NewsTableCustomColumns'));
        // add_action('manage_psyem-donations_posts_custom_column',  array(&$this, PSYEM_PREFIX . 'NewsTableCustomColumnValues'), 10, 2);
        // add_filter('manage_edit-psyem-donations_sortable_columns', array(&$this, PSYEM_PREFIX . 'NewsTableSortableColumns'));

        // programmes
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'ProgrammesCustomPostType'));
        add_action('init',                  array(&$this, PSYEM_PREFIX . 'ProgrammesCustomPostTaxonomy'));
        add_action('psyem-programmes-category_add_form_fields', array(&$this, PSYEM_PREFIX . 'ListingCustomPostsTaxonomyAddCustomField'));
        add_action('psyem-programmes-category_edit_form_fields', array(&$this, PSYEM_PREFIX . 'ListingCustomPostsTaxonomyEditCustomField'));
        add_action('created_psyem-programmes-category', array(&$this, PSYEM_PREFIX . 'ListingCustomPostsTaxonomySaveCustomField'));
        add_action('edited_psyem-programmes-category', array(&$this, PSYEM_PREFIX . 'ListingCustomPostsTaxonomySaveCustomField'));
        // add_action('add_meta_boxes',        array(&$this, PSYEM_PREFIX . 'ProgrammesCustomPostTypeMetaBoxes'));
        // add_action('save_post',             array(&$this, PSYEM_PREFIX . 'ProgrammesCustomPostTypeSaveMetaBoxes'));
        // add_filter('manage_edit-psyem-donations_columns', array(&$this, PSYEM_PREFIX . 'ProgrammesTableCustomColumns'));
        // add_action('manage_psyem-donations_posts_custom_column',  array(&$this, PSYEM_PREFIX . 'ProgrammesTableCustomColumnValues'), 10, 2);
        // add_filter('manage_edit-psyem-donations_sortable_columns', array(&$this, PSYEM_PREFIX . 'ProgrammesTableSortableColumns'));
    }

    function psyem_AddCustomAdminHeadstyles()
    {

        $screen              = get_current_screen();
        $current_screen_page = ($screen && $screen->id)  ? $screen->id : '';

        if (
            $current_screen_page == 'edit-psyem-speaker-category' ||
            $current_screen_page == 'edit-psyem-partner-category' ||
            $current_screen_page == 'edit-psyem-knowledges-category' ||
            $current_screen_page == 'edit-psyem-programmes-category' ||
            $current_screen_page == 'edit-psyem-news-category' ||

            $current_screen_page == 'psyem-speakers' ||
            $current_screen_page == 'psyem-partners' ||
            $current_screen_page == 'psyem-knowledges' ||
            $current_screen_page == 'psyem-programmes' ||
            $current_screen_page == 'psyem-news'
        ) {
            echo '<style>
                select#newpsyem-partner-category_parent {display: none;}
                select#newpsyem-speaker-category_parent {display: none;}
                .term-parent-wrap{display: none;}                
                </style>';
        }

        if (
            ($current_screen_page == 'psyem-orders' || $current_screen_page == 'edit-psyem-orders') ||
            ($current_screen_page == 'psyem-donations' || $current_screen_page == 'edit-psyem-donations') ||
            ($current_screen_page == 'psyem-projectsafes' || $current_screen_page == 'edit-psyem-projectsafes')
        ) {
            echo '<style>              
                  .page-title-action { display: none !important; }          
                 </style>';
        }
    }

    function psyem_RegisterEventsManagerAdminScripts()
    {
        // js
        wp_register_script(PSYEM_PREFIX . 'bootstrap5admjs',   PSYEM_ASSETS . '/js/bootstrap.min.js', array('jquery'), PSYEM_VERSION, true);
        wp_register_script(PSYEM_PREFIX . 'select2admjs',   PSYEM_ASSETS . '/libs/select2/select2.full.min.js', array('jquery'), PSYEM_VERSION, true);
        wp_register_script(PSYEM_PREFIX . 'toasteradmjs',   PSYEM_ASSETS . '/libs/toastr/toastr.js', array('jquery'), PSYEM_VERSION, true);
        wp_register_script(PSYEM_PREFIX . 'swal2admjs',   PSYEM_ASSETS . '/libs/swal2/sweetalert2.js', array('jquery'), PSYEM_VERSION, true);
        wp_register_script(PSYEM_PREFIX . 'helperadmjs', PSYEM_ASSETS . '/js/psyemHelpers.js', array('jquery'), PSYEM_VERSION, true);

        $psyemcriptVer      = filemtime(PSYEM_PATH . 'assets/js/psyemAdminSettings.js');
        wp_register_script(PSYEM_PREFIX . 'settingsadmjs', PSYEM_ASSETS . '/js/psyemAdminSettings.js', array('jquery'), $psyemcriptVer, true);
        $psyemcriptVer      = filemtime(PSYEM_PATH . 'assets/js/psyemOfflineSignup.js');
        wp_register_script(PSYEM_PREFIX . 'offlinesignupadmjs', PSYEM_ASSETS . '/js/psyemOfflineSignup.js', array('jquery'), $psyemcriptVer, true);

        $psyemcriptVer      = filemtime(PSYEM_PATH . 'assets/js/psyemAdminTickets.js');
        wp_register_script(PSYEM_PREFIX . 'ticketadmjs',   PSYEM_ASSETS . '/js/psyemAdminTickets.js', array('jquery'), PSYEM_VERSION, true);

        $psyemcriptVer      = filemtime(PSYEM_PATH . 'assets/js/psyemAdminEvents.js');
        wp_register_script(PSYEM_PREFIX . 'eventmetamediauploader',   PSYEM_ASSETS . '/js/psyemAdminEvents.js', array('jquery'), PSYEM_VERSION, true);
        $psyemcriptVer      = filemtime(PSYEM_PATH . 'assets/js/psyemAdminCoupons.js');
        wp_register_script(PSYEM_PREFIX . 'couponsadmjs',   PSYEM_ASSETS . '/js/psyemAdminCoupons.js', array('jquery'), PSYEM_VERSION, true);
        $psyemcriptVer      = filemtime(PSYEM_PATH . 'assets/js/psyemAdminOrders.js');
        wp_register_script(PSYEM_PREFIX . 'ordersadmjs',   PSYEM_ASSETS . '/js/psyemAdminOrders.js', array('jquery'), PSYEM_VERSION, true);

        // css
        wp_register_style(PSYEM_PREFIX . 'bootstrap5admcss',   PSYEM_ASSETS . '/css/bootstrap.min.css', array(), PSYEM_VERSION);
        wp_register_style(PSYEM_PREFIX . 'select2admcss',   PSYEM_ASSETS . '/libs/select2/select2.min.css', array(), PSYEM_VERSION);
        wp_register_style(PSYEM_PREFIX . 'toasteradmcss',   PSYEM_ASSETS . '/libs/toastr/toastr.css', array(), PSYEM_VERSION);
        wp_register_style(PSYEM_PREFIX . 'swal2admcss',   PSYEM_ASSETS . '/libs/swal2/sweetalert2.css', array(), PSYEM_VERSION);
        wp_register_style(PSYEM_PREFIX . 'helperadmcss',   PSYEM_ASSETS . '/css/psyemHelpers.css', array(), PSYEM_VERSION);
    }

    function psyem_EnqueueEventsManagerAdminScripts()
    {

        $screen = get_current_screen();
        $current_screen_page = ($screen && $screen->id)  ? $screen->id : '';

        if (($current_screen_page === 'psy-events-manager_page_psyem_settings') || (strpos($current_screen_page, 'psyem_settings') !== false)) {

            wp_enqueue_script('jquery');
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'toasteradmjs');
            wp_enqueue_script(PSYEM_PREFIX . 'swal2admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'helperadmjs');
            wp_enqueue_script(PSYEM_PREFIX . 'settingsadmjs');
            wp_localize_script(PSYEM_PREFIX . 'settingsadmjs', 'psyem_ajax', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'settings_nonce' => esc_attr(wp_create_nonce('_nonce')),
                'settings_action' => PSYEM_PREFIX . 'manage_settings',
                'projectsafe_type_action' => PSYEM_PREFIX . 'manage_projectsafe_type'
            ));
            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'toasteradmcss');
            wp_enqueue_style(PSYEM_PREFIX . 'swal2admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'helperadmcss');
        }

        if (($current_screen_page === 'psy-events-manager_page_psyem_offline_signup') || (strpos($current_screen_page, 'psyem_offline_signup') !== false)) {

            wp_enqueue_script('jquery');
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'toasteradmjs');
            wp_enqueue_script(PSYEM_PREFIX . 'swal2admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'helperadmjs');

            wp_localize_script(PSYEM_PREFIX . 'offlinesignupadmjs', 'psyem_offline_ajax', array(
                'offline_ajaxurl'  => admin_url('admin-ajax.php'),
                'offline_nonce'    => esc_attr(wp_create_nonce('_nonce')),
                'offline_action'   => PSYEM_PREFIX . 'manage_offline_signup',
                'offline_redirect' => admin_url('edit.php?post_type=psyem-orders'),
                'server_error'  =>  __('Something went wrong with server end, Please try later', 'psyeventsmanager')
            ));
            wp_enqueue_script(PSYEM_PREFIX . 'offlinesignupadmjs');

            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'toasteradmcss');
            wp_enqueue_style(PSYEM_PREFIX . 'swal2admcss');
        }

        if ($current_screen_page === 'edit-psyem-events' && (get_post_type() == 'psyem-events')) {
            wp_enqueue_script(PSYEM_PREFIX . 'toasteradmjs');
            wp_enqueue_script(PSYEM_PREFIX . 'swal2admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'helperadmjs');

            wp_localize_script(PSYEM_PREFIX . 'eventmetamediauploader', 'psyem_event_ajax', array(
                'event_ajaxurl' => admin_url('admin-ajax.php'),
                'event_nonce' => esc_attr(wp_create_nonce('_nonce')),
                'event_copy_action' => PSYEM_PREFIX . 'manage_copy_event',
                'server_error'  => __('Something went wrong with server end, Please try later', 'psyeventsmanager')
            ));
            wp_enqueue_script(PSYEM_PREFIX . 'eventmetamediauploader');
            wp_enqueue_style(PSYEM_PREFIX . 'toasteradmcss');
            wp_enqueue_style(PSYEM_PREFIX . 'swal2admcss');
        }

        if ($current_screen_page === 'psyem-tickets' && (get_post_type() == 'psyem-tickets')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'select2admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'helperadmjs');
            wp_enqueue_script(PSYEM_PREFIX . 'ticketadmjs');

            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'select2admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'helperadmcss');
        }

        if ($current_screen_page === 'psyem-events' && (get_post_type() == 'psyem-events')) {
            wp_enqueue_script('jquery');
            wp_enqueue_media();
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'select2admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'helperadmjs');
            wp_enqueue_script(PSYEM_PREFIX . 'eventmetamediauploader');

            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'select2admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'helperadmcss');
        }

        if ($current_screen_page === 'psyem-coupons' && (get_post_type() == 'psyem-coupons')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'helperadmjs');
            wp_enqueue_script(PSYEM_PREFIX . 'couponsadmjs');

            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'helperadmcss');
        }

        if ($current_screen_page === 'psyem-participants' && (get_post_type() == 'psyem-participants')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'select2admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'helperadmjs');

            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'select2admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'helperadmcss');
        }

        if ($current_screen_page === 'psyem-partners' && (get_post_type() == 'psyem-partners')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5admjs');

            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5admcss');
        }

        if ($current_screen_page === 'psyem-speakers' && (get_post_type() == 'psyem-speakers')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5admjs');

            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5admcss');
        }

        if ($current_screen_page === 'psyem-orders' && (get_post_type() == 'psyem-orders')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'select2admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'toasteradmjs');
            wp_enqueue_script(PSYEM_PREFIX . 'swal2admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'helperadmjs');

            wp_localize_script(PSYEM_PREFIX . 'ordersadmjs', 'psyem_order_ajax', array(
                'order_ajaxurl' => admin_url('admin-ajax.php'),
                'order_nonce' => esc_attr(wp_create_nonce('_nonce')),
                'order_csv_action' => PSYEM_PREFIX . 'manage_participants_csv',
                'order_send_ticket_action' => PSYEM_PREFIX . 'manage_order_send_tickets',
                'server_error'  => __('Something went wrong with server end, Please try later', 'psyeventsmanager')
            ));
            wp_enqueue_script(PSYEM_PREFIX . 'ordersadmjs');

            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'select2admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'toasteradmcss');
            wp_enqueue_style(PSYEM_PREFIX . 'swal2admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'helperadmcss');
        }

        if ($current_screen_page === 'psyem-donations' && (get_post_type() == 'psyem-donations')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'helperadmjs');

            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'helperadmcss');
        }

        if ($current_screen_page === 'psyem-projectsafes' && (get_post_type() == 'psyem-projectsafes')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'select2admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'helperadmjs');

            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'select2admcss');
            wp_enqueue_style(PSYEM_PREFIX . 'helperadmcss');
        }

        if ($current_screen_page === 'psyem-amounts' && (get_post_type() == 'psyem-amounts')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5admjs');
            wp_enqueue_script(PSYEM_PREFIX . 'helperadmjs');
            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5admcss');
        }
    }

    function psyem_AdminMenus()
    {

        $psyemPage = add_menu_page(
            __('Psy Events Manager', 'psyeventsmanager'),
            __('Psy Events Manager', 'psyeventsmanager'),
            'manage_options',
            'psyeventsmanager',
            array(&$this, PSYEM_PREFIX . 'AdminMenuSettingsPage'),
            'dashicons-index-card',
            100
        );

        $psyemOrderPage = add_submenu_page(
            'psyeventsmanager',
            __('Offline Registration', 'psyeventsmanager'),
            __('Offline Registration', 'psyeventsmanager'),
            'manage_options',
            'psyem_offline_signup',
            array(&$this, PSYEM_PREFIX . 'ManageOfflineRegistration'),
            100
        );

        $settingsPage  = add_submenu_page(
            'psyeventsmanager',
            __('Settings', 'psyeventsmanager'),
            __('Settings', 'psyeventsmanager'),
            'manage_options',
            'psyem_settings',
            array(&$this, PSYEM_PREFIX . 'AdminMenuSettingsPage'),
            100
        );
    }

    function psyem_AdminMenuSettingsPage()
    {
        global $wpdb;

        $psyem_options              = psyem_GetOptionsWithPrefix();
        $speakerCategoriesUrl       = admin_url('edit-tags.php?taxonomy=psyem-speaker-category&post_type=psyem-speakers');
        $partnerCategoriesUrl       = admin_url('edit-tags.php?taxonomy=psyem-partner-category&post_type=psyem-partners');
        $knowledgesCategoriesUrl    = admin_url('edit-tags.php?taxonomy=psyem-knowledges-category&post_type=psyem-knowledges');
        $programmesCategoriesUrl    = admin_url('edit-tags.php?taxonomy=psyem-programmes-category&post_type=psyem-programmes');
        $newsCategoriesUrl          = admin_url('edit-tags.php?taxonomy=psyem-news-category&post_type=psyem-news');
        $projectsafeTypes           = get_option('psyem_projectsafe_types', []);

        $all_pages              = get_pages(array('post_status' => 'publish', 'lang' => 'en'));

        include_once PSYEM_PATH . 'admin/includes/psyemSettings.php';
    }

    function psyem_ManageSettingsAjax()
    {

        $resp      = array(
            'status'    => 'error',
            'message'   => __('Settings has been failed to save', 'psyeventsmanager'),
            'validation' => ''
        );

        global $wpdb;

        $postData = @$this->REQ;

        $affected = 0;
        $isvalid  = psyem_ValidateSettingsData($postData);

        if (empty($isvalid)) {
            global $wpdb;
            if (array_key_exists('Settings', $postData) && !empty($postData['Settings']) && is_array($postData['Settings'])) {
                foreach ($postData['Settings'] as $skey => $sval) {
                    psyem_updateOption($skey, $sval);
                    $affected++;
                }
            }
        }
        if ($affected > 0) {
            $resp['status']          = 'success';
            $resp['message']         = __('Settings Data has been successfully saved',  'psyeventsmanager');
            $resp['insert_id']       = $affected;
        }
        wp_send_json($resp, 200);
    }

    function psyem_ManageProjectsafeTypeAjax()
    {
        $resp      = array(
            'status'    => 'error',
            'message'   => __('Project safe type has been failed to save', 'psyeventsmanager'),
            'validation' => ''
        );

        global $wpdb;
        $postData = @$this->REQ;
        $affected = 0;
        $isvalid  = psyem_ValidateProjectSafeTypeData($postData);

        if (empty($isvalid)) {

            $task           = @$postData['task'];
            $title          = @$postData['title'];
            $title_slug     = sanitize_title(@$postData['title']);
            $row_slug       = @$postData['row_slug'];
            $psTypes        = get_option('psyem_projectsafe_types', []);
            $psTypes        = (!empty($psTypes) && is_array($psTypes)) ? $psTypes : [];

            $itemhtml = '';
            if ($task == 'Create' && !empty($title_slug)) {
                $psTypes[$title_slug] = $title;
                update_option('psyem_projectsafe_types', $psTypes);
                $projectSafeItem = [];
                $projectSafeItem[$title_slug] = $title;
                $projectSafeTypeTitle = $title;
                $projectSafeTypeSlug = $title_slug;

                $typeItemFilePath = PSYEM_PATH . 'admin/includes/psyemProjectsafeTypeItem.php';
                if (@is_file($typeItemFilePath) && @file_exists($typeItemFilePath)) {
                    $itemhtml     = require $typeItemFilePath;
                }
                $affected++;
            }

            if ($task == 'Remove' && !empty($row_slug)) {
                if (isset($psTypes[$row_slug]) && !empty($psTypes[$row_slug])) {
                    unset($psTypes[$row_slug]);
                }
                update_option('psyem_projectsafe_types', $psTypes);
                $affected++;
            }
            if ($affected > 0) {
                $resp['status']          = 'success';
                $resp['rhtml']           = $itemhtml;
                $resp['message']         = __('Project safe type data has been successfully saved',  'psyeventsmanager');
            }
        }

        wp_send_json($resp, 200);
    }

    /* POST TYPE TICKETS - BGN */
    function psyem_TicketsCustomPostType()
    {
        $labels = array(
            'name'                  => _x('Tickets', 'Tickets', 'psyeventsmanager'),
            'singular_name'         => _x('Ticket', 'Tickets', 'psyeventsmanager'),
            'menu_name'             => __('Tickets', 'psyeventsmanager'),
            'name_admin_bar'        => __('Ticket', 'psyeventsmanager'),
            'archives'              => __('Ticket Archives', 'psyeventsmanager'),
            'attributes'            => __('Ticket Attributes', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent Ticket:', 'psyeventsmanager'),
            'all_items'             => __('All Tickets', 'psyeventsmanager'),
            'add_new_item'          => __('Add New Ticket', 'psyeventsmanager'),
            'add_new'               => __('Add New', 'psyeventsmanager'),
            'new_item'              => __('New Ticket', 'psyeventsmanager'),
            'edit_item'             => __('Edit Ticket', 'psyeventsmanager'),
            'update_item'           => __('Update Ticket', 'psyeventsmanager'),
            'view_item'             => __('View Ticket', 'psyeventsmanager'),
            'view_items'            => __('View Tickets', 'psyeventsmanager'),
            'search_items'          => __('Search Ticket', 'psyeventsmanager'),
            'not_found'             => __('Not found', 'psyeventsmanager'),
            'not_found_in_trash'    => __('Not found in Trash', 'psyeventsmanager'),
            'featured_image'        => __('Featured Image', 'psyeventsmanager'),
            'set_featured_image'    => __('Set featured image', 'psyeventsmanager'),
            'remove_featured_image' => __('Remove featured image', 'psyeventsmanager'),
            'use_featured_image'    => __('Use as featured image', 'psyeventsmanager'),
            'insert_into_item'      => __('Insert into ticket', 'psyeventsmanager'),
            'uploaded_to_this_item' => __('Uploaded to this ticket', 'psyeventsmanager'),
            'items_list'            => __('Tickets list', 'psyeventsmanager'),
            'items_list_navigation' => __('Tickets list navigation', 'psyeventsmanager'),
            'filter_items_list'     => __('Filter Tickets list', 'psyeventsmanager'),
        );

        $args = array(
            'label'                 => __('Ticket', 'psyeventsmanager'),
            'description'           => __('Ticket post Type for tickets', 'psyeventsmanager'),
            'labels'                => $labels,
            'supports'              => array('title', 'thumbnail', 'excerpt'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'psyeventsmanager', // Show under custom menu
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'psyem-tickets'),
        );

        register_post_type('psyem-tickets', $args);
    }

    function psyem_TicketsCustomPostTypeMetaBoxes()
    {
        add_meta_box(
            'psyem_ticket_config_meta_box',
            __('Ticket Configurations', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'TicketCustomPostTypeConfigMetaBox'), // Callback function to display the content
            'psyem-tickets', // Post type where the meta box will appear
            'normal', // Context (side, normal, advanced)
            'default' // Priority
        );
    }

    function psyem_TicketCustomPostTypeConfigMetaBox($post)
    {

        $post_id = @$post->ID;
        wp_nonce_field('psyem_save_ticket_config_metabox_data', 'psyem_ticket_config_nonce');
        $configs_data = psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_ticket_');

        $all_coupons   = psytp_GetAllPostsWithMetaPrefix('psyem-coupons', 0, [], 'psyem_coupon_');
        $coupons_data  = get_post_meta(@$post_id, 'psyem_ticket_coupons', true);
        $coupons_data  = (!empty($coupons_data)) ? $coupons_data : [];

        $ticket_metabox_type = 'Config';
        $current_ticket_id   = @$post->ID;
        require PSYEM_PATH . 'admin/includes/psyemTicketMetaboxes.php';
    }

    function psyem_TicketsCustomPostTypeSaveMetaBoxes($post_id)
    {
        // Check if nonce is set
        if (!isset($_POST['psyem_ticket_config_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['psyem_ticket_config_nonce'], 'psyem_save_ticket_config_metabox_data')) {
            return;
        }

        // Check if auto-saving
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $psyemTicketConfigs    = (isset($_POST['psyemTicketConfigs'])) ? $_POST['psyemTicketConfigs'] : array();

        // Configs
        if (!empty($psyemTicketConfigs) && is_array($psyemTicketConfigs)) {
            foreach ($psyemTicketConfigs as $configkey => $configValue) {
                update_post_meta($post_id, $configkey, $configValue);
            }
        }

        $Coupons    = (isset($_POST['psyem_ticket_coupons'])) ? $_POST['psyem_ticket_coupons'] : array();
        // Coupons 
        if (!empty($Coupons) && is_array($Coupons)) {
            update_post_meta($post_id, 'psyem_ticket_coupons', $Coupons);
        } else {
            delete_post_meta($post_id, 'psyem_ticket_coupons');
        }
    }

    function psyem_TicketsTableCustomColumns($columns)
    {

        $columns['price']                  = __('Price (USD)', 'psyeventsmanager');
        $columns['type']                   = __('Type', 'psyeventsmanager');

        $date_column                        = $columns['date'];
        unset($columns['date']);
        $columns['date']                    = $date_column;
        return $columns;
    }

    function psyem_TicketsTableCustomColumnValues($column, $post_id)
    {
        $ticket_meta_data  =  psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_ticket_');
        if (!empty($ticket_meta_data) && is_array($ticket_meta_data)) {

            if ($column == 'price') {
                $psyem_ticket_price = formatPriceWithComma(@$ticket_meta_data['psyem_ticket_price']);
                echo (!empty($psyem_ticket_price)) ? esc_html($psyem_ticket_price) : '-';
            }

            if ($column == 'type') {
                $psyem_ticket_type = @$ticket_meta_data['psyem_ticket_type'];
                echo (!empty($psyem_ticket_type)) ? esc_html($psyem_ticket_type) : '-';
            }
        }
    }

    function psyem_TicketsTableSortableColumns($columns)
    {

        $columns['price']       = 'price';
        $columns['type']        = 'type';
        $columns['dates']       = 'dates';
        return $columns;
    }

    /* POST TYPE TICKETS - END */

    /* POST TYPE EVENTS - BGN */
    function psyem_EventsCustomPostType()
    {
        $labels = array(
            'name'                  => _x('Events', 'Events', 'psyeventsmanager'),
            'singular_name'         => _x('Event', 'Events', 'psyeventsmanager'),
            'menu_name'             => __('Events', 'psyeventsmanager'),
            'name_admin_bar'        => __('Event', 'psyeventsmanager'),
            'archives'              => __('Event Archives', 'psyeventsmanager'),
            'attributes'            => __('Event Attributes', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent Event:', 'psyeventsmanager'),
            'all_items'             => __('All Events', 'psyeventsmanager'),
            'add_new_item'          => __('Add New Event', 'psyeventsmanager'),
            'add_new'               => __('Add New', 'psyeventsmanager'),
            'new_item'              => __('New Event', 'psyeventsmanager'),
            'edit_item'             => __('Edit Event', 'psyeventsmanager'),
            'update_item'           => __('Update Event', 'psyeventsmanager'),
            'view_item'             => __('View Event', 'psyeventsmanager'),
            'view_items'            => __('View Events', 'psyeventsmanager'),
            'search_items'          => __('Search Event', 'psyeventsmanager'),
            'not_found'             => __('Not found', 'psyeventsmanager'),
            'not_found_in_trash'    => __('Not found in Trash', 'psyeventsmanager'),
            'featured_image'        => __('Featured Image', 'psyeventsmanager'),
            'set_featured_image'    => __('Set featured image', 'psyeventsmanager'),
            'remove_featured_image' => __('Remove featured image', 'psyeventsmanager'),
            'use_featured_image'    => __('Use as featured image', 'psyeventsmanager'),
            'insert_into_item'      => __('Insert into event', 'psyeventsmanager'),
            'uploaded_to_this_item' => __('Uploaded to this event', 'psyeventsmanager'),
            'items_list'            => __('Events list', 'psyeventsmanager'),
            'items_list_navigation' => __('Events list navigation', 'psyeventsmanager'),
            'filter_items_list'     => __('Filter Events list', 'psyeventsmanager'),
        );

        $args = array(
            'label'                 => __('Event', 'psyeventsmanager'),
            'description'           => __('Event post Type for events', 'psyeventsmanager'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'psyeventsmanager', // Show under custom menu
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'psyem-events'),
        );

        register_post_type('psyem-events', $args);
    }

    function psyem_EventsCustomPostTypeMetaBoxes()
    {
        add_meta_box(
            'psyem_event_config_meta_box',
            __('Event Configurations', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'EventCustomPostTypeConfigMetaBox'), // Callback function to display the content
            'psyem-events', // Post type where the meta box will appear
            'normal', // Context (side, normal, advanced)
            'default' // Priority
        );

        add_meta_box(
            'psytp_event_partners_meta_box',
            __('Event Partners', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'EventCustomPostTypePartnersMetaBox'), // Callback function to display the content
            'psyem-events', // Post type where the meta box will appear
            'normal', // Context (side, normal, advanced)
            'default' // Priority
        );

        add_meta_box(
            'psytp_event_speakers_meta_box',
            __('Event Speakers', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'EventCustomPostTypeSpeakersMetaBox'), // Callback function to display the content
            'psyem-events', // Post type where the meta box will appear
            'normal', // Context (side, normal, advanced)
            'default' // Priority
        );

        add_meta_box(
            'psytp_event_media_meta_box',
            __('Event Media', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'EventCustomPostTypeMediaMetaBox'), // Callback function to display the content
            'psyem-events', // Post type where the meta box will appear
            'normal', // Context (side, normal, advanced)
            'default' // Priority
        );
    }

    function psyem_EventCustomPostTypeConfigMetaBox($post)
    {

        $post_id = @$post->ID;
        wp_nonce_field('psyem_save_event_config_metabox_data', 'psyem_event_config_nonce');
        $configs_data       = psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_event_');
        $all_tickets        = psytp_GetAllPostsWithMetaPrefix('psyem-tickets', 0, [], 'psyem_ticket_');
        $event_tickets      = get_post_meta(@$post_id, 'psyem_event_tickets', true);
        $event_tickets      = (!empty($event_tickets)) ? $event_tickets : [];

        $event_total_attendees = psyem_GetEventAttendeesCount($post_id);
        // export attendies url     
        $linkParams = array(
            'event_id'  => $post_id,
            '_nonce'    => wp_create_nonce('_nonce'),
            'action'    => PSYEM_PREFIX . 'export_event_attendees'
        );
        $get_data   = http_build_query($linkParams);
        $export_attendees_url  = admin_url('admin-post.php') . '?' . $get_data;

        $event_metabox_type = 'Config';
        $current_event_id   = @$post->ID;
        require PSYEM_PATH . 'admin/includes/psyemEventMetaboxes.php';
    }

    function psyem_EventCustomPostTypeMediaMetaBox($post)
    {
        // Retrieve current images URL if it exists
        wp_nonce_field('psyem_save_event_media_metabox_data', 'psyem_event_media_nonce');
        $media_data = get_post_meta(@$post->ID, 'psyem_event_media_urls', true);
        $event_metabox_type = 'Media';
        $current_event_id = @$post->ID;
        require PSYEM_PATH . 'admin/includes/psyemEventMetaboxes.php';
    }

    function psyem_EventCustomPostTypeSpeakersMetaBox($post)
    {
        wp_nonce_field('psyem_save_event_speakers_metabox_data', 'psyem_event_speakers_nonce');
        $speakerCategoriesPosts = psyem_GetAllCategoriesWithPosts('psyem-speaker-category', true, 'psyem-speakers');
        $event_speakers  = get_post_meta(@$post->ID, 'psyem_event_speakers', true);
        $event_speakers  = (!empty($event_speakers)) ? $event_speakers : [];

        $event_metabox_type = 'Speakers';
        $current_event_id   = @$post->ID;
        require PSYEM_PATH . 'admin/includes/psyemEventMetaboxes.php';
    }

    function psyem_EventCustomPostTypePartnersMetaBox($post)
    {
        $partnerCategoriesPosts = psyem_GetAllCategoriesWithPosts('psyem-partner-category', true, 'psyem-partners');
        wp_nonce_field('psyem_save_event_partners_metabox_data', 'psyem_event_partners_nonce');
        $event_partners  = get_post_meta(@$post->ID, 'psyem_event_partners', true);
        $event_partners  = (!empty($event_partners)) ? $event_partners : [];

        $event_metabox_type = 'Partners';
        $current_event_id   = @$post->ID;
        require PSYEM_PATH . 'admin/includes/psyemEventMetaboxes.php';
    }

    function psyem_EventsCustomPostTypeSaveMetaBoxes($post_id)
    {
        // Check if nonce is set
        if (
            !isset($_POST['psyem_event_config_nonce']) ||
            !isset($_POST['psyem_event_media_nonce']) ||
            !isset($_POST['psyem_event_partners_nonce']) ||
            !isset($_POST['psyem_event_speakers_nonce'])
        ) {
            return;
        }

        if (
            !wp_verify_nonce($_POST['psyem_event_config_nonce'], 'psyem_save_event_config_metabox_data') ||
            !wp_verify_nonce($_POST['psyem_event_media_nonce'], 'psyem_save_event_media_metabox_data') ||
            !wp_verify_nonce($_POST['psyem_event_partners_nonce'], 'psyem_save_event_partners_metabox_data') ||
            !wp_verify_nonce($_POST['psyem_event_speakers_nonce'], 'psyem_save_event_speakers_metabox_data')

        ) {
            return;
        }

        // Check if auto-saving
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $Configs    = (isset($_POST['psyemConfigs'])) ? $_POST['psyemConfigs'] : array();
        $Tickets    = (isset($_POST['psyem_event_tickets'])) ? $_POST['psyem_event_tickets'] : array();
        $Partners   = (isset($_POST['psyem_event_partners'])) ? $_POST['psyem_event_partners'] : array();
        $Speakers   = (isset($_POST['psyem_event_speakers'])) ? $_POST['psyem_event_speakers'] : array();
        $MediaFiles = (isset($_POST['psyem_event_media_urls'])) ? $_POST['psyem_event_media_urls'] : array();

        // Configs
        if (!empty($Configs) && is_array($Configs)) {
            foreach ($Configs as $configkey => $configValue) {
                update_post_meta($post_id, $configkey, $configValue);
            }
        }

        // tickets
        if (!empty($Tickets) && is_array($Tickets)) {
            update_post_meta($post_id, 'psyem_event_tickets', $Tickets);
        } else {
            delete_post_meta($post_id, 'psyem_event_tickets');
        }

        // Partners
        if (!empty($Partners) && is_array($Partners)) {
            update_post_meta($post_id, 'psyem_event_partners', $Partners);
        } else {
            delete_post_meta($post_id, 'psyem_event_partners');
        }

        // Speakers
        if (!empty($Speakers) && is_array($Speakers)) {
            update_post_meta($post_id, 'psyem_event_speakers', $Speakers);
        } else {
            delete_post_meta($post_id, 'psyem_event_speakers');
        }

        // Media
        if (!empty($MediaFiles)) {
            $psyem_event_media_urls = json_decode(stripslashes($MediaFiles), true);
            update_post_meta($post_id, 'psyem_event_media_urls', $psyem_event_media_urls);
        } else {
            delete_post_meta($post_id, 'psyem_event_media_urls');
        }
    }

    function psyem_EventsTableCustomColumns($columns)
    {

        $columns['address']                 = __('Address', 'psyeventsmanager');
        $columns['tickets']                 = __('Tickets', 'psyeventsmanager');
        $columns['dates']                   = __('Schedules', 'psyeventsmanager');
        $columns['regtype']                 = __('Reg. Type', 'psyeventsmanager');
        $columns['total_slots']             = __('Total Slots', 'psyeventsmanager');
        $columns['used_slots']              = __('Used Slots', 'psyeventsmanager');
        $columns['copy_event']              = __('Copy Event', 'psyeventsmanager');

        $date_column                        = $columns['date'];
        unset($columns['date']);
        $columns['date']                    = $date_column;
        return $columns;
    }

    function psyem_EventsTableCustomColumnValues($column, $post_id)
    {
        $event_meta_data  =  psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_event_');
        if (!empty($event_meta_data) && is_array($event_meta_data)) {
            if ($column == 'address') {
                $psyem_event_address = @$event_meta_data['psyem_event_address'];
                echo (!empty($psyem_event_address)) ? esc_html($psyem_event_address) : '-';
            }

            if ($column == 'tickets') {
                $eventTicketsArr = get_post_meta(@$post_id, 'psyem_event_tickets', true);
                $totalTicketsCount   = (!empty($eventTicketsArr) && is_array($eventTicketsArr)) ? count($eventTicketsArr) : 0;
                echo (!empty($totalTicketsCount)) ? esc_html($totalTicketsCount) : '0';
            }

            if ($column == 'dates') {
                $psyem_event_startdate = @$event_meta_data['psyem_event_startdate'];
                $psyem_event_starttime = @$event_meta_data['psyem_event_starttime'];
                $start_date            = psyem_GetFormattedDatetime('d F Y', $psyem_event_startdate);
                $start_time            = psyem_GetFormattedDatetime('h:i A', $psyem_event_startdate . '' . $psyem_event_starttime);

                $psyem_event_enddate = @$event_meta_data['psyem_event_enddate'];
                $psyem_event_endtime = @$event_meta_data['psyem_event_endtime'];
                $end_date               = psyem_GetFormattedDatetime('d F Y', $psyem_event_enddate);
                $end_time               = psyem_GetFormattedDatetime('h:i A', $psyem_event_enddate . '' . $psyem_event_endtime);

                $eventDateTime          = ($start_date) . '<br> TO <br>' . ($end_date);
                echo (!empty($psyem_event_startdate)) ? ($eventDateTime) : '-';
            }

            if ($column == 'regtype') {
                $psyem_event_registration_type = @$event_meta_data['psyem_event_registration_type'];
                echo (!empty($psyem_event_registration_type)) ? esc_html($psyem_event_registration_type) : '-';
            }

            if ($column == 'total_slots') {
                $psyem_event_total_slots = @$event_meta_data['psyem_event_total_slots'];
                echo (!empty($psyem_event_total_slots)) ? esc_html($psyem_event_total_slots) : '0';
            }

            if ($column == 'used_slots') {
                $psyem_event_used_slots = @$event_meta_data['psyem_event_used_slots'];
                echo (!empty($psyem_event_used_slots)) ? esc_html($psyem_event_used_slots) : '0';
            }

            if ($column == 'copy_event') {
                echo '<button type="button" class="button action psyemCopyEventData" data-eventid="' . $post_id . '">
                  ' . __('Copy', 'psyeventsmanager') . '
                <span class="buttonLoader spinner" style="display: none; margin: 5px 0px 0 5px; visibility: visible;"></span>
                </button>';
            }
        }
    }

    function psyem_EventsTableSortableColumns($columns)
    {
        $columns['address']     = 'address';
        $columns['tickets']     = 'tickets';
        $columns['regtype']     = 'regtype';
        $columns['total_slots'] = 'total_slots';
        $columns['used_slots']  = 'used_slots';
        $columns['dates']       = 'dates';
        return $columns;
    }

    function psyem_ManageCopyEventData()
    {

        $resp  = array(
            'status'     => 'error',
            'message'    => __('Event data has been failed to copy', 'psyeventsmanager'),
            'validation' => []
        );

        global $wpdb;

        $postData               = @$this->REQ;
        $isvalid                = psyem_ValidateCopyEventData($postData);
        if (!empty($isvalid)) {
            $resp['validation'] = $isvalid;
            wp_send_json($resp, 200);
        }

        $event_id      = @$postData['event_id'];
        $original_post = get_post($event_id);
        $new_post_id   = 0;
        if (!empty($original_post)) {
            $new_post = array(
                'post_title'    => @$original_post->post_title . ' (Copy)' . time(),
                'post_content'  => @$original_post->post_content,
                'post_status'   => 'draft',
                'post_type'     => @$original_post->post_type,
                'post_excerpt'  => @$original_post->post_excerpt,
                'post_author'   => @$original_post->post_author,
                'post_date'     => current_time('mysql'),
                'post_date_gmt' => current_time('mysql', 1),
            );
            $new_post_id = wp_insert_post($new_post);

            if ($new_post_id > 0) {
                $original_meta_data = get_post_meta($event_id);
                if (!empty($original_meta_data) && is_array($original_meta_data)) {
                    foreach ($original_meta_data as $key => $mvalue) {
                        $metaVal = @$mvalue[0];
                        if ($key !== '_edit_lock' && $key !== '_edit_last') {
                            if ($key == 'psyem_event_total_slots' || $key == 'psyem_event_used_slots') {
                                $metaVal = 0;
                            }
                            update_post_meta($new_post_id, $key, $metaVal);
                        }
                    }
                }

                $event_coupons      = get_post_meta(@$event_id, 'psyem_event_tickets', true);
                $event_speakers     = get_post_meta(@$event_id, 'psyem_event_speakers', true);
                $event_partners     = get_post_meta(@$event_id, 'psyem_event_partners', true);
                $event_medias       = get_post_meta(@$event_id, 'psyem_event_media_urls', true);

                update_post_meta($new_post_id, 'psyem_event_tickets', $event_coupons);
                update_post_meta($new_post_id, 'psyem_event_speakers', $event_speakers);
                update_post_meta($new_post_id, 'psyem_event_partners', $event_partners);
                update_post_meta($new_post_id, 'psyem_event_media_urls', $event_medias);
            }
        }

        if ($new_post_id > 0) {
            $resp['status']          = 'success';
            $resp['message']         = __('Event Data has been successfully copied',  'psyeventsmanager');
        }
        wp_send_json($resp, 200);
    }

    function psyem_AddEventCustomPreviewLink($actions, $post)
    {

        if ($post->post_type === 'psyem-events') {
            $preview_url = get_preview_post_link($post->ID);
            $actions['preview'] = '<a href="' . esc_url($preview_url) . '" target="_blank">&nbsp; ' . __('Preview Event',  'psyeventsmanager') . ' &nbsp; </a>';
        }

        return $actions;
    }

    function psyem_ExportEventAttendees()
    {

        global $wpdb;
        $getData    = @$this->REQ;

        $isvalid     = psyem_ValidateExportEventAttendeesData($getData);

        if (!empty($isvalid)) {
            wp_die($isvalid[0]);
        }
        if (!current_user_can('manage_options')) {
            wp_die(__('You are not authorize to export data', 'psyeventsmanager'));
        }

        $event_id                   = (isset($getData['event_id'])) ? $getData['event_id'] : 0;
        $en_event_id                = (function_exists('pll_get_post')) ? pll_get_post($event_id, 'en') : $event_id;
        $zh_event_id                = (function_exists('pll_get_post')) ? pll_get_post($event_id, 'zh') : $event_id;

        $eventAttendeesEnInfoArr    = [];
        $eventAttendeesZhInfoArr    = [];
        if ($en_event_id > 0) {
            $eventAttendeesEnInfoArr    = get_post_meta(@$en_event_id, 'psyem_event_attendees_info', true);
            $eventAttendeesEnInfoArr    = (!empty($eventAttendeesEnInfoArr) && is_array($eventAttendeesEnInfoArr)) ? $eventAttendeesEnInfoArr : [];
        }
        if ($zh_event_id > 0) {
            $eventAttendeesZhInfoArr    = get_post_meta(@$zh_event_id, 'psyem_event_attendees_info', true);
            $eventAttendeesZhInfoArr    = (!empty($eventAttendeesZhInfoArr) && is_array($eventAttendeesZhInfoArr)) ? $eventAttendeesZhInfoArr : [];
        }

        $attendeesIdsArr                = array_merge($eventAttendeesEnInfoArr, $eventAttendeesZhInfoArr);
        $attendeesIdsArr                = array_values($attendeesIdsArr);

        if (empty($attendeesIdsArr)) {
            wp_die(__('No attendees found for this event.', 'psyeventsmanager'));
        }
        $attendeesIdsArr                = array_unique($attendeesIdsArr);
        $attendeesIdsArr                = array_filter($attendeesIdsArr, function ($value) {
            return $value != 0;
        });
        $attendeesIdsArr                = array_values($attendeesIdsArr);

        if (empty($attendeesIdsArr)) {
            wp_die(__('No attendees found for this event.', 'psyeventsmanager'));
        }

        $fileName = date('Y-m-d--H-i');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="attendees-' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');

        // Create a new Spreadsheet object
        $spreadsheet    = new Spreadsheet();
        $sheet          = $spreadsheet->getActiveSheet();

        // Set the header row
        $sheet->setCellValue('A1', __('ID',  'psyeventsmanager'));
        $sheet->setCellValue('B1', __('Name',  'psyeventsmanager'));
        $sheet->setCellValue('C1', __('Email',  'psyeventsmanager'));
        $sheet->setCellValue('D1', __('Type',  'psyeventsmanager'));
        $sheet->setCellValue('E1', __('Company',  'psyeventsmanager'));
        $sheet->setCellValue('F1', __('Event',  'psyeventsmanager'));
        $sheet->setCellValue('G1', __('Date',  'psyeventsmanager'));

        $args = array(
            'post_type'         => 'psyem-participants',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
            'post__in'          => $attendeesIdsArr,
            'orderby'           => 'post__in'
        );

        $query        = new WP_Query($args);
        $row          = 2; // Start from the second row

        if ($query->have_posts()) {
            foreach ($query->posts as $ppost) {
                $ppostId                    = @$ppost->ID;
                $psMeta                     = psyem_GetPostAllMetakeyValWithPrefix($ppostId, 'psyem_participant_');
                $psyem_participant_event_id = @$psMeta['psyem_participant_event_id'];
                $eventInfo                  = ($psyem_participant_event_id > 0) ? get_post($psyem_participant_event_id) : [];

                $sheet->setCellValue('A' . $row, $ppostId);
                $sheet->setCellValue('B' . $row, @$ppost->post_title);
                $sheet->setCellValue('C' . $row, @$psMeta['psyem_participant_email']);
                $sheet->setCellValue('D' . $row, @$psMeta['psyem_participant_type']);
                $sheet->setCellValue('E' . $row, @$psMeta['psyem_participant_company']);
                $sheet->setCellValue('F' . $row, @$eventInfo->post_title);
                $sheet->setCellValue('G' . $row, get_the_date('d F Y', $ppost));

                $row++;
            }
        }

        // Write the file
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    /* POST TYPE EVENTS - END */

    /* POST TYPE COUPONS - BGN */
    function psyem_CouponsCustomPostType()
    {
        $labels = array(
            'name'                  => _x('Coupons', 'Coupons', 'psyeventsmanager'),
            'singular_name'         => _x('Coupon', 'Coupon', 'psyeventsmanager'),
            'menu_name'             => __('Coupons', 'psyeventsmanager'),
            'name_admin_bar'        => __('Coupon', 'psyeventsmanager'),
            'archives'              => __('Coupon Archives', 'psyeventsmanager'),
            'attributes'            => __('Coupon Attributes', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent Coupon:', 'psyeventsmanager'),
            'all_items'             => __('All Coupons', 'psyeventsmanager'),
            'add_new_item'          => __('Add New Coupon', 'psyeventsmanager'),
            'add_new'               => __('Add New', 'psyeventsmanager'),
            'new_item'              => __('New Coupon', 'psyeventsmanager'),
            'edit_item'             => __('Edit Coupon', 'psyeventsmanager'),
            'update_item'           => __('Update Coupon', 'psyeventsmanager'),
            'view_item'             => __('View Coupon', 'psyeventsmanager'),
            'view_items'            => __('View Coupons', 'psyeventsmanager'),
            'search_items'          => __('Search Coupon', 'psyeventsmanager'),
            'not_found'             => __('Not found', 'psyeventsmanager'),
            'not_found_in_trash'    => __('Not found in Trash', 'psyeventsmanager'),
            'featured_image'        => __('Featured Image', 'psyeventsmanager'),
            'set_featured_image'    => __('Set featured image', 'psyeventsmanager'),
            'remove_featured_image' => __('Remove featured image', 'psyeventsmanager'),
            'use_featured_image'    => __('Use as featured image', 'psyeventsmanager'),
            'insert_into_item'      => __('Insert into coupon', 'psyeventsmanager'),
            'uploaded_to_this_item' => __('Uploaded to this coupon', 'psyeventsmanager'),
            'items_list'            => __('Coupons list', 'psyeventsmanager'),
            'items_list_navigation' => __('Coupons list navigation', 'psyeventsmanager'),
            'filter_items_list'     => __('Filter Coupons list', 'psyeventsmanager'),
        );

        $args = array(
            'label'                 => __('Coupon', 'psyeventsmanager'),
            'description'           => __('Coupon post Type for Coupons', 'psyeventsmanager'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'psyeventsmanager', // Show under custom menu
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'psyem-coupons'),
        );

        register_post_type('psyem-coupons', $args);
    }

    function psyem_CouponsCustomPostTypeMetaBoxes()
    {
        add_meta_box(
            'psyem_coupon_config_meta_box',
            __('Coupon Configurations', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'CouponCustomPostTypeConfigMetaBox'), // Callback function to display the content
            'psyem-coupons', // Post type where the meta box will appear
            'normal', // Context (side, normal, advanced)
            'default' // Priority
        );
    }

    function psyem_CouponCustomPostTypeConfigMetaBox($post)
    {

        $post_id = @$post->ID;
        wp_nonce_field('psyem_save_coupon_config_metabox_data', 'psyem_coupon_config_nonce');
        $coupon_configs_data        = psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_coupon_');
        $coupon_metabox_type        = 'Config';
        require PSYEM_PATH . 'admin/includes/psyemCouponMetaboxes.php';
    }

    function psyem_CouponsCustomPostTypeSaveMetaBoxes($post_id)
    {


        // Check if nonce is set
        if (!isset($_POST['psyem_coupon_config_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['psyem_coupon_config_nonce'], 'psyem_save_coupon_config_metabox_data')) {
            return;
        }

        // Check if auto-saving
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $Configs    = (isset($_POST['psyemCouponConfigs'])) ? $_POST['psyemCouponConfigs'] : array();

        // Configs
        if (!empty($Configs) && is_array($Configs)) {
            foreach ($Configs as $configkey => $configValue) {
                update_post_meta($post_id, $configkey, $configValue);
            }
        }
    }

    function psyem_CouponsTableCustomColumns($columns)
    {

        $columns['type']                    = __('Type', 'psyeventsmanager');
        $columns['code']                    = __('Code', 'psyeventsmanager');
        $columns['expiry']                  = __('Expiry', 'psyeventsmanager');
        $columns['amount_percent']          = __('Amount/Percent', 'psyeventsmanager');
        $date_column                        = $columns['date'];
        unset($columns['date']);
        $columns['date']                    = $date_column;
        return $columns;
    }

    function psyem_CouponsTableCustomColumnValues($column, $post_id)
    {
        $coupon_meta_data  =  psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_coupon_');
        if (!empty($coupon_meta_data) && is_array($coupon_meta_data)) {
            if ($column == 'type') {
                $psyem_coupon_type = @$coupon_meta_data['psyem_coupon_type'];
                echo (!empty($psyem_coupon_type)) ? esc_html($psyem_coupon_type) : '-';
            }
            if ($column == 'code') {
                $psyem_coupon_unique_code = @$coupon_meta_data['psyem_coupon_unique_code'];
                echo (!empty($psyem_coupon_unique_code)) ? esc_html($psyem_coupon_unique_code) : '-';
            }
            if ($column == 'expiry') {
                $psyem_coupon_expiry_date = @$coupon_meta_data['psyem_coupon_expiry_date'];
                echo (!empty($psyem_coupon_expiry_date)) ? esc_html($psyem_coupon_expiry_date) : '-';
            }
            if ($column == 'amount_percent') {
                $psyem_coupon_type = @$coupon_meta_data['psyem_coupon_type'];
                $psyem_coupon_discount_amount = @$coupon_meta_data['psyem_coupon_discount_amount'];
                $psyem_coupon_discount_percentage = @$coupon_meta_data['psyem_coupon_discount_percentage'];
                $amount_percent = 0;
                if ($psyem_coupon_type == 'Fixed') {
                    $amount_percent = $psyem_coupon_discount_amount;
                }
                if ($psyem_coupon_type == 'Percentage') {
                    $amount_percent = $psyem_coupon_discount_percentage;
                }
                echo (!empty($amount_percent)) ? esc_html($amount_percent) : '-';
            }
        }
    }

    function psyem_CouponsTableSortableColumns($columns)
    {
        $columns['type']            = 'type';
        $columns['code']            = 'code';
        $columns['expiry']          = 'expiry';
        $columns['amount_percent']  = 'amount_percent';
        return $columns;
    }

    /* POST TYPE COUPONS - END */

    /* POST TYPE SPEAKERS - BGN */
    function psyem_SpeakersCustomPostType()
    {
        $labels = array(
            'name'                  => _x('Speakers', 'Speakers', 'psyeventsmanager'),
            'singular_name'         => _x('Speaker', 'Speaker', 'psyeventsmanager'),
            'menu_name'             => __('Speakers', 'psyeventsmanager'),
            'name_admin_bar'        => __('Speaker', 'psyeventsmanager'),
            'archives'              => __('Speaker Archives', 'psyeventsmanager'),
            'attributes'            => __('Speaker Attributes', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent Speaker:', 'psyeventsmanager'),
            'all_items'             => __('All Speakers', 'psyeventsmanager'),
            'add_new_item'          => __('Add New Speaker', 'psyeventsmanager'),
            'add_new'               => __('Add New', 'psyeventsmanager'),
            'new_item'              => __('New Speaker', 'psyeventsmanager'),
            'edit_item'             => __('Edit Speaker', 'psyeventsmanager'),
            'update_item'           => __('Update Speaker', 'psyeventsmanager'),
            'view_item'             => __('View Speaker', 'psyeventsmanager'),
            'view_items'            => __('View Speakers', 'psyeventsmanager'),
            'search_items'          => __('Search Speaker', 'psyeventsmanager'),
            'not_found'             => __('Not found', 'psyeventsmanager'),
            'not_found_in_trash'    => __('Not found in Trash', 'psyeventsmanager'),
            'featured_image'        => __('Featured Image', 'psyeventsmanager'),
            'set_featured_image'    => __('Set featured image', 'psyeventsmanager'),
            'remove_featured_image' => __('Remove featured image', 'psyeventsmanager'),
            'use_featured_image'    => __('Use as featured image', 'psyeventsmanager'),
            'insert_into_item'      => __('Insert into speaker', 'psyeventsmanager'),
            'uploaded_to_this_item' => __('Uploaded to this speaker', 'psyeventsmanager'),
            'items_list'            => __('Speakers list', 'psyeventsmanager'),
            'items_list_navigation' => __('Speakers list navigation', 'psyeventsmanager'),
            'filter_items_list'     => __('Filter Speakers list', 'psyeventsmanager'),
        );

        $args = array(
            'label'                 => __('Speaker', 'psyeventsmanager'),
            'description'           => __('Speaker post Type for event speakers', 'psyeventsmanager'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'psyeventsmanager', // Show under custom menu
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'taxonomies'            => ['psyem-speaker-category'],
            'rewrite'               => array('slug' => 'psyem-speakers'),
        );

        register_post_type('psyem-speakers', $args);
    }

    function psyem_CreateSpeakerTaxonomy()
    {
        $labels = array(
            'name'                       => _x('Speaker Categories', 'Speaker Categories', 'psyeventsmanager'),
            'singular_name'              => _x('Speaker Category', 'Speaker Category', 'psyeventsmanager'),
            'search_items'               => __('Search Speaker Categories', 'psyeventsmanager'),
            'all_items'                  => __('All Speaker Categories', 'psyeventsmanager'),
            'parent_item'                => __('Parent Speaker Category', 'psyeventsmanager'),
            'parent_item_colon'          => __('Parent Speaker Category:', 'psyeventsmanager'),
            'edit_item'                  => __('Edit Speaker Category', 'psyeventsmanager'),
            'update_item'                => __('Update Speaker Category', 'psyeventsmanager'),
            'add_new_item'               => __('Add New Speaker Category', 'psyeventsmanager'),
            'new_item_name'              => __('New Speaker Category Name', 'psyeventsmanager'),
            'menu_name'                  => __('Speaker Categories', 'psyeventsmanager'),
        );

        $args = array(
            'labels'                => $labels,
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'psyem-speaker-category'),
            'show_in_menu'          => 'edit.php?post_type=psyem-speakers',
        );

        register_taxonomy('psyem-speaker-category', array('psyem-speakers'), $args);
        register_taxonomy_for_object_type('psyem-speaker-category', 'psyem-speakers');
    }

    function psyem_SpeakerCustomPostTypeMetaBoxes()
    {
        add_meta_box(
            'psyem_speaker_config_meta_box',
            __('Speaker Informations', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'SpeakerCustomPostTypeInfoMetaBox'),
            'psyem-speakers',
            'normal',
            'default'
        );
    }

    function psyem_SpeakerCustomPostTypeInfoMetaBox($post)
    {

        $post_id = @$post->ID;
        wp_nonce_field('psyem_save_speaker_config_metabox_data', 'psyem_speaker_config_nonce');
        $speaker_info_data        = psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_speaker_');
        $speaker_metabox_type     = 'Information';
        require PSYEM_PATH . 'admin/includes/psyemSpeakerMetaboxes.php';
    }

    function psyem_SpeakerCustomPostTypeSaveMetaBoxes($post_id)
    {

        // Check if nonce is set
        if (!isset($_POST['psyem_speaker_config_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['psyem_speaker_config_nonce'], 'psyem_save_speaker_config_metabox_data')) {
            return;
        }

        // Check if auto-saving
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $SpeakerInfos    = (isset($_POST['psyemSpeakerInfos'])) ? $_POST['psyemSpeakerInfos'] : array();

        // Configs
        if (!empty($SpeakerInfos) && is_array($SpeakerInfos)) {
            foreach ($SpeakerInfos as $speakerkey => $speakerValue) {
                update_post_meta($post_id, $speakerkey, $speakerValue);
            }
        }
    }

    function psyem_SpeakersTableCustomColumns($columns)
    {

        $columns['psyem_speaker_designation'] = __('Designation', 'psyeventsmanager');
        $date_column                        = $columns['date'];
        unset($columns['date']);
        $columns['date']                    = $date_column;
        return $columns;
    }

    function psyem_SpeakersTableCustomColumnValues($column, $post_id)
    {
        $speaker_meta_data  =  psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_speaker_');
        if (!empty($speaker_meta_data) && is_array($speaker_meta_data)) {
            if ($column == 'psyem_speaker_designation') {
                $psyem_speaker_designation = @$speaker_meta_data['psyem_speaker_designation'];
                echo (!empty($psyem_speaker_designation)) ? esc_html($psyem_speaker_designation) : '-';
            }
        }
    }

    function psyem_SpeakersTableSortableColumns($columns)
    {
        $columns['psyem_speaker_designation'] = 'psyem_speaker_designation';
        return $columns;
    }
    /* POST TYPE SPEAKERS - END */


    /* POST TYPE PARTNERS - BGN */
    function psyem_PartnersCustomPostType()
    {
        $labels = array(
            'name'                  => _x('Partners', 'Partners', 'psyeventsmanager'),
            'singular_name'         => _x('Partner', 'Partner', 'psyeventsmanager'),
            'menu_name'             => __('Partners', 'psyeventsmanager'),
            'name_admin_bar'        => __('Partner', 'psyeventsmanager'),
            'archives'              => __('Partner Archives', 'psyeventsmanager'),
            'attributes'            => __('Partner Attributes', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent Partner:', 'psyeventsmanager'),
            'all_items'             => __('All Partners', 'psyeventsmanager'),
            'add_new_item'          => __('Add New Partner', 'psyeventsmanager'),
            'add_new'               => __('Add New', 'psyeventsmanager'),
            'new_item'              => __('New Partner', 'psyeventsmanager'),
            'edit_item'             => __('Edit Partner', 'psyeventsmanager'),
            'update_item'           => __('Update Partner', 'psyeventsmanager'),
            'view_item'             => __('View Partner', 'psyeventsmanager'),
            'view_items'            => __('View Partners', 'psyeventsmanager'),
            'search_items'          => __('Search Partner', 'psyeventsmanager'),
            'not_found'             => __('Not found', 'psyeventsmanager'),
            'not_found_in_trash'    => __('Not found in Trash', 'psyeventsmanager'),
            'featured_image'        => __('Featured Image', 'psyeventsmanager'),
            'set_featured_image'    => __('Set featured image', 'psyeventsmanager'),
            'remove_featured_image' => __('Remove featured image', 'psyeventsmanager'),
            'use_featured_image'    => __('Use as featured image', 'psyeventsmanager'),
            'insert_into_item'      => __('Insert into partner', 'psyeventsmanager'),
            'uploaded_to_this_item' => __('Uploaded to this partner', 'psyeventsmanager'),
            'items_list'            => __('Partners list', 'psyeventsmanager'),
            'items_list_navigation' => __('Partners list navigation', 'psyeventsmanager'),
            'filter_items_list'     => __('Filter Partners list', 'psyeventsmanager'),
        );

        $args = array(
            'label'                 => __('Partner', 'psyeventsmanager'),
            'description'           => __('Partner post Type for event partners', 'psyeventsmanager'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'psyeventsmanager', // Show under custom menu
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'taxonomies'            => ['psyem-partner-category'],
            'rewrite'               => array('slug' => 'psyem-partners'),
        );

        register_post_type('psyem-partners', $args);
    }

    function psyem_CreatePartnerTaxonomy()
    {

        $labels =   array(
            'name'                       => _x('Partner Categories', 'Partner Categories', 'psyeventsmanager'),
            'singular_name'              => _x('Partner Category', 'Partner Category', 'psyeventsmanager'),
            'search_items'               => __('Search Partner Categories', 'psyeventsmanager'),
            'all_items'                  => __('All Partner Categories', 'psyeventsmanager'),
            'parent_item'                => __('Parent Partner Category', 'psyeventsmanager'),
            'parent_item_colon'          => __('Parent Partner Category:', 'psyeventsmanager'),
            'edit_item'                  => __('Edit Partner Category', 'psyeventsmanager'),
            'update_item'                => __('Update Partner Category', 'psyeventsmanager'),
            'add_new_item'               => __('Add New Partner Category', 'psyeventsmanager'),
            'new_item_name'              => __('New Partner Category Name', 'psyeventsmanager'),
            'menu_name'                  => __('Partner Categories', 'psyeventsmanager'),
        );

        $args = array(
            'labels'                => $labels,
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'psyem-partner-category'),
            'show_in_menu'          => 'edit.php?post_type=psyem-partners',
        );

        register_taxonomy('psyem-partner-category', array('psyem-partners'), $args);
        register_taxonomy_for_object_type('psyem-partner-category', 'psyem-partners');
    }

    function psyem_PartnerCustomPostTypeMetaBoxes()
    {
        add_meta_box(
            'psyem_partner_config_meta_box',
            __('Partner Informations', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'PartnerCustomPostTypeInfoMetaBox'),
            'psyem-partners',
            'normal',
            'default'
        );
    }

    function psyem_PartnerCustomPostTypeInfoMetaBox($post)
    {

        $post_id = @$post->ID;
        wp_nonce_field('psyem_save_partner_config_metabox_data', 'psyem_partner_config_nonce');
        $partner_info_data        = psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_partner_');
        $partner_metabox_type     = 'Information';
        require PSYEM_PATH . 'admin/includes/psyemPartnerMetaboxes.php';
    }

    function psyem_PartnerCustomPostTypeSaveMetaBoxes($post_id)
    {

        // Check if nonce is set
        if (!isset($_POST['psyem_partner_config_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['psyem_partner_config_nonce'], 'psyem_save_partner_config_metabox_data')) {
            return;
        }

        // Check if auto-saving
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $PartnerInfos    = (isset($_POST['psyemPartnerInfos'])) ? $_POST['psyemPartnerInfos'] : array();

        // Configs
        if (!empty($PartnerInfos) && is_array($PartnerInfos)) {
            foreach ($PartnerInfos as $partnerkey => $partnerValue) {
                update_post_meta($post_id, $partnerkey, $partnerValue);
            }
        }
    }

    function psyem_PartnersTableCustomColumns($columns)
    {

        $columns['psyem_partner_sponsorship_level'] = __('Sponsorship Level', 'psyeventsmanager');
        $date_column                        = $columns['date'];
        unset($columns['date']);
        $columns['date']                    = $date_column;
        return $columns;
    }

    function psyem_PartnersTableCustomColumnValues($column, $post_id)
    {
        $partner_meta_data  =  psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_partner_');
        if (!empty($partner_meta_data) && is_array($partner_meta_data)) {
            if ($column == 'psyem_partner_sponsorship_level') {
                $psyem_partner_sponsorship_level = @$partner_meta_data['psyem_partner_sponsorship_level'];
                echo (!empty($psyem_partner_sponsorship_level)) ? esc_html($psyem_partner_sponsorship_level) : '-';
            }
        }
    }

    function psyem_PartnersTableSortableColumns($columns)
    {
        $columns['psyem_partner_sponsorship_level'] = 'psyem_partner_sponsorship_level';
        return $columns;
    }

    /* POST TYPE PARTNERS - END */


    /* POST TYPE PARTICIPANTS - BGN */
    function psyem_ParticipantsCustomPostType()
    {
        $labels = array(
            'name'                  => _x('Participants', 'Participants', 'psyeventsmanager'),
            'singular_name'         => _x('Participant', 'Participant', 'psyeventsmanager'),
            'menu_name'             => __('Participants', 'psyeventsmanager'),
            'name_admin_bar'        => __('Participant', 'psyeventsmanager'),
            'archives'              => __('Participant Archives', 'psyeventsmanager'),
            'attributes'            => __('Participant Attributes', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent Participant:', 'psyeventsmanager'),
            'all_items'             => __('All Participants', 'psyeventsmanager'),
            'add_new_item'          => __('Add New Participant', 'psyeventsmanager'),
            'add_new'               => __('Add New', 'psyeventsmanager'),
            'new_item'              => __('New Participant', 'psyeventsmanager'),
            'edit_item'             => __('Edit Participant', 'psyeventsmanager'),
            'update_item'           => __('Update Participant', 'psyeventsmanager'),
            'view_item'             => __('View Participant', 'psyeventsmanager'),
            'view_items'            => __('View Participants', 'psyeventsmanager'),
            'search_items'          => __('Search Participant', 'psyeventsmanager'),
            'not_found'             => __('Not found', 'psyeventsmanager'),
            'not_found_in_trash'    => __('Not found in Trash', 'psyeventsmanager'),
            'featured_image'        => __('Featured Image', 'psyeventsmanager'),
            'set_featured_image'    => __('Set featured image', 'psyeventsmanager'),
            'remove_featured_image' => __('Remove featured image', 'psyeventsmanager'),
            'use_featured_image'    => __('Use as featured image', 'psyeventsmanager'),
            'insert_into_item'      => __('Insert into participant', 'psyeventsmanager'),
            'uploaded_to_this_item' => __('Uploaded to this participant', 'psyeventsmanager'),
            'items_list'            => __('Participants list', 'psyeventsmanager'),
            'items_list_navigation' => __('Participants list navigation', 'psyeventsmanager'),
            'filter_items_list'     => __('Filter Participants list', 'psyeventsmanager'),
        );

        $args = array(
            'label'                 => __('Participant', 'psyeventsmanager'),
            'description'           => __('Participant post type for event attendees', 'psyeventsmanager'),
            'labels'                => $labels,
            'supports'              => array('title', 'thumbnail', 'excerpt'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'psyeventsmanager', // Show under custom menu
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'psyem-participants'),
        );

        register_post_type('psyem-participants', $args);
    }

    function psyem_ParticipantsCustomPostTypeMetaBoxes()
    {
        add_meta_box(
            'psyem_participant_config_meta_box',
            __('Participant Informations', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'ParticipantCustomPostTypeInfoMetaBox'), // Callback function to display the content
            'psyem-participants', // Post type where the meta box will appear
            'normal', // Context (side, normal, advanced)
            'default' // Priority
        );
    }

    function psyem_ParticipantCustomPostTypeInfoMetaBox($post)
    {

        $post_id = @$post->ID;
        wp_nonce_field('psyem_save_participant_config_metabox_data', 'psyem_participant_config_nonce');
        $participant_info_data        = psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_participant_');
        $participant_metabox_type     = 'Information';
        require PSYEM_PATH . 'admin/includes/psyemParticipantMetaboxes.php';
    }

    function psyem_ParticipantsCustomPostTypeSaveMetaBoxes($post_id)
    {

        // Check if nonce is set
        if (!isset($_POST['psyem_participant_config_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['psyem_participant_config_nonce'], 'psyem_save_participant_config_metabox_data')) {
            return;
        }

        // Check if auto-saving
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $ParticipantInfos    = (isset($_POST['psyemParticipantInfos'])) ? $_POST['psyemParticipantInfos'] : array();

        // Configs
        if (!empty($ParticipantInfos) && is_array($ParticipantInfos)) {
            foreach ($ParticipantInfos as $participantkey => $participantValue) {
                update_post_meta($post_id, $participantkey, $participantValue);
            }
        }
    }

    function psyem_ParticipantsTableCustomColumns($columns)
    {

        $columns['title']                   = __('Participant Name', 'psyeventsmanager');
        $columns['psyem_participant_email'] = __('Participant Email', 'psyeventsmanager');
        $columns['psyem_participant_type']  = __('Participant Type', 'psyeventsmanager');
        $date_column                        = $columns['date'];
        unset($columns['date']);
        $columns['date']                    = $date_column;

        return $columns;
    }

    function psyem_ParticipantsTableCustomColumnValues($column, $post_id)
    {
        $participant_meta_data  =  psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_participant_');
        if (!empty($participant_meta_data) && is_array($participant_meta_data)) {
            if ($column == 'psyem_participant_email') {
                $psyem_participant_email = @$participant_meta_data['psyem_participant_email'];
                echo (!empty($psyem_participant_email)) ? esc_html($psyem_participant_email) : '-';
            }
            if ($column == 'psyem_participant_type') {
                $psyem_participant_type = @$participant_meta_data['psyem_participant_type'];
                echo (!empty($psyem_participant_type)) ? esc_html($psyem_participant_type) : '-';
            }
        }
    }

    function psyem_ParticipantsTableSortableColumns($columns)
    {
        $columns['psyem_participant_email'] = 'psyem_participant_email';
        $columns['psyem_participant_type'] = 'psyem_participant_type';
        return $columns;
    }

    function psyem_AddParticipantsExportButtonBeforeTable()
    {
        $screen                 = get_current_screen();
        $current_screen_page    = ($screen && $screen->id)  ? $screen->id : '';
        $current_post_type      = ($screen && $screen->post_type)  ? $screen->post_type : '';

        global $wpdb;

        if ($current_screen_page == 'edit-psyem-participants' &&  $current_post_type == 'psyem-participants') {
            try {
                $current_url  = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                $parsed_url   = parse_url($current_url);
                $query_string = @$parsed_url['query'];
                parse_str($query_string, $query_params);
                if (isset($query_params['_nonce'])) {
                    unset($query_params['_nonce']);
                }
                if (isset($query_params['action'])) {
                    unset($query_params['action']);
                }

                $selectedEv = isset($query_params['event_id']) ? $query_params['event_id'] : 0;

                $dargs      = array(
                    'post_type'         => 'psyem-events',
                    'posts_per_page'    => -1,
                    'post_status'       => 'publish',
                    'meta_key'          => 'psyem_event_startdate',
                    'orderby'           => 'meta_value',
                    'order'             => 'DESC',
                );

                $query = new WP_Query($dargs);

                if ($query->have_posts()) {
                    echo '<select name="event_id">';
                    echo '<option value="">' . __('All Events', 'psyeventsmanager') . ' </option>';
                    foreach ($query->posts as $ppost) {
                        echo '<option value="' . $ppost->ID . '" ' . selected($selectedEv, $ppost->ID, false) . '>' . $ppost->post_title . '</option>';
                    }
                    echo '</select>';
                }

                $exportArr = array(
                    '_nonce'  => wp_create_nonce('_nonce'),
                    'action'  => PSYEM_PREFIX . 'participants_export'
                );
                $linkParams = array_merge($exportArr, $query_params);
                $get_data   = http_build_query($linkParams);
                $exporturl  = admin_url('admin-post.php') . '?' . $get_data;
                echo '<span style="display:inline; float: right;">
                    <a class="button button-primary" href="' . $exporturl . '">            
                          ' . __('Export to XLSX', 'psyeventsmanager') . '
                    </a>
                </span>';
            } catch (\Exception $e) {
            }
        }
    }

    function psyem_ExportParticipantsCustomPostType()
    {

        global $wpdb;
        $getData    = @$this->REQ;
        $isvalid     = psyem_ValidateParticipantsExportData($getData);

        if (!empty($isvalid)) {
            wp_die($isvalid[0]);
        }
        if (!current_user_can('manage_options')) {
            wp_die(__('You are not authorize to export data', 'psyeventsmanager'));
        }
        if (isset($getData['_nonce'])) {
            unset($getData['_nonce']);
        }
        if (isset($getData['action'])) {
            unset($getData['action']);
        }
        if (isset($getData['action2'])) {
            unset($getData['action2']);
        }

        // search
        $yearMonthStr  = @$getData['m'];
        $yearMonthStr  = (!empty($yearMonthStr)) ? $yearMonthStr . date('d') : '';
        $yearMonthArr  = (!empty($yearMonthStr)) ? psyem_GetConvertedYearMonthDay($yearMonthStr) : [];
        $searchYear    = @$yearMonthArr['year'];
        $searchMonth   = @$yearMonthArr['month'];

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="participants.xlsx"');
        header('Cache-Control: max-age=0');

        // Create a new Spreadsheet object
        $spreadsheet    = new Spreadsheet();
        $sheet          = $spreadsheet->getActiveSheet();

        // Set the header row
        $sheet->setCellValue('A1', __('ID',  'psyeventsmanager'));
        $sheet->setCellValue('B1', __('Name',  'psyeventsmanager'));
        $sheet->setCellValue('C1', __('Email',  'psyeventsmanager'));
        $sheet->setCellValue('D1', __('Type',  'psyeventsmanager'));
        $sheet->setCellValue('E1', __('Company',  'psyeventsmanager'));
        $sheet->setCellValue('F1', __('Event',  'psyeventsmanager'));
        $sheet->setCellValue('G1', __('Date',  'psyeventsmanager'));

        $args = array(
            'post_type'         => 'psyem-participants',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
        );

        $event_id    = (isset($getData['event_id'])) ? $getData['event_id'] : 0;

        if ($event_id > 0) {
            $en_event_id = (function_exists('pll_get_post')) ? pll_get_post($event_id, 'en') : '';
            $zh_event_id = (function_exists('pll_get_post')) ? pll_get_post($event_id, 'zh') : '';
            $event_ids   = array_filter([$en_event_id, $zh_event_id]);
            if (!empty($event_ids) && is_array($event_ids)) {
                $meta_query = ['relation' => 'OR'];
                foreach ($event_ids as $id) {
                    if ($id > 0) {
                        $meta_query[] = [
                            'key'     => 'psyem_participant_event_id',
                            'value'   => $id,
                            'compare' => '='
                        ];
                    }
                }
                $args['meta_query'] = $meta_query;
            } else {
                $args['meta_query'] = array(
                    array(
                        'key'     => 'psyem_participant_event_id',
                        'value'   => $en_event_id,
                        'compare' => '='
                    )
                );
            }
        }

        if (!empty($searchYear)) {
            $args['date_query']['year'] = $searchYear;
        }
        if (!empty($searchMonth)) {
            $args['date_query']['month'] = $searchMonth;
        }

        $query        = new WP_Query($args);
        $row          = 2; // Start from the second row

        if ($query->have_posts()) {

            foreach ($query->posts as $ppost) {
                $ppostId                    = @$ppost->ID;
                $psMeta                     = psyem_GetPostAllMetakeyValWithPrefix($ppostId, 'psyem_participant_');
                $psyem_participant_event_id = @$psMeta['psyem_participant_event_id'];
                $eventInfo                  = ($psyem_participant_event_id > 0) ? get_post($psyem_participant_event_id) : [];

                $sheet->setCellValue('A' . $row, $ppostId);
                $sheet->setCellValue('B' . $row, @$ppost->post_title);
                $sheet->setCellValue('C' . $row, @$psMeta['psyem_participant_email']);
                $sheet->setCellValue('D' . $row, @$psMeta['psyem_participant_type']);
                $sheet->setCellValue('E' . $row, @$psMeta['psyem_participant_company']);
                $sheet->setCellValue('F' . $row, @$eventInfo->post_title);
                $sheet->setCellValue('G' . $row, get_the_date('d F Y', $ppost));

                $row++;
            }
        }

        // Write the file
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    /* POST TYPE PARTICIPANTS - END */


    /* POST ORDERS - BGN */
    function psyem_OrdersCustomPostType()
    {
        $labels = array(
            'name'                  => _x('Event Orders', 'Orders', 'psyeventsmanager'),
            'singular_name'         => _x('Event Order', 'Order', 'psyeventsmanager'),
            'menu_name'             => __('Orders', 'psyeventsmanager'),
            'name_admin_bar'        => __('Order', 'psyeventsmanager'),
            'archives'              => __('Order Archives', 'psyeventsmanager'),
            'attributes'            => __('Order Attributes', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent Order:', 'psyeventsmanager'),
            'all_items'             => __('All Orders', 'psyeventsmanager'),
            'add_new_item'          => __('Add New Order', 'psyeventsmanager'),
            'add_new'               => __('Add New', 'psyeventsmanager'),
            'new_item'              => __('New Order', 'psyeventsmanager'),
            'edit_item'             => __('Edit Order', 'psyeventsmanager'),
            'update_item'           => __('Update Order', 'psyeventsmanager'),
            'view_item'             => __('View Order', 'psyeventsmanager'),
            'view_items'            => __('View Orders', 'psyeventsmanager'),
            'search_items'          => __('Search Order', 'psyeventsmanager'),
            'not_found'             => __('Not found', 'psyeventsmanager'),
            'not_found_in_trash'    => __('Not found in Trash', 'psyeventsmanager'),
            'featured_image'        => __('Featured Image', 'psyeventsmanager'),
            'set_featured_image'    => __('Set featured image', 'psyeventsmanager'),
            'remove_featured_image' => __('Remove featured image', 'psyeventsmanager'),
            'use_featured_image'    => __('Use as featured image', 'psyeventsmanager'),
            'insert_into_item'      => __('Insert into order', 'psyeventsmanager'),
            'uploaded_to_this_item' => __('Uploaded to this order', 'psyeventsmanager'),
            'items_list'            => __('Orders list', 'psyeventsmanager'),
            'items_list_navigation' => __('Orders list navigation', 'psyeventsmanager'),
            'filter_items_list'     => __('Filter Orders list', 'psyeventsmanager'),
        );

        $args = array(
            'label'                 => __('Order', 'psyeventsmanager'),
            'description'           => __('Order post type for event attendees', 'psyeventsmanager'),
            'labels'                => $labels,
            'supports'              => array('title', 'excerpt'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'psyeventsmanager', // Show under custom menu
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'psyem-orders'),
        );

        register_post_type('psyem-orders', $args);
    }

    function psyem_OrdersCustomPostTypeMetaBoxes()
    {

        add_meta_box(
            'psyem_order_event_meta_box',
            __('Event Informations', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'OrdersCustomPostTypeEventMetaBox'), // Callback function to display the content
            'psyem-orders', // Post type where the meta box will appear
            'normal', // Context (side, normal, advanced)
            'default' // Priority
        );

        add_meta_box(
            'psyem_order_info_meta_box',
            __('Order Informations', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'OrdersCustomPostTypeOrderInfoMetaBox'), // Callback function to display the content
            'psyem-orders', // Post type where the meta box will appear
            'normal', // Context (side, normal, advanced)
            'default' // Priority
        );

        add_meta_box(
            'psyem_order_participants_meta_box',
            __('Participants Informations', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'OrdersCustomPostTypeParticipantsMetaBox'), // Callback function to display the content
            'psyem-orders', // Post type where the meta box will appear
            'normal', // Context (side, normal, advanced)
            'default' // Priority
        );
    }

    function psyem_OrdersCustomPostTypeEventMetaBox($post)
    {

        $post_id = @$post->ID;
        wp_nonce_field('psyem_save_order_event_metabox_data', 'psyem_order_event_nonce');
        $order_info_data    = psyem_GetSinglePostWithMetaPrefix('psyem-orders', $post_id, 'psyem_order_');
        $order_meta_data    = @$order_info_data['meta_data'];
        $orderEventId       = @$order_meta_data['psyem_order_event_id'];
        $psyem_order_payment_payload = get_post_meta(@$post_id, 'psyem_order_payment_payload', true);
        $psyem_order_amount_currency = @$psyem_order_payment_payload['currency'];
        $order_event_info   = psyem_GetSinglePostWithMetaPrefix('psyem-events', $orderEventId, 'psyem_event_');
        $order_metabox_type = 'Event';
        require PSYEM_PATH . 'admin/includes/psyemOrderMetaboxes.php';
    }

    function psyem_OrdersCustomPostTypeOrderInfoMetaBox($post)
    {

        $post_id = @$post->ID;
        wp_nonce_field('psyem_save_order_participants_metabox_data', 'psyem_order_participants_nonce');
        $order_info_data    = psyem_GetSinglePostWithMetaPrefix('psyem-orders', $post_id, 'psyem_order_');
        $order_meta_data    = @$order_info_data['meta_data'];
        $psyem_order_payment_payload = get_post_meta(@$post_id, 'psyem_order_payment_payload', true);
        $psyem_order_amount_currency = @$psyem_order_payment_payload['currency'];
        $order_tickets_data = get_post_meta(@$post_id, 'psyem_order_tickets_info', true);
        $order_metabox_type = 'Informations';
        require PSYEM_PATH . 'admin/includes/psyemOrderMetaboxes.php';
    }

    function psyem_OrdersCustomPostTypeParticipantsMetaBox($post)
    {

        $post_id = @$post->ID;
        wp_nonce_field('psyem_save_order_participants_metabox_data', 'psyem_order_participants_nonce');
        $order_info_data             = psyem_GetSinglePostWithMetaPrefix('psyem-orders', $post_id, 'psyem_order_');
        $order_meta_data             = @$order_info_data['meta_data'];
        $order_participants_data     = get_post_meta(@$post->ID, 'psyem_order_participants', true);
        $psyem_sample_csv_url        =  PSYEM_ASSETS . '/sample/psyemParticipantSample.csv';
        $order_metabox_type          = 'Participants';
        require PSYEM_PATH . 'admin/includes/psyemOrderMetaboxes.php';
    }

    function psyem_OrdersCustomPostTypeSaveMetaBoxes($post_id)
    {

        // $parArr = [
        //     'Main' => 128,
        //     '120'  => 120
        // ];

        // update_post_meta($post_id, 'psyem_order_participants', $parArr);
    }

    function psyem_OrdersTableCustomColumns($columns)
    {
        $columns['title']              = __('Event', 'psyeventsmanager');
        $columns['order_id']           = __('Order ID', 'psyeventsmanager');
        $columns['event_id']           = __('Event ID', 'psyeventsmanager');
        $columns['total_tickets']      = __('Total Tickets', 'psyeventsmanager');
        $columns['dates']              = __('Dates', 'psyeventsmanager');
        $columns['participant_name']   = __('Name', 'psyeventsmanager');
        $columns['participant_email']  = __('Email', 'psyeventsmanager');

        $date_column                   = $columns['date'];
        unset($columns['date']);
        $columns['date']               = $date_column;

        return $columns;
    }

    function psyem_OrdersTableCustomColumnValues($column, $post_id)
    {
        $order_meta_data  =  psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_order_');
        if (!empty($order_meta_data) && is_array($order_meta_data)) {
            
            if ($column == 'order_id') {
                echo (!empty($post_id)) ? esc_html($post_id) : '-';
            }

            if ($column == 'event_id') {
                $psyem_order_event_id = @$order_meta_data['psyem_order_event_id'];
                echo (!empty($psyem_order_event_id)) ? esc_html($psyem_order_event_id) : 0;
            }

            if ($column == 'total_tickets') {
                $psyem_order_total_slots = @$order_meta_data['psyem_order_total_slots'];
                echo (!empty($psyem_order_total_slots)) ? esc_html($psyem_order_total_slots) : 0;
            }

            if ($column == 'dates') {
                $psyem_order_event_id   = @$order_meta_data['psyem_order_event_id'];
                $psyemEventInfo         = psyem_GetSinglePostWithMetaPrefix('psyem-events', $psyem_order_event_id, 'psyem_event_');
                $psyemEventMeta         = @$psyemEventInfo['meta_data'];

                $psyem_event_startdate  = @$psyemEventMeta['psyem_event_startdate'];
                $psyem_event_starttime  = @$psyemEventMeta['psyem_event_starttime'];
                $start_date             = psyem_GetFormattedDatetime('d F Y', $psyem_event_startdate);
                $start_time             = psyem_GetFormattedDatetime('h:i A', $psyem_event_startdate . '' . $psyem_event_starttime);

                $psyem_event_enddate    = @$psyemEventMeta['psyem_event_enddate'];
                $psyem_event_endtime    = @$psyemEventMeta['psyem_event_endtime'];
                $end_date               = psyem_GetFormattedDatetime('d F Y', $psyem_event_enddate);
                $end_time               = psyem_GetFormattedDatetime('h:i A', $psyem_event_enddate . '' . $psyem_event_endtime);

                $eventDateTime          = ($start_date) . '<br> TO <br>' . ($end_date);
                echo (!empty($psyem_event_startdate)) ? ($eventDateTime) : '-';
            }

            if ($column == 'participant_name') {
                $psyem_order_participant_name = @$order_meta_data['psyem_order_participant_name'];
                echo (!empty($psyem_order_participant_name)) ? esc_html($psyem_order_participant_name) : '-';
            }

            if ($column == 'participant_email') {
                $psyem_order_participant_email = @$order_meta_data['psyem_order_participant_email'];
                echo (!empty($psyem_order_participant_email)) ? esc_html($psyem_order_participant_email) : '-';
            }
        }
    }

    function psyem_OrdersTableSortableColumns($columns)
    {
        $columns['order_id']            = 'order_id';
        $columns['total_tickets']       = 'total_tickets';
        $columns['participant_name']    = 'participant_name';
        $columns['participant_email']   = 'participant_email';
        $columns['dates']               = 'dates';
        return $columns;
    }

    function psyem_ManageOrderPrintTickets()
    {

        global $wpdb;
        $postData               = @$this->REQ;
        $isvalid                = psyem_ValidateOrderPrintTicketsData($postData);

        if (!empty($isvalid)) {
            wp_die($isvalid[0]);
        }

        $psyem_options                  = psyem_GetOptionsWithPrefix();
        $psyem_event_verifyqr_page_id   = @$psyem_options['psyem_event_verifyqr_page_id'];

        $order_id               = @$postData['order_id'];
        $participant_id         = @$postData['participant'];
        $order_info_data        = psyem_GetSinglePostWithMetaPrefix('psyem-orders', $order_id, 'psyem_order_');
        if (empty($isvalid) && $order_id > 0 && !empty($order_info_data)) {
            // get ticket participants
            $order_participants_arr     = [];
            $orderParticipantsArr       = get_post_meta(@$order_id, 'psyem_order_participants', true);
            $orderParticipantsIds       = (!empty($orderParticipantsArr) && is_array($orderParticipantsArr)) ? array_values($orderParticipantsArr) : [];

            if ($participant_id > 0) {
                if (!empty($orderParticipantsIds) && in_array($participant_id, $orderParticipantsIds)) {
                    $order_participant_data    = psyem_GetSinglePostWithMetaPrefix('psyem-participants', $participant_id, 'psyem_participant_');
                    if (empty($order_participant_data)) {
                        wp_die(__('Print tickets has been failed to process', 'psyeventsmanager'));
                    }
                    $order_participants_arr[$participant_id]   = $order_participant_data;
                }
            } else {
                if (!empty($orderParticipantsIds) && is_array($orderParticipantsIds)) {
                    foreach ($orderParticipantsIds as $participantId) {
                        $order_participant_data    = psyem_GetSinglePostWithMetaPrefix('psyem-participants', $participantId, 'psyem_participant_');
                        if (!empty($order_participant_data)) {
                            $order_participants_arr[$participantId]   = $order_participant_data;
                        }
                    }
                }
            }

            // prepare logo
            $pdfLogoHtml = '';
            try {
                $pdf_content_type   = 'Logo';
                $logoSamplePath     = PSYEM_ASSETS_PATH . '/images/sitelogo.png';
                $logo_image_data    = file_get_contents($logoSamplePath);
                $logo_base64        = base64_encode($logo_image_data);
                $logo_mime_type     = mime_content_type($logoSamplePath);
                $logo_image_src     = 'data:' . $logo_mime_type . ';base64,' . $logo_base64;
                $pdfLogoHtml        = trim(require PSYEM_PATH . 'admin/includes/psyemOrderTicket.php');
            } catch (\Exception $e) {
                error_log('ManageOrderPrintTickets LOGO ERROR :: ' . $e->getMessage());
            }

            // prepare tickets
            $pdfTicketsHtml = '';
            if (!empty($order_participants_arr) && is_array($order_participants_arr) && count($order_participants_arr) > 0) {
                foreach ($order_participants_arr as $participantID => $participantInfo) {
                    $scanKey            = psyem_safe_b64encode($participantID . '@_@' . $order_id);
                    $verifyQrPageLink   = psyem_GetPageLinkByID($psyem_event_verifyqr_page_id);

                    try {
                        $get_scan_data = http_build_query( array(
                            'ticketinfo'    => $scanKey,
                            'order'         => @$order_id,
                            'participant'   => @$participantID,
                        ) );
                        $qrContentUrl =  $verifyQrPageLink . '?' . $get_scan_data;                    

                        $qrSamplePath = PSYEM_PATH . 'packages/phpqrcode/qrcode.png';
                        $qrSampleUrl  = PSYEM_URL . 'packages/phpqrcode/qrcode.png';
                        QRcode::png($qrContentUrl, $qrSamplePath, QR_ECLEVEL_L, 6, 2);
                        $image_data   = file_get_contents($qrSamplePath);
                        $base64       = base64_encode($image_data);
                        $mime_type    = mime_content_type($qrSamplePath);
                        $qrBase64     = 'data:' . $mime_type . ';base64,' . $base64;

                        $participantInfo['order_id']        = $order_id;
                        $participantInfo['qr_image_src']    = $qrBase64;
                        $fullName = @$participantInfo['title'];
                        $namesArr = psyem_SplitFullName($fullName);
                        $participantInfo = array_merge($participantInfo, $namesArr);

                        $pdf_content_type = 'Ticket';
                        $pdfTicketsHtml  .= trim(require PSYEM_PATH . 'admin/includes/psyemOrderTicket.php');
                    } catch (\Exception $e) {
                        error_log('ManageOrderPrintTickets QR IMAGE ERROR :: ' . $e->getMessage());
                    }
                }
            }

            // prepare event info
            $pdf_content_type   = 'Event';
            $orderEventId       = get_post_meta(@$order_id, 'psyem_order_event_id', true);
            $orderEventInfo     = psyem_GetSinglePostWithMetaPrefix('psyem-events', $orderEventId, 'psyem_event_');
            $orderEventInfo['order_id'] = $order_id;
            $pdfEventHtml       = trim(require PSYEM_PATH . 'admin/includes/psyemOrderTicket.php');

            // prepare footer
            $MasterSettings     = psyem_GetOptionsWithPrefix();
            $pdf_content_type   = 'Footer';
            $TandCPageLink      = (isset($MasterSettings['psyem_terms_url']) && !empty($MasterSettings['psyem_terms_url'])) ? $MasterSettings['psyem_terms_url'] : get_site_url();
            $pdfFooterHtml      = trim(require PSYEM_PATH . 'admin/includes/psyemOrderTicket.php');

            $pdfhtml = '
            <html lang="en" dir="ltr">
            <body style="background-color:#fff;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
            <div style="width:100%; font-size: 16px; line-height: 24px; background: #fff; color: #555; padding: 20px 10px 20px 2px;">             
                ' . $pdfLogoHtml . '
                ' . $pdfEventHtml . '
                ' . $pdfTicketsHtml . '
                ' . $pdfFooterHtml . '
            </div>
            </body>
            </html>
            ';

            $dompdf         = new Dompdf();
            $options        = $dompdf->getOptions();
            $options->setDefaultFont('Courier');
            $dompdf->setOptions($options);
            $dompdf->loadHtml($pdfhtml);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $fileName = 'ticket_' . $order_id . '_' . date('Ymd-His');
            $dompdf->stream($fileName, array("Attachment" => false));
            exit;
        }
        wp_die(__('Print tickets has been failed to process', 'psyeventsmanager'));
    }

    // Ajax BGN
    function psyem_ManageOrderParticipantsCsvAjax()
    {
        $resp      = array(
            'status'    => 'error',
            'message'   => __('Csv has been failed to import', 'psyeventsmanager'),
            'validation' => []
        );

        global $wpdb;

        $postData               = @$this->REQ;
        $isvalid                = psyem_ValidateParticipantsCsvData($postData);
        $resp['validation']     = $isvalid;

        $affected               = 0;
        $participant_order_id   = @$postData['participant_order_id'];

        if (empty($isvalid) && $participant_order_id > 0) {
            // validate participant counts
            $psyem_order_event_id = get_post_meta(@$participant_order_id, 'psyem_order_event_id', true);
            $event_name           = get_the_title($psyem_order_event_id);

            $psyemOrderTotalSlots = get_post_meta(@$participant_order_id, 'psyem_order_total_slots', true);
            $orderParticipantsArr = get_post_meta(@$participant_order_id, 'psyem_order_participants', true);
            $totalParticipants    = (!empty($orderParticipantsArr) && is_array($orderParticipantsArr)) ? count($orderParticipantsArr) : 1;
            if ($totalParticipants >= $psyemOrderTotalSlots) {
                $resp      = array(
                    'status'    => 'error',
                    'message'   => __('Csv has been failed to import', 'psyeventsmanager'),
                    'validation' => [__('You can not upload more paticipants in this order',  'psyeventsmanager')]
                );
                wp_send_json($resp, 200);
            }

            $fileTmpName          = @$_FILES['participant_csv_file']['tmp_name'];
            $fileName             = @$_FILES['participant_csv_file']['name'];
            $csvData              = array_map('str_getcsv', file($fileTmpName));

            $affected             = 1;
            if (!empty($csvData) && is_array($csvData) && count($csvData) > 1 && $psyemOrderTotalSlots > 1) {

                unset($csvData[0]); // unset header
                foreach ($csvData as $csvRow) {
                    // validate participant counts
                    $orderParticipantsArr = get_post_meta(@$participant_order_id, 'psyem_order_participants', true);
                    $totalParticipants    = (!empty($orderParticipantsArr) && is_array($orderParticipantsArr)) ? count($orderParticipantsArr) : 1;
                    if ($totalParticipants >= $psyemOrderTotalSlots) {
                        break;
                    }

                    $participantFName = (isset($csvRow[0]) && !empty($csvRow[0])) ? sanitize_text_field($csvRow[0]) : '';
                    $participantLName = (isset($csvRow[1]) && !empty($csvRow[1])) ? sanitize_textarea_field($csvRow[1]) : '';
                    $participantEmail = (isset($csvRow[2]) && !empty($csvRow[2])) ? sanitize_text_field($csvRow[2]) : '';
                    $participantCompany = (isset($csvRow[3]) && !empty($csvRow[3])) ? sanitize_textarea_field($csvRow[3]) : '';

                    $participantName  = $participantFName . ' ' . $participantLName;

                    $participantPostContent = @$event_name . ' ' . strtolower($event_name) . ' ' . $participantName . ' ' . $participantEmail . ' ' . strtolower($participantName) . ' ' . strtolower($participantEmail);

                    if (!empty($participantName) && !empty($participantEmail)) {
                        $participantExist = psyem_getPostByMetakeyAndValue(
                            'psyem-participants',
                            'psyem_participant_email',
                            strtolower($participantEmail)
                        );
                        $participantExistID = @$participantExist['ID'];
                        if ($participantExistID > 0) {
                            $inserted_post_id   = @$participantExistID;
                            $updated_post_data = array(
                                'ID'           => $inserted_post_id,
                                'post_content' => $participantPostContent
                            );
                            wp_update_post($updated_post_data);
                            update_post_meta($inserted_post_id, 'psyem_participant_event_id', $psyem_order_event_id);
                        } else {
                            $current_time     = current_time('mysql');
                            $current_time_gmt = current_time('mysql', 1); // Get GMT time
                            $post_data = array(
                                'post_title'    => ucfirst($participantName),
                                'post_name'     => sanitize_title($participantName),
                                'post_status'   => 'publish',
                                'post_type'     => 'psyem-participants',
                                'post_content'  => $participantPostContent,
                                'post_date'     => $current_time,
                                'post_date_gmt' => $current_time_gmt,
                            );
                            $insertResp         = $wpdb->insert($wpdb->posts, $post_data);
                            $inserted_post_id   = @$wpdb->insert_id;
                        }

                        if ($inserted_post_id  > 0) {
                            $affected++;
                            // update participanmt meta 
                            update_post_meta($inserted_post_id, 'psyem_participant_first_name', ucfirst($participantFName));
                            update_post_meta($inserted_post_id, 'psyem_participant_last_name', ucfirst($participantLName));
                            update_post_meta($inserted_post_id, 'psyem_participant_company', ucfirst($participantCompany));
                            update_post_meta($inserted_post_id, 'psyem_participant_name', ucfirst($participantName));
                            update_post_meta($inserted_post_id, 'psyem_participant_email', strtolower($participantEmail));
                            update_post_meta($inserted_post_id, 'psyem_participant_type', 'Member');
                            update_post_meta($inserted_post_id, 'psyem_participant_event_id', $psyem_order_event_id);

                            // update order participants
                            if (!empty($orderParticipantsArr) && is_array($orderParticipantsArr)) {
                                $orderParticipantsArr[$inserted_post_id] = $inserted_post_id;
                            }
                            update_post_meta($participant_order_id, 'psyem_order_participants', $orderParticipantsArr);
                        }
                    }
                    if ($psyemOrderTotalSlots == $affected) {
                        break;
                    }
                }
            }
        }

        if ($affected > 0) {
            $resp['status']          = 'success';
            $resp['message']         = __('Csv Data has been successfully imported',  'psyeventsmanager');
            $resp['validation']      = [];
            $resp['total']           = ($affected - 1);
        }
        wp_send_json($resp, 200);
    }

    function psyem_ManageOrderSendTicketsAjax()
    {

        $resp      = array(
            'status'     => 'error',
            'message'    => __('Event ticket has been failed to send', 'psyeventsmanager'),
            'validation' => []
        );

        global $wpdb;

        $postData               = @$this->REQ;
        $isvalid                = psyem_ValidateOrderSendTicketsData($postData);
        if (!empty($isvalid)) {
            $resp['validation'] = $isvalid;
            wp_send_json($resp, 200);
        }

        $psyem_options                  = psyem_GetOptionsWithPrefix();
        $psyem_event_verifyqr_page_id   = @$psyem_options['psyem_event_verifyqr_page_id'];

        $order_id               = @$postData['order_id'];
        $participants           = @$postData['participants'];

        $order_info_data        = psyem_GetSinglePostWithMetaPrefix('psyem-orders', $order_id, 'psyem_order_');
        if (empty($isvalid) && $order_id > 0 && !empty($order_info_data) && !empty($participants) && is_array($participants)) {

            // get ticket participants
            $order_participants_arr     = [];
            $orderParticipantsArr       = get_post_meta(@$order_id, 'psyem_order_participants', true);
            $orderParticipantsIds       = (!empty($orderParticipantsArr) && is_array($orderParticipantsArr)) ? array_values($orderParticipantsArr) : [];

            foreach ($participants  as $participant_id) {
                if ($participant_id > 0) {
                    if (!empty($orderParticipantsIds) && in_array($participant_id, $orderParticipantsIds)) {
                        $order_participant_data    = psyem_GetSinglePostWithMetaPrefix('psyem-participants', $participant_id, 'psyem_participant_');
                        if (empty($order_participant_data)) {
                            $resp['message'] = __('Print tickets participants data not found', 'psyeventsmanager');
                            wp_send_json($resp, 200);
                            break;
                        }
                        $order_participants_arr[$participant_id]   = $order_participant_data;
                    }
                }
            }

            // prepare logo
            $pdfLogoHtml = '';
            try {
                $pdf_content_type   = 'Logo';
                $logoSamplePath     = PSYEM_ASSETS_PATH . '/images/sitelogo.png';
                $logo_image_data    = file_get_contents($logoSamplePath);
                $logo_base64        = base64_encode($logo_image_data);
                $logo_mime_type     = mime_content_type($logoSamplePath);
                $logo_image_src     = 'data:' . $logo_mime_type . ';base64,' . $logo_base64;
                $pdfLogoHtml        = trim(require PSYEM_PATH . 'admin/includes/psyemOrderTicket.php');
            } catch (\Exception $e) {
                error_log('ManageOrderPrintTickets LOGO ERROR :: ' . $e->getMessage());
            }

            // prepare event info
            $pdf_content_type   = 'Event';
            $orderEventId       = get_post_meta(@$order_id, 'psyem_order_event_id', true);
            $orderEventInfo     = psyem_GetSinglePostWithMetaPrefix('psyem-events', $orderEventId, 'psyem_event_');
            $orderEventInfo['order_id'] = $order_id;
            $orderEventMeta     = @$orderEventInfo['meta_data'];
            $pdfEventHtml       = trim(require PSYEM_PATH . 'admin/includes/psyemOrderTicket.php');

            // prepare footer
            $MasterSettings     = psyem_GetOptionsWithPrefix();
            $pdf_content_type   = 'Footer';
            $TandCPageLink      = (isset($MasterSettings['psyem_terms_url']) && !empty($MasterSettings['psyem_terms_url'])) ? $MasterSettings['psyem_terms_url'] : get_site_url();
            $pdfFooterHtml      = trim(require PSYEM_PATH . 'admin/includes/psyemOrderTicket.php');

            $subject = 'Event ticket for - ' . @$orderEventInfo['title'];
            $headers = array('Content-Type: text/html; charset=UTF-8');

            // prepare tickets
            $pdfTicketsHtml = '';
            if (!empty($order_participants_arr) && is_array($order_participants_arr) && count($order_participants_arr) > 0) {
                foreach ($order_participants_arr as $participantID => $participantInfo) {
                    $scanKey            = psyem_safe_b64encode($participantID . '@_@' . $order_id);
                    $verifyQrPageLink   = psyem_GetPageLinkByID($psyem_event_verifyqr_page_id);

                    try {
                        $get_scan_data = http_build_query(array(
                            'ticketinfo'    => $scanKey,
                            'order'         => @$order_id,
                            'participant'   => @$participantID,
                        ));
                        $qrContentUrl =  $verifyQrPageLink . '?' . $get_scan_data;
                        $qrSamplePath = PSYEM_PATH . 'packages/phpqrcode/qrcode.png';
                        $qrSampleUrl  = PSYEM_URL . 'packages/phpqrcode/qrcode.png';
                        QRcode::png($qrContentUrl, $qrSamplePath, QR_ECLEVEL_L, 6, 2);
                        $image_data   = file_get_contents($qrSamplePath);
                        $base64       = base64_encode($image_data);
                        $mime_type    = mime_content_type($qrSamplePath);
                        $qrBase64     = 'data:' . $mime_type . ';base64,' . $base64;

                        $participantInfo['order_id']        = $order_id;
                        $participantInfo['qr_image_src']    = $qrBase64;
                        $fullName = @$participantInfo['title'];
                        $namesArr = psyem_SplitFullName($fullName);
                        $participantInfo = array_merge($participantInfo, $namesArr);
                        $participantMeta = @$participantInfo['meta_data'];
                        $toEmail         = @$participantMeta['psyem_participant_email'];

                        // prpare ticket
                        $pdf_content_type = 'Ticket';
                        $pdfTicketsHtml  .= trim(require PSYEM_PATH . 'admin/includes/psyemOrderTicket.php');
                        if (!empty($pdfTicketsHtml) && !empty($toEmail)) {

                            $message = __('Hello', 'psyeventsmanager') . '  ' . $fullName;
                            $message .= '<p>' . __('Kindly find the attached document for event ticket reference', 'psyeventsmanager') . ' <p>';
                            $message .= '<p> ' . __('Event Address', 'psyeventsmanager') . ': ' . @$orderEventMeta['psyem_event_address'] . '<p>';
                            $message .= '<p> ' . __('See you soon', 'psyeventsmanager') . '.<p>';
                            $message .= '<p> ' . __('Thank You', 'psyeventsmanager') . '<p>';


                            $pdfhtml = '
                            <html lang="en" dir="ltr">
                            <body style="background-color:#fff;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
                            <div style="width:100%; font-size: 16px; line-height: 24px; background: #fff; color: #555; padding: 20px 10px 20px 2px;">             
                                ' . $pdfLogoHtml . '
                                ' . $pdfEventHtml . '
                                ' . $pdfTicketsHtml . '
                                ' . $pdfFooterHtml . '
                            </div>
                            </body>
                            </html>
                            ';

                            $dompdf         = new Dompdf();
                            $options        = $dompdf->getOptions();
                            $options->setDefaultFont('Courier');
                            $dompdf->setOptions($options);
                            $dompdf->loadHtml($pdfhtml);
                            $dompdf->setPaper('A4', 'portrait');
                            $dompdf->render();

                            $fileName   = 'ticket_' . $orderEventId . '_' . $order_id . '_' . $participantID . '.pdf';
                            $upload_dir = wp_upload_dir();
                            $file_path  = $upload_dir['path'] . '/' . $fileName;
                            file_put_contents($file_path, $dompdf->output());
                            $file_url = $upload_dir['url'] . '/' . $fileName;


                            $participantEmailResp = wp_mail($toEmail, $subject, $message, $headers, array($file_path));
                            if ($participantEmailResp && filesize($file_path) > 0) {
                                unlink($file_path);
                            }
                        }
                    } catch (\Exception $e) {
                        error_log('ManageOrderPrintTickets QR IMAGE ERROR :: ' . $e->getMessage());
                    }
                }
            }

            $resp['status']          = 'success';
            $resp['message']         = __('Event ticket has been successfully sent',  'psyeventsmanager');
            $resp['validation']      = [];
            $resp['file_url']        = '';
        }
        wp_send_json($resp, 200);
    }

    function psyem_ManageOfflineRegistration()
    {
        global $wpdb;
        $psyem_events          = psyem_GetAllEventsForForApi([
            'from_date'         => date('Y-m-d'),
            'limit'             => 99999999999999,
        ]);

        include_once PSYEM_PATH . 'admin/includes/psyemOfflineSignup.php';
    }

    function psyem_ManageOfflineRegistrationAjax()
    {
        $resp      = array(
            'status'     => 'error',
            'message'    => __('Offline Registration has been failed to create', 'psyeventsmanager'),
            'validation' => ''
        );

        global $wpdb;

        $postData = @$this->REQ;
        $isvalid  = psyem_ValidateOfflineRegistrationData($postData);

        if (!empty($isvalid)) {
            $resp['validation'] = $isvalid;
            wp_send_json($resp, 200);
        }

        if (empty($isvalid)) {
            $resp =  psyem_ManageCreateOfflineRegistration($postData);
        }
        wp_send_json($resp, 200);
    }
    /* POST ORDERS - END */

    function psyem_ExtendCustomPostTypeSearch($search, $query)
    {
        global $wpdb;
        if (is_admin()) {
            $currentPostType = $query->get('post_type');
            $is_main_query   = $query->is_main_query();
            $search_term     = $query->get('s');

            if ($is_main_query && !empty($search_term) && $currentPostType == 'psyem-orders') {
                $sql    = "
                    or exists (
                        select * from {$wpdb->postmeta} where post_id={$wpdb->posts}.ID
                        and meta_key in ('psyem_order_participant_name','psyem_order_participant_email')
                        and meta_value like %s
                    )
                ";
                $like   = '%' . $wpdb->esc_like($search_term) . '%';
                $search = preg_replace(
                    "#\({$wpdb->posts}.post_title LIKE [^)]+\)\K#",
                    $wpdb->prepare($sql, $like),
                    $search
                );
            }

            if ($is_main_query && !empty($search_term) && $currentPostType == 'psyem-participants') {
                $sql    = "
                    or exists (
                        select * from {$wpdb->postmeta} where post_id={$wpdb->posts}.ID
                        and meta_key in ('psyem_participant_email')
                        and meta_value like %s
                    )
                ";
                $like   = '%' . $wpdb->esc_like($search_term) . '%';
                $search = preg_replace(
                    "#\({$wpdb->posts}.post_title LIKE [^)]+\)\K#",
                    $wpdb->prepare($sql, $like),
                    $search
                );
            }

            if ($is_main_query && !empty($search_term) && $currentPostType == 'psyem-events') {
                $sql    = "
                    or exists (
                        select * from {$wpdb->postmeta} where post_id={$wpdb->posts}.ID
                        and meta_key in ('psyem_event_registration_type','psyem_event_address','psyem_event_startdate','psyem_event_enddate')
                        and meta_value like %s
                    )
                ";
                $like   = '%' . $wpdb->esc_like($search_term) . '%';
                $search = preg_replace(
                    "#\({$wpdb->posts}.post_title LIKE [^)]+\)\K#",
                    $wpdb->prepare($sql, $like),
                    $search
                );
            }

            if ($is_main_query && !empty($search_term) && $currentPostType == 'psyem-coupons') {
                $sql    = "
                    or exists (
                        select * from {$wpdb->postmeta} where post_id={$wpdb->posts}.ID
                        and meta_key in ('psyem_coupon_type','psyem_coupon_unique_code','psyem_coupon_discount_percentage','psyem_coupon_expiry_date')
                        and meta_value like %s
                    )
                ";
                $like   = '%' . $wpdb->esc_like($search_term) . '%';
                $search = preg_replace(
                    "#\({$wpdb->posts}.post_title LIKE [^)]+\)\K#",
                    $wpdb->prepare($sql, $like),
                    $search
                );
            }

            if ($is_main_query && !empty($search_term) && $currentPostType == 'psyem-tickets') {
                $sql    = "
                    or exists (
                        select * from {$wpdb->postmeta} where post_id={$wpdb->posts}.ID
                        and meta_key in ('psyem_ticket_price','psyem_ticket_type','psyem_ticket_group_participant')
                        and meta_value like %s
                    )
                ";
                $like   = '%' . $wpdb->esc_like($search_term) . '%';
                $search = preg_replace(
                    "#\({$wpdb->posts}.post_title LIKE [^)]+\)\K#",
                    $wpdb->prepare($sql, $like),
                    $search
                );
            }

            if ($is_main_query && !empty($search_term) && $currentPostType == 'psyem-projectsafes') {
                $sql    = "
                    or exists (
                        select * from {$wpdb->postmeta} where post_id={$wpdb->posts}.ID
                        and meta_key in ('psyem_projectsafe_phone','psyem_projectsafe_email','psyem_projectsafe_gender','psyem_projectsafe_dob','psyem_projectsafe_region','psyem_projectsafe_district','psyem_projectsafe_address','psyem_projectsafe_source','psyem_projectsafe_method','psyem_projectsafe_status')
                        and meta_value like %s
                    )
                ";
                $like   = '%' . $wpdb->esc_like($search_term) . '%';
                $search = preg_replace(
                    "#\({$wpdb->posts}.post_title LIKE [^)]+\)\K#",
                    $wpdb->prepare($sql, $like),
                    $search
                );
            }
        }

        return $search;
    }

    function psyem_ApplyCustomPostTypeSearch($query)
    {
        global $wpdb;
        global $pagenow, $typenow;
        $getData  = @$this->REQ;
        if ($pagenow == 'edit.php' || $typenow == 'psyem-participants') {
            $event_id = (isset($getData['event_id'])) ? $getData['event_id'] : 0;
            if ($event_id > 0) {
                $query->set('meta_query', array(
                    array(
                        'key'     => 'psyem_participant_event_id',
                        'value'   => $event_id,
                        'compare' => '='
                    )
                ));
            }
        }


        if ($pagenow == 'edit.php' || $typenow == 'psyem-projectsafes') {
            $projectsafe_type = (isset($getData['projectsafe_type'])) ? $getData['projectsafe_type'] : '';
            if (!empty($projectsafe_type)) {
                $query->set('meta_query', array(
                    array(
                        'key'     => 'psyem_projectsafe_type',
                        'value'   => $projectsafe_type,
                        'compare' => '='
                    )
                ));
            }
        }
    }
    /* POST TYPE PROJECT SAFE - BGN */

    function psyem_ProjectsafesCustomPostType()
    {
        $labels = array(
            'name'                  => _x('Projectsafes', 'Projectsafes', 'psyeventsmanager'),
            'singular_name'         => _x('Projectsafe', 'Projectsafe', 'psyeventsmanager'),
            'menu_name'             => __('Projectsafes', 'psyeventsmanager'),
            'name_admin_bar'        => __('Projectsafe', 'psyeventsmanager'),
            'archives'              => __('Projectsafe Archives', 'psyeventsmanager'),
            'attributes'            => __('Projectsafe Attributes', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent Projectsafe:', 'psyeventsmanager'),
            'all_items'             => __('All Projectsafes', 'psyeventsmanager'),
            'add_new_item'          => __('Add New Projectsafe', 'psyeventsmanager'),
            'add_new'               => __('Add New', 'psyeventsmanager'),
            'new_item'              => __('New Projectsafe', 'psyeventsmanager'),
            'edit_item'             => __('Edit Projectsafe', 'psyeventsmanager'),
            'update_item'           => __('Update Projectsafe', 'psyeventsmanager'),
            'view_item'             => __('View Projectsafe', 'psyeventsmanager'),
            'view_items'            => __('View Projectsafes', 'psyeventsmanager'),
            'search_items'          => __('Search Projectsafe', 'psyeventsmanager'),
            'not_found'             => __('Not found', 'psyeventsmanager'),
            'not_found_in_trash'    => __('Not found in Trash', 'psyeventsmanager'),
            'featured_image'        => __('Featured Image', 'psyeventsmanager'),
            'set_featured_image'    => __('Set featured image', 'psyeventsmanager'),
            'remove_featured_image' => __('Remove featured image', 'psyeventsmanager'),
            'use_featured_image'    => __('Use as featured image', 'psyeventsmanager'),
            'insert_into_item'      => __('Insert into participant', 'psyeventsmanager'),
            'uploaded_to_this_item' => __('Uploaded to this participant', 'psyeventsmanager'),
            'items_list'            => __('Projectsafes list', 'psyeventsmanager'),
            'items_list_navigation' => __('Projectsafes list navigation', 'psyeventsmanager'),
            'filter_items_list'     => __('Filter Projectsafes list', 'psyeventsmanager'),
        );

        $args = array(
            'label'                 => __('Projectsafe', 'psyeventsmanager'),
            'description'           => __('Projectsafe post type', 'psyeventsmanager'),
            'labels'                => $labels,
            'supports'              => array('title'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'psyeventsmanager',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'show_in_rest'          => true,
            'capability_type'       => 'post',
            'rewrite'               => array('slug' => 'psyem-projectsafes'),
        );

        register_post_type('psyem-projectsafes', $args);
    }

    function psyem_ProjectsafesCustomPostTypeMetaBoxes()
    {
        add_meta_box(
            'psyem_projectsafe_config_meta_box',
            __('Project safe Informations', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'ProjectsafeCustomPostTypeInfoMetaBox'), // Callback function to display the content
            'psyem-projectsafes', // Post type where the meta box will appear
            'normal', // Context (side, normal, advanced)
            'default' // Priority
        );
    }

    function psyem_ProjectsafeCustomPostTypeInfoMetaBox($post)
    {

        $post_id = @$post->ID;
        wp_nonce_field('psyem_save_projectsafe_config_metabox_data', 'psyem_projectsafe_config_nonce');
        $projectsafe_meta_data        = psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_projectsafe_');
        $projectsafe_metabox_type     = 'Information';
        require PSYEM_PATH . 'admin/includes/psyemProjectsafeMetaboxes.php';
    }

    function psyem_ProjectsafesCustomPostTypeSaveMetaBoxes($post_id)
    {

        // Check if nonce is set
        if (!isset($_POST['psyem_projectsafe_config_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['psyem_projectsafe_config_nonce'], 'psyem_save_projectsafe_config_metabox_data')) {
            return;
        }

        // Check if auto-saving
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $ProjectsafeInfos    = (isset($_POST['psyemProjectsafeInfos'])) ? $_POST['psyemProjectsafeInfos'] : array();

        // Configs
        if (!empty($ProjectsafeInfos) && is_array($ProjectsafeInfos)) {
            foreach ($ProjectsafeInfos as $participantkey => $participantValue) {
                update_post_meta($post_id, $participantkey, $participantValue);
            }
        }
    }

    function psyem_ProjectsafesTableCustomColumns($columns)
    {

        $columns['title']                       = __('Name', 'psyeventsmanager');
        $columns['psyem_projectsafe_type']      = __('Type', 'psyeventsmanager');
        $columns['psyem_projectsafe_phone']     = __('Phone', 'psyeventsmanager');
        $columns['psyem_projectsafe_email']     = __('Email', 'psyeventsmanager');
        $columns['psyem_projectsafe_gender']    = __('Gender', 'psyeventsmanager');
        $columns['psyem_projectsafe_dob']       = __('DOB', 'psyeventsmanager');
        $columns['psyem_projectsafe_region']    = __('Region', 'psyeventsmanager');
        $columns['psyem_projectsafe_district']  = __('District', 'psyeventsmanager');
        $columns['psyem_projectsafe_address']   = __('Address', 'psyeventsmanager');
        $columns['psyem_projectsafe_source']    = __('Source', 'psyeventsmanager');
        $columns['psyem_projectsafe_method']    = __('Method', 'psyeventsmanager');
        $columns['psyem_projectsafe_status']    = __('Status', 'psyeventsmanager');
        $date_column                            = $columns['date'];
        unset($columns['date']);
        $columns['date']                        = $date_column;

        return $columns;
    }

    function psyem_ProjectsafesTableCustomColumnValues($column, $post_id)
    {
        $projectsafe_meta_data  = psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_projectsafe_');
        $psTypes                = get_option('psyem_projectsafe_types', []);
        if (!empty($projectsafe_meta_data) && is_array($projectsafe_meta_data)) {
            if ($column == 'psyem_projectsafe_phone') {
                $psyem_projectsafe_phone = @$projectsafe_meta_data['psyem_projectsafe_phone'];
                echo (!empty($psyem_projectsafe_phone)) ? esc_html($psyem_projectsafe_phone) : '-';
            }
            if ($column == 'psyem_projectsafe_type') {
                $psyem_projectsafe_type = @$projectsafe_meta_data['psyem_projectsafe_type'];
                if (isset($psTypes[$psyem_projectsafe_type]) && !empty($psTypes[$psyem_projectsafe_type])) {
                    $psyem_projectsafe_type = ($psTypes[$psyem_projectsafe_type]);
                }
                echo '<strong>';
                echo (!empty($psyem_projectsafe_type)) ? esc_html($psyem_projectsafe_type) : '-';
                echo '</strong>';
            }
            if ($column == 'psyem_projectsafe_email') {
                $psyem_projectsafe_email = @$projectsafe_meta_data['psyem_projectsafe_email'];
                echo (!empty($psyem_projectsafe_email)) ? esc_html($psyem_projectsafe_email) : '-';
            }
            if ($column == 'psyem_projectsafe_gender') {
                $psyem_projectsafe_gender = @$projectsafe_meta_data['psyem_projectsafe_gender'];
                echo (!empty($psyem_projectsafe_gender)) ? esc_html($psyem_projectsafe_gender) : '-';
            }
            if ($column == 'psyem_projectsafe_dob') {
                $psyem_projectsafe_dob = @$projectsafe_meta_data['psyem_projectsafe_dob_format'];
                echo (!empty($psyem_projectsafe_dob)) ? esc_html($psyem_projectsafe_dob) : '-';
            }
            if ($column == 'psyem_projectsafe_region') {
                $psyem_projectsafe_region = @$projectsafe_meta_data['psyem_projectsafe_region'];
                echo (!empty($psyem_projectsafe_region)) ? esc_html($psyem_projectsafe_region) : '-';
            }
            if ($column == 'psyem_projectsafe_district') {
                $psyem_projectsafe_district = @$projectsafe_meta_data['psyem_projectsafe_district'];
                echo (!empty($psyem_projectsafe_district)) ? esc_html($psyem_projectsafe_district) : '-';
            }
            if ($column == 'psyem_projectsafe_address') {
                $psyem_projectsafe_address = @$projectsafe_meta_data['psyem_projectsafe_address'];
                echo (!empty($psyem_projectsafe_address)) ? esc_html($psyem_projectsafe_address) : '-';
            }
            if ($column == 'psyem_projectsafe_source') {
                $psyem_projectsafe_source = @$projectsafe_meta_data['psyem_projectsafe_source'];
                echo (!empty($psyem_projectsafe_source)) ? esc_html($psyem_projectsafe_source) : '-';
            }
            if ($column == 'psyem_projectsafe_method') {
                $psyem_projectsafe_contact_by = @$projectsafe_meta_data['psyem_projectsafe_contact_by'];
                echo (!empty($psyem_projectsafe_contact_by)) ? esc_html($psyem_projectsafe_contact_by) : '-';
            }
            if ($column == 'psyem_projectsafe_status') {
                $psyem_projectsafe_status = @$projectsafe_meta_data['psyem_projectsafe_status'];
                echo (!empty($psyem_projectsafe_status)) ? esc_html($psyem_projectsafe_status) : '-';
            }
        }
    }

    function psyem_ProjectsafesTableSortableColumns($columns)
    {
        $columns['psyem_projectsafe_phone']     = 'psyem_projectsafe_phone';
        $columns['psyem_projectsafe_email']     = 'psyem_projectsafe_email';
        $columns['psyem_projectsafe_gender']    = 'psyem_projectsafe_gender';
        $columns['psyem_projectsafe_dob']       = 'psyem_projectsafe_dob';
        $columns['psyem_projectsafe_region']    = 'psyem_projectsafe_region';
        $columns['psyem_projectsafe_district']  = 'psyem_projectsafe_district';
        $columns['psyem_projectsafe_address']   = 'psyem_projectsafe_address';
        $columns['psyem_projectsafe_source']    = 'psyem_projectsafe_source';
        $columns['psyem_projectsafe_method']    = 'psyem_projectsafe_method';
        $columns['psyem_projectsafe_status']    = 'psyem_projectsafe_status';
        return $columns;
    }

    function psyem_AddProjectSafeExportButtonBeforeTable()
    {
        $screen                 = get_current_screen();
        $current_screen_page    = ($screen && $screen->id)  ? $screen->id : '';
        $current_post_type      = ($screen && $screen->post_type)  ? $screen->post_type : '';

        global $wpdb;

        if ($current_screen_page == 'edit-psyem-projectsafes' &&  $current_post_type == 'psyem-projectsafes') {
            try {
                $current_url  = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                $parsed_url   = parse_url($current_url);
                $query_string = @$parsed_url['query'];
                parse_str($query_string, $query_params);
                if (isset($query_params['_nonce'])) {
                    unset($query_params['_nonce']);
                }
                if (isset($query_params['action'])) {
                    unset($query_params['action']);
                }
                $selectedPst = isset($query_params['projectsafe_type']) ? $query_params['projectsafe_type'] : '';
                $exportArr = array(
                    '_nonce'  => wp_create_nonce('_nonce'),
                    'action'  => PSYEM_PREFIX . 'projectsafe_export',
                );

                $psyem_projectsafe_types  = get_option('psyem_projectsafe_types', []);
                if (!empty($psyem_projectsafe_types) && is_array($psyem_projectsafe_types)) {
                    echo '<select name="projectsafe_type">';
                    echo '<option value=""> ' . __('All Project Safe Types', 'psyeventsmanager') . '</option>';
                    foreach ($psyem_projectsafe_types as $psyem_projectsafe_type_slug => $psyem_projectsafe_type_title) {
                        echo '<option value="' . $psyem_projectsafe_type_slug . '" ' . selected($selectedPst, $psyem_projectsafe_type_slug, false) . '>' . $psyem_projectsafe_type_title . '</option>';
                    }
                    echo '</select>';
                }

                $linkParams = array_merge($exportArr, $query_params);

                $get_data   = http_build_query($linkParams);
                $exporturl  = admin_url('admin-post.php') . '?' . $get_data;
                echo '<span style="display:inline; float: right;">
                    <a class="button button-primary" href="' . $exporturl . '">            
                         ' . __('Export to XLSX', 'psyeventsmanager') . '
                    </a>
                </span>';
            } catch (\Exception $e) {
            }
        }
    }

    function psyem_ExportProjectsafesCustomPostType()
    {

        global $wpdb;
        $getData    = @$this->REQ;
        $isvalid     = psyem_ValidateProjectSafeExportData($getData);

        if (!empty($isvalid)) {
            wp_die($isvalid[0]);
        }
        if (!current_user_can('manage_options')) {
            wp_die(__('You are not authorize to export data', 'psyeventsmanager'));
        }
        if (isset($getData['_nonce'])) {
            unset($getData['_nonce']);
        }
        if (isset($getData['action'])) {
            unset($getData['action']);
        }
        if (isset($getData['action2'])) {
            unset($getData['action2']);
        }

        // search
        $yearMonthStr  = @$getData['m'];
        $yearMonthStr  = (!empty($yearMonthStr)) ? $yearMonthStr . date('d') : '';
        $yearMonthArr  = (!empty($yearMonthStr)) ? psyem_GetConvertedYearMonthDay($yearMonthStr) : [];
        $searchYear    = @$yearMonthArr['year'];
        $searchMonth   = @$yearMonthArr['month'];


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="prohjectsafes.xlsx"');
        header('Cache-Control: max-age=0');

        // Create a new Spreadsheet object
        $spreadsheet    = new Spreadsheet();
        $sheet          = $spreadsheet->getActiveSheet();

        // Set the header row
        $sheet->setCellValue('A1',  __('ID', 'psyeventsmanager'));
        $sheet->setCellValue('B1', __('First Name', 'psyeventsmanager'));
        $sheet->setCellValue('C1', __('Last Name', 'psyeventsmanager'));
        $sheet->setCellValue('D1', __('FullName', 'psyeventsmanager'));
        $sheet->setCellValue('E1', __('Phone', 'psyeventsmanager'));
        $sheet->setCellValue('F1', __('Email', 'psyeventsmanager'));
        $sheet->setCellValue('G1', __('Gender', 'psyeventsmanager'));
        $sheet->setCellValue('H1', __('DOB', 'psyeventsmanager'));
        $sheet->setCellValue('I1', __('Region', 'psyeventsmanager'));
        $sheet->setCellValue('J1', __('District', 'psyeventsmanager'));
        $sheet->setCellValue('K1', __('Address', 'psyeventsmanager'));
        $sheet->setCellValue('L1', __('Source', 'psyeventsmanager'));
        $sheet->setCellValue('M1', __('Method', 'psyeventsmanager'));
        $sheet->setCellValue('N1', __('Date', 'psyeventsmanager'));
        $sheet->setCellValue('O1', __('Type', 'psyeventsmanager'));

        $args = array(
            'post_type'         => 'psyem-projectsafes',
            'posts_per_page'    => -1,
        );
        if (!empty($searchYear)) {
            $args['date_query']['year'] = $searchYear;
        }
        if (!empty($searchMonth)) {
            $args['date_query']['month'] = $searchMonth;
        }

        // filter
        $filterPst      = isset($getData['projectsafe_type']) ? $getData['projectsafe_type'] : '';
        if (!empty($filterPst)) {
            $args['meta_query'] = array(
                array(
                    'key'     => 'psyem_projectsafe_type',
                    'value'   => $filterPst,
                    'compare' => '='
                )
            );
        }

        $psyem_projectsafe_types  = get_option('psyem_projectsafe_types', []);
        $custom_posts = new WP_Query($args);
        $row          = 2; // Start from the second row
        if ($custom_posts->have_posts()) {
            while ($custom_posts->have_posts()) {
                $custom_posts->the_post();
                $postid     = get_the_ID();
                $psMeta     = psyem_GetPostAllMetakeyValWithPrefix($postid, 'psyem_projectsafe_');

                $psyem_projectsafe_type = @$psMeta['psyem_projectsafe_type'];
                $psyem_projectsafe_type = (isset($psyem_projectsafe_types[$psyem_projectsafe_type]) && !empty($psyem_projectsafe_types[$psyem_projectsafe_type])) ? $psyem_projectsafe_types[$psyem_projectsafe_type] : 'Project Safe';

                $sheet->setCellValue('A' . $row, get_the_ID());
                $sheet->setCellValue('B' . $row, @$psMeta['psyem_projectsafe_firstname']);
                $sheet->setCellValue('C' . $row, @$psMeta['psyem_projectsafe_lastname']);
                $sheet->setCellValue('D' . $row, @$psMeta['psyem_projectsafe_fullname']);
                $sheet->setCellValue('E' . $row, @$psMeta['psyem_projectsafe_phone']);
                $sheet->setCellValue('F' . $row, @$psMeta['psyem_projectsafe_email']);
                $sheet->setCellValue('G' . $row, @$psMeta['psyem_projectsafe_gender']);
                $sheet->setCellValue('H' . $row, @$psMeta['psyem_projectsafe_dob_format']);
                $sheet->setCellValue('I' . $row, @$psMeta['psyem_projectsafe_region']);
                $sheet->setCellValue('J' . $row, @$psMeta['psyem_projectsafe_district']);
                $sheet->setCellValue('K' . $row, @$psMeta['psyem_projectsafe_address']);
                $sheet->setCellValue('L' . $row, @$psMeta['psyem_projectsafe_source']);
                $sheet->setCellValue('M' . $row, @$psMeta['psyem_projectsafe_contact_by']);
                $sheet->setCellValue('N' . $row, get_the_date());
                $sheet->setCellValue('O' . $row, @$psyem_projectsafe_type);
                $row++;
            }
        }
        // Clean up
        wp_reset_postdata();
        // Write the file
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    /* POST TYPE PROJECT SAFE - END */

    /* POST DONATION AMOUNTS - BGN */
    function psyem_DonationAmountsCustomPostType()
    {
        $labels = array(
            'name'                  => _x('Donation Amounts', 'Amounts', 'psyeventsmanager'),
            'singular_name'         => _x('Donation Amount', 'Amount', 'psyeventsmanager'),
            'menu_name'             => __('Amounts', 'psyeventsmanager'),
            'name_admin_bar'        => __('Amount', 'psyeventsmanager'),
            'archives'              => __('Amount Archives', 'psyeventsmanager'),
            'attributes'            => __('Amount Attributes', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent Amount:', 'psyeventsmanager'),
            'all_items'             => __('All Amounts', 'psyeventsmanager'),
            'add_new_item'          => __('Add New Amount', 'psyeventsmanager'),
            'add_new'               => __('Add New', 'psyeventsmanager'),
            'new_item'              => __('New Amount', 'psyeventsmanager'),
            'edit_item'             => __('Edit Amount', 'psyeventsmanager'),
            'update_item'           => __('Update Amount', 'psyeventsmanager'),
            'view_item'             => __('View Amount', 'psyeventsmanager'),
            'view_items'            => __('View Amounts', 'psyeventsmanager'),
            'search_items'          => __('Search Amount', 'psyeventsmanager'),
            'not_found'             => __('Not found', 'psyeventsmanager'),
            'not_found_in_trash'    => __('Not found in Trash', 'psyeventsmanager'),
            'featured_image'        => __('Featured Image', 'psyeventsmanager'),
            'set_featured_image'    => __('Set featured image', 'psyeventsmanager'),
            'remove_featured_image' => __('Remove featured image', 'psyeventsmanager'),
            'use_featured_image'    => __('Use as featured image', 'psyeventsmanager'),
            'insert_into_item'      => __('Insert into amount', 'psyeventsmanager'),
            'uploaded_to_this_item' => __('Uploaded to this amount', 'psyeventsmanager'),
            'items_list'            => __('Amounts list', 'psyeventsmanager'),
            'items_list_navigation' => __('Amounts list navigation', 'psyeventsmanager'),
            'filter_items_list'     => __('Filter Amounts list', 'psyeventsmanager'),
        );

        $args = array(
            'label'                 => __('Amount', 'psyeventsmanager'),
            'description'           => __('Amount post type for donations', 'psyeventsmanager'),
            'labels'                => $labels,
            'supports'              => array('title', 'excerpt'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'psyeventsmanager', // Show under custom menu
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'show_in_rest'          => true,
            'capability_type'       => 'post',
            'rewrite'               => array('slug' => 'psyem-amounts'),
        );

        register_post_type('psyem-amounts', $args);
    }

    function psyem_DonationAmountsCustomPostTypeMetaBoxes()
    {
        add_meta_box(
            'psyem_amount_config_meta_box',
            __('Amount Informations', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'DonationAmountCustomPostTypeInfoMetaBox'),
            'psyem-amounts',
            'normal',
            'default'
        );
    }

    function psyem_DonationAmountCustomPostTypeInfoMetaBox($post)
    {

        $post_id = @$post->ID;
        wp_nonce_field('psyem_save_amount_config_metabox_data', 'psyem_amount_config_nonce');
        $amount_info_data        = psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_amount_');
        $amount_metabox_type     = 'Information';
        require PSYEM_PATH . 'admin/includes/psyemAmountMetaboxes.php';
    }

    function psyem_DonationAmountsCustomPostTypeSaveMetaBoxes($post_id)
    {

        // Check if nonce is set
        if (!isset($_POST['psyem_amount_config_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['psyem_amount_config_nonce'], 'psyem_save_amount_config_metabox_data')) {
            return;
        }

        // Check if auto-saving
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $AmountInfos    = (isset($_POST['psyemAmountInfo'])) ? $_POST['psyemAmountInfo'] : array();

        // Configs
        if (!empty($AmountInfos) && is_array($AmountInfos)) {
            foreach ($AmountInfos as $amountkey => $amountValue) {
                update_post_meta($post_id, $amountkey, $amountValue);
            }
        }
    }

    function psyem_DonationAmountsTableCustomColumns($columns)
    {

        $columns['title']              = __('Amount Name', 'psyeventsmanager');
        $columns['psyem_amount_price'] = __('Price', 'psyeventsmanager');
        $columns['psyem_amount_type']  = __('Type', 'psyeventsmanager');
        $date_column                   = $columns['date'];
        unset($columns['date']);
        $columns['date']                    = $date_column;

        return $columns;
    }

    function psyem_DonationAmountsTableCustomColumnValues($column, $post_id)
    {
        $amount_meta_data  =  psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_amount_');
        if (!empty($amount_meta_data) && is_array($amount_meta_data)) {
            if ($column == 'psyem_amount_price') {
                $psyem_amount_price = formatPriceWithComma(@$amount_meta_data['psyem_amount_price']);
                echo (!empty($psyem_amount_price)) ? (esc_html($psyem_amount_price)) : '-';
            }
            if ($column == 'psyem_amount_type') {
                $psyem_amount_type = @$amount_meta_data['psyem_amount_type'];
                echo (!empty($psyem_amount_type)) ? esc_html($psyem_amount_type) : '-';
            }
        }
    }

    function psyem_DonationAmountsTableSortableColumns($columns)
    {
        $columns['psyem_amount_price'] = 'psyem_amount_price';
        $columns['psyem_amount_type'] = 'psyem_amount_type';
        return $columns;
    }
    /* POST DONATION AMOUNTS - END */

    /* POST DONATION  - BGN */
    function psyem_DonationsCustomPostType()
    {
        $labels = array(
            'name'                  => _x('Donations', 'Donations', 'psyeventsmanager'),
            'singular_name'         => _x('Donation', 'Donation', 'psyeventsmanager'),
            'menu_name'             => __('Donations', 'psyeventsmanager'),
            'name_admin_bar'        => __('Donation', 'psyeventsmanager'),
            'archives'              => __('Donation Archives', 'psyeventsmanager'),
            'attributes'            => __('Donation Attributes', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent Donation:', 'psyeventsmanager'),
            'all_items'             => __('All Donations', 'psyeventsmanager'),
            'add_new_item'          => __('Add New Donation', 'psyeventsmanager'),
            'add_new'               => __('Add New', 'psyeventsmanager'),
            'new_item'              => __('New Donation', 'psyeventsmanager'),
            'edit_item'             => __('Edit Donation', 'psyeventsmanager'),
            'update_item'           => __('Update Donation', 'psyeventsmanager'),
            'view_item'             => __('View Donation', 'psyeventsmanager'),
            'view_items'            => __('View Donations', 'psyeventsmanager'),
            'search_items'          => __('Search Donation', 'psyeventsmanager'),
            'not_found'             => __('Not found', 'psyeventsmanager'),
            'not_found_in_trash'    => __('Not found in Trash', 'psyeventsmanager'),
            'featured_image'        => __('Featured Image', 'psyeventsmanager'),
            'set_featured_image'    => __('Set featured image', 'psyeventsmanager'),
            'remove_featured_image' => __('Remove featured image', 'psyeventsmanager'),
            'use_featured_image'    => __('Use as featured image', 'psyeventsmanager'),
            'insert_into_item'      => __('Insert into donation', 'psyeventsmanager'),
            'uploaded_to_this_item' => __('Uploaded to this donation', 'psyeventsmanager'),
            'items_list'            => __('Donations list', 'psyeventsmanager'),
            'items_list_navigation' => __('Donations list navigation', 'psyeventsmanager'),
            'filter_items_list'     => __('Filter Donations list', 'psyeventsmanager'),
        );

        $args = array(
            'label'                 => __('Donation', 'psyeventsmanager'),
            'description'           => __('Donation post type for donations', 'psyeventsmanager'),
            'labels'                => $labels,
            'supports'              => array('title', 'excerpt'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'psyeventsmanager', // Show under custom menu
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'show_in_rest'          => true,
            'capability_type'       => 'post',
            'rewrite'               => array('slug' => 'psyem-donations'),
        );

        register_post_type('psyem-donations', $args);
    }

    function psyem_DonationsCustomPostTypeMetaBoxes()
    {
        add_meta_box(
            'psyem_donation_config_meta_box',
            __('Donation Informations', 'psyeventsmanager'),
            array(&$this, PSYEM_PREFIX . 'DonationCustomPostTypeInfoMetaBox'),
            'psyem-donations',
            'normal',
            'default'
        );
    }

    function psyem_DonationCustomPostTypeInfoMetaBox($post)
    {

        $post_id = @$post->ID;
        wp_nonce_field('psyem_save_donation_config_metabox_data', 'psyem_donation_config_nonce');
        $donation_info_data    = psyem_GetSinglePostWithMetaPrefix('psyem-donations', $post_id, 'psyem_donation_');
        $donation_meta_data    = @$donation_info_data['meta_data'];

        $donation_metabox_type     = 'Informations';
        $psyem_donation_payment_payload = get_post_meta(@$post_id, 'psyem_donation_payment_payload', true);

        require PSYEM_PATH . 'admin/includes/psyemDonationMetaboxes.php';
    }

    function psyem_DonationsCustomPostTypeSaveMetaBoxes($post_id)
    {

        // Check if nonce is set
        if (!isset($_POST['psyem_donation_config_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['psyem_donation_config_nonce'], 'psyem_save_donation_config_metabox_data')) {
            return;
        }

        // Check if auto-saving
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $DonationInfos    = (isset($_POST['psyemDonationInfo'])) ? $_POST['psyemDonationInfo'] : array();

        // Configs
        if (!empty($DonationInfos) && is_array($DonationInfos)) {
            foreach ($DonationInfos as $donationkey => $donationValue) {
                update_post_meta($post_id, $donationkey, $donationValue);
            }
        }
    }

    function psyem_DonationsTableCustomColumns($columns)
    {

        $columns['title']                   = __('Donation Name', 'psyeventsmanager');
        $columns['psyem_donation_price']    = __('Price', 'psyeventsmanager');
        $columns['psyem_donation_type']     = __('Type', 'psyeventsmanager');
        $columns['psyem_donation_status']   = __('Payment Status', 'psyeventsmanager');
        $date_column                        = $columns['date'];
        unset($columns['date']);
        $columns['date']                    = $date_column;

        return $columns;
    }

    function psyem_DonationsTableCustomColumnValues($column, $post_id)
    {
        $donation_meta_data  =  psyem_GetPostAllMetakeyValWithPrefix($post_id, 'psyem_donation_');
        if (!empty($donation_meta_data) && is_array($donation_meta_data)) {
            if ($column == 'psyem_donation_price') {
                $psyem_donation_price = formatPriceWithComma(@$donation_meta_data['psyem_donation_price']);
                echo (!empty($psyem_donation_price)) ? esc_html($psyem_donation_price) : '-';
            }
            if ($column == 'psyem_donation_type') {
                $psyem_donation_type = @$donation_meta_data['psyem_donation_type'];
                echo (!empty($psyem_donation_type)) ? esc_html($psyem_donation_type) : '-';
            }
            if ($column == 'psyem_donation_status') {
                $psyem_donation_status = @$donation_meta_data['psyem_donation_status'];
                echo (!empty($psyem_donation_status)) ? esc_html($psyem_donation_status) : '-';
            }
        }
    }

    function psyem_DonationsTableSortableColumns($columns)
    {
        $columns['psyem_donation_price'] = 'psyem_donation_price';
        $columns['psyem_donation_type'] = 'psyem_donation_type';
        $columns['psyem_donation_status'] = 'psyem_donation_status';
        return $columns;
    }
    /* POST DONATION  - END */


    /* POST KNOWLEDGE  - BGN */
    function psyem_KnowledgehubCustomPostType()
    {
        $labels = array(
            'name'                  => _x('Knowledge', 'Knowledge', 'psyeventsmanager'),
            'singular_name'         => _x('Knowledge', 'Knowledge', 'psyeventsmanager'),
            'menu_name'             => __('Knowledge', 'psyeventsmanager'),
            'name_admin_bar'        => __('Knowledge', 'psyeventsmanager'),
            'archives'              => __('Knowledge Archives', 'psyeventsmanager'),
            'attributes'            => __('Knowledge Attributes', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent Knowledge:', 'psyeventsmanager'),
            'all_items'             => __('All Knowledges', 'psyeventsmanager'),
            'add_new_item'          => __('Add New Knowledge', 'psyeventsmanager'),
            'add_new'               => __('Add New', 'psyeventsmanager'),
            'new_item'              => __('New Knowledge', 'psyeventsmanager'),
            'edit_item'             => __('Edit Knowledge', 'psyeventsmanager'),
            'update_item'           => __('Update Knowledge', 'psyeventsmanager'),
            'view_item'             => __('View Knowledge', 'psyeventsmanager'),
            'view_items'            => __('View Knowledge', 'psyeventsmanager'),
            'search_items'          => __('Search Knowledge', 'psyeventsmanager'),
            'not_found'             => __('Not found', 'psyeventsmanager'),
            'not_found_in_trash'    => __('Not found in Trash', 'psyeventsmanager'),
            'featured_image'        => __('Featured Image', 'psyeventsmanager'),
            'set_featured_image'    => __('Set featured image', 'psyeventsmanager'),
            'remove_featured_image' => __('Remove featured image', 'psyeventsmanager'),
            'use_featured_image'    => __('Use as featured image', 'psyeventsmanager'),
            'insert_into_item'      => __('Insert into donation', 'psyeventsmanager'),
            'uploaded_to_this_item' => __('Uploaded to this donation', 'psyeventsmanager'),
            'items_list'            => __('Knowledge list', 'psyeventsmanager'),
            'items_list_navigation' => __('Knowledge list navigation', 'psyeventsmanager'),
            'filter_items_list'     => __('Filter Knowledge list', 'psyeventsmanager'),
        );

        $args = array(
            'label'                 => __('Knowledge', 'psyeventsmanager'),
            'description'           => __('Knowledge post type for knowledge', 'psyeventsmanager'),
            'labels'                => $labels,
            'supports'              => array(
                'title',
                'excerpt',
                'thumbnail',
                'editor',
                'template',
                'author',
                'comments'
            ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'psyeventsmanager', // Show under custom menu
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'show_in_rest'          => true,
            'capability_type'       => 'post',
            'taxonomies'            => ['psyem-knowledges-category'],
            'rewrite'               => array('slug' => 'psyem-knowledges'),
        );

        register_post_type('psyem-knowledges', $args);
    }

    function psyem_KnowledgehubCustomPostTaxonomy()
    {

        $labels =   array(
            'name'                  => _x('Knowledge Categories', 'Knowledge Categories', 'psyeventsmanager'),
            'singular_name'         => _x('Knowledge Category', 'Knowledge Category', 'psyeventsmanager'),
            'search_items'          => __('Search Knowledge Categories', 'psyeventsmanager'),
            'all_items'             => __('All Knowledge Categories', 'psyeventsmanager'),
            'parent_item'           => __('Parent Knowledge Category', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent Knowledge Category:', 'psyeventsmanager'),
            'edit_item'             => __('Edit Knowledge Category', 'psyeventsmanager'),
            'update_item'           => __('Update Knowledge Category', 'psyeventsmanager'),
            'add_new_item'          => __('Add New Knowledge Category', 'psyeventsmanager'),
            'new_item_name'         => __('New Knowledge Category Name', 'psyeventsmanager'),
            'menu_name'             => __('Knowledge Categories', 'psyeventsmanager'),
        );

        $args = array(
            'labels'                => $labels,
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'psyem-knowledges-category'),
            'show_in_menu'          => 'edit.php?post_type=psyem-knowledges',
        );

        register_taxonomy('psyem-knowledges-category', array('psyem-knowledges'), $args);
        register_taxonomy_for_object_type('psyem-knowledges-category', 'psyem-knowledges');
    }

    /* POST KNOWLEDGE  - END */

    /* POST NEWS  - BGN */

    function psyem_NewsCustomPostType()
    {
        $labels = array(
            'name'                  => _x('News', 'News', 'psyeventsmanager'),
            'singular_name'         => _x('News', 'News', 'psyeventsmanager'),
            'menu_name'             => __('News', 'psyeventsmanager'),
            'name_admin_bar'        => __('News', 'psyeventsmanager'),
            'archives'              => __('News Archives', 'psyeventsmanager'),
            'attributes'            => __('News Attributes', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent News:', 'psyeventsmanager'),
            'all_items'             => __('All News', 'psyeventsmanager'),
            'add_new_item'          => __('Add New News', 'psyeventsmanager'),
            'add_new'               => __('Add New', 'psyeventsmanager'),
            'new_item'              => __('New News', 'psyeventsmanager'),
            'edit_item'             => __('Edit News', 'psyeventsmanager'),
            'update_item'           => __('Update News', 'psyeventsmanager'),
            'view_item'             => __('View News', 'psyeventsmanager'),
            'view_items'            => __('View News', 'psyeventsmanager'),
            'search_items'          => __('Search News', 'psyeventsmanager'),
            'not_found'             => __('Not found', 'psyeventsmanager'),
            'not_found_in_trash'    => __('Not found in Trash', 'psyeventsmanager'),
            'featured_image'        => __('Featured Image', 'psyeventsmanager'),
            'set_featured_image'    => __('Set featured image', 'psyeventsmanager'),
            'remove_featured_image' => __('Remove featured image', 'psyeventsmanager'),
            'use_featured_image'    => __('Use as featured image', 'psyeventsmanager'),
            'insert_into_item'      => __('Insert into donation', 'psyeventsmanager'),
            'uploaded_to_this_item' => __('Uploaded to this donation', 'psyeventsmanager'),
            'items_list'            => __('News list', 'psyeventsmanager'),
            'items_list_navigation' => __('News list navigation', 'psyeventsmanager'),
            'filter_items_list'     => __('Filter News list', 'psyeventsmanager'),
        );

        $args = array(
            'label'                 => __('News', 'psyeventsmanager'),
            'description'           => __('News post type for news', 'psyeventsmanager'),
            'labels'                => $labels,
            'supports'              => array(
                'title',
                'excerpt',
                'thumbnail',
                'editor',
                'template',
                'author',
                'comments'
            ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'psyeventsmanager', // Show under custom menu
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'taxonomies'            => ['psyem-news-category'],
            'rewrite'               => array('slug' => 'psyem-news'),
        );

        register_post_type('psyem-news', $args);
    }

    function psyem_NewsCustomPostTaxonomy()
    {

        $labels =   array(
            'name'                  => _x('News Categories', 'News Categories', 'psyeventsmanager'),
            'singular_name'         => _x('News Category', 'News Category', 'psyeventsmanager'),
            'search_items'          => __('Search News Categories', 'psyeventsmanager'),
            'all_items'             => __('All News Categories', 'psyeventsmanager'),
            'parent_item'           => __('Parent News Category', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent News Category:', 'psyeventsmanager'),
            'edit_item'             => __('Edit News Category', 'psyeventsmanager'),
            'update_item'           => __('Update News Category', 'psyeventsmanager'),
            'add_new_item'          => __('Add New News Category', 'psyeventsmanager'),
            'new_item_name'         => __('New News Category Name', 'psyeventsmanager'),
            'menu_name'             => __('News Categories', 'psyeventsmanager'),
        );

        $args = array(
            'labels'                => $labels,
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'psyem-news-category'),
            'show_in_menu'          => 'edit.php?post_type=psyem-news',
        );

        register_taxonomy('psyem-news-category', array('psyem-news'), $args);
        register_taxonomy_for_object_type('psyem-news-category', 'psyem-news');
    }

    /* POST NEWS  - END */


    /* POST PROGRAMMES  - BGN */

    function psyem_ProgrammesCustomPostType()
    {
        $labels = array(
            'name'                  => _x('Programmes', 'Programmes', 'psyeventsmanager'),
            'singular_name'         => _x('Programmes', 'Programmes', 'psyeventsmanager'),
            'menu_name'             => __('Programmes', 'psyeventsmanager'),
            'name_admin_bar'        => __('Programmes', 'psyeventsmanager'),
            'archives'              => __('Programmes Archives', 'psyeventsmanager'),
            'attributes'            => __('Programmes Attributes', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent Programmes:', 'psyeventsmanager'),
            'all_items'             => __('All Programmes', 'psyeventsmanager'),
            'add_new_item'          => __('Add New Programmes', 'psyeventsmanager'),
            'add_new'               => __('Add New', 'psyeventsmanager'),
            'new_item'              => __('New Programmes', 'psyeventsmanager'),
            'edit_item'             => __('Edit Programmes', 'psyeventsmanager'),
            'update_item'           => __('Update Programmes', 'psyeventsmanager'),
            'view_item'             => __('View Programmes', 'psyeventsmanager'),
            'view_items'            => __('View Programmes', 'psyeventsmanager'),
            'search_items'          => __('Search Programmes', 'psyeventsmanager'),
            'not_found'             => __('Not found', 'psyeventsmanager'),
            'not_found_in_trash'    => __('Not found in Trash', 'psyeventsmanager'),
            'featured_image'        => __('Featured Image', 'psyeventsmanager'),
            'set_featured_image'    => __('Set featured image', 'psyeventsmanager'),
            'remove_featured_image' => __('Remove featured image', 'psyeventsmanager'),
            'use_featured_image'    => __('Use as featured image', 'psyeventsmanager'),
            'insert_into_item'      => __('Insert into donation', 'psyeventsmanager'),
            'uploaded_to_this_item' => __('Uploaded to this donation', 'psyeventsmanager'),
            'items_list'            => __('Programmes list', 'psyeventsmanager'),
            'items_list_navigation' => __('Programmes list navigation', 'psyeventsmanager'),
            'filter_items_list'     => __('Filter Programmes list', 'psyeventsmanager'),
        );

        $args = array(
            'label'                 => __('Programmes', 'psyeventsmanager'),
            'description'           => __('Programmes post type for programmes', 'psyeventsmanager'),
            'labels'                => $labels,
            'supports'              => array(
                'title',
                'excerpt',
                'thumbnail',
                'editor',
                'template',
                'author',
                'comments'
            ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'psyeventsmanager', // Show under custom menu
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'taxonomies'            => ['psyem-programmes-category'],
            'rewrite'               => array('slug' => 'psyem-programmes'),
        );

        register_post_type('psyem-programmes', $args);
    }

    function psyem_ProgrammesCustomPostTaxonomy()
    {

        $labels =   array(
            'name'                  => _x('Programmes Categories', 'Programmes Categories', 'psyeventsmanager'),
            'singular_name'         => _x('Programmes Category', 'Programmes Category', 'psyeventsmanager'),
            'search_items'          => __('Search Programmes Categories', 'psyeventsmanager'),
            'all_items'             => __('All Programmes Categories', 'psyeventsmanager'),
            'parent_item'           => __('Parent Programmes Category', 'psyeventsmanager'),
            'parent_item_colon'     => __('Parent Programmes Category:', 'psyeventsmanager'),
            'edit_item'             => __('Edit Programmes Category', 'psyeventsmanager'),
            'update_item'           => __('Update Programmes Category', 'psyeventsmanager'),
            'add_new_item'          => __('Add New Programmes Category', 'psyeventsmanager'),
            'new_item_name'         => __('New Programmes Category Name', 'psyeventsmanager'),
            'menu_name'             => __('Programmes Categories', 'psyeventsmanager'),
        );

        $args = array(
            'labels'                => $labels,
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'psyem-programmes-category'),
            'show_in_menu'          => 'edit.php?post_type=psyem-programmes',
        );

        register_taxonomy('psyem-programmes-category', array('psyem-programmes'), $args);
        register_taxonomy_for_object_type('psyem-programmes-category', 'psyem-programmes');
    }
    /* POST PROGRAMMES  - BGN */


    /* TAXONOMY CUSTOM FIELDS BGN */
    function psyem_ListingCustomPostsTaxonomyAddCustomField()
    {

        echo '<div class="form-field">
            <label for="category_icon_image_url">' . __("Category Icon Image URL", "psyeventsmanager") . '</label>
            <input type="text" name="category_icon_image_url" id="category_icon_image_url" value="" />
            <p>' . __("Enter category icon image url", "psyeventsmanager") . '</p>
        </div>';
    }

    function psyem_ListingCustomPostsTaxonomyEditCustomField($term)
    {
        $category_icon_image_url = get_term_meta($term->term_id, 'category_icon_image_url', true);

        echo '<tr class="form-field">
            <th scope="row"><label for="category_icon_image_url">' . __("Category Icon Image URL", "psyeventsmanager") . '</label></th>
            <td>
                <input type="text" name="category_icon_image_url" id="category_icon_image_url" value="' . esc_attr(@$category_icon_image_url) . '" />
                <p class="description">' . __("Enter category icon image url", "psyeventsmanager") . '</p>
            </td>
        </tr>';
    }

    function psyem_ListingCustomPostsTaxonomySaveCustomField($term_id)
    {
        if (isset($_POST['category_icon_image_url'])) {
            update_term_meta($term_id, 'category_icon_image_url', sanitize_text_field($_POST['category_icon_image_url']));
        }
    }
    /* TAXONOMY CUSTOM FIELDS END */
}
$psyemAdmin = new psyemAdminManager();
