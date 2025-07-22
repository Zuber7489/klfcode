<?php ob_start(); ?>
<?php if ((isset($postBlockType) && !empty($postBlockType)) &&  (isset($currentPostData) && !empty($currentPostData))): ?>

    <?php if ($postBlockType == 'LargeImage'): ?>
        <div class="col-md-12 col-lg-6 padding0 bigSingleImageLeft ">
            <div class="row margin0 h100LeftImg">
                <div class="col-12 col-sm-12 col-md-12 padding0">
                    <a href="<?= @$currentPostData['link'] ?>" class="fullWidthLink">
                        <div class="boxLftImg">
                            <div class="bg" style="background: url(<?= @$currentPostData['image'] ?>) no-repeat center;"></div>
                            <div class="content">
                                <h3 class="psyemPostCategory"> <?= (@$currentPostData['category']->name) ?> </h3>
                                <h4><?= __('Posted on', 'psyeventsmanager') ?> <?= @$currentPostData['date'] ?></h4>
                                <h1><?= @$currentPostData['title'] ?></h1>
                                <div href="<?= @$currentPostData['link'] ?>" class="link-oval"><?= __('More', 'psyeventsmanager') ?></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($postBlockType == 'LeftImage'): ?>
        <div class="row margin0 imageOrderChange ">
            <div class="col-6 col-sm-6 col-md-6 padding0">
                <div class="leftImageSide">
                    <div class="imageInnerSide bgImageknow" style="background: url(<?= @$currentPostData['image'] ?>) no-repeat center;"></div>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 padding0">
                <div class="contentLeftSide">
                    <div class="content">
                        <h6 class="psyemPostCategory"><?= (@$currentPostData['category']->name) ?></h6>
                        <p><small><?= __('Posted on', 'psyeventsmanager') ?> <?= @$currentPostData['date'] ?> </small></p>
                        <h3><?= @$currentPostData['title'] ?></h3>
                        <a href="<?= @$currentPostData['link'] ?>" class="btnMore"> <?= __('More', 'psyeventsmanager') ?>
                            <span>
                                <svg aria-hidden="true" class="e-font-icon-svg e-fas-play-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($postBlockType == 'RightImage'): ?>
        <div class="row margin0 imageOrderChange ">
            <div class="col-6 col-sm-6 col-md-6 padding0">
                <div class="contentLeftSide">
                    <div class="content">
                        <h6 class="psyemPostCategory"><?= (@$currentPostData['category']->name) ?></h6>
                        <p><small><?= __('Posted on', 'psyeventsmanager') ?> <?= @$currentPostData['date'] ?> </small></p>
                        <h3><?= @$currentPostData['title'] ?></h3>
                        <a href="<?= @$currentPostData['link'] ?>" class="btnMore"> <?= __('More', 'psyeventsmanager') ?>
                            <span>
                                <svg aria-hidden="true" class="e-font-icon-svg e-fas-play-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 padding0 order-lg-1">
                <div class="leftImageSide">
                    <div class="imageInnerSide bgImageknow" style="background: url(<?= @$currentPostData['image'] ?>) no-repeat center;"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($postBlockType == 'LeftImageBlock'): ?>
        <div class="col-md-12 col-lg-6 padding0 psyemPostCat<?= (@$currentPostData['category']->term_id) ?>">
            <div class="row margin0 imageOrderChange">
                <div class="col-6 col-sm-6 col-md-6 padding0">
                    <div class="leftImageSide">
                        <div class="imageInnerSide bgImageknow" style="background: url(<?= @$currentPostData['image'] ?>) no-repeat center;"></div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 padding0">
                    <div class="contentLeftSide">
                        <div class="content">
                            <h6 class="psyemPostCategory"><?= (@$currentPostData['category']->name) ?></h6>
                            <p><small><?= __('Posted on', 'psyeventsmanager') ?> <?= @$currentPostData['date'] ?> </small></p>
                            <h3><?= @$currentPostData['title'] ?></h3>
                            <a href="<?= @$currentPostData['link'] ?>" class="btnMore"> <?= __('More', 'psyeventsmanager') ?>
                                <span>
                                    <svg aria-hidden="true" class="e-font-icon-svg e-fas-play-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($postBlockType == 'RightImageBlock'): ?>
        <div class="col-md-12 col-lg-6 padding0 psyemPostCat<?= (@$currentPostData['category']->term_id) ?>">
            <div class="row margin0 imageOrderChange">
                <div class="col-6 col-sm-6 col-md-6 padding0">
                    <div class="contentLeftSide">
                        <div class="content">
                            <h6 class="psyemPostCategory"><?= (@$currentPostData['category']->name) ?></h6>
                            <p><small><?= __('Posted on', 'psyeventsmanager') ?> <?= @$currentPostData['date'] ?> </small></p>
                            <h3><?= @$currentPostData['title'] ?></h3>
                            <a href="<?= @$currentPostData['link'] ?>" class="btnMore"> <?= __('More', 'psyeventsmanager') ?>
                                <span>
                                    <svg aria-hidden="true" class="e-font-icon-svg e-fas-play-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 padding0 order-lg-1">
                    <div class="leftImageSide">
                        <div class="imageInnerSide bgImageknow" style="background: url(<?= @$currentPostData['image'] ?>) no-repeat center;"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($postBlockType == 'EventLargeImage'): ?>
        <div class="col-md-12 col-lg-6 padding0 bigSingleImageLeft">
            <div class="row margin0 h100LeftImg">
                <div class="col-12 col-sm-12 col-md-12 padding0">
                    <a href="<?= @$currentPostData['link'] ?>" class="fullWidthLink">
                        <div class="boxLftImg">
                            <div class="bg" style="background: url(<?= @$currentPostData['image'] ?>) no-repeat center;"></div>
                            <div class="content">
                                <div class="programme-date"><?= @$currentPostData['start_date'] ?></div>
                                <h1><?= @$currentPostData['title'] ?></h1>
                                <div class="link-oval"><?= __('More', 'psyeventsmanager') ?></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($postBlockType == 'EventLeftImage'): ?>
        <div class="row margin0 imageOrderChange ">
            <div class="col-6 col-sm-6 col-md-6 padding0">
                <div class="leftImageSide">
                    <div class="imageInnerSide bgImageknow" style="background: url(<?= @$currentPostData['image'] ?>) center center no-repeat; transform: translate(-18.6667px, -9.33333px) scale(1.1);"></div>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 padding0 positionSec">
                <div class="contentLeftSide">
                    <div class="programme-date"><?= @$currentPostData['start_date'] ?></div>
                    <h3><?= @$currentPostData['title'] ?></h3>
                    <a href="<?= @$currentPostData['link'] ?>" class="btnMore"> <?= __('More', 'psyeventsmanager') ?>
                        <span>
                            <svg aria-hidden="true" class="e-font-icon-svg e-fas-play-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($postBlockType == 'EventRightImage'): ?>
        <div class="row margin0 imageOrderChange ">
            <div class="col-6 col-sm-6 col-md-6 padding0 positionSec">
                <div class="contentLeftSide">
                    <div class="programme-date"><?= @$currentPostData['start_date'] ?></div>
                    <h3><?= @$currentPostData['title'] ?></h3>
                    <a href="<?= @$currentPostData['link'] ?>" class="btnMore"> <?= __('More', 'psyeventsmanager') ?>
                        <span>
                            <svg aria-hidden="true" class="e-font-icon-svg e-fas-play-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>

            <div class="col-6 col-sm-6 col-md-6 padding0">
                <div class="leftImageSide">
                    <div class="imageInnerSide bgImageknow" style="background: url(<?= @$currentPostData['image'] ?>) center center no-repeat; transform: translate(-18.6667px, -9.33333px) scale(1.1);"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($postBlockType == 'EventLeftImageBlock'): ?>
        <div class="col-md-12 col-lg-6 padding0">
            <div class="row margin0">
                <div class="col-6 col-sm-6 col-md-6 padding0">
                    <div class="leftImageSide">
                        <div class="imageInnerSide bgImageknow" style="background: url(<?= @$currentPostData['image'] ?>) center center no-repeat; transform: translate(20px, -10px) scale(1.1);"></div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 padding0">
                    <div class="contentLeftSide">
                        <div class="programme-date"><?= @$currentPostData['start_date'] ?></div>
                        <h3><?= @$currentPostData['title'] ?></h3>
                        <a href="<?= @$currentPostData['link'] ?>" class="btnMore">
                            <?= __('More', 'psyeventsmanager') ?>
                            <span>
                                <svg aria-hidden="true" class="e-font-icon-svg e-fas-play-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($postBlockType == 'EventRightImageBlock'): ?>
        <div class="col-md-12 col-lg-6 padding0">
            <div class="row margin0">
                <div class="col-6 col-sm-6 col-md-6 padding0">
                    <div class="contentLeftSide">
                        <div class="programme-date"><?= @$currentPostData['start_date'] ?></div>
                        <h3><?= @$currentPostData['title'] ?></h3>
                        <a href="<?= @$currentPostData['link'] ?>" class="btnMore">
                        <?= __('More', 'psyeventsmanager') ?>
                            <span>
                                <svg aria-hidden="true" class="e-font-icon-svg e-fas-play-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 padding0">
                    <div class="leftImageSide">
                        <div class="imageInnerSide bgImageknow" style="background: url(<?= @$currentPostData['image'] ?>) center center no-repeat; transform: translate(20px, -10px) scale(1.1);"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($postBlockType == 'ProgramLeftImageBlock'): ?>
        <div class="col-md-12 col-lg-6 padding0">
            <div class="row margin0">
                <div class="col-6 col-sm-6 col-md-6 padding0">
                    <div class="leftImageSide">
                        <div class="imageInnerSide bgImageknow" style="background: url(<?= @$currentPostData['image'] ?>) center center no-repeat; transform: translate(20px, -10px) scale(1.1);"></div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 positionSec padding0">
                    <div class="programme-category Xtid-bg-blue positionSec">
                        <img src="<?= @$currentPostData['category']->image_url ?>" class="psyemCatImg rounded-circle">
                    </div>
                    <div class="contentLeftSide">
                        <div class="programme-date"><?= @$currentPostData['date'] ?></div>
                        <h3><?= @$currentPostData['title'] ?></h3>
                        <a href="<?= @$currentPostData['link'] ?>" class="btnMore"> <?= __('More', 'psyeventsmanager') ?>
                            <span>
                                <svg aria-hidden="true" class="e-font-icon-svg e-fas-play-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($postBlockType == 'ProgramRightImageBlock'): ?>
        <div class="col-md-12 col-lg-6 padding0">
            <div class="row margin0">
                <div class="col-6 col-sm-6 col-md-6 positionSec padding0">
                    <div class="programme-category Xtid-bg-blue positionSec">
                        <img src="<?= @$currentPostData['category']->image_url ?>" class="psyemCatImg rounded-circle">
                    </div>
                    <div class="contentLeftSide">
                        <div class="programme-date"><?= @$currentPostData['date'] ?></div>
                        <h3><?= @$currentPostData['title'] ?></h3>
                        <a href="<?= @$currentPostData['link'] ?>" class="btnMore"> <?= __('More', 'psyeventsmanager') ?>
                            <span>
                                <svg aria-hidden="true" class="e-font-icon-svg e-fas-play-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 padding0">
                    <div class="leftImageSide">
                        <div class="imageInnerSide bgImageknow" style="background: url(<?= @$currentPostData['image'] ?>) center center no-repeat; transform: translate(20px, -10px) scale(1.1);"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php endif; ?>
<?php return ob_get_clean(); ?>