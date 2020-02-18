<div class="wrap">
<h3><?php _e('Recipes API Settings', 'recipes'); ?></h3>

    <p>
    <?php _e('More details on this API at https://spoonacular.com/food-api/docs/find-recipes-by-ingredients.', 'recipes'); ?>
    </p>

    <hr>

    <form id="recipes-admin-form">

<table class="form-table">
            <tbody>
      
                <tr>
                    <td scope="row">
                        <label><?php _e('Private key', 'recipes'); ?></label>
                    </td>
                    <td>
                        <input name="recipes_private_key"
                               id="recipes_private_key"
                               class="regular-text"
                               value="<?php echo (isset($data['private_key'])) ? $data['private_key'] : ''; ?>"/>
                    </td>
                </tr>
                <tr>
     
                </tr>
                
            
                <?php if (!empty($data['private_key'])) : ?>
                    
                    <?php
                    // if we don't even have a response from the API
                    //  && !empty($data['private_key'])
                    if (empty($recipes)) : ?>

                        <tr>
                            <td>
                                <p class="notice notice-error">
                                    <?php _e('An error happened on the WordPress side. Make sure your server allows remote calls.', 'recipes'); ?>
                                </p>
                            </td>
                        </tr>

                    <?php
                    // If we have an error returned by the API
                    elseif (isset($recipes['message'])): ?>

                        <tr>
                            <td>
                                <p class="notice notice-error">
                                    <?php echo $recipes['message']; ?>
                                </p>
                            </td>
                        </tr>

                    <?php
                    // If the recipes were returned
                    else: ?>
                        <tr>
                
                        <td>
                        
                            <h4><?php _e('Spoonacular  API Options', 'recipes'); ?></h4>
                        
                        </td>
                    
                        </tr>
                        <tr>
                            <td>
                                <p class="notice notice-success">
                                    <?php _e('The API connection to https://spoonacular.com successful!', 'recipes'); ?>
                                </p>

                                <div>
                                    <label><?php _e('Choose ingredients (ex beef, flour)', 'recipes'); ?></label>
                                </div>
                                <input name="recipes_ingredients"
                                       id="recipes_ingredients"
                                       class="regular-text"
                                       value="<?php echo (isset($data['ingredients'])) ? $data['ingredients'] : 'beef'; ?>"/>
                                       <div>
                                    <label><?php _e('A comma-separated list of ingredients that the recipes should contain.', 'recipes'); ?></label>
                                </div>
                                       <hr>
                                       
                            </td>
                            
                        </tr>
                       
                        <tr>

                            <td>
                            <div class="label-holder">
                                <label><?php _e('Number of recipes to show (from 0 to 5000)', 'recipes'); ?></label>
                            </div>
                                <input name="recipes_number"
                                       id="recipes_number"
                                       class="regular-text"
                                       type="number"
                                       value="<?php echo (isset($data['number'])) ? $data['number'] : '10'; ?>"/>
                            </td>
                           
                            <td>
                                <div class="label-holder">
                                    <label><?php _e('Ranking', 'recipes'); ?></label>
                                </div>
                                <select name="recipes_ranking"
                                        id="recipes_ranking">
                                    <option value="1" <?php echo (!isset($data['ranking']) ||  (isset($data['ranking']) && $data['ranking'] == 1)) ? 'selected' : ''; ?>>
                                        <?php _e('Maximize ingredient use first', 'recipes'); ?>
                                    </option>
                                    <option value="2"  <?php echo ((isset($data['ranking']) && $data['ranking'] == 2)) ? 'selected' : ''; ?>>
                                        <?php _e('Minimize missing ingredients first', 'recipes'); ?>
                                    </option>
                                </select>
                            </td>
                            <td>
                                <div class="label-holder">
                                    <label><?php _e('License', 'recipes'); ?>  </label>
                                </div>
                                <select name="recipes_license"
                                        id="recipes_license">
                                    <option selected value="true" <?php echo (!isset($data['license']) || (isset($data['license']) && $data['license'] === 'true')) ? 'selected' : ''; ?>>
                                        <?php _e('All', 'recipes'); ?>
                                    </option>
                                    <option  value="false" <?php echo ((isset($data['license']) && $data['license'] === 'false')) ? 'selected' : ''; ?>>
                                        <?php _e('Only Attribution', 'recipes'); ?>
                                    </option>
                                </select>
                            </td>
                        </tr>
                        
                    <?php endif; ?>

                <?php else: ?>

                    <tr>
                        <td>
                            <p>Please fill in your API's private key to see  plugin options.</p>
                        </td>
                    </tr>

                <?php endif; ?>

                <tr>
                    <td colspan="2">
                        <button class="button button-primary" id="recipes-admin-save" type="submit"><?php _e('Save', 'recipes'); ?></button>
                    </td>
                </tr>
                <!-- Shortcodes -->
                 <!-- Short code data -->  
                 <?php if (!empty($data['private_key'])) : ?>                    
                 <tr>
                        <td>
                        <div class="label-holder">
                            <h4><?php _e('Shortcodes', 'recipes'); ?></h4>
                            </div>
                            <p><strong>Single Recipe Link:</strong> [recipes_single recipe_id=""]
                              <strong>Optional Params:</strong> class="" style="" title="" text=""
                            </p>
                            <p><strong>Bulk Recipe Loop:</strong> [recipes_loop amount="10"]
                            <strong>Optional Params: </strong>search="1" format="list" title=""
                            </p> 
                        </td>

                        <td>
                                <div class="label-holder">
                                    <h4><?php _e('Pages', 'recipes'); ?></h4>
                                </div>
                                <p><strong>All Recipes:</strong>  <a target="_blank" href="<?php echo get_site_url() ?>/all-recipes"><?php echo get_site_url() ?>/all-recipes</a></p>
                            <p><strong>Single Recipe:</strong> <?php echo get_site_url() ?>/viewrecipe/{id}?title={title}</p>
                        </td>
                        </tr>
                        
                             <!-- End Short code data -->
                <?php endif; ?>
                
            </tbody>
        </table>

    </form>

</div>

