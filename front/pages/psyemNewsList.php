<?php ob_start(); ?>

<?php
$REQData                 = (isset($_GET) && !empty($_GET)) ? $_GET : [];
$searchq                 = (isset($REQData['search_key'])) ? $REQData['search_key'] : '';
$searchCat               = (isset($REQData['search_cat'])) ? $REQData['search_cat'] : 0;
$cpage                   = (isset($REQData['cpage'])) ? $REQData['cpage'] : '';

$REQData['limit']        = 11;
$REQData['search_key']   = $searchq;
$REQData['search_cat']   = $searchCat;
$REQData['cpage']        = $cpage;
$REQData['post_type']    = 'psyem-news';
$REQData['meta_prefix']  = '';
$REQData['taxonomy']     = 'psyem-news-category';

$AllPostsData            = psyem_GetAllListingPosts($REQData);
$AllPosts                = @$AllPostsData['data'];
$PaginationData          = @$AllPostsData['pagination'];
$AllNewsCategories       = psyem_GetAllCategoriesWithPosts('psyem-news-category', true);
?>

<style>
    .site-main {
        overflow: hidden;
        max-width: 100% !important;
    }
</style>

<div class="psyemListingCont" style="display: none;">
    <div class="searchSection">
        <div class="container-fuild">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-4">
                    <form action="" method="get" class="psyemPostListSearchForm">
                        <input type="submit" value="" class="search-submit">
                        <input type="hidden" name="search_cat" value="" class="search_cat">
                        <input type="search" name="search_key" <?= (!empty($searchq)) ? 'autofocus="autofocus"' : '' ?> class="search-text" placeholder="<?= __('Search', 'psyeventsmanager') ?>..." autocomplete="off" value="<?= $searchq ?>">
                    </form>
                </div>
                <div class="col-md-8">
                    <?php if (!empty($AllNewsCategories) && is_array($AllNewsCategories) && count($AllNewsCategories) > 0): ?>
                        <ul class="categoryTabs">
                            <?php foreach ($AllNewsCategories as $ccatid => $ccatInfo): ?>
                                <li class="psyemShowCatCont" data-term="<?= @$ccatInfo['term_id'] ?>">
                                    <img src="<?= @$ccatInfo['term_image'] ?>" alt="<?= @$ccatInfo['term_name'] ?>" class="psyemCatImg rounded-circle" style="width: 30px;" />
                                    <a href="javascript:void(0);" class="<?= ($searchCat == @$ccatInfo['term_id']) ? 'actual-active' : '' ?>">
                                        <?= @$ccatInfo['term_name'] ?> (<?= @$ccatInfo['term_count'] ?>)
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <li class="psyemShowAllCats">
                                <a href="javascript:void(0);">
                                    <?= __('View All', 'psyeventsmanager') ?>
                                </a>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <?php
    $largeBlock  = '';
    $firstBlock  = '';
    $secondBlock = '';
    $RowBlocks   = '';

    /*  firstSectionBlog - BGN */
    if (!empty($AllPosts) && is_array($AllPosts)):
        $pcount = 0;
        $ccount = 0;
        foreach ($AllPosts as $cpostid => $currentPostData):
            $pcount++;
            $postBlockType = '';

            if (($pcount == 1)) {
                $postBlockType = 'LargeImage';
                $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                    $largeBlock = require $pfFilePath;
                    $largeBlock  = trim($largeBlock);
                }
            }

            if (($pcount == 2)) {
                $postBlockType = 'LeftImage';
                $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                    $firstBlock = require $pfFilePath;
                    $firstBlock  = trim($firstBlock);
                }
            }

            if (($pcount == 3)) {
                $postBlockType = 'RightImage';
                $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                    $secondBlock = require $pfFilePath;
                    $secondBlock  = trim($secondBlock);
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
    /*  firstSectionBlog - END */

    /*  secondSectionFold - BGN */
    echo '<div class="secondSectionFold">
      <div class="row margin0 w-100">';
    if (!empty($AllPosts) && is_array($AllPosts)):
        $pcount = 0;
        $ccount = 0;
        foreach ($AllPosts as $cpostid => $currentPostData):
            $pcount++;
            $postBlockType = '';
            if (($pcount == 1) || ($pcount == 2) || ($pcount == 3)) {
                continue;
            }
            if ($pcount > 3) {
                $ccount++;
                if (($ccount == 1)  || ($ccount == 2)) {
                    $postBlockType = 'LeftImageBlock';
                    $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                    if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                        $firstBlockHtml = require $pfFilePath;
                        echo $firstBlockHtml = trim($firstBlockHtml);
                    }
                }

                if (($ccount == 3)  || ($ccount == 4)) {
                    $postBlockType = 'RightImageBlock';
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

    /*  secondSectionFold - END */

    if (!empty($AllPosts) && is_array($AllPosts) && count($AllPosts) > 0): ?>
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
    <?php else: ?>
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

</div>
<?php return ob_get_clean(); ?>