<?php

/*
 *  Plugin Name:    Extra Options
 *  Description:    A collection of options for a better WordPress experience and performance
 *  Version:        0.0.4
 *  Author:         Demetris
 *  Author URI:     http://op111.net/
 *  Plugin URI:     http://op111.net/code/xo
 *  Text Domain:    xo
 *  Domain Path:    /lang/
 *
 *  Copyright:      2010 Demetris Kikizas (Δημήτρης Κίκιζας)
 *
 *  License:        GNU General Public Licence 2
 *  License URI:    http://www.gnu.org/licenses/gpl-2.0.html
 *  
 *  This program is distributed under the terms of the
 *  GNU General Public License 2 as published by the Free Software Foundation.
 *
 *  It is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 *  without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *  See the GNU General Public License 2 for more details.
 */


#   include 'xo-exp.php';


#
#   If on backend, run code only needed there.  Else, run code only needed on the frontend.
#

if (is_admin()) {
    require 'xo-adm.php';
    
    add_action('admin_menu', 'xo_add_options_page');

    add_action('admin_print_styles-settings_page_xo', 'xo_admin_print_style', 64, 0);

    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'xo_add_settings_link');

    register_activation_hook(__FILE__, 'xo_do_activation');

    /**
     *  Runs on activation and on updates.  
     *
     *  @since 0.0.1
     *
     *  @uses add_option()
     *  @uses deactivate_plugins()
     *  @uses delete_option()
     *  @uses get_option()
     */
    function xo_do_activation()
    {
        global $wp_version;
        
        if (version_compare($wp_version, '2.9.9', '<=')) {
            if (function_exists('deactivate_plugins')) {
                deactivate_plugins(__FILE__);
            }
            
            die(__('The plugin Extra Options for WordPress requires WordPress 3.0 or newer.', 'xo'));
        }

        add_option('xo_ajax_libs_google',       0);
        add_option('xo_favicon_link',           0);
        add_option('xo_meta_desc_excerpt',      0);
        add_option('xo_meta_desc_home',         0);
        add_option('xo_meta_noindex_idx',       0);
        add_option('xo_meta_nofollow_idx',      0);
        add_option('xo_page_excerpt_box',       0);
        add_option('xo_rm_code_edit',           0);
        add_option('xo_rm_convert_chars',       0);
        add_option('xo_rm_feed_links',          0);
        add_option('xo_rm_feed_links_extra',    0);
        add_option('xo_rm_generator',           0);
        add_option('xo_rm_more_anchor',         0);
        add_option('xo_rm_P_police',            0);
        add_option('xo_rm_rel_canonical',       0);
        add_option('xo_rm_rel_nav_links',       0);
        add_option('xo_rm_rel_shortlink',       0);
        add_option('xo_rm_rich_edit',           0);
        add_option('xo_rm_rsd_link' ,           0);
        add_option('xo_rm_texturize',           0);
        add_option('xo_rm_texturize_cmnt',      0);
        add_option('xo_rm_wlw_link',            0);
        add_option('xo_trash_24h',              0);
        
        #   Transition from *xo_rm_post_revisions* (in v0.0.1) to *xo_post_revisions* (in v0.0.2)
        if (get_option('xo_rm_post_revisions') and get_option('xo_rm_post_revisions') == 1) {
            add_option('xo_post_revisions', 0);
            delete_option('xo_rm_post_revisions');
        } else {
            add_option('xo_post_revisions', 9);
        }
    }
} else {
    #   Gets AJAX libraries from the Google CDN.  (Only for the frontend)
    if (get_option('xo_ajax_libs_google') == 1) {
        #   Lib versions for WP 3.1 and later
        if (version_compare($wp_version, '3.0.9', '>')) {
            $jqv = '1.4.4';
            $ptv = '1.6.1.0';
            $sov = '2.2';
        #   Lib versions for WP 3.0
        } elseif (version_compare($wp_version, '2.9.9', '>')) {
            $jqv = '1.4.2';
            $ptv = '1.6.1.0';
            $sov = '2.2';
        } else {
            return;
        }
        
        #   Base URI of AJAX libs CDN
        if (is_ssl()) {
            $abu = 'https://ajax.googleapis.com/ajax/libs';
        } else {
            $abu = 'http://ajax.googleapis.com/ajax/libs';
        }
        
        #   20101015.  jQuery UI in Google CDN is full package.  WP bundle is different.  So don’t mess with jQuery UI!
        #   20100929.  SWFObject does not seem to work from the footer.  Make sure jQuery UI works!
        wp_deregister_script('jquery');
        wp_deregister_script('prototype');
        wp_deregister_script('swfobject');
        wp_register_script('jquery', $abu . '/jquery/' . $jqv . '/jquery.min.js', false, null);
        wp_register_script('prototype', $abu . '/prototype/' . $ptv . '/prototype.js', false, null);
        wp_register_script('swfobject', $abu . '/swfobject/' . $sov . '/swfobject.js', false, null);
    }

    if (get_option('xo_favicon_link') == 1) {
        add_action('wp_head', 'xo_favicon_link');
    }

    if (get_option('xo_meta_desc_excerpt') == 1) {
        add_action('wp_head', 'xo_meta_desc_excerpt');
    }

    if (get_option('xo_meta_desc_home') == 1) {
        add_action('wp_head', 'xo_meta_desc_home');
    }

    if (get_option('xo_meta_noindex_idx') == 1 and get_option('xo_meta_nofollow_idx') == 1) {
        add_action('wp_head', 'xo_meta_noindex_nofollow_idx');
    } elseif (get_option('xo_meta_noindex_idx') == 1 and get_option('xo_meta_nofollow_idx') == 0) {
        add_action('wp_head', 'xo_meta_noindex_idx');
    } elseif (get_option('xo_meta_noindex_idx') == 0 and get_option('xo_meta_nofollow_idx') == 1) {
        add_action('wp_head', 'xo_meta_nofollow_idx');
    }

    if (get_option('xo_rm_admin_bar') == 1) {
        add_filter('show_admin_bar', '__return_false');
    }

    if (get_option('xo_rm_convert_chars') == 1) {
        foreach (array(
            'bloginfo',
            'comment_author',
            'comment_excerpt',
            'comment_text',
            'link_description',
            'link_name',
            'link_notes',
            'term_description',
            'term_name',
            'term_name_rss',
            'the_content',
            'the_excerpt',
            'the_excerpt_rss',
            'the_title',
            'widget_title',
            'wp_title',
        ) as $item) {
            remove_filter($item, 'convert_chars');
        }
    }

    if (get_option('xo_rm_feed_links') == 1) {
        remove_action('wp_head', 'feed_links', 2);
    }

    if (get_option('xo_rm_feed_links_extra') == 1) {
        remove_action('wp_head', 'feed_links_extra', 3);
    }

    if (get_option('xo_rm_generator') == 1) {
        add_filter('the_generator', create_function('$a', 'return null;'));
        remove_action('wp_head', 'wp_generator');
    }

    if (get_option('xo_rm_more_anchor') == 1) {
        add_filter('the_content_more_link', 'xo_rm_more_anchor');
    }

    if (get_option('xo_rm_P_police') == 1) {
        remove_filter('the_content', 'capital_P_dangit', 11);
        remove_filter('the_title', 'capital_P_dangit', 11);
        
        if (version_compare($wp_version, '3.0.9', '>')) {
            remove_filter('comment_text', 'capital_P_dangit', 31);
        } else {
            remove_filter('comment_text', 'capital_P_dangit', 11);
        }
    }

    if (get_option('xo_rm_rel_canonical') == 1) {
        remove_action('wp_head', 'rel_canonical');
    }

    if (get_option('xo_rm_rel_nav_links') == 1) {
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0   );
        remove_action('wp_head', 'index_rel_link'                           );
        remove_action('wp_head', 'parent_post_rel_link',            10, 0   );
        remove_action('wp_head', 'start_post_rel_link',             10, 0   );
    }

    if (get_option('xo_rm_rel_shortlink') == 1) {
        remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
        #   Bundling the two together is wrong...
        #   Make a separate option;  or a group of options for header fields.
        #   remove_action('template_redirect', 'wp_shortlink_header', 11, 0);
    }

    if (get_option('xo_rm_rsd_link') == 1) {
        remove_action('wp_head', 'rsd_link');
    }

    if (get_option('xo_rm_texturize') == 1) {
        foreach (array(
            'bloginfo',
            'category_description',
            'link_description',
            'link_name',
            'link_notes',
            'list_cats',
            'single_post_title',
            'term_name',
            'the_content',
            'the_excerpt',
            'the_title',
            'widget_title',
            'wp_title',
        ) as $item) {
            remove_filter($item, 'wptexturize');
        }
    }

    if (get_option('xo_rm_texturize_cmnt') == 1) {
        remove_filter('comment_author', 'wptexturize');
        remove_filter('comment_text', 'wptexturize');
    }

    if (get_option('xo_rm_wlw_link') == 1) {
        remove_action('wp_head', 'wlwmanifest_link');
    }

    /**
     *  Emits favicon link to be printed in the document HEAD.
     *
     *  @since 0.0.1
     */
    function xo_favicon_link()
    {
        echo '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />' . "\n";
    }

    /**
     *  Makes and emits META descriptions from manual excerpts for posts of any type, including Pages.
     *
     *  @since 0.0.1
     *
     *  @uses __()
     *  @uses esc_attr()
     *  @uses get_permalink()
     *  @uses get_the_excerpt()
     *  @uses has_excerpt()
     *  @uses is_singular()
     */
    function xo_meta_desc_excerpt()
    {
        if (!is_singular()) {
            return;
        }

        if (!has_excerpt()) {
            return;
        }

        $d = get_the_excerpt();
        
        #   For Twenty Ten...
        if (function_exists('twentyten_continue_reading_link')) {
            $l = ' <a href="'. get_permalink() . '">' . __('Continue reading <span class="meta-nav">&rarr;</span>') . '</a>';
            $d = str_replace($l, '', $d);
        }

        $d = strip_tags($d);
        $d = (function_exists('mb_substr'))? mb_substr($d, 0, 192): substr($d, 0, 192);
        $d = preg_replace('/\s+/', ' ', $d);
        $d = trim($d);
        $d = esc_attr($d);
            
        echo '<meta name="description" content="' . $d . '" />' . "\n";
    }

    /**
     *  Makes and emits META descriptions for the home page from the tagline.
     *
     *  @since 0.0.1
     *
     *  @uses esc_attr()
     *  @uses get_bloginfo()
     */
    function xo_meta_desc_home()
    {
        if (is_home()) {
            if (strlen(get_bloginfo('description', 'display'))) {
                echo '<meta name="description" content="' . get_bloginfo('description') . '" />' . "\n";
            }
        }
    }

    /**
     *  Emits “noindex” meta-element for all documents except:
     *  1.  Front page
     *  2.  Home page
     *  3.  Posts of any type, including Pages, attachements and custom types
     *
     *  @since 0.0.1
     *
     *  @uses is_front_page()
     *  @uses is_home()
     *  @uses is_singular()
     */
    function xo_meta_noindex_idx()
    {
        if (is_singular() or is_home() or is_front_page()) {
            return;
        }

        echo '<meta name="robots" content="noindex" />' . "\n";
    }

    /**
     *  Emits “noindex, nofollow” meta-element for all documents except:
     *  1.  Front page
     *  2.  Home page
     *  3.  Posts of any type, including Pages, attachements and custom types
     *
     *  @since 0.0.1
     *
     *  @uses is_front_page()
     *  @uses is_home()
     *  @uses is_singular()
     */
    function xo_meta_noindex_nofollow_idx()
    {
        if (is_singular() or is_home() or is_front_page()) {
            return;
        }

        echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
    }

    /**
     *  Emits “nofollow” meta-element for all documents except:
     *  1.  Front page
     *  2.  Home page
     *  3.  Posts of any type, including Pages, attachements and custom types
     *
     *  @since 0.0.1
     *
     *  @uses is_front_page()
     *  @uses is_home()
     *  @uses is_singular()
     */
    function xo_meta_nofollow_idx()
    {
        if (is_singular() or is_home() or is_front_page()) {
            return;
        }

        echo '<meta name="robots" content="nofollow" />' . "\n";
    }

    /**
     *  Removes the anchor part from READ MORE... links
     *
     *  Inspired from the {@link http://www.thunderguy.com/semicolon/wordpress/seemore-wordpress-plugin/ Seemore plugin by Bennett McElwee}
     *
     *  @since 0.0.1
     *
     *  @uses get_the_ID()
     */
    function xo_rm_more_anchor($o)
    {
        return str_replace('#more-' . get_the_ID(), '', $o);
    }
}


#
#   Run code needed everywhere (backend and frontend)
#

if (defined('WP_POST_REVISIONS')) {
    $xo_is_revisions_defined = true;
} elseif (get_option('xo_post_revisions') != 9) {
    define('WP_POST_REVISIONS', get_option('xo_post_revisions'));
}

if (get_option('xo_rm_rich_edit') == 1) {
    add_filter('user_can_richedit', '__return_false');
}

if (defined('EMPTY_TRASH_DAYS') and constant('EMPTY_TRASH_DAYS') != 30) {
    $xo_is_trash_defined = true;
} elseif (get_option('xo_trash_24h') == 1) {
    define('EMPTY_TRASH_DAYS', 1);
}

#   EOF