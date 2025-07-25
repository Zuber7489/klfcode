<?php 
/**
 * Snippet Name: Project SAFE Form Integration with Existing System
 * Description: Integrates form editor with existing psyem-projectsafes custom post type
 * Version: 1.0.0
 * Author: PsyEvents Team
 * Type: PHP Snippet
 * Location: Everywhere
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Only run if PsyEvents Manager plugin is active
if (!class_exists('psyemEventsManager')) {
    return;
}

class PsyemProjectSafeFormIntegration {
    
    private static $instance = null;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        // Hook into existing form submission process
        add_action('psyem_project_safe_form_submit', array($this, 'handle_dynamic_form_submission'));
        add_filter('psyem_project_safe_form_fields', array($this, 'get_dynamic_form_fields'));
        
        // Add settings to existing admin
        add_action('add_meta_boxes', array($this, 'add_form_editor_link_metabox'));
        
        // Register shortcode
        add_shortcode('psyem_project_safe_form', array($this, 'render_project_safe_form'));
        
        // Handle form submissions
        add_action('init', array($this, 'handle_form_submission'));
        
        // Admin notices
        add_action('admin_notices', array($this, 'admin_notices'));
    }
    
    /**
     * Handle form submission using existing system
     */
    public function handle_dynamic_form_submission($form_data) {
        // Get form configuration
        $form_config = get_option('psyem_project_safe_form_config', array());
        
        if (empty($form_config)) {
            return false;
        }
        
        // Prepare data in format expected by existing system
        $participant_info = array();
        $contact_info = array();
        
        // Map dynamic form data to existing structure
        foreach ($form_data as $field_name => $field_value) {
            if (strpos($field_name, 'field_') === 0) {
                $clean_name = str_replace('field_', '', $field_name);
                
                // Map to existing categories
                if (in_array($clean_name, array('first_name', 'last_name', 'gender', 'dob_day', 'dob_month', 'dob_year'))) {
                    $participant_info[$clean_name] = sanitize_text_field($field_value);
                } elseif (in_array($clean_name, array('phone', 'email', 'region', 'district', 'address'))) {
                    $contact_info[$clean_name] = sanitize_text_field($field_value);
                } else {
                    // Health questions
                    $participant_info[$clean_name] = sanitize_text_field($field_value);
                }
            }
        }
        
        // Use existing psyemHelper function if available
        if (function_exists('psyem_SubmitProjectSafeRequest')) {
            return psyem_SubmitProjectSafeRequest($participant_info, $contact_info);
        }
        
        return $this->create_project_safe_post($participant_info, $contact_info);
    }
    
    /**
     * Create project safe post using existing system structure
     */
    private function create_project_safe_post($participant_info, $contact_info) {
        global $wpdb;
        
        $participant_name = ($participant_info['first_name'] ?? '') . ' ' . ($participant_info['last_name'] ?? '');
        $current_time = current_time('mysql');
        $current_time_gmt = current_time('mysql', 1);
        
        // Create post content
        $post_content = $this->generate_post_content($participant_info, $contact_info);
        
        $ps_title = ucfirst(trim($participant_name)) . ' - Project Safe Request';
        $post_data = array(
            'post_title' => $ps_title,
            'post_name' => sanitize_title($ps_title),
            'post_status' => 'publish',
            'post_content' => $post_content,
            'post_excerpt' => wp_trim_words($post_content, 50),
            'post_type' => 'psyem-projectsafes',
            'post_date' => $current_time,
            'post_date_gmt' => $current_time_gmt,
            'post_author' => get_current_user_id() ?: 1,
        );
        
        $insert_result = wp_insert_post($post_data, true);
        
        if (!is_wp_error($insert_result) && $insert_result > 0) {
            // Save meta data
            $this->save_project_safe_meta($insert_result, $participant_info, $contact_info);
            
            // Send notifications
            $this->send_notifications($insert_result, $participant_info, $contact_info);
            
            return $insert_result;
        }
        
        return false;
    }
    
    /**
     * Save meta data for project safe post
     */
    private function save_project_safe_meta($post_id, $participant_info, $contact_info) {
        // Save participant info as meta
        foreach ($participant_info as $key => $value) {
            update_post_meta($post_id, 'psyem_participant_' . $key, sanitize_text_field($value));
        }
        
        // Save contact info as meta
        foreach ($contact_info as $key => $value) {
            update_post_meta($post_id, 'psyem_contact_' . $key, sanitize_text_field($value));
        }
        
        // Additional meta fields
        update_post_meta($post_id, 'psyem_submission_date', current_time('mysql'));
        update_post_meta($post_id, 'psyem_submission_ip', $this->get_user_ip());
        update_post_meta($post_id, 'psyem_form_version', 'wpcode_v1.0');
        update_post_meta($post_id, 'psyem_form_source', 'dynamic_form_editor');
    }
    
    /**
     * Get user IP address safely
     */
    private function get_user_ip() {
        $ip_keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = sanitize_text_field($_SERVER[$key]);
                // Handle comma-separated IPs
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return 'Unknown';
    }
    
    /**
     * Generate post content from form data
     */
    private function generate_post_content($participant_info, $contact_info) {
        ob_start();
        ?>
        <div class="project-safe-submission">
            <h3>Participant Information</h3>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <?php foreach ($participant_info as $key => $value): ?>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold; width: 40%;">
                            <?php echo esc_html(ucwords(str_replace('_', ' ', $key))); ?>
                        </td>
                        <td style="padding: 8px; border: 1px solid #ddd;">
                            <?php echo esc_html($value); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            
            <h3>Contact Information</h3>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <?php foreach ($contact_info as $key => $value): ?>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold; width: 40%;">
                            <?php echo esc_html(ucwords(str_replace('_', ' ', $key))); ?>
                        </td>
                        <td style="padding: 8px; border: 1px solid #ddd;">
                            <?php echo esc_html($value); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            
            <p style="font-style: italic; color: #666; margin-top: 20px;">
                <strong>Submitted on:</strong> <?php echo current_time('F j, Y g:i A'); ?><br>
                <strong>Form Type:</strong> Dynamic Project SAFE Form<br>
                <strong>Submission ID:</strong> <?php echo uniqid('PS'); ?>
            </p>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Send notifications
     */
    private function send_notifications($post_id, $participant_info, $contact_info) {
        $form_config = get_option('psyem_project_safe_form_config', array());
        $notification_email = $form_config['settings']['notification_email'] ?? get_option('admin_email');
        
        if ($notification_email && is_email($notification_email)) {
            $participant_name = trim(($participant_info['first_name'] ?? '') . ' ' . ($participant_info['last_name'] ?? ''));
            $subject = 'New Project SAFE Registration - ' . $participant_name;
            
            $message = "New Project SAFE registration received:\n\n";
            $message .= "Participant: " . $participant_name . "\n";
            $message .= "Email: " . ($contact_info['email'] ?? 'Not provided') . "\n";
            $message .= "Phone: " . ($contact_info['phone'] ?? 'Not provided') . "\n";
            $message .= "Date: " . current_time('F j, Y g:i A') . "\n\n";
            $message .= "View details: " . admin_url('post.php?post=' . $post_id . '&action=edit') . "\n";
            
            $headers = array('Content-Type: text/plain; charset=UTF-8');
            wp_mail($notification_email, $subject, $message, $headers);
        }
    }
    
    /**
     * Handle form submission
     */
    public function handle_form_submission() {
        if (isset($_POST['psyem_project_safe_submit']) && 
            wp_verify_nonce($_POST['psyem_nonce'], 'psyem_project_safe_form')) {
            
            $form_data = $_POST;
            unset($form_data['psyem_project_safe_submit'], $form_data['psyem_nonce']);
            
            $post_id = $this->handle_dynamic_form_submission($form_data);
            
            if ($post_id) {
                $redirect_url = add_query_arg('psyem_submission', 'success', wp_get_referer());
                wp_redirect($redirect_url);
                exit;
            } else {
                $redirect_url = add_query_arg('psyem_submission', 'error', wp_get_referer());
                wp_redirect($redirect_url);
                exit;
            }
        }
    }
    
    /**
     * Render project safe form shortcode
     */
    public function render_project_safe_form($atts) {
        $atts = shortcode_atts(array(
            'type' => 'project-safe',
            'show_title' => 'true',
        ), $atts, 'psyem_project_safe_form');
        
        // Get form configuration
        $form_config = get_option('psyem_project_safe_form_config', array());
        
        if (empty($form_config)) {
            return '<div class="psyem-form-error" style="padding: 15px; background: #ffebee; border-left: 4px solid #f44336; margin: 15px 0;">
                        <strong>Notice:</strong> Form configuration not found. Please configure the form in the admin panel.
                    </div>';
        }
        
        // Check for submission messages
        $submission_message = '';
        if (isset($_GET['psyem_submission'])) {
            if ($_GET['psyem_submission'] === 'success') {
                $success_msg = $form_config['settings']['success_message'] ?? 'Thank you for your registration!';
                $submission_message = '<div class="psyem-success-message" style="padding: 15px; background: #e8f5e8; border-left: 4px solid #4caf50; margin: 15px 0;">
                                           <strong>Success!</strong> ' . esc_html($success_msg) . '
                                       </div>';
            } elseif ($_GET['psyem_submission'] === 'error') {
                $submission_message = '<div class="psyem-error-message" style="padding: 15px; background: #ffebee; border-left: 4px solid #f44336; margin: 15px 0;">
                                           <strong>Error:</strong> There was a problem submitting your form. Please try again.
                                       </div>';
            }
        }
        
        ob_start();
        ?>
        <div class="psyem-project-safe-form-wrapper" style="max-width: 600px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <?php echo $submission_message; ?>
            
            <?php if ($atts['show_title'] === 'true' && !empty($form_config['settings']['title'])): ?>
                <h2 style="text-align: center; color: #333; margin-bottom: 10px;">
                    <?php echo esc_html($form_config['settings']['title']); ?>
                </h2>
            <?php endif; ?>
            
            <?php if (!empty($form_config['settings']['description'])): ?>
                <div style="text-align: center; color: #666; margin-bottom: 30px;">
                    <p><?php echo esc_html($form_config['settings']['description']); ?></p>
                </div>
            <?php endif; ?>
            
            <form method="post" class="psyem-dynamic-form">
                <?php wp_nonce_field('psyem_project_safe_form', 'psyem_nonce'); ?>
                <input type="hidden" name="psyem_project_safe_submit" value="1">
                
                <?php if (!empty($form_config['steps'])): ?>
                    <?php foreach ($form_config['steps'] as $step_index => $step): ?>
                        <div class="psyem-form-step" data-step="<?php echo $step_index + 1; ?>" 
                             style="<?php echo $step_index === 0 ? 'display: block;' : 'display: none;'; ?>">
                            
                            <h3 style="color: #0073aa; border-bottom: 2px solid #0073aa; padding-bottom: 10px; margin-bottom: 20px;">
                                <?php echo esc_html($step['title']); ?>
                            </h3>
                            
                            <?php if (!empty($step['fields'])): ?>
                                <?php foreach ($step['fields'] as $field): ?>
                                    <div style="margin-bottom: 20px;">
                                        <?php echo $this->render_form_field($field); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                            <div style="display: flex; justify-content: space-between; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                                <?php if ($step_index > 0): ?>
                                    <button type="button" class="psyem-prev-btn" style="padding: 12px 24px; background: #666; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                        <?php _e('Previous', 'psyeventsmanager'); ?>
                                    </button>
                                <?php else: ?>
                                    <div></div>
                                <?php endif; ?>
                                
                                <?php if ($step_index < count($form_config['steps']) - 1): ?>
                                    <button type="button" class="psyem-next-btn" style="padding: 12px 24px; background: #0073aa; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                        <?php _e('Next', 'psyeventsmanager'); ?>
                                    </button>
                                <?php else: ?>
                                    <button type="submit" style="padding: 12px 24px; background: #4caf50; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                                        <?php _e('Submit Registration', 'psyeventsmanager'); ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </form>
        </div>
        
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            var currentStep = 1;
            var totalSteps = $('.psyem-form-step').length;
            
            $('.psyem-next-btn').click(function() {
                var currentStepElement = $('.psyem-form-step[data-step="' + currentStep + '"]');
                var isValid = validateStep(currentStepElement);
                
                if (isValid && currentStep < totalSteps) {
                    currentStepElement.hide();
                    currentStep++;
                    $('.psyem-form-step[data-step="' + currentStep + '"]').show();
                }
            });
            
            $('.psyem-prev-btn').click(function() {
                if (currentStep > 1) {
                    $('.psyem-form-step[data-step="' + currentStep + '"]').hide();
                    currentStep--;
                    $('.psyem-form-step[data-step="' + currentStep + '"]').show();
                }
            });
            
            function validateStep(stepElement) {
                var isValid = true;
                
                stepElement.find('[required]:visible').each(function() {
                    var $field = $(this);
                    var value = $field.val().trim();
                    
                    if (!value) {
                        $field.css('border-color', '#f44336');
                        isValid = false;
                    } else {
                        $field.css('border-color', '#ddd');
                    }
                });
                
                if (!isValid) {
                    alert('Please fill in all required fields before proceeding.');
                }
                
                return isValid;
            }
        });
        </script>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Render individual form field
     */
    private function render_form_field($field) {
        $field_id = 'field_' . uniqid();
        $required = ($field['required'] ?? false) ? 'required' : '';
        $required_mark = ($field['required'] ?? false) ? '<span style="color: #d63638;">*</span>' : '';
        
        $output = '<label for="' . esc_attr($field_id) . '" style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">';
        $output .= esc_html($field['label'] ?? '') . ' ' . $required_mark . '</label>';
        
        $input_style = 'width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 4px; font-size: 16px; transition: border-color 0.3s ease;';
        
        switch ($field['type'] ?? 'text') {
            case 'text':
            case 'email':
            case 'tel':
            case 'number':
            case 'date':
                $output .= '<input type="' . esc_attr($field['type']) . '" ';
                $output .= 'id="' . esc_attr($field_id) . '" ';
                $output .= 'name="' . esc_attr($field['name'] ?? '') . '" ';
                $output .= 'placeholder="' . esc_attr($field['placeholder'] ?? '') . '" ';
                $output .= 'style="' . $input_style . '" ';
                $output .= $required . ' />';
                break;
                
            case 'textarea':
                $output .= '<textarea ';
                $output .= 'id="' . esc_attr($field_id) . '" ';
                $output .= 'name="' . esc_attr($field['name'] ?? '') . '" ';
                $output .= 'placeholder="' . esc_attr($field['placeholder'] ?? '') . '" ';
                $output .= 'style="' . $input_style . ' min-height: 100px; resize: vertical;" ';
                $output .= 'rows="4" ' . $required . '></textarea>';
                break;
                
            case 'select':
                $output .= '<select id="' . esc_attr($field_id) . '" name="' . esc_attr($field['name'] ?? '') . '" ';
                $output .= 'style="' . $input_style . '" ' . $required . '>';
                $output .= '<option value="">' . esc_html($field['placeholder'] ?? 'Select...') . '</option>';
                
                if (!empty($field['options'])) {
                    foreach ($field['options'] as $option) {
                        $output .= '<option value="' . esc_attr($option['value'] ?? '') . '">';
                        $output .= esc_html($option['label'] ?? '');
                        $output .= '</option>';
                    }
                }
                $output .= '</select>';
                break;
                
            case 'radio':
                if (!empty($field['options'])) {
                    foreach ($field['options'] as $index => $option) {
                        $option_id = $field_id . '_' . $index;
                        $output .= '<div style="margin-bottom: 8px;">';
                        $output .= '<label for="' . esc_attr($option_id) . '" style="display: inline-flex; align-items: center; font-weight: normal;">';
                        $output .= '<input type="radio" id="' . esc_attr($option_id) . '" ';
                        $output .= 'name="' . esc_attr($field['name'] ?? '') . '" ';
                        $output .= 'value="' . esc_attr($option['value'] ?? '') . '" ';
                        $output .= 'style="margin-right: 8px;" ' . $required . ' />';
                        $output .= esc_html($option['label'] ?? '');
                        $output .= '</label></div>';
                    }
                }
                break;
                
            case 'checkbox':
                $output .= '<div>';
                $output .= '<label for="' . esc_attr($field_id) . '" style="display: inline-flex; align-items: center; font-weight: normal;">';
                $output .= '<input type="checkbox" id="' . esc_attr($field_id) . '" ';
                $output .= 'name="' . esc_attr($field['name'] ?? '') . '" ';
                $output .= 'value="1" style="margin-right: 8px;" ' . $required . ' />';
                $output .= esc_html($field['label'] ?? '');
                $output .= '</label></div>';
                break;
        }
        
        if (!empty($field['description'])) {
            $output .= '<div style="margin-top: 5px; font-size: 14px; color: #666; font-style: italic;">';
            $output .= esc_html($field['description']);
            $output .= '</div>';
        }
        
        return $output;
    }
    
    /**
     * Get dynamic form fields
     */
    public function get_dynamic_form_fields($fields = array()) {
        $form_config = get_option('psyem_project_safe_form_config', array());
        
        if (!empty($form_config['steps'])) {
            return $form_config['steps'];
        }
        
        return $fields;
    }
    
    /**
     * Add form editor link to existing meta boxes
     */
    public function add_form_editor_link_metabox() {
        if (post_type_exists('psyem-projectsafes')) {
            add_meta_box(
                'psyem_form_editor_link',
                __('Form Editor', 'psyeventsmanager'),
                array($this, 'render_form_editor_link_metabox'),
                'psyem-projectsafes',
                'side',
                'high'
            );
        }
    }
    
    /**
     * Render form editor link meta box
     */
    public function render_form_editor_link_metabox($post) {
        ?>
        <div style="text-align: center;">
            <p><?php _e('Manage the Project SAFE registration form:', 'psyeventsmanager'); ?></p>
            <p>
                <a href="<?php echo admin_url('admin.php?page=psyem-project-safe-editor'); ?>" 
                   class="button button-primary" target="_blank">
                    <?php _e('Open Form Editor', 'psyeventsmanager'); ?>
                </a>
            </p>
            <p>
                <small><?php _e('Use the form editor to customize fields, validation, and settings.', 'psyeventsmanager'); ?></small>
            </p>
        </div>
        <?php
    }
    
    /**
     * Display admin notices
     */
    public function admin_notices() {
        $screen = get_current_screen();
        
        if ($screen && $screen->post_type === 'psyem-projectsafes') {
            if (!get_option('psyem_project_safe_form_config')) {
                echo '<div class="notice notice-info is-dismissible">
                        <p><strong>Project SAFE Form Integration:</strong> 
                           <a href="' . admin_url('admin.php?page=psyem-project-safe-editor') . '">Configure your form editor</a> 
                           to customize registration fields.
                        </p>
                      </div>';
            }
        }
    }
}

// Initialize the integration
add_action('plugins_loaded', function() {
    if (class_exists('psyemEventsManager') || function_exists('psyem_SubmitProjectSafeRequest')) {
        PsyemProjectSafeFormIntegration::getInstance();
    }
});