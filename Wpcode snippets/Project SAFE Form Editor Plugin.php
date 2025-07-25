<?php 

/**
 * Project SAFE Form Editor Plugin
 * 
 * A complete WordPress plugin for editing Project SAFE form fields,
 * validation rules, and form settings with admin interface.
 * 
 * @package PsyEventsManager
 * @version 1.0.0
 * @author PsyEvents Team
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class PsyemProjectSafeFormEditor {
    
    /**
     * Form configuration option name
     */
    const FORM_CONFIG_OPTION = 'psyem_project_safe_form_config';
    
    /**
     * Initialize the form editor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_psyem_save_form_config', array($this, 'ajax_save_form_config'));
        add_action('wp_ajax_psyem_reset_form_config', array($this, 'ajax_reset_form_config'));
        add_action('wp_ajax_psyem_clear_form_config', array($this, 'ajax_clear_form_config'));
        add_action('wp_ajax_psyem_add_form_field', array($this, 'ajax_add_form_field'));
        add_action('wp_ajax_psyem_delete_form_field', array($this, 'ajax_delete_form_field'));
    }
    
    /**
     * Add admin menu item to WordPress dashboard
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Project SAFE Forms', 'psyeventsmanager'),
            __('Project SAFE', 'psyeventsmanager'),
            'manage_options',
            'psyem-project-safe-editor',
            array($this, 'render_admin_page'),
            'dashicons-forms',
            30
        );
        
        add_submenu_page(
            'psyem-project-safe-editor',
            __('Form Editor', 'psyeventsmanager'),
            __('Form Editor', 'psyeventsmanager'),
            'manage_options',
            'psyem-project-safe-editor',
            array($this, 'render_admin_page')
        );
    }
    
    /**
     * Register settings for form configuration
     */
    public function register_settings() {
        register_setting(
            'psyem_project_safe_form_settings',
            self::FORM_CONFIG_OPTION,
            array(
                'type' => 'array',
                'sanitize_callback' => array($this, 'sanitize_form_config'),
                'default' => $this->get_default_form_config()
            )
        );
    }
    
    /**
     * Enqueue admin scripts and styles - inline CSS and JS
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'psyem-project-safe-editor') === false) {
            return;
        }
        
        // Enqueue jQuery UI components
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        
        // Enqueue WordPress color picker
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        // Add inline CSS
        add_action('admin_head', array($this, 'admin_styles'));
        
        // Add inline JavaScript
        add_action('admin_footer', array($this, 'admin_scripts'));
        
        // Localize script with AJAX data
        wp_localize_script('jquery', 'psyemFormEditor', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('psyem_form_editor_nonce'),
            'strings' => array(
                'saved' => __('Form configuration saved successfully!', 'psyeventsmanager'),
                'error' => __('Error saving form configuration.', 'psyeventsmanager'),
                'confirm_reset' => __('Are you sure you want to reset to default configuration?', 'psyeventsmanager'),
                'confirm_delete' => __('Are you sure you want to delete this field?', 'psyeventsmanager'),
            )
        ));
    }
    
    /**
     * Add inline CSS styles
     */
    public function admin_styles() {
        ?>
        <style type="text/css">
        .psyem-form-editor-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        
        .psyem-form-editor-sidebar {
            width: 300px;
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 0;
        }
        
        .psyem-sidebar-section {
            border-bottom: 1px solid #eee;
            padding: 15px;
        }
        
        .psyem-sidebar-section:last-child {
            border-bottom: none;
        }
        
        .psyem-sidebar-section h3 {
            margin: 0 0 15px 0;
            padding: 0;
            font-size: 14px;
            font-weight: 600;
            color: #1d2327;
        }
        
        .psyem-form-editor-main {
            flex: 1;
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
        }
        
        .psyem-form-preview-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
        }
        
        .psyem-form-preview-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }
        
        .psyem-form-actions {
            display: flex;
            gap: 10px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #1d2327;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .psyem-available-fields {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .psyem-field-type {
            padding: 10px;
            margin-bottom: 5px;
            background: #f1f1f1;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: move;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .psyem-field-type:hover {
            background: #e1f5fe;
            border-color: #0073aa;
        }
        
        .psyem-field-type .dashicons {
            color: #666;
        }
        
        .psyem-field-type .field-label {
            font-weight: 500;
            color: #1d2327;
        }
        
        .psyem-form-steps {
            padding: 20px;
        }
        
        .psyem-form-step {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
        }
        
        .psyem-step-header {
            padding: 15px;
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .psyem-step-header h4 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #1d2327;
        }
        
        .psyem-step-actions {
            display: flex;
            gap: 8px;
        }
        
        .psyem-step-content {
            padding: 15px;
        }
        
        .psyem-form-fields {
            min-height: 60px;
            margin-bottom: 15px;
        }
        
        .psyem-form-field {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            background: #fafafa;
            transition: all 0.3s ease;
        }
        
        .psyem-form-field:hover {
            border-color: #0073aa;
            box-shadow: 0 2px 5px rgba(0,115,170,0.1);
        }
        
        .psyem-field-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .psyem-field-header .field-handle {
            cursor: move;
            color: #666;
        }
        
        .psyem-field-header .field-label {
            flex: 1;
            font-weight: 600;
            color: #1d2327;
        }
        
        .field-actions {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        
        .required-indicator {
            color: #d63638;
            font-weight: bold;
        }
        
        .psyem-field-preview {
            padding: 10px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .psyem-field-preview input,
        .psyem-field-preview textarea,
        .psyem-field-preview select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f9f9f9;
        }
        
        /* Specific field type previews */
        .radio-field-preview input[type="radio"],
        .checkbox-field-preview input[type="checkbox"] {
            width: auto !important;
            padding: 0 !important;
            margin-right: 8px !important;
            background: transparent !important;
            accent-color: #0073aa;
        }
        
        .radio-field-preview label,
        .checkbox-field-preview label {
            display: block !important;
            margin-bottom: 8px !important;
            cursor: pointer !important;
            padding: 5px !important;
            background: none !important;
            border: none !important;
            width: auto !important;
        }
        
        .radio-field-preview label:hover,
        .checkbox-field-preview label:hover {
            background: #f0f0f0 !important;
            border-radius: 4px;
        }
        
        .select-field-preview select {
            width: 100% !important;
            padding: 10px 12px !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            background: #fff !important;
            font-size: 14px !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="%23666" d="M2 0L0 2h4zm0 5L0 3h4z"/></svg>') !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            background-size: 12px !important;
            cursor: pointer !important;
        }
        
        /* Remove any conflicting styles */
        .psyem-field-preview input[type="radio"],
        .psyem-field-preview input[type="checkbox"] {
            appearance: auto !important;
            -webkit-appearance: auto !important;
        }
        
        /* Consistent field preview styling */
        .text-field-preview,
        .textarea-field-preview,
        .date-field-preview,
        .default-field-preview {
            margin-bottom: 5px;
        }
        
        .text-field-preview input,
        .textarea-field-preview textarea,
        .date-field-preview input,
        .default-field-preview input {
            transition: border-color 0.3s ease;
        }
        
        .text-field-preview input:focus,
        .textarea-field-preview textarea:focus,
        .date-field-preview input:focus,
        .default-field-preview input:focus {
            border-color: #0073aa !important;
            outline: none !important;
            box-shadow: 0 0 0 1px #0073aa !important;
        }
        
        /* Date field specific styling */
        .date-field-preview input[type="date"] {
            background-color: #fff !important;
            cursor: pointer !important;
        }
        
        .date-field-preview input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            color: #0073aa;
        }
        
        /* Field preview containers */
        .radio-field-preview,
        .checkbox-field-preview {
            border: 1px solid #e0e0e0;
            margin-bottom: 5px;
        }
        
        .select-field-preview {
            margin-bottom: 5px;
        }
        
        /* Prevent multiple dropdown arrows */
        .psyem-field-preview select {
            background-image: none !important;
        }
        
        .psyem-field-preview .select-field-preview select {
            background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="%23666" d="M2 0L0 2h4zm0 5L0 3h4z"/></svg>') !important;
        }
        
        .psyem-field-preview label {
            display: block;
            margin-bottom: 5px;
            font-weight: normal;
        }
        
        .field-description {
            display: block;
            margin-top: 5px;
            font-style: italic;
            color: #666;
            font-size: 12px;
        }
        
        .psyem-drop-zone {
            padding: 20px;
            border: 2px dashed #ddd;
            border-radius: 4px;
            text-align: center;
            color: #666;
            background: #f9f9f9;
            transition: all 0.3s ease;
        }
        
        .psyem-drop-zone.drag-over {
            border-color: #0073aa;
            background: #e1f5fe;
            color: #0073aa;
        }
        
        .psyem-modal {
            position: fixed;
            z-index: 100000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        
        .psyem-modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: none;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .psyem-modal-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }
        
        .psyem-modal-close:hover,
        .psyem-modal-close:focus {
            color: #000;
        }
        
        .field-options-group {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            background: #f9f9f9;
        }
        
        .field-option-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }
        
        .field-option-item input {
            flex: 1;
        }
        
        .field-option-item .remove-option {
            color: #d63638;
            cursor: pointer;
            padding: 8px;
            margin-left: 8px;
            border-radius: 3px;
            transition: background-color 0.2s ease;
        }
        
        .field-option-item .remove-option:hover {
            background-color: #f8d7da;
        }
        
        .field-option-item .option-label {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .field-option-item .option-label:focus {
            border-color: #0073aa;
            outline: none;
            box-shadow: 0 0 0 1px #0073aa;
        }
        
        .field-options-group p small {
            color: #666;
            font-style: italic;
        }
        
        .success-message {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .button.loading {
            position: relative;
        }
        
        .button.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: #fff;
            border-radius: 50%;
            animation: button-loading-spinner 1s ease infinite;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }
        
        @keyframes button-loading-spinner {
            from {
                transform: rotate(0turn);
            }
            to {
                transform: rotate(1turn);
            }
        }
        
        .ui-sortable-helper {
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transform: rotate(2deg);
            z-index: 999;
        }
        
        .ui-sortable-placeholder {
            background: #e1f5fe;
            border: 2px dashed #0073aa;
            border-radius: 4px;
            height: 60px;
            margin-bottom: 15px;
        }
        
        .psyem-form-field.dragging {
            opacity: 0.8;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transform: scale(1.02);
            transition: all 0.2s ease;
        }
        
        .info-message {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        @media (max-width: 768px) {
            .psyem-form-editor-container {
                flex-direction: column;
            }
            
            .psyem-form-editor-sidebar {
                width: 100%;
            }
            
            .psyem-form-actions {
                flex-direction: column;
            }
            
            .psyem-form-preview-header {
                flex-direction: column;
                gap: 10px;
            }
        }
        </style>
        <?php
    }
    
    /**
     * Add inline JavaScript
     */
    public function admin_scripts() {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            var formEditor = {
                currentEditingField: null,
                
                init: function() {
                    this.initSortable();
                    this.initDragDrop();
                    this.bindEvents();
                    this.initColorPicker();
                },
                
                initSortable: function() {
                    // Check if jQuery UI sortable is available
                    if (typeof $.fn.sortable === 'undefined') {
                        console.warn('jQuery UI sortable not available');
                        return;
                    }
                    
                    try {
                        $('.psyem-form-fields').sortable({
                            handle: '.field-handle',
                            placeholder: 'ui-sortable-placeholder',
                            connectWith: '.psyem-form-fields',
                            tolerance: 'pointer',
                            cursor: 'move',
                            opacity: 0.8,
                            start: function(event, ui) {
                                // Visual feedback when drag starts
                                ui.item.addClass('dragging');
                                formEditor.showMessage('Reordering field... Drop to save new position.', 'info');
                            },
                            stop: function(event, ui) {
                                // Remove dragging class
                                ui.item.removeClass('dragging');
                            },
                            update: function(event, ui) {
                                // Field order changed
                                formEditor.updateFieldPositions();
                            }
                        });
                    } catch (error) {
                        console.error('Error initializing sortable:', error);
                    }
                },
                
                initDragDrop: function() {
                    // Check if jQuery UI is available
                    if (typeof $.fn.draggable === 'undefined' || typeof $.fn.droppable === 'undefined') {
                        console.warn('jQuery UI draggable/droppable not available. Using click events instead.');
                        this.initClickToAdd();
                        return;
                    }
                    
                    try {
                        $('.psyem-field-type').draggable({
                            helper: 'clone',
                            connectToSortable: '.psyem-form-fields',
                            stop: function(event, ui) {
                                var fieldType = $(ui.helper).data('field-type');
                                if (fieldType) {
                                    formEditor.addNewField(fieldType, ui.helper.closest('.psyem-form-fields'));
                                    $(ui.helper).remove();
                                }
                            }
                        });
                        
                        $('.psyem-drop-zone').droppable({
                            accept: '.psyem-field-type',
                            over: function(event, ui) {
                                $(this).addClass('drag-over');
                            },
                            out: function(event, ui) {
                                $(this).removeClass('drag-over');
                            },
                            drop: function(event, ui) {
                                $(this).removeClass('drag-over');
                                var fieldType = ui.draggable.data('field-type');
                                if (fieldType) {
                                    formEditor.addNewField(fieldType, $(this).siblings('.psyem-form-fields'));
                                }
                            }
                        });
                    } catch (error) {
                        console.error('Error initializing drag and drop:', error);
                        this.initClickToAdd();
                    }
                },
                
                initClickToAdd: function() {
                    // Fallback: click to add fields
                    $('.psyem-field-type').off('click').on('click', function() {
                        var fieldType = $(this).data('field-type');
                        var targetContainer = $('.psyem-form-fields').first();
                        if (fieldType && targetContainer.length) {
                            formEditor.addNewField(fieldType, targetContainer);
                        }
                    });
                    
                    // Add visual indication
                    $('.psyem-drop-zone').html('<p>Click on field types from the sidebar to add them here</p>');
                },
                
                bindEvents: function() {
                    // Save form configuration
                    $(document).on('click', '#psyem-save-form', function(e) {
                        e.preventDefault();
                        formEditor.saveFormConfig();
                    });
                    
                    // Reset form configuration
                    $(document).on('click', '#psyem-reset-form', function(e) {
                        e.preventDefault();
                        var confirmMessage = (typeof psyemFormEditor !== 'undefined' && psyemFormEditor.strings) 
                            ? psyemFormEditor.strings.confirm_reset 
                            : 'Are you sure you want to reset to default configuration?';
                        if (confirm(confirmMessage)) {
                            formEditor.resetFormConfig();
                        }
                    });
                    
                    // Clear all configuration
                    $(document).on('click', '#psyem-clear-config', function(e) {
                        e.preventDefault();
                        if (confirm('Are you sure you want to clear all form data and reset to default? This cannot be undone.')) {
                            formEditor.clearFormConfig();
                        }
                    });
                    
                    // Edit field
                    $(document).on('click', '.edit-field', function(e) {
                        e.preventDefault();
                        var fieldElement = $(this).closest('.psyem-form-field');
                        if (fieldElement.length) {
                            formEditor.editField(fieldElement);
                        }
                    });
                    
                    // Delete field
                    $(document).on('click', '.delete-field', function(e) {
                        e.preventDefault();
                        var confirmMessage = (typeof psyemFormEditor !== 'undefined' && psyemFormEditor.strings) 
                            ? psyemFormEditor.strings.confirm_delete 
                            : 'Are you sure you want to delete this field?';
                        if (confirm(confirmMessage)) {
                            $(this).closest('.psyem-form-field').remove();
                        }
                    });
                    
                    // Duplicate field
                    $(document).on('click', '.duplicate-field', function(e) {
                        e.preventDefault();
                        var fieldElement = $(this).closest('.psyem-form-field');
                        if (fieldElement.length) {
                            try {
                                var clonedField = fieldElement.clone();
                                var currentLabel = clonedField.find('.field-label').text();
                                clonedField.find('.field-label').text(currentLabel + ' (Copy)');
                                fieldElement.after(clonedField);
                            } catch (error) {
                                console.error('Error duplicating field:', error);
                                formEditor.showMessage('Error duplicating field', 'error');
                            }
                        }
                    });
                    
                    // Toggle step
                    $(document).on('click', '.toggle-step', function(e) {
                        e.preventDefault();
                        var stepContent = $(this).closest('.psyem-form-step').find('.psyem-step-content');
                        stepContent.slideToggle();
                        $(this).text(stepContent.is(':visible') ? 'Collapse' : 'Expand');
                    });
                    
                    // Modal events
                    $(document).on('click', '.psyem-modal-close, #cancel_field_edit', function() {
                        $('#psyem-field-editor-modal').hide();
                        formEditor.currentEditingField = null;
                    });
                    
                    $(document).on('click', '#save_field_changes', function(e) {
                        e.preventDefault();
                        formEditor.saveFieldChanges();
                    });
                    
                    // Add field option
                    $(document).on('click', '#add_field_option', function(e) {
                        e.preventDefault();
                        formEditor.addFieldOption();
                    });
                    
                    // Remove field option
                    $(document).on('click', '.remove-option', function(e) {
                        e.preventDefault();
                        $(this).closest('.field-option-item').remove();
                    });
                    
                    // Field type change
                    $(document).on('change', '#field_type', function() {
                        var fieldType = $(this).val();
                        if (['select', 'radio', 'checkbox'].includes(fieldType)) {
                            $('.field-options-group').show();
                        } else {
                            $('.field-options-group').hide();
                        }
                    });
                    
                    // Auto-generate option values from labels as user types
                    $(document).on('input', '.option-label', function() {
                        var label = $(this).val().trim();
                        var valueField = $(this).siblings('.option-value');
                        
                        if (label) {
                            // Auto-generate value from label
                            var value = label.toLowerCase()
                                .replace(/[^a-z0-9\s]/g, '') // Remove special characters
                                .replace(/\s+/g, '_') // Replace spaces with underscores
                                .replace(/_{2,}/g, '_') // Replace multiple underscores with single
                                .replace(/^_|_$/g, ''); // Remove leading/trailing underscores
                            
                            // Fallback if value becomes empty
                            if (!value) {
                                value = 'option_' + Date.now();
                            }
                            
                            valueField.val(value);
                        } else {
                            valueField.val('');
                        }
                    });
                },
                
                initColorPicker: function() {
                    if ($.fn.wpColorPicker) {
                        $('.color-picker').wpColorPicker();
                    }
                },
                
                addNewField: function(fieldType, container) {
                    var fieldData = {
                        type: fieldType,
                        name: 'field_' + fieldType + '_' + Date.now(),
                        label: this.getDefaultLabel(fieldType),
                        placeholder: 'Enter ' + fieldType,
                        description: '',
                        required: false, // New fields default to not required
                        options: []
                    };
                    
                    var fieldHtml = this.generateFieldHtml(fieldData);
                    container.append(fieldHtml);
                },
                
                getDefaultLabel: function(fieldType) {
                    var labels = {
                        'text': 'Text Input',
                        'email': 'Email Address',
                        'tel': 'Phone Number',
                        'select': 'Dropdown Selection',
                        'checkbox': 'Checkbox',
                        'radio': 'Radio Button',
                        'textarea': 'Text Area',
                        'date': 'Date',
                        'number': 'Number'
                    };
                    return labels[fieldType] || fieldType;
                },
                
                generateFieldHtml: function(fieldData) {
                    // Show required indicator if field is required
                    var requiredIndicator = fieldData.required ? '<span class="required-indicator">*</span>' : '';
                    var fieldPreview = this.generateFieldPreview(fieldData);
                    
                    return '<div class="psyem-form-field" data-field-type="' + fieldData.type + '">' +
                        '<div class="psyem-field-header">' +
                        '<span class="dashicons dashicons-move field-handle"></span>' +
                        '<span class="field-label">' + fieldData.label + '</span>' +
                        '<div class="field-actions">' +
                        requiredIndicator +
                        '<button type="button" class="button button-small edit-field">Edit</button>' +
                        '<button type="button" class="button button-small duplicate-field">Duplicate</button>' +
                        '<button type="button" class="button button-small delete-field">Delete</button>' +
                        '</div>' +
                        '</div>' +
                        '<div class="psyem-field-preview">' + fieldPreview + '</div>' +
                        '<input type="hidden" class="field-data" value="' + this.escapeHtml(JSON.stringify(fieldData)) + '" />' +
                        '</div>';
                },
                
                generateFieldPreview: function(fieldData) {
                    var preview = '';
                    
                    switch (fieldData.type) {
                        case 'text':
                        case 'email':
                        case 'tel':
                        case 'number':
                            preview = '<input type="' + fieldData.type + '" placeholder="' + fieldData.placeholder + '" disabled />';
                            break;
                        case 'textarea':
                            preview = '<textarea placeholder="' + fieldData.placeholder + '" disabled></textarea>';
                            break;
                        case 'select':
                            preview = '<div class="select-field-preview">';
                            preview += '<select disabled>';
                            preview += '<option value="">' + fieldData.placeholder + '</option>';
                            if (fieldData.options && fieldData.options.length > 0) {
                                fieldData.options.forEach(function(option) {
                                    preview += '<option value="' + this.escapeHtml(option.value) + '">' + this.escapeHtml(option.label) + '</option>';
                                }.bind(this));
                            }
                            preview += '</select></div>';
                            break;
                        case 'checkbox':
                            preview = '<label><input type="checkbox" disabled /> ' + fieldData.label + '</label>';
                            break;
                        case 'radio':
                            if (fieldData.options && fieldData.options.length > 0) {
                                fieldData.options.forEach(function(option) {
                                    preview += '<label><input type="radio" name="preview_radio" disabled /> ' + option.label + '</label><br>';
                                });
                            }
                            break;
                        case 'date':
                            preview = '<div class="date-field-preview"><input type="date" placeholder="' + fieldData.placeholder + '" disabled /></div>';
                            break;
                    }
                    
                    if (fieldData.description) {
                        preview += '<small class="field-description">' + fieldData.description + '</small>';
                    }
                    
                    return preview;
                },
                
                editField: function(fieldElement) {
                    this.currentEditingField = fieldElement;
                    var fieldData = JSON.parse(fieldElement.find('.field-data').val());
                    
                    // Populate modal form
                    $('#field_label').val(fieldData.label);
                    $('#field_placeholder').val(fieldData.placeholder);
                    $('#field_description').val(fieldData.description);
                    
                    // Handle field options
                    if (['select', 'radio', 'checkbox'].includes(fieldData.type)) {
                        $('.field-options-group').show();
                        this.populateFieldOptions(fieldData.options || []);
                    } else {
                        $('.field-options-group').hide();
                    }
                    
                    $('#psyem-field-editor-modal').show();
                },
                
                populateFieldOptions: function(options) {
                    var container = $('#field_options_container');
                    container.empty();
                    
                    options.forEach(function(option) {
                        formEditor.addFieldOption(option.value, option.label);
                    });
                    
                    if (options.length === 0) {
                        this.addFieldOption();
                    }
                },
                
                addFieldOption: function(value, label) {
                    value = value || '';
                    label = label || '';
                    
                    var optionHtml = '<div class="field-option-item">' +
                        '<input type="hidden" value="' + value + '" class="option-value" />' +
                        '<input type="text" placeholder="Enter option text (e.g., Yes, No, Maybe)" value="' + label + '" class="option-label" style="width: calc(100% - 40px);" />' +
                        '<span class="dashicons dashicons-trash remove-option"></span>' +
                        '</div>';
                    
                    $('#field_options_container').append(optionHtml);
                },
                
                saveFieldChanges: function() {
                    if (!this.currentEditingField) return;
                    
                    var fieldData = JSON.parse(this.currentEditingField.find('.field-data').val());
                    
                    // Update field data
                    fieldData.label = $('#field_label').val();
                    fieldData.placeholder = $('#field_placeholder').val();
                    fieldData.description = $('#field_description').val();
                    // Keep existing required status instead of forcing false
                    
                    // Update options - auto-generate values from labels
                    var options = [];
                    $('#field_options_container .field-option-item').each(function() {
                        var label = $(this).find('.option-label').val().trim();
                        if (label) {
                            // Auto-generate value from label: lowercase, replace spaces with underscores, remove special chars
                            var value = label.toLowerCase()
                                .replace(/[^a-z0-9\s]/g, '') // Remove special characters
                                .replace(/\s+/g, '_') // Replace spaces with underscores
                                .replace(/_{2,}/g, '_') // Replace multiple underscores with single
                                .replace(/^_|_$/g, ''); // Remove leading/trailing underscores
                            
                            // Fallback if value becomes empty
                            if (!value) {
                                value = 'option_' + (options.length + 1);
                            }
                            
                            options.push({value: value, label: label});
                            
                            // Update the hidden value field for consistency
                            $(this).find('.option-value').val(value);
                        }
                    });
                    fieldData.options = options;
                    
                    // Update field element
                    this.currentEditingField.find('.field-label').text(fieldData.label);
                    this.currentEditingField.find('.field-data').val(JSON.stringify(fieldData));
                    
                    // Update required indicator based on field's required status
                    var requiredIndicator = this.currentEditingField.find('.required-indicator');
                    if (fieldData.required && requiredIndicator.length === 0) {
                        this.currentEditingField.find('.field-actions').prepend('<span class="required-indicator">*</span>');
                    } else if (!fieldData.required && requiredIndicator.length > 0) {
                        requiredIndicator.remove();
                    }
                    
                    // Update preview
                    this.currentEditingField.find('.psyem-field-preview').html(this.generateFieldPreview(fieldData));
                    
                    $('#psyem-field-editor-modal').hide();
                    this.currentEditingField = null;
                },
                
                saveFormConfig: function() {
                    var $button = $('#psyem-save-form');
                    $button.addClass('loading').prop('disabled', true);
                    
                    var formConfig = this.collectFormData();
                    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'psyem_save_form_config',
                            nonce: '<?php echo wp_create_nonce('psyem_form_editor_nonce'); ?>',
                            form_config: JSON.stringify(formConfig)
                        },
                        success: function(response) {
                            if (response.success) {
                                formEditor.showMessage(response.data, 'success');
                            } else {
                                formEditor.showMessage(response.data, 'error');
                            }
                        },
                        error: function() {
                            formEditor.showMessage('An error occurred while saving.', 'error');
                        },
                        complete: function() {
                            $button.removeClass('loading').prop('disabled', false);
                        }
                    });
                },
                
                resetFormConfig: function() {
                    var $button = $('#psyem-reset-form');
                    $button.addClass('loading').prop('disabled', true);
                    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'psyem_reset_form_config',
                            nonce: '<?php echo wp_create_nonce('psyem_form_editor_nonce'); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                location.reload();
                            } else {
                                formEditor.showMessage(response.data, 'error');
                            }
                        },
                        error: function() {
                            formEditor.showMessage('An error occurred while resetting.', 'error');
                        },
                        complete: function() {
                            $button.removeClass('loading').prop('disabled', false);
                        }
                    });
                },
                
                clearFormConfig: function() {
                    var $button = $('#psyem-clear-config');
                    $button.addClass('loading').prop('disabled', true);
                    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'psyem_clear_form_config',
                            nonce: '<?php echo wp_create_nonce('psyem_form_editor_nonce'); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                formEditor.showMessage('Form cleared successfully! Reloading...', 'success');
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                formEditor.showMessage(response.data || 'Error clearing form', 'error');
                            }
                        },
                        error: function() {
                            formEditor.showMessage('An error occurred while clearing.', 'error');
                        },
                        complete: function() {
                            $button.removeClass('loading').prop('disabled', false);
                        }
                    });
                },
                
                collectFormData: function() {
                    var formConfig = {
                        settings: {
                            title: $('#form_title').val(),
                            description: $('#form_description').val(),
                            success_message: $('#success_message').val(),
                            notification_email: $('#notification_email').val(),
                            enable_captcha: $('#enable_captcha').is(':checked'),
                            enable_double_optin: $('#enable_double_optin').is(':checked')
                        },
                        steps: []
                    };
                    
                    $('.psyem-form-step').each(function() {
                        var stepData = {
                            title: $(this).find('.psyem-step-header h4').text(),
                            fields: []
                        };
                        
                        $(this).find('.psyem-form-field').each(function() {
                            var fieldData = JSON.parse($(this).find('.field-data').val());
                            stepData.fields.push(fieldData);
                        });
                        
                        formConfig.steps.push(stepData);
                    });
                    
                    return formConfig;
                },
                
                showMessage: function(message, type) {
                    type = type || 'success'; // Default to success
                    var messageClassMap = {
                        'success': 'success-message',
                        'error': 'error-message',
                        'info': 'info-message'
                    };
                    
                    var messageClass = messageClassMap[type] || 'success-message';
                    var messageHtml = '<div class="' + messageClass + '">' + message + '</div>';
                    
                    // Try to use the dedicated messages container first
                    var $messagesContainer = $('#psyem-form-messages');
                    if ($messagesContainer.length) {
                        $messagesContainer.html(messageHtml);
                    } else {
                        // Fallback to prepending to main container
                        $('.psyem-form-editor-container').prepend(messageHtml);
                    }
                    
                    // Auto-hide after different times based on type
                    var autoHideTime = type === 'info' ? 2000 : 4000;
                    setTimeout(function() {
                        $('.' + messageClass).fadeOut(function() {
                            $(this).remove();
                        });
                    }, autoHideTime);
                    
                    // Only scroll to top for error/success messages, not info
                    if (type !== 'info') {
                        $('html, body').animate({
                            scrollTop: 0
                        }, 300);
                    }
                },
                
                updateFieldPositions: function() {
                    // This method tracks field position changes and automatically saves them
                    console.log('Field positions updated - auto-saving...');
                    
                    // Auto-save form configuration when fields are reordered
                    setTimeout(function() {
                        formEditor.saveFormConfig();
                    }, 500); // Small delay to allow UI to settle
                },
                
                escapeHtml: function(text) {
                    var map = {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    };
                    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
                }
            };
            
            // Initialize form editor
            formEditor.init();
            
            // Make formEditor available globally
            window.psyemFormEditor = formEditor;
        });
        </script>
        <?php
    }
    
    /**
     * Render the admin page
     */
    public function render_admin_page() {
        $form_config = get_option(self::FORM_CONFIG_OPTION, $this->get_default_form_config());
        
        // Check if form config is corrupted and reset if needed
        if (!is_array($form_config) || empty($form_config['settings']) || empty($form_config['steps'])) {
            $form_config = $this->get_default_form_config();
            update_option(self::FORM_CONFIG_OPTION, $form_config);
        }
        
        // Ensure all required settings exist
        if (!isset($form_config['settings'])) {
            $form_config['settings'] = array();
        }
        
        $default_settings = array(
            'title' => __('Register For Project SAFE', 'psyeventsmanager'),
            'description' => __('Two simple steps to register for the Project SAFE program.', 'psyeventsmanager'),
            'success_message' => __('Thank you for your registration!', 'psyeventsmanager'),
            'notification_email' => get_option('admin_email'),
        );
        
        $form_config['settings'] = array_merge($default_settings, $form_config['settings']);
        
        ?>
        <div class="wrap">
            <h1><?php _e('Project SAFE Form Editor', 'psyeventsmanager'); ?></h1>
            <p><?php _e('Create and customize your Project SAFE registration forms with this intuitive form builder.', 'psyeventsmanager'); ?></p>
            
            <!-- Add success/error messages area -->
            <div id="psyem-form-messages"></div>
            
            <div class="psyem-form-editor-container">
                <div class="psyem-form-editor-sidebar">
                    <div class="psyem-sidebar-section">
                        <h3><?php _e('Form Settings', 'psyeventsmanager'); ?></h3>
                        <form id="psyem-form-settings">
                            <?php wp_nonce_field('psyem_form_editor_nonce', 'psyem_nonce'); ?>
                            
                            <div class="form-group">
                                <label for="form_title"><?php _e('Form Title', 'psyeventsmanager'); ?></label>
                                <input type="text" id="form_title" name="form_title" 
                                       value="<?php echo esc_attr($form_config['settings']['title']); ?>" 
                                       class="widefat" />
                            </div>
                            
                            <div class="form-group">
                                <label for="form_description"><?php _e('Form Description', 'psyeventsmanager'); ?></label>
                                <textarea id="form_description" name="form_description" 
                                          class="widefat" rows="3"><?php echo esc_textarea($form_config['settings']['description']); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="success_message"><?php _e('Success Message', 'psyeventsmanager'); ?></label>
                                <textarea id="success_message" name="success_message" 
                                          class="widefat" rows="3"><?php echo esc_textarea($form_config['settings']['success_message']); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="notification_email"><?php _e('Notification Email', 'psyeventsmanager'); ?></label>
                                <input type="email" id="notification_email" name="notification_email" 
                                       value="<?php echo esc_attr($form_config['settings']['notification_email']); ?>" 
                                       class="widefat" />
                            </div>
                        </form>
                    </div>
                    
                    <div class="psyem-sidebar-section">
                        <h3><?php _e('Available Field Types', 'psyeventsmanager'); ?></h3>
                        <p><small><?php _e('Drag and drop these field types into your form steps below, or click to add them.', 'psyeventsmanager'); ?></small></p>
                        <div class="psyem-available-fields">
                            <?php $this->render_available_fields(); ?>
                        </div>
                    </div>
                    
                    <div class="psyem-sidebar-section">
                        <h3><?php _e('Quick Actions', 'psyeventsmanager'); ?></h3>
                        <button type="button" class="button button-secondary widefat" id="psyem-clear-config">
                            <?php _e('Clear All & Reset', 'psyeventsmanager'); ?>
                        </button>
                        <p><small><?php _e('This will reset the form to default configuration.', 'psyeventsmanager'); ?></small></p>
                    </div>
                </div>
                
                <div class="psyem-form-editor-main">
                    <div class="psyem-form-preview-header">
                        <h3><?php _e('Form Builder', 'psyeventsmanager'); ?></h3>
                        <div class="psyem-form-actions">
                            <button type="button" class="button button-secondary" id="psyem-reset-form">
                                <?php _e('Reset to Default', 'psyeventsmanager'); ?>
                            </button>
                            <button type="button" class="button button-primary" id="psyem-save-form">
                                <?php _e('Save Changes', 'psyeventsmanager'); ?>
                            </button>
                        </div>
                    </div>
                    
                    <div class="psyem-form-steps">
                        <?php $this->render_form_steps($form_config); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Field Editor Modal -->
        <div id="psyem-field-editor-modal" class="psyem-modal" style="display: none;">
            <div class="psyem-modal-content">
                <span class="psyem-modal-close">&times;</span>
                <h3><?php _e('Edit Field Properties', 'psyeventsmanager'); ?></h3>
                <form id="psyem-field-editor-form">
                    <div class="form-group">
                        <label for="field_label"><?php _e('Field Label', 'psyeventsmanager'); ?></label>
                        <input type="text" id="field_label" name="field_label" class="widefat" />
                    </div>
                    
                    <div class="form-group">
                        <label for="field_placeholder"><?php _e('Placeholder Text', 'psyeventsmanager'); ?></label>
                        <input type="text" id="field_placeholder" name="field_placeholder" class="widefat" />
                    </div>
                    
                    <div class="form-group">
                        <label for="field_description"><?php _e('Help Text', 'psyeventsmanager'); ?></label>
                        <textarea id="field_description" name="field_description" class="widefat" rows="2"></textarea>
                    </div>
                    
                    <div class="form-group field-options-group" style="display: none;">
                        <label><?php _e('Field Options', 'psyeventsmanager'); ?></label>
                        <p><small><?php _e('Simply enter the text you want users to see. The system will automatically create the technical values.', 'psyeventsmanager'); ?></small></p>
                        <div id="field_options_container">
                            <!-- Dynamic options will be added here -->
                        </div>
                        <button type="button" class="button" id="add_field_option">
                            <?php _e('Add Option', 'psyeventsmanager'); ?>
                        </button>
                    </div>
                    
                    <div class="form-group">
                        <button type="button" class="button button-primary" id="save_field_changes">
                            <?php _e('Save Changes', 'psyeventsmanager'); ?>
                        </button>
                        <button type="button" class="button" id="cancel_field_edit">
                            <?php _e('Cancel', 'psyeventsmanager'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render available field types
     */
    private function render_available_fields() {
        $field_types = array(
            'text' => array(
                'label' => __('Text Input', 'psyeventsmanager'),
                'icon' => 'dashicons-editor-textcolor'
            ),
            'email' => array(
                'label' => __('Email', 'psyeventsmanager'),
                'icon' => 'dashicons-email'
            ),
            'tel' => array(
                'label' => __('Phone', 'psyeventsmanager'),
                'icon' => 'dashicons-phone'
            ),
            'select' => array(
                'label' => __('Dropdown', 'psyeventsmanager'),
                'icon' => 'dashicons-arrow-down-alt2'
            ),
            'checkbox' => array(
                'label' => __('Checkbox', 'psyeventsmanager'),
                'icon' => 'dashicons-yes'
            ),
            'radio' => array(
                'label' => __('Radio Button', 'psyeventsmanager'),
                'icon' => 'dashicons-marker'
            ),
            'textarea' => array(
                'label' => __('Text Area', 'psyeventsmanager'),
                'icon' => 'dashicons-text'
            ),
            'date' => array(
                'label' => __('Date Picker', 'psyeventsmanager'),
                'icon' => 'dashicons-calendar-alt'
            ),
            'number' => array(
                'label' => __('Number', 'psyeventsmanager'),
                'icon' => 'dashicons-calculator'
            ),
        );
        
        foreach ($field_types as $type => $config) {
            echo '<div class="psyem-field-type" data-field-type="' . esc_attr($type) . '">';
            echo '<span class="dashicons ' . esc_attr($config['icon']) . '"></span>';
            echo '<span class="field-label">' . esc_html($config['label']) . '</span>';
            echo '</div>';
        }
    }
    
    /**
     * Render form steps editor
     */
    private function render_form_steps($form_config) {
        foreach ($form_config['steps'] as $step_index => $step) {
            ?>
            <div class="psyem-form-step" data-step="<?php echo esc_attr($step_index); ?>">
                <div class="psyem-step-header">
                    <h4><?php echo esc_html($step['title']); ?></h4>
                    <div class="psyem-step-actions">
                        <button type="button" class="button button-small toggle-step">
                            <?php _e('Collapse', 'psyeventsmanager'); ?>
                        </button>
                    </div>
                </div>
                
                <div class="psyem-step-content">
                    <div class="psyem-form-fields" data-step="<?php echo esc_attr($step_index); ?>">
                        <?php
                        if (!empty($step['fields'])) {
                            foreach ($step['fields'] as $field_index => $field) {
                                $this->render_form_field_editor($field, $step_index, $field_index);
                            }
                        }
                        ?>
                    </div>
                    <div class="psyem-drop-zone">
                        <p><?php _e('Drag field types from the sidebar to add them to this step', 'psyeventsmanager'); ?></p>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    
    /**
     * Render individual form field editor
     */
    private function render_form_field_editor($field, $step_index, $field_index) {
        ?>
        <div class="psyem-form-field" data-step="<?php echo esc_attr($step_index); ?>" 
             data-field="<?php echo esc_attr($field_index); ?>" data-field-type="<?php echo esc_attr($field['type']); ?>">
            <div class="psyem-field-header">
                <span class="dashicons dashicons-move field-handle"></span>
                <span class="field-label"><?php echo esc_html($field['label']); ?></span>
                <div class="field-actions">
                    <?php if (!empty($field['required'])): ?>
                        <span class="required-indicator">*</span>
                    <?php endif; ?>
                    <button type="button" class="button button-small edit-field">
                        <?php _e('Edit', 'psyeventsmanager'); ?>
                    </button>
                    <button type="button" class="button button-small duplicate-field">
                        <?php _e('Duplicate', 'psyeventsmanager'); ?>
                    </button>
                    <button type="button" class="button button-small delete-field">
                        <?php _e('Delete', 'psyeventsmanager'); ?>
                    </button>
                </div>
            </div>
            
            <div class="psyem-field-preview">
                <?php $this->render_field_preview($field); ?>
            </div>
            
            <input type="hidden" class="field-data" value="<?php echo esc_attr(wp_json_encode($field)); ?>" />
        </div>
        <?php
    }
    
    /**
     * Render field preview
     */
    private function render_field_preview($field) {
        // Add safety checks
        $field_type = isset($field['type']) ? $field['type'] : 'text';
        $field_label = isset($field['label']) ? $field['label'] : '';
        $field_placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
        $field_description = isset($field['description']) ? $field['description'] : '';
        $field_options = isset($field['options']) && is_array($field['options']) ? $field['options'] : array();
        
        switch ($field_type) {
            case 'text':
            case 'email':
            case 'tel':
            case 'number':
                echo '<div class="text-field-preview">';
                echo '<input type="' . esc_attr($field_type) . '" placeholder="' . esc_attr($field_placeholder) . '" disabled style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; background: #fff; box-sizing: border-box;" />';
                echo '</div>';
                break;
                
            case 'textarea':
                echo '<div class="textarea-field-preview">';
                echo '<textarea placeholder="' . esc_attr($field_placeholder) . '" disabled style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; min-height: 80px; resize: vertical; font-size: 14px; background: #fff; box-sizing: border-box; font-family: inherit;"></textarea>';
                echo '</div>';
                break;
                
            case 'select':
                echo '<div class="select-field-preview">';
                echo '<select disabled>';
                echo '<option value="">' . esc_html($field_placeholder) . '</option>';
                
                if (!empty($field_options)) {
                    foreach ($field_options as $option) {
                        $option_value = isset($option['value']) ? $option['value'] : '';
                        $option_label = isset($option['label']) ? $option['label'] : '';
                        
                        if (!empty($option_label)) {
                            echo '<option value="' . esc_attr($option_value) . '">' . esc_html($option_label) . '</option>';
                        }
                    }
                } else {
                    echo '<option value="option1">Sample Option 1</option>';
                    echo '<option value="option2">Sample Option 2</option>';
                }
                echo '</select>';
                echo '</div>';
                break;
                
            case 'checkbox':
                echo '<div class="checkbox-field-preview" style="padding: 10px; background: #f9f9f9; border-radius: 4px;">';
                if (!empty($field_options)) {
                    foreach ($field_options as $option) {
                        $option_value = isset($option['value']) ? $option['value'] : '';
                        $option_label = isset($option['label']) ? $option['label'] : '';
                        
                        if (!empty($option_label)) {
                            echo '<label style="display: block; margin-bottom: 8px; cursor: pointer; padding: 5px;">';
                            echo '<input type="checkbox" value="' . esc_attr($option_value) . '" disabled style="margin-right: 8px; accent-color: #0073aa;" /> ';
                            echo '<span style="font-size: 14px;">' . esc_html($option_label) . '</span>';
                            echo '</label>';
                        }
                    }
                } else {
                    echo '<label style="display: block; margin-bottom: 8px; cursor: pointer; padding: 5px;">';
                    echo '<input type="checkbox" disabled style="margin-right: 8px; accent-color: #0073aa;" /> ';
                    echo '<span style="font-size: 14px;">' . esc_html($field_label) . '</span>';
                    echo '</label>';
                }
                echo '</div>';
                break;
                
            case 'radio':
                echo '<div class="radio-field-preview" style="padding: 10px; background: #f9f9f9; border-radius: 4px;">';
                if (!empty($field_options)) {
                    $radio_name = 'preview_radio_' . uniqid();
                    foreach ($field_options as $option) {
                        $option_value = isset($option['value']) ? $option['value'] : '';
                        $option_label = isset($option['label']) ? $option['label'] : '';
                        
                        if (!empty($option_label)) {
                            echo '<label style="display: block; margin-bottom: 8px; cursor: pointer; padding: 5px;">';
                            echo '<input type="radio" name="' . esc_attr($radio_name) . '" value="' . esc_attr($option_value) . '" disabled style="margin-right: 8px; accent-color: #0073aa;" /> ';
                            echo '<span style="font-size: 14px;">' . esc_html($option_label) . '</span>';
                            echo '</label>';
                        }
                    }
                } else {
                    echo '<label style="display: block; margin-bottom: 8px; cursor: pointer; padding: 5px;">';
                    echo '<input type="radio" disabled style="margin-right: 8px; accent-color: #0073aa;" /> ';
                    echo '<span style="font-size: 14px;">Sample Option 1</span></label>';
                    echo '<label style="display: block; margin-bottom: 8px; cursor: pointer; padding: 5px;">';
                    echo '<input type="radio" disabled style="margin-right: 8px; accent-color: #0073aa;" /> ';
                    echo '<span style="font-size: 14px;">Sample Option 2</span></label>';
                }
                echo '</div>';
                break;
                
            case 'date':
                echo '<div class="date-field-preview">';
                echo '<input type="date" disabled style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; background: #fff; box-sizing: border-box;" />';
                echo '</div>';
                break;
                
            default:
                echo '<div class="default-field-preview">';
                echo '<input type="text" placeholder="' . esc_attr($field_placeholder) . '" disabled style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; background: #fff; box-sizing: border-box;" />';
                echo '</div>';
                break;
        }
        
        if (!empty($field_description)) {
            echo '<div class="field-description" style="margin-top: 5px; font-size: 12px; color: #666; font-style: italic;">' . esc_html($field_description) . '</div>';
        }
    }
    
    /**
     * AJAX handler for saving form configuration
     */
    public function ajax_save_form_config() {
        check_ajax_referer('psyem_form_editor_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'psyeventsmanager'));
        }
        
        $form_config = json_decode(stripslashes($_POST['form_config']), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            wp_send_json_error(__('Invalid JSON data', 'psyeventsmanager'));
        }
        
        $sanitized_config = $this->sanitize_form_config($form_config);
        $saved = update_option(self::FORM_CONFIG_OPTION, $sanitized_config);
        
        if ($saved) {
            // Log the save action
            error_log('Project SAFE form configuration saved by user: ' . get_current_user_id());
            wp_send_json_success(__('Form configuration saved successfully!', 'psyeventsmanager'));
        } else {
            wp_send_json_error(__('Failed to save form configuration', 'psyeventsmanager'));
        }
    }
    
    /**
     * AJAX handler for resetting form configuration
     */
    public function ajax_reset_form_config() {
        check_ajax_referer('psyem_form_editor_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'psyeventsmanager'));
        }
        
        $default_config = $this->get_default_form_config();
        $reset = update_option(self::FORM_CONFIG_OPTION, $default_config);
        
        if ($reset) {
            error_log('Project SAFE form configuration reset by user: ' . get_current_user_id());
            wp_send_json_success(array(
                'message' => __('Form configuration reset to default', 'psyeventsmanager'),
                'config' => $default_config
            ));
        } else {
            wp_send_json_error(__('Failed to reset form configuration', 'psyeventsmanager'));
        }
    }
    
    /**
     * AJAX handler for adding form field
     */
    public function ajax_add_form_field() {
        check_ajax_referer('psyem_form_editor_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'psyeventsmanager'));
        }
        
        $field_type = sanitize_text_field($_POST['field_type']);
        $step_index = intval($_POST['step_index']);
        
        // Generate new field data
        $field_data = array(
            'type' => $field_type,
            'name' => 'field_' . $field_type . '_' . time(),
            'label' => $this->get_default_field_label($field_type),
            'placeholder' => 'Enter ' . $field_type,
            'description' => '',
            'required' => false,
            'options' => array()
        );
        
        wp_send_json_success(array(
            'field_data' => $field_data,
            'message' => __('Field added successfully', 'psyeventsmanager')
        ));
    }
    
    /**
     * AJAX handler for deleting form field
     */
    public function ajax_delete_form_field() {
        check_ajax_referer('psyem_form_editor_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'psyeventsmanager'));
        }
        
        wp_send_json_success(__('Field deleted successfully', 'psyeventsmanager'));
    }
    
    /**
     * AJAX handler for clearing form configuration
     */
    public function ajax_clear_form_config() {
        check_ajax_referer('psyem_form_editor_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'psyeventsmanager'));
        }
        
        $default_config = $this->get_default_form_config();
        $cleared = update_option(self::FORM_CONFIG_OPTION, $default_config);
        
        if ($cleared) {
            error_log('Project SAFE form configuration cleared by user: ' . get_current_user_id());
            wp_send_json_success(array(
                'message' => __('Form configuration cleared and reset to default', 'psyeventsmanager'),
                'config' => $default_config
            ));
        } else {
            wp_send_json_error(__('Failed to clear form configuration', 'psyeventsmanager'));
        }
    }
    
    /**
     * Get default field label
     */
    private function get_default_field_label($field_type) {
        $labels = array(
            'text' => __('Text Input', 'psyeventsmanager'),
            'email' => __('Email Address', 'psyeventsmanager'),
            'tel' => __('Phone Number', 'psyeventsmanager'),
            'select' => __('Dropdown Selection', 'psyeventsmanager'),
            'checkbox' => __('Checkbox Option', 'psyeventsmanager'),
            'radio' => __('Radio Button', 'psyeventsmanager'),
            'textarea' => __('Text Area', 'psyeventsmanager'),
            'date' => __('Date Field', 'psyeventsmanager'),
            'number' => __('Number Input', 'psyeventsmanager'),
        );
        
        return isset($labels[$field_type]) ? $labels[$field_type] : ucfirst($field_type);
    }
    
    /**
     * Sanitize form configuration data
     */
    public function sanitize_form_config($config) {
        $sanitized = array();
        
        // Sanitize settings
        $sanitized['settings'] = array(
            'title' => sanitize_text_field($config['settings']['title'] ?? ''),
            'description' => sanitize_textarea_field($config['settings']['description'] ?? ''),
            'success_message' => sanitize_textarea_field($config['settings']['success_message'] ?? ''),
            'notification_email' => sanitize_email($config['settings']['notification_email'] ?? ''),
        );
        
        // Sanitize steps
        $sanitized['steps'] = array();
        if (isset($config['steps']) && is_array($config['steps'])) {
            foreach ($config['steps'] as $step) {
                $sanitized_step = array(
                    'title' => sanitize_text_field($step['title'] ?? ''),
                    'fields' => array()
                );
                
                if (isset($step['fields']) && is_array($step['fields'])) {
                    foreach ($step['fields'] as $field) {
                        $sanitized_step['fields'][] = $this->sanitize_field_config($field);
                    }
                }
                
                $sanitized['steps'][] = $sanitized_step;
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Sanitize individual field configuration
     */
    private function sanitize_field_config($field) {
        $allowed_types = array('text', 'email', 'tel', 'select', 'checkbox', 'radio', 'textarea', 'date', 'number');
        
        $sanitized = array(
            'type' => in_array($field['type'] ?? '', $allowed_types) ? $field['type'] : 'text',
            'name' => sanitize_key($field['name'] ?? ''),
            'label' => sanitize_text_field($field['label'] ?? ''),
            'placeholder' => sanitize_text_field($field['placeholder'] ?? ''),
            'description' => sanitize_text_field($field['description'] ?? ''),
            'required' => (bool) ($field['required'] ?? false),
            'options' => array()
        );
        
        // Sanitize options for select, radio, checkbox fields
        if (isset($field['options']) && is_array($field['options'])) {
            foreach ($field['options'] as $option) {
                $sanitized['options'][] = array(
                    'value' => sanitize_key($option['value'] ?? ''),
                    'label' => sanitize_text_field($option['label'] ?? '')
                );
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Get default form configuration
     */
    private function get_default_form_config() {
        return array(
            'settings' => array(
                'title' => __('Register For Project SAFE', 'psyeventsmanager'),
                'description' => __('Two simple steps to register for the Project SAFE program. All personal details will be kept strictly confidential.', 'psyeventsmanager'),
                'success_message' => __('Thank you for your registration! We will put you in our first priority list. You will be notified by email or SMS for successful registration.', 'psyeventsmanager'),
                'notification_email' => get_option('admin_email'),
            ),
            'steps' => array(
                array(
                    'title' => __('Participant Information', 'psyeventsmanager'),
                    'fields' => array(
                        array(
                            'type' => 'text',
                            'name' => 'field_first_name',
                            'label' => __('First Name (Same with HKID)', 'psyeventsmanager'),
                            'placeholder' => __('First Name (Same with HKID)', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array()
                        ),
                        array(
                            'type' => 'text',
                            'name' => 'field_last_name',
                            'label' => __('Last Name (Same with HKID)', 'psyeventsmanager'),
                            'placeholder' => __('Last Name (Same with HKID)', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array()
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'field_gender',
                            'label' => __('Gender', 'psyeventsmanager'),
                            'placeholder' => __('Gender', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array(
                                array('value' => 'female', 'label' => __('Female', 'psyeventsmanager')),
                                array('value' => 'male', 'label' => __('Male', 'psyeventsmanager')),
                            )
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'field_day_of_birth',
                            'label' => __('Day of Birth', 'psyeventsmanager'),
                            'placeholder' => __('Day of Birth', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array_map(function($d) { return array('value' => $d, 'label' => $d); }, range(1, 31))
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'field_month_of_birth',
                            'label' => __('Month of Birth', 'psyeventsmanager'),
                            'placeholder' => __('Month of Birth', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array(
                                array('value' => '1', 'label' => __('January', 'psyeventsmanager')),
                                array('value' => '2', 'label' => __('February', 'psyeventsmanager')),
                                array('value' => '3', 'label' => __('March', 'psyeventsmanager')),
                                array('value' => '4', 'label' => __('April', 'psyeventsmanager')),
                                array('value' => '5', 'label' => __('May', 'psyeventsmanager')),
                                array('value' => '6', 'label' => __('June', 'psyeventsmanager')),
                                array('value' => '7', 'label' => __('July', 'psyeventsmanager')),
                                array('value' => '8', 'label' => __('August', 'psyeventsmanager')),
                                array('value' => '9', 'label' => __('September', 'psyeventsmanager')),
                                array('value' => '10', 'label' => __('October', 'psyeventsmanager')),
                                array('value' => '11', 'label' => __('November', 'psyeventsmanager')),
                                array('value' => '12', 'label' => __('December', 'psyeventsmanager')),
                            )
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'field_year_of_birth',
                            'label' => __('Year of Birth', 'psyeventsmanager'),
                            'placeholder' => __('Year of Birth', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array_map(
                                function($y) { return array('value' => $y, 'label' => $y); },
                                range(2007, 1925, -1)
                            )
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'field_sexual_experience',
                            'label' => __('Do you have any sexual experience?', 'psyeventsmanager'),
                            'placeholder' => __('Do you have any sexual experience?', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array(
                                array('value' => 'yes', 'label' => __('Yes', 'psyeventsmanager')),
                                array('value' => 'no', 'label' => __('No', 'psyeventsmanager')),
                            )
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'field_cervical_screening',
                            'label' => __('Have you ever had any cervical screening in the last 3 years?', 'psyeventsmanager'),
                            'placeholder' => __('Have you ever had any cervical screening in the last 3 years?', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array(
                                array('value' => 'yes', 'label' => __('Yes', 'psyeventsmanager')),
                                array('value' => 'no', 'label' => __('No', 'psyeventsmanager')),
                            )
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'field_undergoing_treatment',
                            'label' => __('Are you undergoing treatment for CIN or cervical cancer?', 'psyeventsmanager'),
                            'placeholder' => __('Are you undergoing treatment for CIN or cervical cancer?', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array(
                                array('value' => 'yes', 'label' => __('Yes', 'psyeventsmanager')),
                                array('value' => 'no', 'label' => __('No', 'psyeventsmanager')),
                            )
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'field_received_hpv',
                            'label' => __('Have you ever received HPV vaccine?', 'psyeventsmanager'),
                            'placeholder' => __('Have you ever received HPV vaccine?', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array(
                                array('value' => 'yes', 'label' => __('Yes', 'psyeventsmanager')),
                                array('value' => 'no', 'label' => __('No', 'psyeventsmanager')),
                            )
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'field_pregnant',
                            'label' => __('Are you pregnant?', 'psyeventsmanager'),
                            'placeholder' => __('Are you pregnant?', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array(
                                array('value' => 'yes', 'label' => __('Yes', 'psyeventsmanager')),
                                array('value' => 'no', 'label' => __('No', 'psyeventsmanager')),
                            )
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'field_hysterectomy',
                            'label' => __('Did you have a hysterectomy?', 'psyeventsmanager'),
                            'placeholder' => __('Did you have a hysterectomy?', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array(
                                array('value' => 'yes', 'label' => __('Yes', 'psyeventsmanager')),
                                array('value' => 'no', 'label' => __('No', 'psyeventsmanager')),
                            )
                        ),
                    )
                ),
                array(
                    'title' => __('Contact Information', 'psyeventsmanager'),
                    'fields' => array(
                        array(
                            'type' => 'tel',
                            'name' => 'field_phone',
                            'label' => __('Phone Number', 'psyeventsmanager'),
                            'placeholder' => __('Phone Number', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array()
                        ),
                        array(
                            'type' => 'email',
                            'name' => 'field_email',
                            'label' => __('Email Address', 'psyeventsmanager'),
                            'placeholder' => __('Email Address', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array()
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'field_region',
                            'label' => __('Region', 'psyeventsmanager'),
                            'placeholder' => __('Region', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array(
                                array('value' => 'Hong Kong Island', 'label' => __('Hong Kong Island', 'psyeventsmanager')),
                                array('value' => 'Kowloon', 'label' => __('Kowloon', 'psyeventsmanager')),
                                array('value' => 'New Territories', 'label' => __('New Territories', 'psyeventsmanager')),
                            )
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'field_district',
                            'label' => __('District', 'psyeventsmanager'),
                            'placeholder' => __('District', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array(
                                array('value' => 'Central and Western', 'label' => __('Central and Western', 'psyeventsmanager')),
                                array('value' => 'Eastern', 'label' => __('Eastern', 'psyeventsmanager')),
                                array('value' => 'Southern', 'label' => __('Southern', 'psyeventsmanager')),
                                array('value' => 'Wan Chai', 'label' => __('Wan Chai', 'psyeventsmanager')),
                                array('value' => 'Sham Shui Po', 'label' => __('Sham Shui Po', 'psyeventsmanager')),
                                array('value' => 'Kowloon City', 'label' => __('Kowloon City', 'psyeventsmanager')),
                                array('value' => 'Kwun Tong', 'label' => __('Kwun Tong', 'psyeventsmanager')),
                                array('value' => 'Wong Tai Sin', 'label' => __('Wong Tai Sin', 'psyeventsmanager')),
                                array('value' => 'Yau Tsim Mong', 'label' => __('Yau Tsim Mong', 'psyeventsmanager')),
                                array('value' => 'Islands', 'label' => __('Islands', 'psyeventsmanager')),
                                array('value' => 'Kwai Tsing', 'label' => __('Kwai Tsing', 'psyeventsmanager')),
                                array('value' => 'North', 'label' => __('North', 'psyeventsmanager')),
                                array('value' => 'Sai Kung', 'label' => __('Sai Kung', 'psyeventsmanager')),
                                array('value' => 'Sha Tin', 'label' => __('Sha Tin', 'psyeventsmanager')),
                                array('value' => 'Tai Po', 'label' => __('Tai Po', 'psyeventsmanager')),
                                array('value' => 'Tsuen Wan', 'label' => __('Tsuen Wan', 'psyeventsmanager')),
                                array('value' => 'Tuen Mun', 'label' => __('Tuen Mun', 'psyeventsmanager')),
                                array('value' => 'Yuen Long', 'label' => __('Yuen Long', 'psyeventsmanager')),
                            )
                        ),
                        array(
                            'type' => 'text',
                            'name' => 'field_address',
                            'label' => __('Address', 'psyeventsmanager'),
                            'placeholder' => __('Address', 'psyeventsmanager'),
                            'description' => '',
                            'required' => true,
                            'options' => array()
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'field_source',
                            'label' => __('How have you heard about this study?', 'psyeventsmanager'),
                            'placeholder' => __('How have you heard about this study?', 'psyeventsmanager'),
                            'description' => '',
                            'required' => false,
                            'options' => array(
                                array('value' => 'Karen Leung Foundation Website', 'label' => __('Karen Leung Foundation Website', 'psyeventsmanager')),
                                array('value' => 'PHASE Scientific', 'label' => __('PHASE Scientific', 'psyeventsmanager')),
                                array('value' => 'Social Media', 'label' => __('Social Media (eg. Facebook, Instagram, etc)', 'psyeventsmanager')),
                                array('value' => 'School News', 'label' => __('School News', 'psyeventsmanager')),
                                array('value' => 'Health Talk by Karen Leung Foundation', 'label' => __('Health Talk by Karen Leung Foundation', 'psyeventsmanager')),
                            )
                        ),
                    )
                )
            )
        );
    }
    
    /**
     * Get form configuration for frontend use
     */
    public static function get_form_config() {
        return get_option(self::FORM_CONFIG_OPTION, array());
    }
}

// Initialize the form editor
new PsyemProjectSafeFormEditor(); 