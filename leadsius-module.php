<?php
/*
Plugin Name: Leadsius - Wordpress Plugin
Description: WP-Leadsius is a WordPress plugin which integrates Leadsius and WordPress.
With WP-Leadsius you can add Leadsius tracking script to your Wordpress site as well as creating web forms and call-to-actions.
Version: 1.0.2
Author: Leadsius AB
*/

/*error_reporting(E_ALL); 
ini_set( 'display_errors','1');*/
define('INSIGHTS_DS', DIRECTORY_SEPARATOR);
define('INSIGHTS_PATH', dirname(__FILE__) . INSIGHTS_DS);
define('INSIGHTS_TEMPLATES_PATH', INSIGHTS_PATH . 'templates' . INSIGHTS_DS);
//define('INSIGHTS_STATIC_URL', home_url('/wp-content/themes/saab-orio/'));
define('INSIGHTS_HOME_TEMPLATE_NAME', 'insights-home.php');
define('INSIGHTS_ASSETS_URL', plugins_url('/assets',__FILE__));
require_once(INSIGHTS_PATH . 'lib' . INSIGHTS_DS . 'LeadsiusAPIGateway.php');
require_once(INSIGHTS_PATH . 'functions' . INSIGHTS_DS . 'helpers.php');
//require_once(INSIGHTS_PATH . 'functions' . INSIGHTS_DS . 'insights-custom-post.php');
//




//require_once(INSIGHTS_PATH . 'functions' . INSIGHTS_DS . 'insights-settings-page.php');
require_once(INSIGHTS_PATH . 'functions' . INSIGHTS_DS . 'widget-leadsius-cta.php');
//require_once(INSIGHTS_PATH . 'functions' . INSIGHTS_DS . 'widget-leadsius-tablist.php');
require_once(INSIGHTS_PATH . 'functions' . INSIGHTS_DS . 'widget-leadsius-form.php');


//require_once(INSIGHTS_PATH . 'functions' . INSIGHTS_DS . 'widget-leadsius-articles.php');


require_once(INSIGHTS_PATH . 'functions' . INSIGHTS_DS . 'leadsius-forms-editor-option.php');
require_once(INSIGHTS_PATH . 'functions' . INSIGHTS_DS . 'leadsius-forms-shortcodes.php');
require_once(INSIGHTS_PATH . 'functions' . INSIGHTS_DS . 'leadsius-cta-shortcodes.php');
require_once(INSIGHTS_PATH . 'include' . INSIGHTS_DS . 'icons.php');

if(get_option('widget_widget_cta_insights')==false){

    add_option('widget_widget_cta_insights');
}

if(get_option('widget_widget_leadsius_form')==false){

    add_option('widget_widget_leadsius_form');
}


$GLOBALS['insights_leadsius_forms'] = array();
if ( $leadsiusToken = get_option( 'insights_leadsius_token', '') )
{

    $leadsiusAPIGateway = new LeadsiusAPIGateway($leadsiusToken);

    $leadsiusWebforms = $leadsiusAPIGateway->getAllForms();
    $GLOBALS['insights_leadsius_forms'] = $leadsiusWebforms;
    $GLOBALS['leadsiusAPIGateway'] = $leadsiusAPIGateway;

    if ( isset($_GET['thank-you']) && isset($leadsiusWebforms->webforms) )
    {
        foreach ($leadsiusWebforms->webforms as $form)
        {
            if ( $form->id == $_GET['thank-you'] )
            {
                echo $form->alert_message;
                die();
            }
        }
    }
}

$colors=array("white","grey");

$GLOBALS['select_colors']=$colors;

// function insights_activate()
// {
//     update_option('insights_plugin_active_id', uniqid());
// }
// register_activation_hook( __FILE__, 'insights_activate' );

// /* Load Translation */
// function insights_lang_setup()
// {
//     load_theme_textdomain('insights', plugin_dir_path( __FILE__) . '/translation');
// }
// add_action('after_setup_theme', 'insights_lang_setup');
// /* End Load Translation */

// /* Define Sidebar */
// function insights_widget_areas_setup()
// {
//     register_sidebar( array(
//         'name'          => __( 'Insights Sidebar', 'insights' ),
//         'id'            => 'widget-area-insights-side',
//         'description'   => __( 'Appears on the right side of the insights pages.', 'insights' )
//         ) );
// }
// add_action( 'widgets_init', 'insights_widget_areas_setup', 15 );
// /* End Define Sidebar */

// /* Override insights templates */
// function override_single_template($loc)
// {
//     $object = get_queried_object();
//     if ($object->post_type == 'insight')
//     {
//         update_post_meta($object->ID, 'post_views', get_post_meta($object->ID, 'post_views', true) + 1);

//         return get_insights_template_part_path('single');

//     }

//     return $loc;
// }
// //add_filter('single_template', 'override_single_template');

// function override_category_template($loc)
// {
//     $object = get_queried_object();
//     if ($object->taxonomy == 'insights_category')
//     {
//         return get_insights_template_part_path('category');
//     }

//     return $loc;
// }
// //add_filter('taxonomy_template', 'override_category_template');

// function override_page_template($loc)
// {
//     $object = get_queried_object();

//     if ( get_post_meta($object->ID, 'is-insights-home', true) == 'yes' )
//     {
//         return get_insights_template_part_path('home');
//     }

//     return $loc;
// }
// //add_filter('page_template', 'override_page_template');
// /* End override insights templates */

// /* Set Image Sizes */
// function insights_theme_setup()
// {
//     add_theme_support( 'post-thumbnails' );
//     add_image_size('insights-post-banner', 700);
//     add_image_size('insights-post-big', 640, 492, true);
//     add_image_size('insights-post-regular', 280, 160, true);
//     add_image_size('insights-post-square', 360, 360, true);
//     add_image_size('insights-post-thumb', 220, 126, true);
// }
// add_action( 'after_setup_theme', 'insights_theme_setup' );
/* End Set Image Sizes */





/**  admin menu hook **/

add_action( 'admin_menu', 'leadsius_admin_menu' );

function leadsius_admin_menu() {


    add_menu_page( 'Leadsius', 'Leadsius', 'manage_options', 'leadsius-conf','general',plugins_url('/assets/img/Leadsius_wp_icon.png',__FILE__) );
    add_submenu_page( 'leadsius-conf', 'Settings', 'Settings', 'manage_options', 'leadsius-conf', 'general');
    add_submenu_page( 'leadsius-conf', 'Web form', 'Web form', 'manage_options', 'webform', 'webform');
    add_submenu_page( 'leadsius-conf', 'CTA', 'CTA', 'manage_options', 'cta', 'cta');

}



function general() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    /* template for API conf*/
    include(INSIGHTS_PATH . 'functions' . INSIGHTS_DS . 'insights-settings-page.php');
}


function webform() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    include(INSIGHTS_PATH . 'templates' . INSIGHTS_DS . 'webform-settings.php');

    ?>

    <pre>
        <?php
        // print_r(get_option('widget_widget_leadsius_form'));
        // print_r(get_option('sidebars_widgets'));
        //echo get_option('leadsius_system_token','');
        ?>
    </pre>
<?php }


function cta() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    include(INSIGHTS_PATH . 'templates' . INSIGHTS_DS . 'cta-settings.php');
    ?>
    <pre>
            <?php
            // print_r(get_option('widget_widget_leadsius_form'));
            // print_r(get_option('sidebars_widgets'));
            //echo get_option('leadsius_system_token','');
            ?>
        </pre>
<?php }

/** end  admin menu hook **/



function add_handlebars(){


    wp_enqueue_script('handlebars.js',
        INSIGHTS_ASSETS_URL . '/js/handlebars.js',false);



}


function add_handlebars_helpers(){


    wp_enqueue_script('helpers.js',
        INSIGHTS_ASSETS_URL . '/js/Handlebars-Helpers/src/helpers.js',false);



}





function add_color_picker2(){


    wp_enqueue_script('spectrum.js',
        INSIGHTS_ASSETS_URL . '/js/spectrum/spectrum.js');

    wp_enqueue_style('spectrum.css',
        INSIGHTS_ASSETS_URL . '/js/spectrum/spectrum.css');

}





add_action( 'admin_enqueue_scripts', 'add_handlebars' );
add_action( 'admin_enqueue_scripts', 'add_handlebars_helpers' );

add_action( 'admin_enqueue_scripts', 'add_color_picker2' );



/********************************************************
 **********************************************************
 *******************************************************
 ***********************************************************
 ************************************************************
 *********************************************************/


/**
 * copy cta ajax backend
 */


function cta_settings_copy(){

    $id_form=$_POST['id_form'];

    $old_forms=get_option('widget_widget_cta_insights');


    $to_copy=$old_forms[$id_form];



    $to_copy['name']=$to_copy['name'].' copia';

    array_push($old_forms, $to_copy);

    update_option('widget_widget_cta_insights',$old_forms);

    end($old_forms);
    $last_id=key($old_forms);

    $to_copy['id']=$last_id;
    print_r(json_encode($to_copy));
    die();


}

add_action( 'wp_ajax_cta_settings_copy', 'cta_settings_copy' );


/**
 * new  cta ajax backend
 */

function cta_settings_save_new() {


    

    $form_insert=array();
    $new_form=$_POST['datos'];
    $array_length=count($new_form);

    $pages="";


    for ($i=0; $i < $array_length; $i++) {

            $form_insert[$new_form[$i]['name']]=$new_form[$i]['value'];


    }

    $form_insert['pages']='all;';
    $old_forms=get_option('widget_widget_cta_insights');
    if(!$old_forms){

        $old_forms=array();
    }
    $form_insert['category']="shortcode";
    array_push($old_forms, $form_insert);

    $r=update_option('widget_widget_cta_insights',$old_forms);





    end($old_forms);
    $id_ultimo = key($old_forms);
    $form_insert['id']=$id_ultimo;
    echo json_encode($form_insert);
    die();
}

add_action( 'wp_ajax_cta_settings_save_new', 'cta_settings_save_new' );


/**
 * delete cta ajax backend
 */

function cta_settings_delete(){

    $old_forms=get_option('widget_widget_cta_insights');

    $id_form=$_POST['id_form'];

    unset($old_forms[$id_form]);

    $elimino=update_option('widget_widget_cta_insights',$old_forms);



    if($elimino){

        echo "deleted";
    }
    else{

        echo "error";
    }
    die();

}



add_action( 'wp_ajax_cta_settings_delete', 'cta_settings_delete' );


/**
 * edit cta ajax backend, get the fields
 */


function cta_settings_edit(){

    global $insights_leadsius_forms,$insights_widget_icons,$select_colors;

    $badges = array( "Free", "Hot", "New", "Top" );
    $styles = array( "Naked", "Box-Color" );

    $options=array();
    $id_form=$_POST['id_form'];
    $old_forms=get_option('widget_widget_cta_insights');

    $form_to_edit['request']=$old_forms[$id_form];


    //print_r($old_forms[$id_form]);die();


    $d=0;
    /*foreach ($badges as $value) {

        $options['badges'][$d]['val']=$value;

        if($old_forms[$id_form]['badge']==$value){

            $options['badges'][$d]['selected']="selected";

        }
        else{

            $options['badges'][$d]['selected']="";

        }


        $d++;
    }*/

    $e=0;
    foreach ($styles as $value) {

        $options['styles'][$e]['val']=$value;

        if($old_forms[$id_form]['style']==$value){

            $options['styles'][$e]['selected']="selected";

        }
        else{

            $options['styles'][$e]['selected']="";

        }





        $e++;
    }


    $f=0;


    /*foreach ($insights_widget_icons as $key => $value) {

        $options['icons'][$f]['key']=$key;
        $options['icons'][$f]['val']=$value;


        if($old_forms[$id_form]['icon']==$key){

            $options['icons'][$f]['selected']="selected";

        }
        else{

            $options['icons'][$f]['selected']="";

        }

        $f++;

    }*/


    $g=0;

    if($old_forms[$id_form]['style']=="Box-Color"){

        $options['show_color']=true;

    }else{

        $options['show_color']=false;
    }




    foreach ($select_colors as  $value) {
        # code...
        $options['color'][$g]['val']=$value;

        if($old_forms[$id_form]['color']==$value){

            $options['color'][$g]['selected']="selected";

        }
        else{

            $options['color'][$g]['selected']="";

        }
        $g++;
    }





    $result=array($form_to_edit,$options);


    echo json_encode($result);

    die();
}


add_action( 'wp_ajax_cta_settings_edit', 'cta_settings_edit' );



/**
 * update cta ajax backend
 */

function cta_settings_update(){


//print_r($_REQUEST);
    $id_form=$_POST['id_form'];
    $to_update_data=$_POST['datos'];

    $old_forms=get_option('widget_widget_cta_insights');

    //$form_to_update=$old_forms[$id_form];
    unset($old_forms[$id_form]);



    $num=count($to_update_data);
    $updated=array();
    $pages="";
    for ($i=0; $i < $num; $i++) {
        # code...
        #
            $updated[$to_update_data[$i]['name']]=$to_update_data[$i]['value'];

    }
    $updated['pages']='all;';
    $updated['category']="shortcode";
    $old_forms[$id_form]=$updated;
    $actualizo=update_option('widget_widget_cta_insights',$old_forms);

    if($actualizo){

        //echo "updated";
        //
        echo  json_encode($updated);
    }
    else{

        echo "error";
    }

    //print_r($updated);
    die();

}


add_action( 'wp_ajax_cta_settings_update', 'cta_settings_update' );

/********************************************************
 **********************************************************
 *******************************************************
 ***********************************************************
 ************************************************************
 *********************************************************/



/**
 * copy form ajax backend
 */


function web_form_settings_copy(){

    $id_form=$_POST['id_form'];

    $old_forms=get_option('widget_widget_leadsius_form');


    $to_copy=$old_forms[$id_form];



    $to_copy['name']=$to_copy['name'].' copia';

    array_push($old_forms, $to_copy);

    update_option('widget_widget_leadsius_form',$old_forms);

    end($old_forms);
    $last_id=key($old_forms);

    $to_copy['id']=$last_id;
    print_r(json_encode($to_copy));
    die();


}

add_action( 'wp_ajax_web_form_settings_copy', 'web_form_settings_copy' );


/**
 * update form ajax backend
 */

function web_form_settings_update(){


//print_r($_REQUEST);
    $id_form=$_POST['id_form'];
    $to_update_data=$_POST['datos'];

    $old_forms=get_option('widget_widget_leadsius_form');

    //$form_to_update=$old_forms[$id_form];
    unset($old_forms[$id_form]);


    $num=count($to_update_data);
    $updated=array();
    $pages="";
    for ($i=0; $i < $num; $i++) {
        # code...
        #

            $updated[$to_update_data[$i]['name']]=$to_update_data[$i]['value'];



    }
    $updated['pages']='all;';
    $updated['category']="shortcode";
    $old_forms[$id_form]=$updated;
    $actualizo=update_option('widget_widget_leadsius_form',$old_forms);

    if($actualizo){

        //echo "updated";
        //
        echo  json_encode($updated);
    }
    else{

        echo "error";
    }

    //print_r($updated);
    die();

}


add_action( 'wp_ajax_web_form_settings_update', 'web_form_settings_update' );



/**
 * save new form ajax backend
 */

function web_form_settings_save_new() {
    //check_ajax_referer( "helloworld" );


    $form_insert=array();
    $new_form=$_POST['datos'];
    $array_length=count($new_form);

    $pages="";


    for ($i=0; $i < $array_length; $i++) {

            $form_insert[$new_form[$i]['name']]=$new_form[$i]['value'];


    }

    $form_insert['pages']='all;';
    $old_forms=get_option('widget_widget_leadsius_form');
    if($old_forms==false){

        $old_forms=array();
    }
    $form_insert['category']="shortcode";
    array_push($old_forms, $form_insert);


    update_option('widget_widget_leadsius_form',$old_forms);



    end($old_forms);
    $id_ultimo = key($old_forms);
    $form_insert['id']=$id_ultimo;
    echo json_encode($form_insert);
    die();
}

add_action( 'wp_ajax_web_form_settings_save_new', 'web_form_settings_save_new' );
/**
 * delete form ajax backend
 */




function web_form_settings_delete(){

    $old_forms=get_option('widget_widget_leadsius_form');

    $id_form=$_POST['id_form'];

    unset($old_forms[$id_form]);

    $elimino=update_option('widget_widget_leadsius_form',$old_forms);



    if($elimino){

        echo "deleted";
    }
    else{

        echo "error";
    }
    die();

}


add_action( 'wp_ajax_web_form_settings_delete', 'web_form_settings_delete' );



function web_form_settings_edit(){

    global $insights_leadsius_forms,$insights_widget_icons,$select_colors;

    $badges = array( "Free", "Hot", "New", "Top" );
    $styles = array( "Naked", "Box-Color" );

    $options=array();
    $id_form=$_POST['id_form'];
    $old_forms=get_option('widget_widget_leadsius_form');

    $form_to_edit['request']=$old_forms[$id_form];


    //print_r($old_forms[$id_form]);die();
    $c=0;
    foreach($insights_leadsius_forms->webforms as $formu){

        $options['form'][$c]['name']=$formu->name;
        $options['form'][$c]['id']=$formu->id;
        if($formu->id==$old_forms[$id_form]['form']){

            $options['form'][$c]['selected']="selected";
        }
        else{

            $options['form'][$c]['selected']="";
        }


        $c++;
    }

    $d=0;
    /*foreach ($badges as $value) {

        $options['badges'][$d]['val']=$value;

        if($old_forms[$id_form]['badge']==$value){

            $options['badges'][$d]['selected']="selected";

        }
        else{

            $options['badges'][$d]['selected']="";

        }


        $d++;
    }*/

    $e=0;
    foreach ($styles as $value) {

        $options['styles'][$e]['val']=$value;

        if($old_forms[$id_form]['style']==$value){

            $options['styles'][$e]['selected']="selected";

        }
        else{

            $options['styles'][$e]['selected']="";

        }





        $e++;
    }


    $f=0;


    /*foreach ($insights_widget_icons as $key => $value) {

        $options['icons'][$f]['key']=$key;
        $options['icons'][$f]['val']=$value;


        if($old_forms[$id_form]['icon']==$key){

            $options['icons'][$f]['selected']="selected";

        }
        else{

            $options['icons'][$f]['selected']="";

        }

        $f++;

    }*/


    $g=0;

    if($old_forms[$id_form]['style']=="Box-Color"){

        $options['show_color']=true;

    }else{

        $options['show_color']=false;
    }



    foreach ($select_colors as  $value) {
        # code...
        $options['color'][$g]['val']=$value;

        if($old_forms[$id_form]['color']==$value){

            $options['color'][$g]['selected']="selected";

        }
        else{

            $options['color'][$g]['selected']="";

        }
        $g++;
    }

    /*$g=0;

    $pages_form=explode(";",$old_forms[$id_form]['pages']);

    $wpPages = get_pages(array( 'posts_per_page' => -1 ));


    $options['pages']['default'][0]['id']="all";
    $options['pages']['default'][0]['title']="Everywhere";
    if(in_array('all',$pages_form)){
        $options['pages']['default'][0]['selected']="selected";
    }
    else{

        $options['pages']['default'][0]['selected']="";
    }

    $options['pages']['default'][1]['id']="just-insights";
    $options['pages']['default'][1]['title']="Just inside the insights section";
    if(in_array('just-insights',$pages_form)){
        $options['pages']['default'][1]['selected']="selected";
    }
    else{

        $options['pages']['default'][1]['selected']="";
    }

    foreach($wpPages as $page){


       $options['pages']['blog_pages'][$g]['id']=$page->ID;
       $options['pages']['blog_pages'][$g]['title']=$page->post_title;

       if(in_array($page->ID,$pages_form)){

        $options['pages']['blog_pages'][$g]['selected']="selected";

    }
    else{

       $options['pages']['blog_pages'][$g]['selected']="";
    }
    $g++;
    }


    $wpPages = get_terms('insights_category');
    $h=0;

    foreach($wpPages as $page){


       $options['pages']['ic'][$h]['id']="ic".$page->term_id;
       $options['pages']['ic'][$h]['title']=$page->$page->name;;

       if(in_array("ic".$page->term_id,$pages_form)){

        $options['pages']['ic'][$h]['selected']="selected";

    }
    else{

       $options['pages']['ic'][$h]['selected']="";
    }
    $h++;
    }


    $wpPages = get_posts(array( 'post_type' => 'insight', 'posts_per_page' => -1 ));
    $i=0;

    foreach($wpPages as $page){

       $options['pages']['ip'][$i]['id']=$page->ID;
       $options['pages']['ip'][$i]['title']=$page->post_title;


       if(in_array($page->ID,$pages_form)){

        $options['pages']['ip'][$i]['selected']="selected";

    }else{

       $options['pages']['ip'][$i]['selected']="";
       $i++;
    }
    }*/

    $result=array($form_to_edit,$options);


    echo json_encode($result);

    die();
}


add_action( 'wp_ajax_web_form_settings_edit', 'web_form_settings_edit' );



function add_tracker_script(){


    $sys_token=get_option('leadsius_system_token', '');

    if($sys_token!=''){
        $script='<script type="text/javascript">';
        $script.='var lsBaseURL = (("https:" == document.location.protocol) ? "https://tracker.leadsius.com/djs/" : "http://tracker.leadsius.com/djs/");';
        $script.='document.write(unescape("%3Cscript src=';
        $script.="'";
        $script.='" + lsBaseURL + "tracker.js?_k=';
        $script.=$sys_token;
        $script.="'";
        $script.="type='text/javascript'";
        $script.='%3E%3C/script%3E"));';
        $script.='</script>';

        echo $script;
    }
}


add_action('wp_footer', 'add_tracker_script',4);

function get_insights_header()
{
    printf('
        %s
        %s
        %s
<script type="text/javascript" src="%s/js/leadsius-forms.js"></script>
',
load_insights_asset('font-awesome/css', 'font-awesome.css'),
load_insights_asset('css', 'main.css'),
load_insights_asset('css', 'responsive.css'),
INSIGHTS_ASSETS_URL
);
}
add_action( 'wp_head', 'get_insights_header' );





/* Use Link Window In Widgets Page */
if (!function_exists('insights_add_editor_to_widgets'))
{
    function insights_add_editor_to_widgets()
    {
        $pages = get_pages();
        $posts=get_posts();
        $ulPages = "";

        $index = 0;
        $index2 = 0;
        foreach($pages as $page)
        {
            $ulPages .= sprintf('<li class="%s"><input type="hidden" value="%s" class="item-permalink" /><span class="item-title">%s</span><span class="item-info">Page</span></li>'
                , ($index++ % 2 ? 'alternate' : ''), get_permalink($page), $page->post_title);
        }
        foreach($posts as $post)
        {
            $ulPages .= sprintf('<li class="%s"><input type="hidden" value="%s" class="item-permalink" /><span class="item-title">%s</span><span class="item-info">Post</span></li>'
                , ($index2++ % 2 ? 'alternate' : ''), get_permalink($post), $post->post_title);
        }
        $escaped = addslashes($ulPages);
        echo '
        <style type="text/css">
             #wp-tool-editor-wrap { display: none; }
        </style>

        <script type="text/javascript">
            (function($){
                window.ShowLinkWindow = function(btn)
                {
                    var field = btn.siblings("input");
                    var wpLink = $("#wp-link");

                    wpLink.parent().dialog({
                        title: "Insert link",
                        width: 480,
                        height: 480,
                        modal: true,
                        dialogClass: "wp-dialog"
                    });

wpLink.find("#url-field, #link-title-field").val("");

wpLink.find("#wp-link-close").unbind("click").click(function(){

    wpLink.parents(".ui-dialog").eq(0).find(".ui-button").trigger("click");

    return false;

});
wpLink.find(".submitdelete").unbind("click").click(function(){
    wpLink.parents(".ui-dialog").eq(0).find(".ui-button").trigger("click");

    return false;
});
wpLink.find("#wp-link-submit").unbind("click").click(function(){
    wpLink.parents(".ui-dialog").eq(0).find(".ui-button").trigger("click");
    field.val(wpLink.find("#url-field").val());

    return false;
});

wpLink.find("#most-recent-results ul").html(\'' . $escaped . '\');
wpLink.find("#internal-toggle").remove();
wpLink.find("#search-panel").show();
wpLink.css("width","480px");
}
})(jQuery);
</script>
';
        wp_editor('', 'tool-editor');
    }
}
add_action('admin_head', 'insights_add_editor_to_widgets');

function insights_options_scripts_and_styles()
{

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-dialog');

}
add_action('admin_print_scripts', 'insights_options_scripts_and_styles');?>