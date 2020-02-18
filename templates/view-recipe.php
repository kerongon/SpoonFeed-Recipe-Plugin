
<?php get_header(); ?>
<?php
        $page_id = get_query_var('recipe_id');
        $site_url = get_site_url();
        $api_url = 'https://spoonacular-recipe-food-nutrition-v1.p.mashape.com/recipes/'.$page_id.'/information';
        $data = $this->getData();
        $private_key = $data['private_key'];
        $args = array( 'headers' => array(
            'Accept' => 'application/json',
            'X-Mashape-Key' => $private_key)
        );
        $response = wp_remote_get($api_url, $args);
        $similar_url = 'https://spoonacular-recipe-food-nutrition-v1.p.mashape.com/recipes/'.$page_id.'/similar';
        $similar_response = wp_remote_get($similar_url, $args);
        
        if (! 200 == wp_remote_retrieve_response_code($response) || ! 200 == wp_remote_retrieve_response_code($similar_response)) {
            return false;
        }
        $spoon_recipe = json_decode(wp_remote_retrieve_body($response), true);
        $similar_recipes = json_decode(wp_remote_retrieve_body($similar_response), true);
        ?>
<?php


?>
 <?php if (!empty($private_key)) {
    ?> 
         
<!-- class="content-area" -->
<div id="primary" >
    <main id="main" class="site-main" role="main">
   

       
            <!-- Start Container -->
            <div class="recipes-container" id="recipes-single-view">
                
            <h1 style="text-align:center"><?php echo $spoon_recipe['title']; ?></h1>
            <img class="recipes-center-img recipes-single-main-image" src="<?php echo $spoon_recipe['image']; ?>" width="250">
            <div class="recipes-row">
            <?php if ($spoon_recipe['glutenFree'] == true) {
        ?> 
            <div class="recipes-col-3">
            <div class="recipes-badge">
            <img class="recipes-center-img " src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/gluten-free.svg'; ?>">
            <span class="recipes-center">Yes</span>
            </div>
            </div>
             <?php
    } else {
        ?>
            <div class="recipes-col-3">
            <div class="recipes-badge">
            <img class="recipes-center-img " src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/gluten-free.svg'; ?>">
            <span class="recipes-center">NO</span>
            </div>
            </div>
            <?php
    } ?> 

        <?php if ($spoon_recipe['dairyFree'] == true) {
        ?> 
            <div class="recipes-col-3">
            <div class="recipes-badge">
            <img class="recipes-center-img " src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/dairy-free.svg'; ?>">
            <span class="recipes-center">Yes</span>
            </div>
            </div>
             <?php
    } else {
        ?>
            <div class="recipes-col-3">
            <div class="recipes-badge">
            <img class="recipes-center-img " src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/dairy-free.svg'; ?>">
            <span class="recipes-center">NO</span>
            </div>
            </div>
            <?php
    } ?> 
         <?php if ($spoon_recipe['preparationMinutes'] == true) {
        ?> 
        <div class="recipes-col-3">
            <div class="recipes-badge">
            <img class="recipes-center-img " src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/fast.svg'; ?>">
            <span class="recipes-center"> Prep: <?php echo $spoon_recipe['preparationMinutes'] ?> mins</span>
            </div>
            </div>
            <?php
    } ?> 

            <?php if ($spoon_recipe['cookingMinutes'] == true) {
        ?> 
        <div class="recipes-col-3">
            <div class="recipes-badge">
            <img class="recipes-center-img " src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/fast.svg'; ?>">
            <span class="recipes-center"> Cook: <?php echo $spoon_recipe['cookingMinutes'] ?> mins</span>
            </div>
            </div>
            <?php
    } ?> 

            </div>
      
           
            <div class="recipes-row">
            <br><br>     
              <h4>INGREDIENTS</h4>

         <?php foreach ($spoon_recipe['extendedIngredients'] as $extended) {
        ?> 
            <!-- <div class="card-container card--fixedWidth">
             
            </div> -->


          <div class="recipes-col-6">
  
        <div class='recipes-ing-container'>
            <div> 
            <?php if ($extended['image']) {
            ?> 
            <img  src="https://spoonacular.com/cdn/ingredients_100x100/<?php echo $extended['image']; ?>" class='recipes-icon-details'>
            <?php
        } ?> 
        </div>
            <div style='margin-left:60px;'>
            <h4><?php echo ucwords($extended['name']); ?> </h4>
            <span>Aisle: <?php echo $extended['aisle']; ?></span><br>
            <span> Direction: <?php echo $extended['originalString']; ?></span><br>
            <span>Amount #: <?php echo $extended['amount']; ?> <?php echo $extended['unit']; ?></span>
            </div> 
            </div>
        </div> 

    <?php
    } ?> 
    
    </div>
  <?php if ($spoon_recipe['analyzedInstructions']) {
        ?> 
<div class="recipes-row">
            <br><br>     
              <h4>PREPARATION</h4>
        <div class="recipes-col-12">
    
    <?php foreach ($spoon_recipe['analyzedInstructions'] as $steps) {
            ?>
            <!-- Start Steps -->
            
            <ol class="recipes-list-numbered">
         <?php foreach ($steps['steps'] as $step) {
                ?> 

                  
                
               
        <?php
        $numbers_array = ['1', '2','3','4','5','6','7','8', '9','10']; ?>       
        <li> 
        <?php if (in_array($step['step'], $numbers_array)) {
            ?>
             <?php echo 'Prep for next step' ?>  
        <?php
        } else { ?>
          <?php echo $step['step']; ?>  
          <?php
                    } ?>
         </li>
       
        <?php if ($step['ingredients']) {
                        ?> 
                    
                     <ul>
        <strong>Required Ingredients: </strong><br>
        <?php foreach ($step['ingredients'] as $ingredients) {
                            ?> 
                    <div class="recipes-main-list">
                    <?php echo ucfirst($ingredients['name']); ?> <img  src="https://spoonacular.com/cdn/ingredients_100x100/<?php echo $ingredients['image']; ?>" class='recipes-icon-details'>  
                    </div>

               <!-- <img  src="https://spoonacular.com/cdn/ingredients_100x100/<?php echo $ingredients['image']; ?>" class='recipes-icon-details'>   -->
                
            
                <?php
                        } ?>
                    </ul>
        <br><br>
                 <?php
                    } ?>

                <?php if (count($step['ingredients']) >= 9) {
                        ?>
                <br><br>
                <?php
                    } ?>
        
        
     

        <ul> 
         <?php if ($step['equipment']) {
                        ?> 
        <strong>Equipment: </strong><br>
        <?php foreach ($step['equipment'] as $equipment) {
                            ?> 
                     
                        <div class="recipes-main-equip-list">
                    <?php echo ucfirst($equipment['name']); ?> 
                    <!-- <img  src="https://spoonacular.com/cdn/ingredients_100x100/<?php echo $equipment['image']; ?>" class='recipes-icon-details'>   -->
        </div>
                <?php
                        } ?>
        <?php
                    } ?> 
            </ul> 

        <?php
            } ?>
        <!-- End Equipments -->
            
            </ol>
            
        <?php
        } ?> 
        <!-- End Steps -->
        </div>
              </div>
              <?php
    } ?>
            
            <!-- Start Instructions -->
            <?php if ($spoon_recipe['instructions']) {
        ?>
             <div class="recipes-instruction-area">
              <div class="recipes-row">
                <h4>Instructions</h4>
                  <p class="recipes-justify"><?php echo $spoon_recipe['instructions']; ?></p>
              </div>
             </div>
            <?php
    } ?>
            <!-- End Instructions -->



                <!-- Start Wine Paring Area -->
              <?php if ($spoon_recipe['winePairing']==null || empty($spoon_recipe['winePairing'])) {
        ?>

                <?php
    } else { ?>

              <div class="recipes-wine-area">
              <div class="recipes-row">
              <h4>Wine Pairing</h4>
                  <p><?php echo $spoon_recipe['winePairing']['pairingText']; ?></p>

              </div>
                <?php foreach ($spoon_recipe['winePairing']['productMatches'] as $productm) { ?>
              
              <div class="recipes-row">
              <h5>Product Matches</h5>
          
                       
                 <div class="recipes-col-5">
                        <span> <strong>Title:</strong> <?php echo($productm['title']); ?> </span><br>
                        
                </div>
                <div class="recipes-col-3">
                <span> <strong>Price:</strong> <?php echo($productm['price']); ?> </span><br>

                </div>  
                
                <div class="recipes-col-4">
                <span> <strong>Average Rating:</strong> <?php echo($productm['averageRating'] * 100); ?> %</span><br>

                </div> 
                     
            </div>
        <div class="recipes-row">
        <div class="recipes-col-2">
            <img class="recipes-center-img " src="<?php echo $productm['imageUrl'] ?>">
        </div>
        <div class="recipes-col-10">
           
            <?php if ($productm['description']) {
        ?>
                <span> <strong>Description:</strong> <?php echo($productm['description']); ?> </span>

            <?php
    } ?>
                    <p>

                    <a  target="_blank" href="<?php echo($productm['link']); ?>">Link</a>
                    </p>
                    
                    
                        </div>

        </div>
                <?php } ?>
                </div>
              
                <!-- End Wine Paring Area -->
                



    <?php } ?> 
    <?php if ($spoon_recipe['diets'] || $spoon_recipe['occasions']) {
        ?>
    <div class="recipes-row">
                    <div class="recipes-col-6">
                    <?php if ($spoon_recipe['diets']) {
            ?>
                        <strong>Diets: </strong><span class="diet-barge"><?php echo implode(', ', $spoon_recipe['diets']); ?></span>
                        <?php
        } ?>
                    </div>
                    <div class="recipes-col-6">
                    <?php if ($spoon_recipe['occasions']) {
            ?>
                    <strong>Occasions: </strong><span class="diet-barge"><?php echo implode(', ', $spoon_recipe['occasions']); ?></span>
                    <?php
        } ?>
                    </div>
                </div>
                <?php
    } ?>

                <!-- Extra -->
            <div class="recipes-row">

                 <div class="recipes-col-3">
            <div class="recipes-badge-bot">
            <!-- <img class="recipes-center-img " src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/fast.svg'; ?>"> -->
            <span class="recipes-center"> <strong>Ready: <?php echo $spoon_recipe['readyInMinutes'] ?>  mins</strong> </span>
        
            </div>
            </div>

             <div class="recipes-col-3">
            <div class="recipes-badge-bot">

            <span class="recipes-center"> <strong>Servings: <?php echo $spoon_recipe['servings'] ?> </strong> </span>
            <p class="recipes-center">  </p>
            </div>
            </div> 
                        
            <div class="recipes-col-3">
            <div class="recipes-badge-bot">
            <span class="recipes-center"> <strong>Smart Points: <?php echo $spoon_recipe['weightWatcherSmartPoints'] ?> </strong> </span>

            </div>
            </div>
        
         
            

             <div class="recipes-col-3">
                <?php if ($spoon_recipe['veryPopular']) {
        ?>
            <div class="recipes-badge-bot">
            <!-- <img class="recipes-center-img " src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/popular.svg'; ?>"> -->

            <span class="recipes-center"> <strong>Popular</strong></span>
         
            <!-- <p class="center"> Yes</p> -->
            </div>
                <?php
    } ?>
            </div> 
        


                </div>
      
            
        <h2 class="recipes-related-h2">Related Recipes</h2>   
        <?php foreach ($similar_recipes as $similar) { ?>
        <!-- Recipe Start -->
        <?php if ($similar['image']) {
        ?>
            <?php $similar_slug = sanitize_title($similar['title']); ?>
        <div class="recipes-related-card"> 
            <div class="recipes-related-image">
                <img src="https://webknox.com/recipeImages/<?php echo $similar['image'] ?> "> 
               
                <div class="recipes-related-name">
                    <a href="<?php echo $site_url; ?>/viewrecipe/<?php echo $similar['id']; ?>?title='<?php echo $similar_slug; ?>"><h3><?php echo $similar['title'] ?></h3></a>
                </div>
            </div>
            <ul class="recipes-related-media">
            <li><?php echo $similar['servings'] ?> Servings</li>
                <li><?php echo $similar['readyInMinutes'] ?> mins</li>
              
            </ul>
        </div>
        <?php
    } ?> 
        <!-- Recipe End -->
  
        <?php } ?>                   

</div> 
    
    </main><!-- .site-main -->

</div><!-- .content-area -->
<?php
} ?> 

<script>
// Set Title due to wordpress no regonizing the page
var set_title = '<?php echo $spoon_recipe['title']; ?>';
if (document.title != set_title) {
    document.title = set_title;
}

</script>

<?php get_footer();
?>
