
<section class="recipes-grid-container">
<h2><?php echo $title ?></h2>
<?php
if ($search == 1) {
    include plugin_dir_path(__FILE__) . 'templates/search-form.php';
}
$format = $format == 'list' ? 'recipes-main-list' : 'recipes-main-masonry';
$list_br = $format == 'recipes-main-list' ? '</br>' : '';

?>

<div class="<?php echo $format ?>">
    
    <?php foreach ($recipes as $recipe) {
    ?> 
    <?php
    $recipe_slug = sanitize_title($recipe['title']);
    $site_url = get_site_url(); ?>
    <a href="<?php echo $site_url; ?>/viewrecipe/<?php echo $recipe['id']; ?>?title='<?php echo $recipe_slug; ?>'">
    <article class="recipes-all-recipes">
    <img class="recipes-main-img" src="<?php echo $recipe['image']; ?>" width="550">
    <div class="recipes-grid-body" >
    <h3> <?php echo $recipe['title']; ?></h3>
    <span>
    <?php echo $recipe['likes']; ?> 
            <img style="width:20px;  display: inline; margin-left: 5px;" src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'recipes/images/popular.svg'; ?>">
            
         
    </span>
    </div>
    <!-- <span>
    <i class="fas fa-adjust"></i> </span> -->
    </article>
    </a> 
    <?php echo $list_br ?>
    <?php
} ?>
</div>
</section>

