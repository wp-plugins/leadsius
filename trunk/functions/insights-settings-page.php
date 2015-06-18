<?php



/*function insights_settings()
{
    add_submenu_page('edit.php?post_type=insight', 'Insights Settings', 'Insights Settings', 'edit_posts', 'insights_settings_content', 'insights_settings_content');
}
add_action('admin_menu' , 'insights_settings');*/

/*function insights_settings_content()
{*/

    //print_r(_POST);die();
    if (!empty($_POST))
    {
        //update_option("insights_category_base", $_POST["insights_category_base"]);
        //update_option("insights_post_base", $_POST["insights_post_base"]);
        update_option("insights_leadsius_token", sanitize_text_field($_POST["insights_leadsius_token"]));


        update_option("leadsius_system_token", sanitize_text_field($_POST["leadsius_system_token"]));


/**
 * [add_tracker_script description]
 *
 * add the script to the footer
 * 
 */
/*function add_tracker_script(){


    /*$script='<script type="text/javascript">';
    $script.='var lsBaseURL = (("https:" == document.location.protocol) ? "https://tracker.leadsius.com/djs/" : "http://tracker.leadsius.com/djs/");';
    //$script.='document.write(unescape("%3Cscript src='" + lsBaseURL + "tracker.js?_k='.$_POST["leadsius_system_token"].'' type='text/javascript'%3E%3C/script%3E"));';
    $script.='document.write(unescape("%3Cscript src=';
        $script.="'";
        $script.='" + lsBaseURL + "tracker.js?_k=';
        $script.=$_POST["leadsius_system_token"];
        $script.="'";
        $script.="type='text/javascript'";
        $script.='%3E%3C/script%3E"));';
$script.='</script>';

echo $script;*/

?>

<?php /*}

$es=add_action('wp_footer', 'add_tracker_script',20);*/

        //$oldHome = get_insights_homepage();
        //delete_post_meta($oldHome->ID, 'is-insights-home');
        //update_post_meta($_POST["insights_homepage"], 'is-insights-home', 'yes');

echo '<script type="text/javascript">



//window.location.href = window.location.href;

</script>

<style type="text/css">

    textarea{
        margin-top: 10px;
    }

</style>
';
}
?>

<div class="wrap">
    <div class="icon32" id="icon-options-general"><br /></div>
    <h2>Settings</h2>

    <form method="post" action="" name="form">

        <h3 class="title">Leadsius for Wordpress is a plugin that seamlessly integrates your Wordpress website to your Leadsius account.</h3>

        
        <table class="form-table">
            <tbody>
                  <!--  <tr>
                        <td style="width: 150px;">
                            <label>Category base</label>
                        </td>
                        <td>
                            <input type="text" class="regular-text code" value="<?php echo get_option('insights_category_base', '') != '' ? get_option('insights_category_base', '') : 'insights-category'; ?>" name="insights_category_base" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Post base</label>
                        </td>
                        <td>
                            <input type="text" class="regular-text code" value="<?php echo get_option('insights_post_base', '') != '' ? get_option('insights_post_base', '') : 'insight'; ?>" name="insights_post_base" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Insights homepage</label>
                        </td>
                        <td>
                            <?php $pages = get_pages(); ?>
                            <?php $currentHome = get_insights_homepage(); ?>
                            <select name="insights_homepage">
                                <?php foreach($pages as $page): ?>
                                    <option value="<?php echo $page->ID; ?>" <?php if ($page->ID == $currentHome->ID): ?>selected="selected"<?php endif; ?>><?php echo $page->post_title; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr> -->
                    <tr>
                        <td>
                            <label>Leadsius API Token</label>


                        </td>


                    </tr>
                    <tr>
                        <td><i>Insert your Leadsius api token. When logged in to Leadsius, you can create your api token under Settings > API. </i></td>
                    </tr>
                    <tr>
                        <td>
                            <textarea name="insights_leadsius_token" id="" cols="70" rows="3"><?php echo get_option('insights_leadsius_token', ''); ?></textarea>
                        </td>
                    </tr>
                        <td></td>
                    <tr>

                        <td>
                            <label>Leadsius System Key</label>


                        </td>


                    </tr>
                  <tr>
                      <td><i>Insert your Leadsius system key. In Leadsius, copy your system key under Settings > Account Info. </i></td>
                  </tr>
                  <tr>
                      <td>
                          <textarea name="leadsius_system_token" id="" cols="70" rows="3"><?php echo get_option('leadsius_system_token', ''); ?></textarea>
                      </td>
                  </tr>


                </tbody>
            </table>

            <p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit"></p>
        </form>
    </div>