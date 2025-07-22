<?php ob_start(); ?>
<?php
/**
 * Template Name: Psyem Events List shortcode
 */
?>
<?php
$REQData        = (isset($_GET) && !empty($_GET)) ? $_GET : [];
$searchq        = (isset($REQData['search_key'])) ? $REQData['search_key'] : '';
$search_filter  = (isset($REQData['search_filter'])) ? $REQData['search_filter'] : '';
$cpage          = (isset($REQData['cpage'])) ? $REQData['cpage'] : '';
$REQData['limit']        = 11;
$REQData['search_filter'] = $search_filter;
$REQData['search_key']   = $searchq;
$REQData['cpage']        = $cpage;
$REQData['from_date']    = '';
$REQData['to_date']      = '';
$REQData['signup_type']  = '';

$AllEventsInfo  = psyem_GetAllEventsForOrder($REQData);
$AllEventsData  = @$AllEventsInfo['data'];
$PaginationData = @$AllEventsInfo['pagination'];
?>

<div class="psyemEventsListCont" style="display: none;">
    <div class="searchSection">
        <div class="container-fuild">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-6">
                    <form action="" method="get" class="psyemPostListSearchForm">
                        <input type="submit" value="" class="search-submit">
                        <input type="hidden" name="search_filter" value="<?= $search_filter ?>" class="search_filter">
                        <input type="search" name="search_key" class="search-text" <?= (!empty($searchq)) ? 'autofocus="autofocus"' : '' ?> placeholder="<?= __('Search', 'psyeventsmanager') ?>..." autocomplete="off" value="<?= $searchq ?>">
                    </form>
                </div>
                <div class="col-md-6">
                    <ul class="categoryTabs">
                        <li class="psyemApplyFilterBtn" data-filter="upcoming">
                            <a href="javascript:void(0);" class="<?= ($search_filter == 'upcoming') ? 'actual-active' : '' ?>">
                                <?= __('UPCOMING EVENTS', 'psyeventsmanager') ?>
                            </a>
                        </li>
                        <li class="psyemApplyFilterBtn" data-filter="past">
                            <a href="javascript:void(0);" class="<?= ($search_filter == 'past') ? 'actual-active' : '' ?>">
                                <?= __('PAST EVENTS', 'psyeventsmanager') ?>
                            </a>
                        </li>
                        <li class="psyemApplyFilterBtn" data-filter="all">
                            <a href="javascript:void(0);" class="<?= ($search_filter == 'all') ? 'actual-active' : '' ?>">
                                <?= __('VIEW ALL', 'psyeventsmanager') ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php
    $largeBlock  = '';
    $firstBlock  = '';
    $secondBlock = '';
    $RowBlocks   = '';
    $postBlockType = '';

    /* firstSectionBlog - BGN */
    if (!empty($AllEventsData) && is_array($AllEventsData)):
        $pcount = 0;
        $ccount = 0;
        foreach ($AllEventsData as $cpostid => $currentPostData):
            $pcount++;
            $postBlockType = '';

            if (($pcount == 1)) {
                $postBlockType = 'EventLargeImage';
                $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                    $largeBlock = require $pfFilePath;
                    $largeBlock = trim($largeBlock);
                }
            }

            if (($pcount == 2)) {
                $postBlockType = 'EventLeftImage';
                $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                    $firstBlock = require $pfFilePath;
                    $firstBlock = trim($firstBlock);
                }
            }

            if (($pcount == 3)) {
                $postBlockType = 'EventRightImage';
                $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                    $secondBlock = require $pfFilePath;
                    $secondBlock = trim($secondBlock);
                }
                break;
            }
        endforeach;

        echo '
            <div class="firstSectionBlog">
                <div class="row margin0 w-100">
                    ' . $largeBlock . '
                    <div class="col-md-12 col-lg-6 padding0">
                        ' . $firstBlock . '
                        ' . $secondBlock . '
                    </div>
                </div>
            </div>';
    endif;
    /* firstSectionBlog - END */

    /*  secondSectionFold - BGN */
    echo '<div class="secondSectionFold">
        <div class="row margin0 w-100">';
    if (!empty($AllEventsData) && is_array($AllEventsData)):
        $pcount = 0;
        $ccount = 0;
        foreach ($AllEventsData as $cpostid => $currentPostData):
            $pcount++;
            $postBlockType = '';
            if (($pcount == 1) || ($pcount == 2) || ($pcount == 3)) {
                continue;
            }
            if ($pcount > 3) {
                $ccount++;
                if (($ccount == 1)  || ($ccount == 2)) {
                    $postBlockType = 'EventLeftImageBlock';
                    $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                    if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                        $firstBlockHtml = require $pfFilePath;
                        echo $firstBlockHtml = trim($firstBlockHtml);
                    }
                }

                if (($ccount == 3)  || ($ccount == 4)) {
                    $postBlockType = 'EventRightImageBlock';
                    $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                    if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                        $secondBlockHtml = require $pfFilePath;
                        echo $secondBlockHtml = trim($secondBlockHtml);
                    }
                }

                if (($ccount == 4)) {
                    $ccount = 0;
                }
            }
        endforeach;
    endif;
    echo '</div></div>';
    ?>

    <?php if (!empty($AllEventsData) && is_array($AllEventsData) && count($AllEventsData) > 0): else: ?>
        <div class="secondSectionFold">
            <div class="row margin0 w-100">
                <div class="col-md-12 text-center pAll-0">
                    <div class="alert alert-danger">
                        <?= __('No records found', 'psyeventsmanager') ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row text-center mt-4 mb-4">
        <div class="col-md-12 text-center">
            <div class="psyPagination page_nav pagination" style="justify-content: normal; margin: 0px;">
                <?php
                echo paginate_links(array(
                    'base'      => add_query_arg('cpage', '%#%'),
                    'prev_text' => __('<<'),
                    'next_text' => __('>>'),
                    'total'     => @$PaginationData['total_page'] ?? 0,
                    'current'   => max(1, get_query_var('cpage'))
                ));
                ?>
            </div>
        </div>
    </div>
</div>
<?php return ob_get_clean(); ?>