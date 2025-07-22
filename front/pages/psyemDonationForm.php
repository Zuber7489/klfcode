<?php ob_start(); ?>
<style>
    .sponsor-call-to-action::after {
        background: url(<?= PSYEM_ASSETS . '/images/more-arrow.svg' ?>) no-repeat center;
    }

    .donation-call-to-action::after {
        background: url(<?= PSYEM_ASSETS . '/images/more-arrow.svg' ?>) no-repeat center;
    }

    .site-main {
        min-width: 100%;
    }
</style>

<div class="psyemDonationSection psyemDonationCont" style="display: none;">
    <div class="header_donation" style="background-image:url(<?= PSYEM_ASSETS . '/images/donate-bg.jpg' ?>)">
        <div class="cic-1" style=" background: url(<?= PSYEM_ASSETS . '/images/cic-3.png' ?>) no-repeat center;"></div>
        <div class="cic-2" style=" background: url(<?= PSYEM_ASSETS . '/images/cic-4.png' ?>) no-repeat center;"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="content">
                        <h1 class="title"> <?= __('Make a Donation', 'psyeventsmanager') ?> </h1>
                        <p>
                            <?php
                            $tText = 'Your donation will go a long way in helping to save lives by supporting our efforts to promote prevention, early detection, and optimal treatment of gynaecological cancer in Hong Kong';
                            esc_html_e($tText, 'psyeventsmanager');
                            ?>.
                        </p>
                        <p>
                            <?php
                            $tText = 'We are a registered charity in both the US and Hong Kong and are able to provide tax deductible receipts for both jurisdictions';
                            esc_html_e($tText, 'psyeventsmanager');
                            ?>.
                        </p>
                        <p>&nbsp;</p>
                    </div>
                </div>

                <div class="col-md-12 col-lg-6 posRealtive">
                    <div class="right_section">
                        <div class="donation_type monthly">
                            <h2> <?= __('Monthly Donation', 'psyeventsmanager') ?></h2>
                            <p> <?= __('Help keep our programs running', 'psyeventsmanager') ?></p>
                            <a href="javascript:void(0);"
                                data-toggle="modal"
                                data-target="#lab-slide-bottom-popup"
                                class="donation-call-to-action psyemMonthlyDonationElm"
                                data-donation-title="<?= __('Monthly Donation', 'psyeventsmanager') ?>">
                                <?= __('Select Amount', 'psyeventsmanager') ?>
                            </a>
                        </div>
                        <div class="donation_type onetime">
                            <h2> <?= __('One Time Donation', 'psyeventsmanager') ?></h2>
                            <p><?= __('Every penny counts', 'psyeventsmanager') ?></p>
                            <a href="javascript:void(0);"
                                class="donation-call-to-action psyemOnetimeDonationElm"
                                data-donation-title="<?= __('One Time Donation', 'psyeventsmanager') ?>">
                                <?= __('Select Amount', 'psyeventsmanager') ?>
                            </a>
                        </div>
                        <div class="donation_type sponsorship">
                            <h2><?= __('Donate towards our Core Objectives', 'psyeventsmanager') ?></h2>
                            <p><?= __('Make a targeted impact', 'psyeventsmanager') ?></p>
                            <a href="<?= $onetime_donation_page_link ?>"
                                class="sponsor-call-to-action">
                                <?= __('Areas of Impact', 'psyeventsmanager') ?>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

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