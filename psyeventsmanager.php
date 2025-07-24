<?php
/* 
* Plugin Name: PSY Events Manager
* Plugin URI: https://smtlabs.io
* Description: Custom Plugin for Events, Tickets, Sales and Orders Managements
* Author: Pawan Kumar
* Author URI: https://smtlabs.io
* Version: 1.0.0
* Text Domain: psyeventsmanager
* Domain Path: /languages
* Plugin Slug: psyeventsmanager
* Requires at least: 6.7
* Requires PHP: 8.0 
*/


if (!defined('ABSPATH')) {
    exit;
}
if (!defined('PSYEM_URL')) {
    define('PSYEM_URL', plugin_dir_url(__FILE__));
}
if (!defined('PSYEM_PATH')) {
    define('PSYEM_PATH', plugin_dir_path(__FILE__));
}
if (!defined('PSYEM_PREFIX')) {
    define('PSYEM_PREFIX', 'psyem_');
}
if (!defined('PSYEM_VERSION')) {
    define('PSYEM_VERSION', '1.0.0');
}
if (!defined('PSYEM_ASSETS_PATH')) {
    define('PSYEM_ASSETS_PATH', PSYEM_PATH . 'assets');
}
if (!defined('PSYEM_ASSETS')) {
    define('PSYEM_ASSETS', PSYEM_URL . 'assets');
}
if (!defined('PSYEM_PLUGINS')) {
    define('PSYEM_PLUGINS', PSYEM_URL . 'plugins');
}
/* @Plugin Initial setup codes BGN */
register_activation_hook(__FILE__,     array('psyemEventsManagerInitials',    'psyemEventsManagerInitial_checkDependency'));
register_activation_hook(__FILE__,     array('psyemEventsManagerInitials',    'psyemEventsManagerInitial_CreateAdminPages'));
register_deactivation_hook(__FILE__,   array('psyemEventsManagerInitials',    'psyemEventsManagerInitial_DeactivateCallback'));
register_uninstall_hook(__FILE__,      array('psyemEventsManagerInitials',    'psyemEventsManagerInitial_UninstallCallback'));
/* INCLUDE HELPERS */
require PSYEM_PATH . 'helpers/psyemHelper.php';
require PSYEM_PATH . 'helpers/psyemValidationsHelper.php';
require PSYEM_PATH . 'helpers/psyemStripeHelper.php';

/* EVENT MANAGER INITIALS BGN */
class psyemEventsManagerInitials
{
    public static function psyemEventsManagerInitial_checkDependency()
    {
        global $wpdb;
        ob_start();
        self::psyemEventsManagerInitial_CreateDbtables();
        self::psyemEventsManagerInitial_InsertDbTableData();
    }

    public static function psyemEventsManagerInitial_CreateDbtables()
    {
        global $wpdb;
    }

    public static function psyemEventsManagerInitial_InsertDbTableData()
    {
        global $wpdb;
        ob_clean();
    }

    public static function psyemEventsManagerInitial_CreateAdminPages()
    {
        global $wpdb;
        global $post;
        $user_id = get_current_user_id();
        global $user_ID;
    }

    public static function psyemEventsManagerInitial_DeactivateCallback()
    {
        global $wpdb;
    }

    public static function psyemEventsManagerInitial_UninstallCallback()
    {
        global $wpdb;
        if (!defined('WP_UNINSTALL_PLUGIN')) {
            wp_die('You are not authorized to do this operation.');
        }
    }
}
$psyemEventsManagerInitials = new psyemEventsManagerInitials();
/* EVENT MANAGER INITIALS END */

/* EVENT MANAGER BGN */
class psyemEventsManager
{
    function __construct()
    {
        global $wpdb, $post;
        $this->psyem_InitEventsManagerActions();
    }

    function psyem_InitEventsManagerActions()
    {
        add_action('plugins_loaded',     array(&$this, PSYEM_PREFIX . 'loadTextdomain'));
    }

    function psyem_loadTextdomain()
    {
         load_plugin_textdomain('psyeventsmanager', false, dirname(plugin_basename(__FILE__)) . '/languages');       
    }
}
/* EVENT MANAGER END */

/* PROJECT SAFE FORM EDITOR BGN */
// Load form editor only in admin
if (is_admin()) {
    require 'admin/psyemProjectSafeFormEditor.php';
}

// Add form shortcode support
add_shortcode('psyem_project_safe_form', 'psyem_project_safe_form_shortcode');

// Form shortcode function
function psyem_project_safe_form_shortcode($atts) {
    $atts = shortcode_atts(array(
        'type' => 'project-safe',
        'show_title' => 'true',
    ), $atts, 'psyem_project_safe_form');
    
    // Get form configuration
    $form_config = get_option('psyem_project_safe_form_config', array());
    
    if (empty($form_config)) {
        return '<div class="psyem-form-error">Form configuration not found. Please configure the form in admin panel.</div>';
    }
    
    ob_start();
    ?>
    <div class="psyem-project-safe-form-wrapper">
        <?php if ($atts['show_title'] === 'true' && !empty($form_config['settings']['title'])): ?>
            <h2><?php echo esc_html($form_config['settings']['title']); ?></h2>
        <?php endif; ?>
        
        <form id="psyem-dynamic-form" method="post">
            <?php wp_nonce_field('psyem_form_submit', 'psyem_nonce'); ?>
            
            <?php foreach ($form_config['steps'] as $step_index => $step): ?>
                <div class="psyem-step" data-step="<?php echo $step_index + 1; ?>" 
                     style="<?php echo $step_index === 0 ? 'display:block;' : 'display:none;'; ?>">
                    
                    <h3><?php echo esc_html($step['title']); ?></h3>
                    
                    <?php foreach ($step['fields'] as $field): ?>
                        <div class="field-wrapper">
                            <label><?php echo esc_html($field['label']); ?>
                                <?php if ($field['required']): ?><span style="color:red;">*</span><?php endif; ?>
                            </label>
                            
                            <?php switch($field['type']):
                                case 'text':
                                case 'email':
                                case 'tel': ?>
                                    <input type="<?php echo esc_attr($field['type']); ?>" 
                                           name="<?php echo esc_attr($field['name']); ?>"
                                           placeholder="<?php echo esc_attr($field['placeholder']); ?>"
                                           <?php echo $field['required'] ? 'required' : ''; ?> />
                                    <?php break;
                                    
                                case 'select': ?>
                                    <select name="<?php echo esc_attr($field['name']); ?>" 
                                            <?php echo $field['required'] ? 'required' : ''; ?>>
                                        <option value=""><?php echo esc_html($field['placeholder']); ?></option>
                                        <?php foreach ($field['options'] as $option): ?>
                                            <option value="<?php echo esc_attr($option['value']); ?>">
                                                <?php echo esc_html($option['label']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php break;
                                    
                                case 'textarea': ?>
                                    <textarea name="<?php echo esc_attr($field['name']); ?>"
                                              placeholder="<?php echo esc_attr($field['placeholder']); ?>"
                                              <?php echo $field['required'] ? 'required' : ''; ?>></textarea>
                                    <?php break;
                                    
                            endswitch; ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="step-navigation">
                        <?php if ($step_index > 0): ?>
                            <button type="button" class="prev-btn">Previous</button>
                        <?php endif; ?>
                        
                        <?php if ($step_index < count($form_config['steps']) - 1): ?>
                            <button type="button" class="next-btn">Next</button>
                        <?php else: ?>
                            <button type="submit">Submit</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </form>
    </div>
    
    <style>
    .psyem-project-safe-form-wrapper {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .field-wrapper {
        margin-bottom: 15px;
    }
    .field-wrapper label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
    }
    .field-wrapper input,
    .field-wrapper select,
    .field-wrapper textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }
    .step-navigation {
        margin-top: 20px;
        text-align: center;
    }
    .step-navigation button {
        padding: 10px 20px;
        margin: 0 5px;
        background: #0073aa;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .prev-btn {
        background: #666 !important;
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        var currentStep = 1;
        var totalSteps = $('.psyem-step').length;
        
        $('.next-btn').click(function() {
            $('.psyem-step[data-step="' + currentStep + '"]').hide();
            currentStep++;
            $('.psyem-step[data-step="' + currentStep + '"]').show();
        });
        
        $('.prev-btn').click(function() {
            $('.psyem-step[data-step="' + currentStep + '"]').hide();
            currentStep--;
            $('.psyem-step[data-step="' + currentStep + '"]').show();
        });
    });
    </script>
    <?php
    
    return ob_get_clean();
}

// Handle form submission
add_action('init', 'psyem_handle_form_submission');
function psyem_handle_form_submission() {
    if (isset($_POST['psyem_nonce']) && wp_verify_nonce($_POST['psyem_nonce'], 'psyem_form_submit')) {
        // Process form data
        $form_data = $_POST;
        unset($form_data['psyem_nonce']);
        
        // Save to database or send email
        error_log('Project SAFE form submitted: ' . print_r($form_data, true));
        
        // Redirect with success message
        wp_redirect(add_query_arg('form_submitted', '1', wp_get_referer()));
        exit;
    }
}

// Create database table for form submissions
function psyem_create_form_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'psyem_project_safe_submissions';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        form_data longtext NOT NULL,
        submitted_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Add table creation to plugin activation
add_action('psyemEventsManagerInitial_checkDependency', 'psyem_create_form_table');
/* PROJECT SAFE FORM EDITOR END */

require 'admin/psyemAdmin.php';
require 'front/psyemFront.php';
$psyemEventsManager = new psyemEventsManager();
