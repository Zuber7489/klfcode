<?php ob_start(); ?>
<div id="content-area" class="text-start psyemProjectSafeCont" style="display: none;">
    <div class="region region-content">
        <article class="node node-project-teal-form node-project-teal node-teal-form">
            <div class="node-inner">
                <div class="teal__container teal__container--form">
                    <header class="node-teal-form__header">
                        <h1 class="node-teal-form__title">
                            <?= __('Register For Project SAFE', 'psyeventsmanager') ?>
                        </h1>
                        <div class="node-teal-form__body">
                            <p>
                                <?= __('Two simple steps to register', 'psyeventsmanager') ?>
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
                                            placeholder="<?= __('First Name (Same with HKID)', 'psyeventsmanager') ?>" />
                                        <label for="field_first_name">
                                            <?= __('First Name (Same with HKID)', 'psyeventsmanager') ?>
                                            <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item half">
                                        <input id="field_last_name" required type="text" name="field_last_name"
                                            placeholder="<?= __('Last Name (Same with HKID)', 'psyeventsmanager') ?>" />
                                        <label for="field_last_name">
                                            <?= __('Last Name (Same with HKID)', 'psyeventsmanager') ?>
                                            <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item">
                                        <select id="field_gender" required name="field_gender">
                                            <option value="">
                                                <?= __('Gender', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="female">
                                                <?= __('Female', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="male">
                                                <?= __('Male', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_gender">Gender<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="form-item third">
                                        <select required name="field_dob_day">
                                            <option value="">
                                                <?= __('Day of Birth', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="01">1</option>
                                            <option value="02">2</option>
                                            <option value="03">3</option>
                                            <option value="04">4</option>
                                            <option value="05">5</option>
                                            <option value="06">6</option>
                                            <option value="07">7</option>
                                            <option value="08">8</option>
                                            <option value="09">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                            <option value="17">17</option>
                                            <option value="18">18</option>
                                            <option value="19">19</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>
                                            <option value="22">22</option>
                                            <option value="23">23</option>
                                            <option value="24">24</option>
                                            <option value="25">25</option>
                                            <option value="26">26</option>
                                            <option value="27">27</option>
                                            <option value="28">28</option>
                                            <option value="29">29</option>
                                            <option value="30">30</option>
                                            <option value="31">31</option>
                                        </select>
                                        <label for="field_dob_day">
                                            <?= __('Day of Birth', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item third">
                                        <select required name="field_dob_month">
                                            <option value="">
                                                <?= __('Month of Birth', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="01"><?= __('January', 'psyeventsmanager') ?></option>
                                            <option value="02"><?= __('February', 'psyeventsmanager') ?></option>
                                            <option value="03"><?= __('March', 'psyeventsmanager') ?></option>
                                            <option value="04"><?= __('April', 'psyeventsmanager') ?></option>
                                            <option value="05"><?= __('May', 'psyeventsmanager') ?></option>
                                            <option value="06"><?= __('June', 'psyeventsmanager') ?></option>
                                            <option value="07"><?= __('July', 'psyeventsmanager') ?></option>
                                            <option value="08"><?= __('August', 'psyeventsmanager') ?></option>
                                            <option value="09"><?= __('September', 'psyeventsmanager') ?></option>
                                            <option value="10"><?= __('October', 'psyeventsmanager') ?></option>
                                            <option value="11"><?= __('November', 'psyeventsmanager') ?></option>
                                            <option value="12"><?= __('December', 'psyeventsmanager') ?></option>
                                        </select>
                                        <label for="field_dob_month">
                                            <?= __('Month of Birth', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item third">
                                        <select required name="field_dob_year">
                                            <option value="">
                                                <?= __('Year of Birth', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                            </option>
                                            <?php foreach (psyem_GetPreviousYearsFromYear((date("Y") - 17)) as $pyear) : ?>
                                                <option value="<?= $pyear ?>"><?= $pyear ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label for="field_dob_year">
                                            <?= __('Year of Birth', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item">
                                        <select required name="field_sexual_experience">
                                            <option value="">
                                                <?= __('Do you have any sexual experience?', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="yes">
                                                <?= __('Yes', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="no">
                                                <?= __('No', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_sexual_experience">
                                            <?= __('Do you have any sexual experience?', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item">
                                        <select required name="field_cervical_screening">
                                            <option value="">
                                                <?= __('Have you ever had any cervical screening in the last 3 years?', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="yes">
                                                <?= __('Yes', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="no">
                                                <?= __('No', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_cervical_screening">
                                            <?= __('Have you ever had any cervical screening in the last 3 years?', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item">
                                        <select required name="field_undergoing_treatment">
                                            <option value="">
                                                <?= __('Are you undergoing treatment for CIN or cervical cancer?', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="yes">
                                                <?= __('Yes', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="no">
                                                <?= __('No', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_undergoing_treatment">
                                            <?= __('Are you undergoing treatment for CIN or cervical cancer?', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item">
                                        <select required name="field_received_hpv">
                                            <option value="">
                                                <?= __('Have you ever received HPV vaccine?', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="yes">
                                                <?= __('Yes', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="no">
                                                <?= __('No', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_received_hpv">
                                            <?= __('Have you ever received HPV vaccine?', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item">
                                        <select required name="field_pregnant">
                                            <option value="">
                                                <?= __('Are you pregnant?', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="yes">
                                                <?= __('Yes', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="no">
                                                <?= __('No', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_pregnant">
                                            <?= __('Are you pregnant?', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item">
                                        <select required name="field_hysterectomy">
                                            <option value="">
                                                <?= __('Did you have a hysterectomy?', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                            </option>
                                            <option value="yes">
                                                <?= __('Yes', 'psyeventsmanager') ?>
                                            </option>
                                            <option value="no">
                                                <?= __('No', 'psyeventsmanager') ?>
                                            </option>
                                        </select>
                                        <label for="field_hysterectomy">
                                            <?= __('Did you have a hysterectomy?', 'psyeventsmanager') ?><span class="text-danger">*</span>
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
                                        <input id="field_phone" required type="text" name="field_phone" class="strict_integer strict_phone strict_space" placeholder="<?= __('Phone number', 'psyeventsmanager') ?>*" />
                                        <label for="field_phone">
                                            <?= __('Phone number', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="form-item half">
                                        <input id="field_email" required type="email" name="field_email" placeholder="<?= __('Email Address', 'psyeventsmanager') ?>*" />
                                        <label for="field_email">
                                            <?= __('Email Address', 'psyeventsmanager') ?><span class="text-danger">*</span>
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
                                            <?= __('Region', 'psyeventsmanager') ?><span class="text-danger">*</span>
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
                                            <?= __('District', 'psyeventsmanager') ?><span class="text-danger">*</span>
                                        </label>
                                    </div>

                                    <div class="form-item">
                                        <input id="field_address" type="text" name="field_address" placeholder="<?= __('Address', 'psyeventsmanager') ?>" />
                                        <label for="field_address">
                                            <?= __('Address', 'psyeventsmanager') ?><span class="text-danger">*</span>
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
                                            <?= __('How have you heard about this study?', 'psyeventsmanager') ?>
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