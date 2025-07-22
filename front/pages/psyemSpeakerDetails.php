<?php

/**
 * Template Name: Psyem Events Speaker Details
 */
?>
<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
get_header();
while (have_posts()) : the_post();
    $GET                = (isset($_GET) && !empty($_GET)) ? $_GET : array();
    $SpeakerId          = get_the_ID();
    $psyemSpeakerInfo   = psyem_GetSinglePostWithMetaPrefix('psyem-speakers', $SpeakerId, 'psyem_speaker_');
    $psyemSpeakerMeta   = @$psyemSpeakerInfo['meta_data'];
    $excerpt            = @$psyemSpeakerInfo['excerpt'];
    $fetauredImage      = @$psyemSpeakerInfo['image'];
?>
    <main id="content" <?php post_class('site-main psyemSpeakerInfo'); ?> style="max-width: 100%; overflow: hidden;">
        <section class="topBradcampImage" style="background:url(<?= $fetauredImage ?>); background-position: center; background-repeat: no-repeat; background-size: cover;">
            <div class="container">
                <div class="row justify-content-between mrAll-0">
                    <div class="col-md-12 pAll-0">
                        <p class="bradCamp">
                            <a href="javascript:void(0);">
                                <?= __('Event Speakers', 'psyeventsmanager') ?> >
                            </a>
                            <?= @$psyemSpeakerInfo['title'] ?>
                        </p>
                    </div>
                </div>

                <div class="row justify-content-between align-items-end mrAll-0">
                    <div class="col-md-3 pAll-0 SpeakerImgCont">
                        <img src="<?= $fetauredImage ?>" class="imgWdth" alt="<?= @$psyemSpeakerInfo['title'] ?>" />
                    </div>
                    <div class="col-md-9">
                        <h2 class="gTxt">
                            <?= @$psyemSpeakerInfo['title'] ?>
                            <small class="d-block postSubTitle mt-2">
                                <?= @$psyemSpeakerMeta['psyem_speaker_designation'] ?>
                            </small>
                        </h2>
                    </div>
                </div>
            </div>
        </section>

        <section class="contentDetailSec">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="aboutHeaDetail"><?= __('About the Speaker', 'psyeventsmanager') ?></h3>
                        <div class="post-excerpt mb-5" id="Sp<?= $SpeakerId ?>">
                            <?php
                            echo (!empty($excerpt)) ? $excerpt : '';
                            ?>
                        </div>
                        <div class="post-content mb-5">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="contentPostInfoSec">
            <div class="row">
                <div class="col-md-12">
                    <div class="post-links mb-3">
                        <?php wp_link_pages(); ?>
                    </div>
                </div>
                <div class="col-md-12">
                    <?php if (has_tag()) : ?>
                        <div class="post-tags mb-3">
                            <?php the_tags('<span class="tag-links">' . esc_html__('Tagged ', 'psyeventsmanager'), ', ', '</span>'); ?>
                        </div>
                    <?php endif; ?>
                </div>
        </section>

        <section class="contentCommentsSec">
            <div class="row">
                <div class="col-md-12">
                    <div class="post-comments mb-3">
                        <?php comments_template(); ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php
endwhile;
get_footer();
