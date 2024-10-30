<?php

/*
  Plugin Name: jirnee-Share
  Plugin URI: https://www.jirnee.com
  Description: jirnee share- connecting to people places. To activate this plugin please enter the api details under settings, Jirnee-Settings.
  Author: Rajiv Shrestha
  Version: 1.0.0
  Author URI: http://jirnee.com
 */
//define('IDIDGO_PLUGIN_URL', plugin_dir_url( __FILE__ ));

$siteurl = get_option('siteurl');
define('IDIDGO_PLUGIN_FOLDER', dirname(plugin_basename(__FILE__)));
define('IDIDGO_PLUGIN_URL', $siteurl . '/wp-content/plugins/' . IDIDGO_PLUGIN_FOLDER);
define('IDIDGO_PLUGIN_FILE_PATH', dirname(__FILE__));
define('IDIDGO_PLUGIN_DIR_NAME', basename(IDIDGO_PLUGIN_FILE_PATH));
// this is the table prefix
global $wpdb;
$pro_table_prefix = $wpdb->prefix . 'idg_';
define('IDIDGO_PLUGIN_TABLE_PREFIX', $pro_table_prefix);

register_activation_hook(__FILE__, 'IDIDGO_PLUGIN_install');


function IDIDGO_PLUGIN_install() {
    global $wpdb;
    echo 'a';
    $table = IDIDGO_PLUGIN_TABLE_PREFIX . "jirnee";
    $structure = "CREATE TABLE $table (
        consumerKey VARCHAR(20) NOT NULL,
        apiKey VARCHAR(20) NOT NULL);";
    echo $structure;
    $wpdb->query($structure);
    // Populate table
    /*  $wpdb->query("INSERT INTO $table(name, website, description)
      VALUES('Pro Questions', 'pro-questions.com','This Is A Programming Questions Site')"); */
}

function IDIDGO_PLUGIN_uninstall() {
    global $wpdb;
    $table = IDIDGO_PLUGIN_TABLE_PREFIX . "jirnee";
    $structure = "drop table if exists $table";
    $wpdb->query($structure);
}

/* -------------------------------------------------------- */

//add_action('wp_title','rajivtest');
add_filter('the_content', 'rajivtest',1);
add_action('admin_menu', 'ididgo_admin_actions');

function idg_admin_actions() {
    echo 'this is admin-rajiv';
}

function ididgo_admin() {
    include('idg_admin.php');
}

function ididgo_admin_actions() {
    add_options_page("jirnee", "jirnee-Settings", 1, "idg_admin", "ididgo_admin");
}


function rajivtest() {
    global $post;
    global $wpdb;
    $posttags = get_the_tags();
//    echo 'rajiv';
//      echo '<pre>';
//        print_r($post);
//        echo '</pre>';
    if ($post->post_type == 'post') {
        $somtags = '';
        $dgCnKey = "";
        $dgApiKey = "";
        $sql = "SELECT * FROM " . IDIDGO_PLUGIN_TABLE_PREFIX . "jirnee where  1";
        $results = $wpdb->get_results($sql);
        if (count($results) > 0) {
            foreach ($results as $result) {
                $dgCnKey = $result->consumerKey;
                $dgApiKey = $result->apiKey;
            }
        }

        if ($posttags) {
            foreach ($posttags as $tag) {
                //$somtags.= $tag->name . ' ';
                $mytag .= $tag->name . ',';
            }
            $somtags.= $post->post_content;
        }
//        echo '<pre>';
//        print_r($post);
//        echo '</pre>';
        $dg_title = urlencode($post->post_title);
        $somtags.="<br/><script src=\"https://www.jirnee.com/api/wpapi/myapi.js\"></script>
 <div id=\"idg_loctags\" style=\"display:none;\">".urlencode($mytag)."</div>
<div id=\"idg_wrapperj\"></div>
<div id=\"idg_aKey\" style=\"display:none;\">$dgApiKey</div>
<div id=\"idg_cKey\" style=\"display:none;\">$dgCnKey</div>
   <div id=\"idg_pTitle\" style=\"display:none;\">$dg_title</div>";
        /* tests */
        if (has_post_thumbnail()) {
            $rjsGetThumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID));
             $somtags.= "<div id=\"idg_thumb\" style=\"display:none;\">" .urlencode($rjsGetThumbnail[0]). "</div>";
        } else {
            $somtags.= "<div id=\"idg_thumb\" style=\"display:none;\">" . urlencode('https://www.jirnee.com/img/jirneelogo.png') . "</div>";
        }
        //print_r(get_post_thumbnail_id($post->ID));
        // $rjtst= get_the_content('Read more',true);
        //print '----------------'.$rjtst;
//    $my_excerpt = get_the_excerpt();
//if ( $my_excerpt != '' ) {
//	// Some string manipulation performed
//}
//echo $my_excerpt; // Outputs the processed value to the page
        /**/
        //the_excerpt_max_charlength(40);
        $posTeaser = custom_text_length(140, ' ', 'content');
 $posTeaser=strip_tags($posTeaser);
        $somtags.="<div id=\"idg_teaser\" style=\"display:none;\">" . urlencode($posTeaser) . "</div>";
        //echo '<br>-----------<hr/>';

        return $somtags;
    } else  return $post->post_content;
}



function the_excerpt_max_charlength($charlength) {
    $excerpt = get_the_excerpt($post->id);
    $charlength++;

    if (mb_strlen($excerpt) > $charlength) {
        $subex = mb_substr($excerpt, 0, $charlength - 5);
        $exwords = explode(' ', $subex);
        $excut = - ( mb_strlen($exwords[count($exwords) - 1]) );
        if ($excut < 0) {
            echo mb_substr($subex, 0, $excut);
        } else {
            echo $subex;
        }
        echo '[...]';
    } else {
        echo $excerpt;
    }
}

/*
  takes 3 parameters: trim length, more link text, and
  content type (blank for excerpt or 'content' for content)
  returns trimmed text block wrapped in <p> tags with more link to post
 */

function custom_text_length($charlength, $more_link, $c_type, $post=NULL) {
    $text = '';
    if ($c_type == 'content') {
        $raw_text = (empty($post)) ? get_the_content() : $post->post_content;
    } else {
        $raw_text = (empty($post)) ? get_the_excerpt() : $post->post_excerpt;
    }
    $link = (empty($post)) ? '<a href="' . get_permalink() . '">' . $more_link . '</a>' : '<a href="' . get_permalink($post->ID) . '">' . $more_link . '</a>';
    if (mb_strlen($raw_text) > $charlength) {
        $subex = mb_substr($raw_text, 0, $charlength - 5);
        // $subex = '<p>'.$subex.'…'.$link.'</p>';
        $subex = '<p>' . $subex . ' . . </p>';

        return $subex;
    } else {
        //$raw_text = '<p>'.$raw_text.'…'.$link.'<p>';
        $raw_text = '<p>' . $raw_text . '. . <p>';

        return $raw_text;
    }
}

?>