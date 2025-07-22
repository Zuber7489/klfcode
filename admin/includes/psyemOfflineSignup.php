<div class="wrap" id="psyMainCont">
    <div class="row mb-5">
        <div class="col-sm-12">
            <h1 class="wp-heading-inline">
                <?= __('Create Offline Registration', 'psyeventsmanager') ?>
            </h1>
        </div>
    </div>

    <form class="form-horizontal" action="" id="offlineRegistrationForm" novalidate="novalidate">
        <div class="row mb-5">
            <div class="col-sm-12 text-start">
                <button type="button" class="btn btn-success saveOfflineRegistrationBtn">
                    <span class="spinner-border buttonLoader spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                    <i class="fa fa-save"></i>
                    <?= __('Create', 'psyeventsmanager') ?>
                </button>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label fw-bold" for="offline_event">
                        <?= __('Event', 'psyeventsmanager') ?>
                        <span class="required">*</span>
                    </label>
                    <select class="form-control" name="offline_event">
                        <option value=""><?= __('-- Select --', 'psyeventsmanager') ?></option>
                        <?php if (isset($psyem_events) && isset($psyem_events['data']) && !empty($psyem_events['data'])): ?>
                            <?php foreach ($psyem_events['data'] as $psyem_event): ?>
                                <?php if (psyem_IsEventBookingAllowed($psyem_event['ID'], $psyem_event) == 'Yes'): ?>
                                    <option value="<?= @$psyem_event['ID'] ?>">
                                        <?= @$psyem_event['title'] ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label fw-bold" for="offline_firstname">
                        <?= __('Participant First Name', 'psyeventsmanager') ?>
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="offline_firstname" class="form-control strict_space" value="" placeholder="<?= __('Enter Participant First Name', 'psyeventsmanager') ?>" />
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label fw-bold" for="offline_lastname">
                        <?= __('Participant Last Name', 'psyeventsmanager') ?>
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="offline_lastname" class="form-control strict_space" value="" placeholder="<?= __('Enter Participant Last Name', 'psyeventsmanager') ?>" />
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-4">
                <label class="form-label fw-bold" for="offline_email">
                    <?= __('Registration Email', 'psyeventsmanager') ?>
                    <span class="required">*</span>
                </label>
                <input type="email" class="form-control strict_space" name="offline_email" value="" placeholder="<?= __('Enter Registration Email Address', 'psyeventsmanager') ?>">
            </div>

            <div class="col-sm-4">
                <label class="form-label fw-bold" for="offline_company">
                    <?= __('Company Name', 'psyeventsmanager') ?>
                </label>
                <input type="text" class="form-control strict_space" name="offline_company" value="" placeholder="<?= __('Enter company name', 'psyeventsmanager') ?>">
            </div>

            <div class="col-sm-4">
                <label class="form-label fw-bold" for="offline_tickets">
                    <?= __('Number Of Participants', 'psyeventsmanager') ?>
                    <span class="required">*</span>
                </label>
                <input type="number" class="form-control strict_space strict_integer" name="offline_tickets" value="1" placeholder="<?= __('Enter Number Of Participants Count', 'psyeventsmanager') ?>">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-12 text-start mt-5">
                <button type="button" class="btn btn-success saveOfflineRegistrationBtn">
                    <span class="spinner-border buttonLoader spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                    <i class="fa fa-save"></i>
                    <?= __('Create', 'psyeventsmanager') ?>
                </button>
            </div>
        </div>
    </form>
</div>