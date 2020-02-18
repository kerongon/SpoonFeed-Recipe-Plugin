<?php
get_header();
$query = $_GET['s'];
$data = $this->getData();
$filter_query = preg_replace('#\s+#', ',', trim($query));
$recipes = $this->getSearchedRecipes(
    $data['private_key'],
    $data['number'],
    $data['ingredients'],
    $data['licence'],
    $data['ranking'],
    $filter_query
);
?>

    <?php if (isset($query) & $_GET['s'] != '') {
    ?>
 
    <section class="recipes-grid-container">
    <h2>Results for "<?php echo $query ?>"</h2>

    <div class="recipes-main-masonry">
    <?php foreach ($recipes as $recipe) {
        ?> 
            <?php $recipe_slug = sanitize_title($recipe['title']); ?>
            <a href="<?php echo $site_url; ?>/viewrecipe/<?php echo $recipe['id']; ?>?title='<?php echo $recipe_slug; ?>'">
            <article class="recipes-all-recipes">
            <img src="<?php echo $recipe['image']; ?>" width="550">
            <div class="recipes-grid-body" >
            <h3> <?php echo $recipe['title']; ?></h3>
            <span>
            <?php echo $recipe['likes']; ?>
            <img style="width:20px" src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/popular.svg'; ?>">
            <i class="fas fa-adjust"></i> </span>
            </div>
            </article>
        </a>  
        <?php
    } ?> 
    </div>
    </div>

    <?php
} else {
        ?>
    <p>No Data to Display</p>

    <?php
    } ?>
<?php get_footer(); ?>