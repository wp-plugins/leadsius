<?php

function get_most_popular_posts($page = 1, $postsPerPage = 3)
{
    global $wpdb;

    return $wpdb->get_results( sprintf("SELECT p.*, pm.meta_value as 'post_views'
        FROM " . $wpdb->posts . " p
        LEFT JOIN " . $wpdb->postmeta . " pm
        ON pm.post_id = p.ID
        AND pm.meta_key = 'post_views'
        WHERE post_type = 'insight'
        AND post_status = 'publish'
        ORDER BY pm.meta_value DESC, p.post_date DESC
        LIMIT %d,%d;
        ", (($page-1)*$postsPerPage), $postsPerPage));
}

function get_insights_breadcrumb()
{
//    get_insights_template_part('breadcrumb');

    return '';
}

function text_truncate($string, $length, $stripHtmlTags = false, $dots = "...")
{
    $length = $length + 5;
    $position = $length;

    if ($stripHtmlTags)
    {
        $string = strip_tags($string);
    }

    if (strlen($string) > $length)
    {
        for ($i=$length-1; $i>0; $i--)
        {
            if ($string[$i] == " " && $i != 0 && $string[$i-1] != ",")
            {
                $position = $i;
                break;
            }
        }

        return substr($string, 0, $position) . $dots;
    }

    return $string;
}

function where_to_show_selector($fieldName, $pages)
{
    ?>

    <select multiple="multiple" class="widefat" name="<?php echo $fieldName; ?>[]" style="height: 180px;">
        <option value="all" <?php if (is_array($pages) && in_array('all', $pages)): ?>selected="selected"<?php endif; ?>><?php _e('All', 'insights'); ?></option>
        <option value="frontpage" <?php if (is_array($pages) && in_array('frontpage', $pages)): ?>selected="selected"<?php endif; ?>><?php _e('Frontpage', 'insights'); ?></option>
        <option value="home" <?php if (is_array($pages) && in_array('home', $pages)): ?>selected="selected"<?php endif; ?>><?php _e('Home', 'insights'); ?></option>
        <option value="archive" <?php if (is_array($pages) && in_array('archive', $pages)): ?>selected="selected"<?php endif; ?>><?php _e('Archive', 'insights'); ?></option>
        <option value="search" <?php if (is_array($pages) && in_array('search', $pages)): ?>selected="selected"<?php endif; ?>><?php _e('Search', 'insights'); ?></option>
        <option value="single" <?php if (is_array($pages) && in_array('single', $pages)): ?>selected="selected"<?php endif; ?>><?php _e('Single', 'insights'); ?></option>
        <option value="pages" <?php if (is_array($pages) && in_array('pages', $pages)): ?>selected="selected"<?php endif; ?>><?php _e('Pages', 'insights'); ?></option>
        <optgroup label="Select Pages">
            <?php $wpPages = get_pages(array( 'posts_per_page' => -1 )); ?>
            <?php foreach($wpPages as $page): ?>
                <option value="<?php echo $page->ID; ?>" <?php if (is_array($pages) && in_array($page->ID, $pages)): ?>selected="selected"<?php endif; ?>><?php echo $page->post_title; ?></option>
            <?php endforeach; ?>
        </optgroup>

        <optgroup label="Select Categories">
            <?php $wpCat = get_categories(); ?>
            <?php foreach($wpCat as $category): ?>
                <option value="<?php echo $category->taxonomy; ?>" <?php if (is_array($pages) && in_array($category->cat_ID, $pages)): ?>selected="selected"<?php endif; ?>><?php echo $category->name; ?></option>
            <?php endforeach; ?>
        </optgroup>

    </select>

    <?php
}

function get_insights_homepage()
{
    global $wpdb;

    return $wpdb->get_row("SELECT p.* FROM " . $wpdb->posts . " p INNER JOIN " . $wpdb->postmeta . " pm ON pm.post_id = p.ID WHERE pm.meta_key = 'is-insights-home' AND pm.meta_value = 'yes' ORDER BY post_date ASC LIMIT 0,1" );
}

function is_insights_home()
{
    return get_post_meta(get_queried_object_id(), 'is-insights-home', true) == 'yes';
}
function is_insights_front()
{
    return is_insights_home() && !is_insights_search();
}
function is_insights_search()
{
    return isset($_GET['search']) || isset($_REQUEST['search']);
}
function is_insights_category()
{
    $obj = get_queried_object();

    return isset($obj->taxonomy) && $obj->taxonomy == 'insights_category';
}
function is_insight_premium($id = null)
{
    if ( is_null($id) )
    {
        $id = get_queried_object_id();
    }

    return get_post_meta($id, 'premium_insight', true) == 'yes';
}

function get_insights_plugin_url($additional = '')
{
    return plugins_url(basename(dirname(__FILE__))) . $additional;
}
function get_insights_plugin_assets_url($additional = '')
{
    return get_insights_plugin_url('/assets' . $additional);
}
function get_insights_plugin_image_url($additional = '')
{
    return get_insights_plugin_assets_url('/img' . $additional);
}

function get_insights_template_part_path($templateName)
{
    $extension = '.php';
    $altPath = get_template_directory() . INSIGHTS_DS . 'insights' . INSIGHTS_DS . 'templates' . INSIGHTS_DS . $templateName . $extension;

    if ( file_exists($altPath) )
    {
        return $altPath;
    }

    return sprintf( '%s%s%s', INSIGHTS_TEMPLATES_PATH, $templateName, $extension );
}
function get_insights_template_part($templateName, $vars = array())
{
    foreach ( $vars as $key => $value )
    {
        eval( '$' . $key . ' = $value;' );
    }

    include( get_insights_template_part_path($templateName) );
}

function get_insights_search_url()
{
    $sitePages = get_pages();
    $searchFormUrl = '';
    foreach($sitePages as $pg)
    {
        if (get_post_meta($pg->ID, 'is-insights-home', true) == 'yes')
        {
            $searchFormUrl = get_permalink($pg);
        }
    }

    return $searchFormUrl;
}

function load_insights_asset($folder, $file)
{
    $ret = sprintf('<link rel="stylesheet" type="text/css" href="%s/%s/%s" />', INSIGHTS_ASSETS_URL, $folder, $file);

    if ( file_exists( get_stylesheet_directory() . INSIGHTS_DS . 'insights' . INSIGHTS_DS . 'assets' . INSIGHTS_DS . $folder . INSIGHTS_DS . $file ) )
    {
        $ret .= sprintf('<link rel="stylesheet" type="text/css" href="%s/insights/assets/%s/%s?t=r" />', get_template_directory_uri(), $folder, $file);
    }

    return $ret;
}

if (!function_exists('nl2brC'))
{
    function nl2brC($text)
    {
        if ($text != htmlspecialchars($text))
        {
            return $text;
        }

        return nl2br($text);
    }
}

if (!function_exists('is_insights_widget_available_here'))
{
    function is_insights_widget_available_here($pages)
    {
        $currentQueriedObject = get_queried_object();





        if (!is_array($pages))
        {
            $pages = array( $pages );
        }

        /* Allow For Insights */
        if (in_array('all', $pages))
        {
            return true;
        }
       /* if (in_array('just-insights', $pages))
        {
            if ( is_a($currentQueriedObject, 'WP_Post') && get_post_meta($currentQueriedObject, 'is-insights-home', true) == 'yes' )
            {
                return true;
            }
            else if ( isset($currentQueriedObject->taxonomy) && $currentQueriedObject->taxonomy == 'insights_category' )
            {
                return true;
            }
            else if ( is_a($currentQueriedObject, 'WP_Post') && $currentQueriedObject->post_type == 'insight' )
            {
                return true;
            }
        }*/
        if ( is_a($currentQueriedObject, 'WP_Post') && in_array($currentQueriedObject->taxonomy,$pages)){

            return true;

        }


        if ( is_a($currentQueriedObject, 'WP_Post') && in_array($currentQueriedObject->ID, $pages))
        {
            return true;
        }

        if(is_home() && in_array("home", $pages)){

            return true;
        }


        if(is_front_page() && in_array("frontpage", $pages)){

            return true;
        }

        if(is_archive() && in_array("archive", $pages)){

            return true;
        }

        if(is_search() && in_array("search", $pages)){

            return true;
        }

        if(is_single() && in_array("single", $pages)){

            return true;
        }

        if(is_page() && in_array("page", $pages)){

            return true;
        }


     /*   if (isset($currentQueriedObject->taxonomy) && $currentQueriedObject->taxonomy == 'insights_category')
        {
            if (in_array('ic'.$currentQueriedObject->term_id, $pages))
            {
                return true;
            }
        }*/

        return false;
    }
}

function insights_translate_date($date)
{
    if ( defined('WPLANG') && WPLANG == 'se_SE' )
    {
        return str_replace(
            array('January','February','March','April','May','June','July','August','September','October','November','December'),
            array('Januari', 'Februari', 'Mars', 'April', 'Maj', 'Juni', 'Juli', 'Augusti', 'September', 'Oktober', 'November', 'December'),
            $date
            );
    }

    return $date;
}


function get_sidebar_parent($id){

    $widgets=wp_get_sidebars_widgets(); 

    foreach ($widgets as $key => $value) {
                        # code...
        if(!empty($value)){

            for($i=0;$i<count($value);$i++){


                if($id==$value[$i]){

                    echo $key;
                }
            }

        }



    }



}