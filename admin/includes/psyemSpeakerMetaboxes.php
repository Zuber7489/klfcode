<?php if (isset($speaker_metabox_type) && !empty($speaker_metabox_type)) {  ?>
    <?php if ($speaker_metabox_type == 'Information') {
        if (isset($speaker_info_data) && !empty($speaker_info_data)) {
            extract($speaker_info_data);
        }
    ?>
        <div class="row mb-4">
            <div class="col-sm-5">
                <label class="form-label fw-bold"><?= __('Speaker Designation', 'psyeventsmanager') ?></label>
                <input type="text" class="form-control strict_space" name="psyemSpeakerInfos[psyem_speaker_designation]" value="<?= @$psyem_speaker_designation ?>" placeholder="<?= __('Enter Speaker Designation', 'psyeventsmanager') ?>">
            </div>
        </div>
    <?php } ?>
<?php } ?>