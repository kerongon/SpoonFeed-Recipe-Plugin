<?php
/*
 * Template Name: All Recipes
 */

get_header();
$site_url = get_site_url();

?>




<!--<div id="primary" class="content-area">

    <main id="main" class="site-main" role="main">

    </main>
        
  
</div>-->

 <?php if (!empty($private_key)) {
    ?> <?php
} ?> 
<section class="recipes-grid-container">
<?php
  include plugin_dir_path(__FILE__) . '/search-form.php';
?>
<h2>Recipes</h2>
  <div class="recipes-main-masonry">
      
        <?php
        $data = $this->getData();
        $recipes = $this->getRecipes($data['private_key'], $data['number'], $data['ingredients'], $data['licence'], $data['ranking']);

        ?>
 
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
       </span>
            </div>
            </article>
        </a>  
        <?php
        } ?> 
       
        <div class="page-load-status">
  <div class="loader-ellips infinite-scroll-request">
    <span class="loader-ellips__dot"></span>
    <span class="loader-ellips__dot"></span>
    <span class="loader-ellips__dot"></span>
    <span class="loader-ellips__dot"></span>
  </div>
  <p class="infinite-scroll-last">End of content</p>
  <p class="infinite-scroll-error">No recipes to load</p>
</div>  
</div>
</section><br><br>



<script src="https://unpkg.com/infinite-scroll@3/dist/infinite-scroll.pkgd.min.js"></script>
<script>


var infScroll = new InfiniteScroll( '.main-masonry', {
//     path: function() {
//     return 'http://recipes.test/all-recipes/'
//    '&page=' + this.pageIndex;
//   },
//   append: '.all-recipes',
  debug           : true,
  status: '.page-load-status',
  path: 'page{{#}}', // hack
  loadOnScroll: false, // disable loading
  history: false,
    // dataType        : 'html',
    // maxPage         : 5,
//   status: '.page-load-status',
});


</script>
<?php get_footer();
