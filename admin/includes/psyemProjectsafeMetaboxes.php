<?php if (isset($projectsafe_metabox_type) && !empty($projectsafe_metabox_type)) {  ?>
    <?php if ($projectsafe_metabox_type == 'Information') { ?>
        <div class="row psyPostMetaboxPSInfo mb-4">
            <div class="col-sm-3">
                <label class="form-label fw-bold"> <?= __('First Name', 'psyeventsmanager') ?></label>
                <p class="mb-1"><?= @$projectsafe_meta_data['psyem_projectsafe_firstname'] ?></p>
            </div>
            <div class="col-sm-3">
                <label class="form-label fw-bold"> <?= __('Last Name', 'psyeventsmanager') ?></label>
                <p class="mb-1"><?= @$projectsafe_meta_data['psyem_projectsafe_lastname'] ?></p>
            </div>
            <div class="col-sm-3">
                <label class="form-label fw-bold"> <?= __('Email', 'psyeventsmanager') ?></label>
                <p class="mb-1"><?= @$projectsafe_meta_data['psyem_projectsafe_email'] ?></p>
            </div>
            <div class="col-sm-3">
                <label class="form-label fw-bold"> <?= __('Phone', 'psyeventsmanager') ?></label>
                <p class="mb-1"><?= @$projectsafe_meta_data['psyem_projectsafe_phone'] ?></p>
            </div>
        </div>

        <div class="row psyPostMetaboxPSInfo mb-4">
            <div class="col-sm-3">
                <label class="form-label fw-bold"> <?= __('Gender', 'psyeventsmanager') ?></label>
                <p class="mb-1"><?= @$projectsafe_meta_data['psyem_projectsafe_gender'] ?></p>
            </div>
            <div class="col-sm-3">
                <label class="form-label fw-bold"> <?= __('DOB', 'psyeventsmanager') ?></label>
                <p class="mb-1"><?= @$projectsafe_meta_data['psyem_projectsafe_dob_format'] ?></p>
            </div>
            <div class="col-sm-3">
                <label class="form-label fw-bold"> <?= __('Source', 'psyeventsmanager') ?></label>
                <p class="mb-1"><?= @$projectsafe_meta_data['psyem_projectsafe_source'] ?></p>
            </div>
            <div class="col-sm-3">
                <label class="form-label fw-bold"> <?= __('Status', 'psyeventsmanager') ?></label>
                <p class="mb-1"><?= @$projectsafe_meta_data['psyem_projectsafe_status'] ?></p>
            </div>
        </div>

        <hr />

        <div class="row psyPostMetaboxPSInfo mb-4">
            <div class="col-sm-12">
                <label class="form-label"> <?= __('Location', 'psyeventsmanager') ?></label>
            </div>
            <div class="col-sm-3">
                <label class="form-label fw-bold"> <?= __('Region', 'psyeventsmanager') ?></label>
                <p class="mb-1"><?= @$projectsafe_meta_data['psyem_projectsafe_region'] ?></p>
            </div>
            <div class="col-sm-3">
                <label class="form-label fw-bold"> <?= __('District', 'psyeventsmanager') ?></label>
                <p class="mb-1"><?= @$projectsafe_meta_data['psyem_projectsafe_district'] ?></p>
            </div>
            <div class="col-sm-6">
                <label class="form-label fw-bold"> <?= __('Address', 'psyeventsmanager') ?></label>
                <p class="mb-1"><?= @$projectsafe_meta_data['psyem_projectsafe_address'] ?></p>
            </div>
        </div>

        <hr />

        <div class="row psyPostMetaboxPSInfo mb-4">
            <div class="col-sm-12">
                <label class="form-label"> <?= __('Questionary', 'psyeventsmanager') ?></label>
                <table class="table table-responsive table-hovered table-bordered">
                    <tbody>
                        <tr>
                            <th> <?= __('Do you have any sexual experience?', 'psyeventsmanager') ?></th>
                            <th class="text-center">
                                <?= @$projectsafe_meta_data['psyem_projectsafe_sexual_experience'] ?>
                            </th>
                        </tr>
                        <tr>
                            <th> <?= __('Have you ever had any cervical screening in the last 3 years?', 'psyeventsmanager') ?></th>
                            <th class="text-center">
                                <?= @$projectsafe_meta_data['psyem_projectsafe_cervical_screening'] ?>
                            </th>
                        </tr>
                        <tr>
                            <th> <?= __('Are you undergoing treatment for CIN or cervical cancer?', 'psyeventsmanager') ?></th>
                            <th class="text-center">
                                <?= @$projectsafe_meta_data['psyem_projectsafe_undergoing_treatment'] ?>
                            </th>
                        </tr>
                        <tr>
                            <th> <?= __('Have you ever received HPV vaccine?', 'psyeventsmanager') ?></th>
                            <th class="text-center">
                                <?= @$projectsafe_meta_data['psyem_projectsafe_received_hpv'] ?>
                            </th>
                        </tr>
                        <tr>
                            <th> <?= __('Are you pregnant?', 'psyeventsmanager') ?></th>
                            <th class="text-center">
                                <?= @$projectsafe_meta_data['psyem_projectsafe_pregnant'] ?>
                            </th>
                        </tr>
                        <tr>
                            <th> <?= __('Did you have a hysterectomy?', 'psyeventsmanager') ?></th>
                            <th class="text-center">
                                <?= @$projectsafe_meta_data['psyem_projectsafe_hysterectomy'] ?>
                            </th>
                        </tr>
                        <tr>
                            <th> <?= __('How would you like to be contacted for the program?', 'psyeventsmanager') ?></th>
                            <th class="text-center">
                                <?= @$projectsafe_meta_data['psyem_projectsafe_contact_by'] ?>
                            </th>
                        </tr>
                        <tr>
                            <th> <?= __('How have you heard about this study?', 'psyeventsmanager') ?></th>
                            <th class="text-center">
                                <?= @$projectsafe_meta_data['psyem_projectsafe_source'] ?>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr />

        <div class="row psyPostMetaboxPSInfo mb-4">
            <div class="col-sm-12">
                <label class="form-label"> <?= __('Consents', 'psyeventsmanager') ?></label>
                <table class="table table-responsive table-hovered table-bordered">
                    <tbody>
                        <tr>
                            <th>
                                <?php
                                $tText = 'I understand that this program is part of a clinical study and enrollment is only for participants who agree to the terms and conditions on the clinical study consent form presented at the clinic on the day of appointment';
                                esc_html_e('I understand that this program is part of a clinical study and enrollment is only for participants who agree to the terms and conditions on the clinical study consent form presented at the clinic on the day of appointment', 'psyeventsmanager');
                                ?>.
                            </th>
                            <th class="text-center">
                                <?= @$projectsafe_meta_data['psyem_projectsafe_check_clinical'] ?>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <?php
                                $tText = 'I confirm, that I have read and understood the information sheet for the project and have had the opportunity to view and study the educational videos provided for a better overall grasp on how to protect myself against cervical cancer';
                                esc_html_e('I confirm, that I have read and understood the information sheet for the project and have had the opportunity to view and study the educational videos provided for a better overall grasp on how to protect myself against cervical cancer', 'psyeventsmanager');
                                ?>.
                            </th>
                            <th class="text-center">
                                <?= @$projectsafe_meta_data['psyem_projectsafe_check_infosheet'] ?>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <?php
                                $tText = 'I understand that my participation is voluntary and that I am free to withdraw at any time, without giving any reason, without my medical care or legal rights being affected';
                                esc_html_e('I understand that my participation is voluntary and that I am free to withdraw at any time, without giving any reason, without my medical care or legal rights being affected', 'psyeventsmanager');
                                ?>.
                            </th>
                            <th class="text-center">
                                <?= @$projectsafe_meta_data['psyem_projectsafe_check_participation'] ?>
                            </th>
                        </tr>
                        <tr>
                            <th> <?= esc_html__('I agree to take part in the above cervical screening programme', 'psyeventsmanager') ?></th>
                            <th class="text-center">
                                <?= @$projectsafe_meta_data['psyem_projectsafe_check_study'] ?>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <?php
                                $tText = 'I here with acknowledge that, if I am currently experiencing irregular bleeding, spotting or pain during my menses, sex or randomly, I cannot join the project. We kindly encourage you to contact your GP immediately and seek a professional opinion';
                                esc_html_e('I here with acknowledge that, if I am currently experiencing irregular bleeding, spotting or pain during my menses, sex or randomly, I cannot join the project. We kindly encourage you to contact your GP immediately and seek a professional opinion', 'psyeventsmanager');
                                ?>.
                            </th>
                            <th class="text-center">
                                <?= @$projectsafe_meta_data['psyem_projectsafe_check_doctor'] ?>
                            </th>
                        </tr>
                        <tr>
                            <th> <?= esc_html__('Terms & Conditions', 'psyeventsmanager') ?></th>
                            <th class="text-center">
                                <?= @$projectsafe_meta_data['psyem_projectsafe_check_tandc'] ?>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
<?php } ?>