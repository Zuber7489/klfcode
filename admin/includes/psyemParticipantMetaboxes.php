<?php if (isset($participant_metabox_type) && !empty($participant_metabox_type)) {  ?>
    <?php if ($participant_metabox_type == 'Information') {
        if (isset($participant_info_data) && !empty($participant_info_data)) {
            extract($participant_info_data);
        }
    ?>
        <div class="row mb-4">
            <div class="col-sm-3">
                <label class="form-label fw-bold"><?= __('Email', 'psyeventsmanager') ?></label>
                <input type="email" class="form-control strict_space" name="psyemParticipantInfos[psyem_participant_email]" value="<?= @$psyem_participant_email ?>" placeholder="<?= __('Enter participant email address', 'psyeventsmanager') ?>">
            </div>

            <div class="col-sm-3">
                <label class="form-label fw-bold"><?= __('Company', 'psyeventsmanager') ?></label>
                <input type="text" class="form-control strict_space" name="psyemParticipantInfos[psyem_participant_company]" value="<?= @$psyem_participant_company ?>" placeholder="<?= __('Enter participant company name', 'psyeventsmanager') ?>">
            </div>

            <div class="col-sm-3">
                <label class="form-label fw-bold"><?= __('Type', 'psyeventsmanager') ?></label>
                <select class="form-select" name="psyemParticipantInfos[psyem_participant_type]">
                    <option value="">
                        <?= __('-- Select --', 'psyeventsmanager') ?>
                    </option>
                    <option value="Main" <?= (@$psyem_participant_type == 'Main') ? 'selected="selected"' : '' ?>>
                        <?= __('Main', 'psyeventsmanager') ?>
                    </option>
                    <option value="Member" <?= (@$psyem_participant_type == 'Member') ? 'selected="selected"' : '' ?>>
                        <?= __('Member', 'psyeventsmanager') ?>
                    </option>
                </select>
            </div>
        </div>
    <?php } ?>
<?php } ?>