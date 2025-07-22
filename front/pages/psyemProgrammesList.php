<?php ob_start(); ?>

<?php
$REQData                 = (isset($_GET) && !empty($_GET)) ? $_GET : [];
$searchq                 = (isset($REQData['search_key'])) ? $REQData['search_key'] : '';
$searchCat               = (isset($REQData['search_cat'])) ? $REQData['search_cat'] : 0;
$cpage                   = (isset($REQData['cpage'])) ? $REQData['cpage'] : '';

$REQData['limit']        = 10;
$REQData['search_key']   = $searchq;
$REQData['search_cat']   = $searchCat;
$REQData['cpage']        = $cpage;
$REQData['post_type']    = 'psyem-programmes';
$REQData['meta_prefix']  = '';
$REQData['taxonomy']     = 'psyem-programmes-category';

$AllPostsData            = psyem_GetAllListingPosts($REQData);
$AllPosts                = @$AllPostsData['data'];
$PaginationData          = @$AllPostsData['pagination'];
$AllNewsCategories       = psyem_GetAllCategoriesWithPosts('psyem-programmes-category', true);
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
                        <input type="search" name="search_key" class="search-text" <?= (!empty($searchq)) ? 'autofocus="autofocus"' : '' ?> placeholder="<?= __('Search', 'psyeventsmanager') ?>..." autocomplete="off" value="<?= $searchq ?>">
                    </form>
                </div>
                <div class="col-md-8">
                    <?php if (!empty($AllNewsCategories) && is_array($AllNewsCategories) && count($AllNewsCategories) > 0): ?>
                        <ul class="categoryTabs">
                            <?php foreach ($AllNewsCategories as $ccatid => $ccatInfo): ?>
                                <li class="psyemShowCatCont" data-term="<?= @$ccatInfo['term_id'] ?>">
                                    <img src="<?= @$ccatInfo['term_image'] ?>" alt="<?= @$ccatInfo['term_name'] ?>" class="psyemCatImg rounded-circle" style="width: 30px;" />
                                    <a href="javascript:void(0);" class="<?=($searchCat == @$ccatInfo['term_id']) ? 'actual-active': ''?>"> 
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

    <div class="secondSectionFold">
        <div class="row margin0 w-100 ">
            <?php
            if (!empty($AllPosts) && is_array($AllPosts)):
                $pcount = 0;
                foreach ($AllPosts as $cpostid => $currentPostData): $pcount++;
                    if (($pcount == 1)  || ($pcount == 2)) {
                        $postBlockType = 'ProgramRightImageBlock';
                        $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                            $secondBlockHtml = require $pfFilePath;
                            echo $secondBlockHtml = trim($secondBlockHtml);
                        }
                    } else {
                        $postBlockType = 'ProgramLeftImageBlock';
                        $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                            $secondBlockHtml = require $pfFilePath;
                            echo $secondBlockHtml = trim($secondBlockHtml);
                        }

                        if (($pcount == 4)) {
                            $pcount = 0;
                        }
                    }
                endforeach;
            ?>
            <?php else: ?>
                <div class="col-md-12 text-center pAll-0">
                    <div class="alert alert-danger">
                        <?= __('No records found', 'psyeventsmanager') ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row text-center mt-4 mb-4">
        <div class="col-md-12 text-center">
            <div class="psyPagination page_nav pagination" style="justify-content: normal; margin: 0px;">
                <?php
                echo paginate_links(array(
                    'base'      => add_query_arg('cpage', '%#%'),
                    'prev_text' => __('<<'),
                    'next_text' => __('>>'),
                    'total'     => @$PaginationData['total_page']  ?? 0,
                    'current'   => max(1, get_query_var('cpage'))
                ));
                ?>
            </div>
        </div>
    </div>
</div>
<?php return ob_get_clean(); ?>