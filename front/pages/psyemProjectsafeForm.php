<?php 
// Get dynamic form configuration
$form_config = get_option('psyem_project_safe_form_config', array());

// Function to get dynamic field label
function psyem_get_dynamic_field_label($field_name, $default_label, $form_config) {
    if (empty($form_config['steps'])) {
        return __($default_label, 'psyeventsmanager');
    }
    
    foreach ($form_config['steps'] as $step) {
        if (!empty($step['fields'])) {
            foreach ($step['fields'] as $field) {
                if (isset($field['name']) && $field['name'] === $field_name) {
                    return !empty($field['label']) ? $field['label'] : __($default_label, 'psyeventsmanager');
                }
            }
        }
    }
    
    return __($default_label, 'psyeventsmanager');
}

// Function to get dynamic field placeholder
function psyem_get_dynamic_field_placeholder($field_name, $default_placeholder, $form_config) {
    if (empty($form_config['steps'])) {
        return __($default_placeholder, 'psyeventsmanager');
    }
    
    foreach ($form_config['steps'] as $step) {
        if (!empty($step['fields'])) {
            foreach ($step['fields'] as $field) {
                if (isset($field['name']) && $field['name'] === $field_name) {
                    return !empty($field['placeholder']) ? $field['placeholder'] : __($default_placeholder, 'psyeventsmanager');
                }
            }
        }
    }
    
    return __($default_placeholder, 'psyeventsmanager');
}

// Function to check if we should use dynamic form rendering
function psyem_should_use_dynamic_form($form_config) {
    // Use dynamic if we have form config and it's properly structured
    return !empty($form_config) && !empty($form_config['steps']) && is_array($form_config['steps']);
}

// Function to render dynamic form
function psyem_render_dynamic_form($form_config, $projectsafe_type = 'project-safe') {
    if (!psyem_should_use_dynamic_form($form_config)) {
        return false; // Fallback to hardcoded form
    }
    
    ob_start();
    ?>
    <div id="content-area" class="text-start psyemProjectSafeCont" style="display: none;">
        <div class="region region-content">
            <article class="node node-project-teal-form node-project-teal node-teal-form">
                <div class="node-inner">
                    <div class="teal__container teal__container--form">
                        <header class="node-teal-form__header">
                            <h1 class="node-teal-form__title">
                                <?= !empty($form_config['settings']['title']) ? esc_html($form_config['settings']['title']) : __('Register For Project SAFE', 'psyeventsmanager') ?>
                            </h1>
                            <div class="node-teal-form__body">
                                <p>
                                    <?= !empty($form_config['settings']['description']) ? esc_html($form_config['settings']['description']) : __('Two simple steps to register', 'psyeventsmanager') ?>
                                </p>
                            </div>
                        </header>

                        <form class="teal-form mb-0 hideThankyouCont">
                            <ul class="teal-form__steps">
                                <?php foreach ($form_config['steps'] as $step_index => $step): ?>
                                    <li data-step="<?= $step_index + 1 ?>" class="<?= $step_index === 0 ? 'active' : '' ?>">
                                        <span>
                                            <?= ($step_index + 1) . '. ' . esc_html($step['title']) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </form>

                        <?php foreach ($form_config['steps'] as $step_index => $step): ?>
                            <div data-step="<?= $step_index + 1 ?>" class="teal-form__content <?= $step_index === 0 ? 'active' : '' ?> <?= $step_index === 0 ? 'one1' : 'two2' ?> hideThankyouCont">
                                <form class="teal-form mt-0" id="psyem<?= $step_index === 0 ? 'First' : 'Second' ?>StepForm">
                                    <div class="teal-form__content-inner">
                                        <?php if ($step_index === 0): ?>
                                            <p class="teal-form__intro">
                                                <input type="hidden" name="field_projectsafe_type" value="<?= esc_attr($projectsafe_type) ?>" />
                                                <?= __('Please fill in the following information if you would like join the project. <br>All personal details will be kept strictly confidential', 'psyeventsmanager') ?>
                                                <br>
                                                <span class="req-fields-info text-danger">
                                                    * <?= __('Required field', 'psyeventsmanager') ?>
                                                </span>
                                            </p>
                                        <?php else: ?>
                                            <p class="teal-form__intro">
                                                <?= __('Step 2: Please provide your contact information', 'psyeventsmanager') ?>
                                                <br>
                                                <span class="req-fields-info text-danger">
                                                    * <?= __('Required field', 'psyeventsmanager') ?>
                                                </span>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <div class="form-teal__fields">
                                            <?php 
                                            // Render fields in the order they appear in form config
                                            psyem_render_dynamic_step_fields($step['fields']); 
                                            ?>
                                        </div>
                                        
                                        <?php if ($step_index === 0): ?>
                                            <div class="form-item">
                                                <button type="button" class="btn-teal" id="psyemGoToSecondStep">
                                                    <?= __('Next', 'psyeventsmanager') ?>
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <div class="form-item">
                                                <button type="button" class="btn-teal-secondary" id="psyemGoToFirstStep">
                                                    <?= __('Back', 'psyeventsmanager') ?>
                                                </button>
                                                <button type="submit" class="btn-teal" id="psyemRegisterProjectSafe">
                                                    <?= __('Submit', 'psyeventsmanager') ?>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </form>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Thank you message -->
                        <div class="teal-form__content thankyou showThankyouCont" style="display: none;">
                            <div class="teal-form__content-inner">
                                <h2><?= __('Thank you!', 'psyeventsmanager') ?></h2>
                                <p><?= !empty($form_config['settings']['success_message']) ? esc_html($form_config['settings']['success_message']) : __('Thank you for your registration!', 'psyeventsmanager') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
    
    <?php
    return ob_get_clean();
}

// Function to render dynamic step fields
function psyem_render_dynamic_step_fields($fields) {
    foreach ($fields as $field) {
        psyem_render_dynamic_field($field);
    }
}

// Function to render individual dynamic field
function psyem_render_dynamic_field($field) {
    $field_name = $field['name'];
    $field_type = $field['type'];
    $field_label = $field['label'];
    $field_placeholder = $field['placeholder'];
    $field_options = isset($field['options']) ? $field['options'] : array();
    $is_required = isset($field['required']) ? $field['required'] : false;
    $required_attr = $is_required ? 'required' : '';
    $required_star = $is_required ? '<span class="text-danger">*</span>' : '';
    
    switch ($field_type) {
        case 'text':
        case 'email':
        case 'tel':
            $input_type = $field_type === 'tel' ? 'text' : $field_type;
            $css_class = $field_type === 'tel' ? 'strict_integer strict_phone strict_space' : '';
            ?>
            <div class="form-item form-item--text half">
                <input id="<?= esc_attr($field_name) ?>" <?= $required_attr ?> type="<?= esc_attr($input_type) ?>" 
                       name="<?= esc_attr($field_name) ?>" class="<?= esc_attr($css_class) ?>"
                       placeholder="<?= esc_attr($field_placeholder) ?>" />
                <label for="<?= esc_attr($field_name) ?>">
                    <?= esc_html($field_label) ?><?= $required_star ?>
                </label>
            </div>
            <?php
            break;
            
        case 'date':
            ?>
            <div class="form-item form-item--date half">
                <input id="<?= esc_attr($field_name) ?>" <?= $required_attr ?> type="date" 
                       name="<?= esc_attr($field_name) ?>" 
                       placeholder="<?= esc_attr($field_placeholder) ?>" />
                <label for="<?= esc_attr($field_name) ?>">
                    <?= esc_html($field_label) ?><?= $required_star ?>
                </label>
            </div>
            <?php
            break;
            
        case 'select':
            $css_class = '';
            ?>
            <div class="form-item <?= esc_attr($css_class) ?>">
                <select id="<?= esc_attr($field_name) ?>" <?= $required_attr ?> name="<?= esc_attr($field_name) ?>">
                    <option value="">
                        <?= esc_html($field_placeholder) ?><?= $required_star ?>
                    </option>
                    <?php
                    // Handle special cases for regions and other select fields
                    if (function_exists('psyem_GetPreviousYearsFromYear') && $field_name === 'field_some_year_field') {
                        foreach (psyem_GetPreviousYearsFromYear((date("Y") - 17)) as $year) {
                                echo '<option value="' . esc_attr($year) . '">' . esc_html($year) . '</option>';
                            }
                        } else {
                            // Fallback years
                            for ($year = date("Y") - 17; $year >= date("Y") - 60; $year--) {
                                echo '<option value="' . esc_attr($year) . '">' . esc_html($year) . '</option>';
                            }
                        }
                    } elseif ($field_name === 'field_region') {
                        if (function_exists('psyem_GetAllRegions')) {
                            foreach (psyem_GetAllRegions() as $region) {
                                echo '<option value="' . esc_attr($region) . '">' . esc_html(__($region, 'psyeventsmanager')) . '</option>';
                            }
                        } else {
                            // Fallback regions
                            $regions = array('Hong Kong Island', 'Kowloon', 'New Territories');
                            foreach ($regions as $region) {
                                echo '<option value="' . esc_attr($region) . '">' . esc_html(__($region, 'psyeventsmanager')) . '</option>';
                            }
                        }
                    } elseif ($field_name === 'field_district') {
                        if (function_exists('psyem_GetAllDistricts')) {
                            foreach (psyem_GetAllDistricts() as $district) {
                                echo '<option value="' . esc_attr($district) . '">' . esc_html(__($district, 'psyeventsmanager')) . '</option>';
                            }
                        } else {
                            // Fallback districts
                            $districts = array(
                                'Central and Western', 'Eastern', 'Southern', 'Wan Chai',
                                'Sham Shui Po', 'Kowloon City', 'Kwun Tong', 'Wong Tai Sin', 'Yau Tsim Mong',
                                'Islands', 'Kwai Tsing', 'North', 'Sai Kung', 'Sha Tin', 'Tai Po', 'Tsuen Wan', 'Tuen Mun', 'Yuen Long'
                            );
                            foreach ($districts as $district) {
                                echo '<option value="' . esc_attr($district) . '">' . esc_html(__($district, 'psyeventsmanager')) . '</option>';
                            }
                        }
                    } else {
                        // Use field options from form config
                        foreach ($field_options as $option) {
                            $option_value = isset($option['value']) ? $option['value'] : '';
                            $option_label = isset($option['label']) ? $option['label'] : '';
                            if (!empty($option_value) && !empty($option_label)) {
                                echo '<option value="' . esc_attr($option_value) . '">' . esc_html($option_label) . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
                <label for="<?= esc_attr($field_name) ?>">
                    <?= esc_html($field_label) ?><?= $required_star ?>
                </label>
            </div>
            <?php
            break;
            
        case 'textarea':
            ?>
            <div class="form-item">
                <textarea id="<?= esc_attr($field_name) ?>" <?= $required_attr ?> name="<?= esc_attr($field_name) ?>"
                          placeholder="<?= esc_attr($field_placeholder) ?>"></textarea>
                <label for="<?= esc_attr($field_name) ?>">
                    <?= esc_html($field_label) ?><?= $required_star ?>
                </label>
            </div>
            <?php
            break;
            
        default:
            // Default to text input
            ?>
            <div class="form-item">
                <input id="<?= esc_attr($field_name) ?>" <?= $required_attr ?> type="text" 
                       name="<?= esc_attr($field_name) ?>"
                       placeholder="<?= esc_attr($field_placeholder) ?>" />
                <label for="<?= esc_attr($field_name) ?>">
                    <?= esc_html($field_label) ?><?= $required_star ?>
                </label>
            </div>
            <?php
            break;
    }
}

// Check if we should use dynamic form
if (psyem_should_use_dynamic_form($form_config)) {
    // Render dynamic form and return early
    echo psyem_render_dynamic_form($form_config, $projectsafe_type ?? 'project-safe');
    return;
}

// If dynamic form is not available, fall back to hardcoded form below
ob_start(); ?>
<div id="content-area" class="text-start psyemProjectSafeCont" style="display: none;">
    <div class="region region-content">
        <article class="node node-project-teal-form node-project-teal node-teal-form">
            <div class="node-inner">
                <div class="teal__container teal__container--form">
                    <header class="node-teal-form__header">
                        <h1 class="node-teal-form__title">
                            <?= !empty($form_config['settings']['title']) ? esc_html($form_config['settings']['title']) : __('Register For Project SAFE', 'psyeventsmanager') ?>
                        </h1>
                        <div class="node-teal-form__body">
                            <p>
                                <?= !empty($form_config['settings']['description']) ? esc_html($form_config['settings']['description']) : __('Two simple steps to register', 'psyeventsmanager') ?>
                            </p>
                        </div>
                    </header>

                    <form class="teal-form mb-0 hideThankyouCont">
                        <ul class="teal-form__steps">
                            <li data-step="1" class="active">
                                <span>
                                    1. <?= __('Participant Information', 'psyeventsmanager') ?>
                                </span>
                            </li>
                            <li data-step="2" class="">
                                <span>
                                    2. <?= __('Contact information', 'psyeventsmanager') ?>
                                </span>
                            </li>
                        </ul>
                    </form>

                    <div data-step="1" class="teal-form__content active one1 hideThankyouCont">
                        <form class="teal-form mt-0" id="psyemFirstStepForm">
                            <div class="teal-form__content-inner">
                                <p class="teal-form__intro">
                                    <input type="hidden" name="field_projectsafe_type" value="<?= (isset($projectsafe_type) && !empty($projectsafe_type)) ? $projectsafe_type : 'project-safe' ?>" />
                                    <?= __('Please fill in the following information if you would like join the project. <br>All personal details will be kept strictly confidential', 'psyeventsmanager') ?>
                                    <br>
                                    <span class="req-fields-info text-danger">
                                        * <?= __('Required field', 'psyeventsmanager') ?>
                                    </span>
                                </p>
                                <div class="form-teal__fields">
                                    <div class="form-item form-item--text half">
                                        <input id="field_first_name" required type="text" name="field_first_name"
                                            placeholder="<?= esc_attr(psyem_get_dynamic_field_placeholder('field_first_name', 'First Name (Same with HKID)', $form_config)) ?>" />
                                        <label for="field_first_name">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_first_name', 'First Name (Same with HKID)', $form_config)) ?>
                                            <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item half">
                                        <input id="field_last_name" required type="text" name="field_last_name"
                                            placeholder="<?= esc_attr(psyem_get_dynamic_field_placeholder('field_last_name', 'Last Name (Same with HKID)', $form_config)) ?>" />
                                        <label for="field_last_name">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_last_name', 'Last Name (Same with HKID)', $form_config)) ?>
                                            <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item">
                                        <select id="field_gender" required name="field_gender">
                                            <option value="">
                                                <?= esc_html(psyem_get_dynamic_field_placeholder('field_gender', 'Gender', $form_config)) ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="female">
                                                <?= __('Female', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="male">
                                                <?= __('Male', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_gender"><?= esc_html(psyem_get_dynamic_field_label('field_gender', 'Gender', $form_config)) ?><span class="text-danger">*</span></label>
                                    </div>
                                    <div class="form-item form-item--date half">
                                        <input id="field_date_of_birth" required type="date" 
                                               name="field_date_of_birth" 
                                               placeholder="<?= esc_attr(psyem_get_dynamic_field_placeholder('field_date_of_birth', 'Select your date of birth', $form_config)) ?>" />
                                        <label for="field_date_of_birth">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_date_of_birth', 'Date of Birth', $form_config)) ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item">
                                        <select required name="field_sexual_experience">
                                            <option value="">
                                                <?= esc_html(psyem_get_dynamic_field_placeholder('field_sexual_experience', 'Do you have any sexual experience?', $form_config)) ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="yes">
                                                <?= __('Yes', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="no">
                                                <?= __('No', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_sexual_experience">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_sexual_experience', 'Do you have any sexual experience?', $form_config)) ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item">
                                        <select required name="field_cervical_screening">
                                            <option value="">
                                                <?= esc_html(psyem_get_dynamic_field_placeholder('field_cervical_screening', 'Have you ever had any cervical screening in the last 3 years?', $form_config)) ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="yes">
                                                <?= __('Yes', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="no">
                                                <?= __('No', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_cervical_screening">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_cervical_screening', 'Have you ever had any cervical screening in the last 3 years?', $form_config)) ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item">
                                        <select required name="field_undergoing_treatment">
                                            <option value="">
                                                <?= esc_html(psyem_get_dynamic_field_placeholder('field_undergoing_treatment', 'Are you undergoing treatment for CIN or cervical cancer?', $form_config)) ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="yes">
                                                <?= __('Yes', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="no">
                                                <?= __('No', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_undergoing_treatment">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_undergoing_treatment', 'Are you undergoing treatment for CIN or cervical cancer?', $form_config)) ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item">
                                        <select required name="field_received_hpv">
                                            <option value="">
                                                <?= esc_html(psyem_get_dynamic_field_placeholder('field_received_hpv', 'Have you ever received HPV vaccine?', $form_config)) ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="yes">
                                                <?= __('Yes', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="no">
                                                <?= __('No', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_received_hpv">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_received_hpv', 'Have you ever received HPV vaccine?', $form_config)) ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item">
                                        <select required name="field_pregnant">
                                            <option value="">
                                                <?= esc_html(psyem_get_dynamic_field_placeholder('field_pregnant', 'Are you pregnant?', $form_config)) ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="yes">
                                                <?= __('Yes', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="no">
                                                <?= __('No', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_pregnant">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_pregnant', 'Are you pregnant?', $form_config)) ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item">
                                        <select required name="field_hysterectomy">
                                            <option value="">
                                                <?= esc_html(psyem_get_dynamic_field_placeholder('field_hysterectomy', 'Did you have a hysterectomy?', $form_config)) ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="yes">
                                                <?= __('Yes', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="no">
                                                <?= __('No', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_hysterectomy">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_hysterectomy', 'Did you have a hysterectomy?', $form_config)) ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item teal-form__checkboxes">
                                        <div class="form-item">
                                            <input name="field_agree_clinical" id="field_agree_clinical" type="checkbox" required />
                                            <label for="field_agree_clinical">
                                                <?php
                                                $tText = 'I understand that this program is part of a clinical study and enrollment is only for participants who agree to the terms and conditions on the clinical study consent form presented at the clinic on the day of appointment';
                                                esc_html_e('I understand that this program is part of a clinical study and enrollment is only for participants who agree to the terms and conditions on the clinical study consent form presented at the clinic on the day of appointment', 'psyeventsmanager');
                                                ?>.
                                                <span class="text-danger">*</span>
                                            </label>
                                        </div>
                                        <div class="form-item">
                                            <input name="field_info_sheet" id="field_info_sheet" type="checkbox" required />
                                            <label for="field_info_sheet">
                                                <?php
                                                $tText = 'I confirm, that I have read and understood the information sheet for the project and have had the opportunity to view and study the educational videos provided for a better overall grasp on how to protect myself against cervical cancer';
                                                esc_html_e('I confirm, that I have read and understood the information sheet for the project and have had the opportunity to view and study the educational videos provided for a better overall grasp on how to protect myself against cervical cancer', 'psyeventsmanager');
                                                ?>.
                                                <span class="text-danger">*</span>
                                            </label>
                                        </div>
                                        <div class="form-item">
                                            <input name="field_participation" id="field_participation" type="checkbox" required />
                                            <label for="field_participation">
                                                <?php
                                                $tText = 'I understand that my participation is voluntary and that I am free to withdraw at any time, without giving any reason, without my medical care or legal rights being affected';
                                                esc_html_e('I understand that my participation is voluntary and that I am free to withdraw at any time, without giving any reason, without my medical care or legal rights being affected', 'psyeventsmanager');
                                                ?>.
                                                <span class="text-danger">*</span>
                                            </label>
                                        </div>

                                        <div class="form-item">
                                            <input name="field_agree_study" id="field_agree_study" type="checkbox" required />
                                            <label for="field_agree_study">
                                                <?= __('I agree to take part in the above cervical screening programme', 'psyeventsmanager') ?>
                                                <span class="text-danger">*</span>
                                            </label>
                                        </div>
                                        <div class="form-item">
                                            <input name="field_agree_doctor" id="field_agree_doctor" type="checkbox" required />
                                            <label for="field_agree_doctor">
                                                <?php
                                                $tText = 'I here with acknowledge that, if I am currently experiencing irregular bleeding, spotting or pain during my menses, sex or randomly, I cannot join the project. We kindly encourage you to contact your GP immediately and seek a professional opinion';
                                                esc_html_e('I here with acknowledge that, if I am currently experiencing irregular bleeding, spotting or pain during my menses, sex or randomly, I cannot join the project. We kindly encourage you to contact your GP immediately and seek a professional opinion', 'psyeventsmanager');
                                                ?>.
                                                <span class="text-danger">*</span>
                                            </label>
                                        </div>
                                        <div class="form-item">
                                            <input name="field_agree_tnc" id="field_agree_tnc" type="checkbox" required />
                                            <label for="field_agree_tnc">
                                                <?= __('I have read and agree to the', 'psyeventsmanager') ?>
                                                <a href="<?= (isset($psyem_options) && isset($psyem_options['psyem_terms_url'])) ? $psyem_options['psyem_terms_url'] : '' ?>" target="_blank"><?= __('Terms & Conditions', 'psyeventsmanager') ?></a>.
                                                <span class="text-danger">*</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="teal-form__step-submit project-teal__sign-up-button" id="psyemFirstStepBtn">
                                    <span class="spinner-border buttonLoader spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                                    <?= __('Next', 'psyeventsmanager') ?>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div data-step="2" class="teal-form__content two1 hideThankyouCont">
                        <form class="teal-form mt-0" id="psyemSecondStepForm">
                            <div class="teal-form__content-inner">
                                <p>
                                    <span class="req-fields-info text-danger">
                                        * <?= __('Required field', 'psyeventsmanager') ?>
                                    </span>
                                </p>
                                <p class="teal-form__group-label form-item">
                                    <?= __('How would you like to be contacted for the program?', 'psyeventsmanager') ?>
                                    <span class="text-danger">*</span>
                                </p>
                                <div class="form-teal__fields">
                                    <div class="form-item teal-form__checkboxes form-item teal-form__checkboxes--large">
                                        <div class="form-item">
                                            <input id="field_contact_sms" type="checkbox" name="field_contact_sms" value="sms">
                                            <label for="field_contact_sms">
                                                <?= __('SMS', 'psyeventsmanager') ?>
                                            </label>
                                        </div>
                                        <div class="form-item">
                                            <input id="field_contact_email" type="checkbox" name="field_contact_email" value="email">
                                            <label for="field_contact_email">
                                                <?= __('Email', 'psyeventsmanager') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <p class="teal-form__group-label">
                                    <?= __('Contact Details', 'psyeventsmanager') ?>
                                </p>
                                <div class="form-teal__fields">
                                    <div class="form-item half">
                                        <input id="field_phone" required type="text" name="field_phone" class="strict_integer strict_phone strict_space" placeholder="<?= esc_attr(psyem_get_dynamic_field_placeholder('field_phone', 'Phone number', $form_config)) ?>" />
                                        <label for="field_phone">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_phone', 'Phone number', $form_config)) ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item half">
                                        <input id="field_email" required type="email" name="field_email" placeholder="<?= esc_attr(psyem_get_dynamic_field_placeholder('field_email', 'Email Address', $form_config)) ?>" />
                                        <label for="field_email">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_email', 'Email Address', $form_config)) ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item half">
                                        <select id="field_region" name="field_region">
                                            <option value="">
                                                <?= __('Region', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="Hong Kong Island">
                                                <?= __('Hong Kong Island', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="Kowloon">
                                                <?= __('Kowloon', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="New Territories">
                                                <?= __('New Territories', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_region">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_region', 'Region', $form_config)) ?><span class="text-danger">*</span>
                                        </label>
                                    </div>

                                    <div class="form-item half">
                                        <select id="field_district" name="field_district">
                                            <option value="">
                                                <?= __('District', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="hongkongisland" value="Central and Western">
                                                <?= __('Central and Western', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="hongkongisland" value="Eastern">
                                                <?= __('Eastern', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="hongkongisland" value="Southern">
                                                <?= __('Southern', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="hongkongisland" value="Wan Chai">
                                                <?= __('Wan Chai', 'psyeventsmanager') ?>
                                            </option>

                                            <option class="kowloon" value="Sham Shui Po">
                                                <?= __('Sham Shui Po', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="kowloon" value="Kowloon City">
                                                <?= __('Kowloon City', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="kowloon" value="Kwun Tong">
                                                <?= __('Kwun Tong', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="kowloon" value="Wong Tai Sin">
                                                <?= __('Wong Tai Sin', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="kowloon" value="Yau Tsim Mong">
                                                <?= __('Yau Tsim Mong', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="newterritories" value="Islands">
                                                <?= __('Islands', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="newterritories" value="Kwai Tsing">
                                                <?= __('Kwai Tsing', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="newterritories" value="North">
                                                <?= __('North', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="newterritories" value="Sai Kung">
                                                <?= __('Sai Kung', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="newterritories" value="Sha Tin">
                                                <?= __('Sha Tin', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="newterritories" value="Tai Po">
                                                <?= __('Tai Po', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="newterritories" value="Tsuen Wan">
                                                <?= __('Tsuen Wan', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="newterritories" value="Tuen Mun">
                                                <?= __('Tuen Mun', 'psyeventsmanager') ?>
                                            </option>
                                            <option class="newterritories" value="Yuen Long">
                                                <?= __('Yuen Long', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_district">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_district', 'District', $form_config)) ?><span class="text-danger">*</span>
                                        </label>
                                    </div>

                                    <div class="form-item">
                                        <input id="field_address" type="text" name="field_address" placeholder="<?= esc_attr(psyem_get_dynamic_field_placeholder('field_address', 'Address', $form_config)) ?>" />
                                        <label for="field_address">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_address', 'Address', $form_config)) ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                </div>
                                <p class="teal-form__group-label">
                                    <?= __('Let us know more about you', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                </p>
                                <div class="form-teal__fields">
                                    <div class="form-item">
                                        <select id="field_source" name="field_source">
                                            <option value="">
                                                <?= __('How have you heard about this study?', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="Karen Leung Foundation Website">
                                                <?= __('Karen Leung Foundation Website', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="PHASE Scientific">
                                                <?= __('PHASE Scientific', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="Social Media">
                                                <?= __('Social Media (eg. Facebook, Instagram, etc)', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="School News">
                                                <?= __('School News', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="Health Talk by Karen Leung Foundation">
                                                <?= __('Health Talk by Karen Leung Foundation', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_source">
                                            <?= esc_html(psyem_get_dynamic_field_label('field_source', 'How have you heard about this study?', $form_config)) ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="teal-form__actions">
                                    <button type="button" class="project-teal__sign-up-button project-teal__sign-up-button--back c2" id="teal-form__step-2-back">
                                        <?= __('Back', 'psyeventsmanager') ?>
                                    </button>
                                    <button type="button" class="teal-form__step-submit project-teal__sign-up-button" id="psyemSecondStepBtn">
                                        <span class="spinner-border buttonLoader spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                                        <?= __('Submit', 'psyeventsmanager') ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div data-step="3" class="teal-form__content three1 showThankyouCont">
                        <form class="teal-form mt-0" id="psyemThirdStepForm">
                            <div class="teal-form__content-inner">
                                <div class="form-teal__fields">
                                    <strong class="psyemTealThanksHeading">
                                        <?= __('Thank you for your registration! We will put you in our first priority list', 'psyeventsmanager') ?>
                                    </strong>
                                </div>
                                <div class="form-teal__fields">
                                    <p class="psyemTealThanksMesg">
                                        <?= __('The project quota is full. You will be notified by an Email or Sms for successful registration', 'psyeventsmanager') ?>
                                    </p>
                                </div>
                                <div class="form-teal__fields">
                                    <strong class="psyemTealThanksRef">
                                        [<?= __('Reference no', 'psyeventsmanager') ?>: <span class="psyemPsReferenceNo"></span>]
                                    </strong>
                                </div>

                                <div class="teal-form__actions">
                                    <a href="<?php echo home_url(); ?>" class="teal-form__step-submit project-teal__sign-up-button">
                                        <?= __('BACK TO HOMEPAGE', 'psyeventsmanager') ?>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>
<?php return ob_get_clean(); ?>