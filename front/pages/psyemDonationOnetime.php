<?php ob_start(); ?>
<div class="modal fade" id="psyemDonationModal" data-keyboard="false" data-backdrop="false">
    <div class="modal-dialog m-0">
        <div class="modal-content">
            <div class="lab-modal-body" style="left: 0;">
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="wrapperModal" id="psyemDonationAmountsCont"></div>
            </div>
        </div>
    </div>
</div>
<?php return ob_get_clean(); ?>