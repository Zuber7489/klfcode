<?php ob_start(); ?>
<?php
if (!empty($DonationAmounts) && is_array($DonationAmounts)) {
    $amount_type    = (isset($amount_type)) ? $amount_type : '';
    $monthlyTitle   =  __('Monthly Donation', 'psyeventsmanager');
    $oneTimeTitle   =  __('One Time Donation', 'psyeventsmanager');
    $modalTitle     = ($amount_type == 'Monthly') ? $monthlyTitle : $oneTimeTitle;
    $newModalTitle  = __('Make a targeted impact towards our core objectives', 'psyeventsmanager');
    $modalTitle     = (isset($sourceModalTitle) && empty($sourceModalTitle)) ? $newModalTitle : $modalTitle;
    $CurrenyType                    = psyem_GetCurrenyType();
    $psyem_options                  = psyem_GetOptionsWithPrefix();
    $psyem_currency_exchange_rate   = @$psyem_options['psyem_currency_exchange_rate'];
    $otherPh        = __('Type Amount', 'psyeventsmanager') ;
?>
    <div class="donation-amount-select">
        <div class="title"><?= $modalTitle ?></div>
        <div class="container donation-selected">

            <?php
            foreach ($DonationAmounts as $DonationAmount):
                $amountID = @$DonationAmount['ID'];
                $amountMeta = @$DonationAmount['meta_data'];
                $amount_price = @$amountMeta['psyem_amount_price'];
                if ($CurrenyType != 'USD') {
                    $amount_price   = psyem_ConvertUsdToHkd($amount_price, $psyem_currency_exchange_rate);
                }

                $psyem_amount_price = psyem_roundPrecision($amount_price);
                $amountIDEnc = psyem_safe_b64encode_id($amountID);
                if ($psyem_amount_price > 5):
            ?>
                    <a href="javascript:void(0);" class="donation-amount" data-amountenc="<?= $amountIDEnc ?>" data-amountfor="<?= $amount_type ?>">
                        <span class="donation_currency"><?= psyem_GetCurrenySign(); ?></span>
                        <?= formatPriceWithComma($psyem_amount_price); ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="custom-donation-fieldgroup">
                <label for="customdonationamt" class="donation_currency_label">
                    <span class="donation_currency"><?= psyem_GetCurrenySign(); ?></span>
                </label>
                <input type="text" name="customdonationamt" class="donation-amount custom placeholder-shown strict_space strict_numeric strict_decimal" placeholder="<?=$otherPh?>" style="opacity: 1;" data-amountenc="Custom" data-amountfor="<?= $amount_type ?>">
            </div>
        </div>
        <a href="javascript:void(0);" class="link-oval submit-donation">
            <?= __('Next', 'psyeventsmanager') ?>
        </a>
    </div>
<?php } ?>
<?php return ob_get_clean(); ?>