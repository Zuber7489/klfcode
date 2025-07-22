<?php ob_start(); ?>
<?php
if (isset($projectSafeTypeSlug) && !empty($projectSafeTypeSlug)) {
    if (isset($projectSafeTypeTitle) && !empty($projectSafeTypeTitle)) {
?>
        <div class="form-group pstypeRowCont">
            <div class="input-group mb-3">
                <span class="form-control" readonly>[psyem-projectsafe-form type="<?= $projectSafeTypeSlug ?>"]</span>
                <span class="form-control" readonly><?= $projectSafeTypeTitle ?></span>               
                <div class="input-group-append">
                    <span class="input-group-text cursor-pointer bg-danger text-white projectsafeTypeRemoveBtn" data-slug="<?= $projectSafeTypeSlug ?>" data-task="Remove">
                        <span class="dashicons cursor-pointer dashicons-trash"></span>
                    </span>
                </div>
            </div>
        </div>
<?php }
} ?>

<?php return ob_get_clean(); ?>