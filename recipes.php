<?php
/**
 * Plugin Name:       SpoonFeed Recipes
 * Description:       Query Recipes from  https://spoonacular.com/api/docs/recipes-api
 * Version:           1.0.0
 * Author:            Keron Gonzales.
 * Author URI:        https://kerongonzales.tech
 * Text Domain:       kerongonzales
 * License:           GPL-2.0+
 */
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/*
 * Plugin constants
 */
if (!defined('RECIPES_URL')) {
    define('RECIPES_URL', plugin_dir_url(__FILE__));
}
if (!defined('RECIPES_PATH')) {
    define('RECIPES_PATH', plugin_dir_path(__FILE__));
}
 /**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('RECIPES_VERSION', '1.0.0');
/*
 * Main class
 */
/**
 * Class Recipes
 *
 * This class creates the option page and add the web app script
 */
class Recipes
{
 
    /**
     * Recipes constructor.
     *
     * The main plugin actions registered for WordPress
     */

    /**
    * The security nonce
    *
    * @var string
    */

    /**
     * The option name recipes
     *
     * @var string
     */
    private $option_name = 'recipes_data';
    /**
     * The security nonce
     *
     * @var string
     */
    private $_nonce = 'recipes_admin';
    

    public function __construct()
    {
        add_action('admin_menu', array( $this, 'addAdminMenu' ));
        add_action('wp_ajax_store_admin_data', array( $this, 'storeAdminData' ));
        add_action('admin_enqueue_scripts', array( $this, 'addAdminScripts' ));
        add_action('wp_enqueue_scripts', array( $this, 'addFrontScripts' ));
        add_shortcode('recipes_loop', array( $this, 'allRecipesShortCode'));
        add_shortcode('recipes_single', array( $this, 'singleRecipeShortCode'));
        

        // Page to view all recipes
        add_filter('generate_rewrite_rules', function ($wp_rewrite) {
            $wp_rewrite->rules = array_merge(
                ['all-recipes/?$' => 'index.php?custom-recipe-url=1'],
                $wp_rewrite->rules
            );
        });
        add_filter('query_vars', function ($query_vars) {
            $query_vars[] = 'custom-recipe-url';
            return $query_vars;
        });
        add_action('template_redirect', function () {
            $custom = intval(get_query_var('custom-recipe-url'));
            if ($custom) {
                include plugin_dir_path(__FILE__) . 'templates/all-recipes.php';
                die;
            }
        });

        
        // View Single Recipes
        add_action('init', function () {
            add_rewrite_rule(
                'viewrecipe/([0-9]+)/?',
                'index.php?pagename=viewrecipe&recipe_id=$matches[1]',
                'top'
            );
        }, 10, 0);
        
        add_action('init', function () {
            add_rewrite_tag('%recipe_id%', '([^&]+)');
        }, 10, 0);
        

        add_action('template_redirect', function () {
            $recipe_id = intval(get_query_var('recipe_id'));
            if ($recipe_id) {
                include plugin_dir_path(__FILE__) . 'templates/view-recipe.php';
                die;
            }
        });

        // Search Recipes
        add_filter('generate_rewrite_rules', function ($wp_rewrite) {
            $wp_rewrite->rules = array_merge(
                ['search-all-recipes/?$' => 'index.php?search-recipe-url=1'],
                $wp_rewrite->rules
            );
        });
        add_filter('query_vars', function ($query_vars) {
            $query_vars[] = 'search-recipe-url';
            return $query_vars;
        });
        add_action('template_redirect', function () {
            $custom = intval(get_query_var('search-recipe-url'));
            if ($custom) {
                include plugin_dir_path(__FILE__) . 'templates/search.php';
                die;
            }
        });
        // End Search Recipes


        // Tests to Redirect
        add_action('init', function () {
            add_rewrite_rule(
                '^properties/([0-9]+)/?',
                'index.php?pagename=properties&property_id=$matches[1]',
                'top'
            );
        }, 10, 0);
        
        add_action('init', function () {
            add_rewrite_tag('%property_id%', '([^&]+)');
        }, 10, 0);

        add_action('template_redirect', function () {
            $property_id = intval(get_query_var('property_id'));
            if ($property_id) {
                include plugin_dir_path(__FILE__) . 'templates/recipe.php';
                die;
            }
        });
        
        add_action('wp_loaded', array($this, 'my_flush_rules'));
    }


    

    // Alway run to flush when redirect added.
    public function my_flush_rules()
    {
        $rules = get_option('rewrite_rules');
        if (! isset($rules['(project)/(\d*)$'])) {
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
        }
    }


    private function getData()
    {
        return get_option($this->option_name, array());
    }
    


    private function getRecipes($private_key, $num, $ing, $limitLicense, $ranking)
    {
        // , $ingredients, $limitLicense, $fillIngredients, $number,$ranking
        $data = array();
        $number = $num ? $num : 10;
        $ingredients = $ing ? $ing : 'beef';
        $license =  $limitLicense ? $limitLicense  : true;
        $rank = $ranking ? $ranking : 1;
        $response = wp_remote_get(
            'https://spoonacular-recipe-food-nutrition-v1.p.mashape.com/recipes/findByIngredients?fillIngredients=true
            &ingredients='.$ingredients.'&limitLicense='.$license.'&number='.$number.'&ranking='.$rank,
            array( 'headers' => array(
                'Accept' => 'application/json',
                'X-Mashape-Key' => $private_key)
            )
        );

        if (is_array($response) && !is_wp_error($response)) {
            $data = json_decode($response['body'], true);
        }
    
        return $data;
    }


    private function getSearchedRecipes($private_key, $num, $ing, $limitLicense, $ranking, $search)
    {
        // , $ingredients, $limitLicense, $fillIngredients, $number,$ranking
        $data = array();
        $number = $num ? $num : 10;
        $ingredients = $ing ? $ing : 'beef';
        $license =  $limitLicense ? $limitLicense  : true;
        $rank = $ranking ? $ranking : 1;
        $response = wp_remote_get(
            'https://spoonacular-recipe-food-nutrition-v1.p.mashape.com/recipes/findByIngredients?fillIngredients=true
            &ingredients='.$ingredients.','.$search.'&limitLicense='.$license.'&number='.$number.'&ranking='.$rank,
            array( 'headers' => array(
                'Accept' => 'application/json',
                'X-Mashape-Key' => $private_key)
            )
        );

        if (is_array($response) && !is_wp_error($response)) {
            $data = json_decode($response['body'], true);
        } else {
            $data = 'No Matches';
        }
    
        return $data;
    }

   

    public function storeAdminData()
    {
        if (wp_verify_nonce($_POST['security'], $this->_nonce) === false) {
            die('Invalid Request!');
        }
    
        $data = $this->getData();
        
        foreach ($_POST as $field => $value) {
            if (substr($field, 0, 13) !== "recipes_" || empty($value)) {
                continue;
            }
            // Remove prefix
            $field = substr($field, 13);
    
            $data[$field] = $value;
        }
        update_option($this->option_name, $data);
    
        echo __('Successfully  Saved!', 'recipes');
        die();
    }

    public function addAdminMenu()
    {
        add_menu_page(
            __('Recipes', 'recipes'),
            __('Recipes', 'recipes'),
            'manage_options',
            'recipes',
            array($this, 'adminLayout'),
            plugin_dir_url(__FILE__) . 'images/beef-favicon.png'
            // 'dashicons-carrot'
        );
    }
    // Front End Style
    public function addFrontScripts()
    {
        wp_register_style('front', RECIPES_URL. 'assets/css/style.css', false, 1.0);
        wp_enqueue_style('front');

        wp_register_script('front', RECIPES_URL. 'assets/js/main.js', false, 1.0);
        wp_enqueue_script('front');
        // wp_register_style('cta_stylesheet', plugins_url('/css/rs.css', __FILE__));
        // wp_enqueue_style('cta_stylesheet');
    }


    /**
     * Adds Admin Scripts for the Ajax call
     */
    public function addAdminScripts()
    {
        wp_enqueue_style('recipes-admin', RECIPES_URL. 'assets/css/admin.css', false, 1.0);
        wp_enqueue_script('recipes-admin', RECIPES_URL. '/assets/js/admin.js', array(), 1.0);
        $admin_options = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        '_nonce'   => wp_create_nonce($this->_nonce),
        );
        wp_localize_script('recipes-admin', 'recipes_exchanger', $admin_options);
    }
    
    public function allRecipesShortCode($atts)
    {
        $data = $this->getData();
        $atts = shortcode_atts(array(
            'amount' => '10', // Default 10
            'search' => '0', // 0 - hide 1 - show
            'search_amount' => '',
            'format' => 'masonry', // masonry, list
            'title' => 'Recipes'
        ), $atts);
        extract($atts);
        ob_start();

        if (!empty($data['private_key'])) {
            $recipes = $this->getRecipes($data['private_key'], $amount, $data['ingredients'], $data['licence'], $data['ranking']);
            include plugin_dir_path(__FILE__) . 'all-shortcode.php';
        }
        
        return ob_get_clean();
    }

    public function singleRecipeShortCode($atts)
    {
        $site_url = get_site_url();
        $atts = shortcode_atts(array(
            'recipe_id' => '',
            'class' => '',
            'style' => '',
            'title' => '',
            'text' => ''
        ), $atts);
        extract($atts);
        $html = '<a  title="'.$title.'" style="'.$style.'" class="'.$class.'" href="'.$site_url.'/viewrecipe/'.$recipe_id.'">'.$text.'</a>';
        // echo "$site_url/viewrecipe/$id";
        echo $html;
        ob_start();
        return ob_get_clean();
        // [recipes_single recipe_id="" class="" style=""]
    }

    // Admin Layout Still Need to be redone

    public function adminLayout()
    {
        $data = $this->getData();
 
        $recipes = $this->getRecipes(
            $data['private_key'],
            $data['number'],
            $data['ingredients'],
            $data['licence'],
            $data['ranking']
        ); ?>
    
        <?php
        include plugin_dir_path(__FILE__) . 'admin.php';
    }
}
 
/*
 * Start
 */
new Recipes();
