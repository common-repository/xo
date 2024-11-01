<?php

/**
 *  @since 0.0.1
 *
 *  @package WordPress
 *  @subpackage XO 
 */


if (get_option('xo_page_excerpt_box') == 1) {
    add_post_type_support('page', 'excerpt');
}

if (defined('DISALLOW_FILE_EDIT')) {
    $xo_is_file_edit_defined = true;
} elseif (get_option('xo_rm_code_edit') == 1) {
    define('DISALLOW_FILE_EDIT', 1);
}


/**
 *  Registers settings screen.
 *
 *  @since 0.0.1
 *
 *  @uses add_options_page
 */
function xo_add_options_page()
{
    add_options_page('Extra Options', 'Extra', 'manage_options', 'xo', 'xo_options_page');
}

/**
 *  Filters the plugin’s action links (which are an array) to append link to settings screen. 
 *
 *  @since 0.0.1
 */
function xo_add_settings_link($o)
{
    $o[] = '<a href="options-general.php?page=xo">' . __('Settings', 'xo-wp') . "</a>";
    
    return $o;
}

/**
 *  Prints CSS rules for the settings screen.
 *
 *  H3 styling stolen and adapted from {@link http://p2theme.com/ P2}.
 *
 *  @since 0.0.1
 */
function xo_admin_print_style()
{
    echo <<<STYLE
<style>
form h3 {
    border-bottom: 1px solid #DDD;
    font-family: Georgia, serif;
    font-weight: 700;
    margin-bottom: 0.5em;
    margin-top: 2em;
    padding-bottom: 5px;
}
form h3.first {
    margin-top: 0.5em;
}
form .form-table th {
    /*
        Zero right padding to make LABEL + INPUT a continuous clickable area
    */
    padding-right: 0;
    width: 320px;
}
form .form-table td {
    /*
        Zero left padding to make LABEL + INPUT a continuous clickable area
    */
    padding-left: 0;
    vertical-align: middle;
}
form .form-table td label,
form .form-table th label {
    display: block;
    float: left;
    width: 100%;
}
</style>

STYLE;
}
    
/**
 *  Prints and handles the form.
 *
 *  @since 0.0.1
 *
 *  @uses __()
 *  @uses checked()
 *  @uses current_user_can()
 *  @uses disabled()
 *  @uses esc_attr()
 *  @uses get_option()
 *  @uses register_setting()
 *  @uses screen_icon()
 *  @uses settings_fields()
 *  @uses wp_die()
 *  @uses update_option()
 */
function xo_options_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'xo'));
    }

    global $xo_is_file_edit_defined;
    global $xo_is_revisions_defined;
    global $xo_is_trash_defined;
    
    global $wp_version;

    $xo_msg_modified_elsewhere  = ' — ' . '<em>' . __('Idle setting: Default modified elsewhere.', 'xo') . '</em>';
    $xo_msg_favicon_missing     = ' — ' . '<em>' . __('No favicon found in root directory.', 'xo') . '</em>';

    register_setting('xo', 'xo_ajax_libs_google',       'intval'  );
    register_setting('xo', 'xo_favicon_link',           'intval'  );
    register_setting('xo', 'xo_meta_desc_excerpt',      'intval'  );
    register_setting('xo', 'xo_meta_desc_home',         'intval'  );
    register_setting('xo', 'xo_meta_noindex_idx',       'intval'  );
    register_setting('xo', 'xo_meta_nofollow_idx',      'intval'  );
    register_setting('xo', 'xo_page_excerpt_box',       'intval'  );
    register_setting('xo', 'xo_post_revisions',         'intval'  );
    register_setting('xo', 'xo_rm_admin_bar',           'intval'  );
    register_setting('xo', 'xo_rm_code_edit',           'intval'  );
    register_setting('xo', 'xo_rm_convert_chars',       'intval'  );
    register_setting('xo', 'xo_rm_feed_links',          'intval'  );
    register_setting('xo', 'xo_rm_feed_links_extra',    'intval'  );
    register_setting('xo', 'xo_rm_generator',           'intval'  );
    register_setting('xo', 'xo_rm_more_anchor',         'intval'  );
    register_setting('xo', 'xo_rm_P_police',            'intval'  );
    register_setting('xo', 'xo_rm_rel_canonical',       'intval'  );
    register_setting('xo', 'xo_rm_rel_nav_links',       'intval'  );
    register_setting('xo', 'xo_rm_rel_shortlink',       'intval'  );
    register_setting('xo', 'xo_rm_rich_edit',           'intval'  );
    register_setting('xo', 'xo_rm_rsd_link',            'intval'  );
    register_setting('xo', 'xo_rm_texturize',           'intval'  );
    register_setting('xo', 'xo_rm_texturize_cmnt',      'intval'  );
    register_setting('xo', 'xo_rm_wlw_link',            'intval'  );
    register_setting('xo', 'xo_trash_24h',              'intval'  );

    $xo_ajax_libs_google    = get_option('xo_ajax_libs_google'      );
    $xo_favicon_link        = get_option('xo_favicon_link'          );
    $xo_meta_desc_excerpt   = get_option('xo_meta_desc_excerpt'     );
    $xo_meta_desc_home      = get_option('xo_meta_desc_home'        );
    $xo_meta_noindex_idx    = get_option('xo_meta_noindex_idx'      );
    $xo_meta_nofollow_idx   = get_option('xo_meta_nofollow_idx'     );
    $xo_page_excerpt_box    = get_option('xo_page_excerpt_box'      );
    $xo_post_revisions      = get_option('xo_post_revisions'        );
    $xo_rm_admin_bar        = get_option('xo_rm_admin_bar'          );
    $xo_rm_code_edit        = get_option('xo_rm_code_edit'          );
    $xo_rm_convert_chars    = get_option('xo_rm_convert_chars'      );
    $xo_rm_feed_links       = get_option('xo_rm_feed_links'         );
    $xo_rm_feed_links_extra = get_option('xo_rm_feed_links_extra'   );
    $xo_rm_more_anchor      = get_option('xo_rm_more_anchor'        );
    $xo_rm_P_police         = get_option('xo_rm_P_police'           );
    $xo_rm_rel_canonical    = get_option('xo_rm_rel_canonical'      );
    $xo_rm_rel_nav_links    = get_option('xo_rm_rel_nav_links'      );
    $xo_rm_rel_shortlink    = get_option('xo_rm_rel_shortlink'      );
    $xo_rm_rich_edit        = get_option('xo_rm_rich_edit'          );
    $xo_rm_rsd_link         = get_option('xo_rm_rsd_link'           );
    $xo_rm_texturize        = get_option('xo_rm_texturize'          );
    $xo_rm_texturize_cmnt   = get_option('xo_rm_texturize_cmnt'     );
    $xo_rm_wlw_link         = get_option('xo_rm_wlw_link'           );
    $xo_rm_generator        = get_option('xo_rm_generator'          );
    $xo_trash_24h           = get_option('xo_trash_24h'             );

    if (isset($_POST['action']) and esc_attr($_POST['action']) == 'update') {

        $xo_post_revisions      = $_POST['xo_post_revisions'];

        $xo_ajax_libs_google    = isset($_POST['xo_ajax_libs_google']       );
        $xo_favicon_link        = isset($_POST['xo_favicon_link']           );
        $xo_meta_desc_excerpt   = isset($_POST['xo_meta_desc_excerpt']      );
        $xo_meta_desc_home      = isset($_POST['xo_meta_desc_home']         );
        $xo_meta_noindex_idx    = isset($_POST['xo_meta_noindex_idx']       );
        $xo_meta_nofollow_idx   = isset($_POST['xo_meta_nofollow_idx']      );
        $xo_page_excerpt_box    = isset($_POST['xo_page_excerpt_box']       );
        $xo_rm_admin_bar        = isset($_POST['xo_rm_admin_bar']           );
        $xo_rm_code_edit        = isset($_POST['xo_rm_code_edit']           );
        $xo_rm_convert_chars    = isset($_POST['xo_rm_convert_chars']       );
        $xo_rm_feed_links       = isset($_POST['xo_rm_feed_links']          );
        $xo_rm_feed_links_extra = isset($_POST['xo_rm_feed_links_extra']    );
        $xo_rm_generator        = isset($_POST['xo_rm_generator']           );
        $xo_rm_more_anchor      = isset($_POST['xo_rm_more_anchor']         );
        $xo_rm_P_police         = isset($_POST['xo_rm_P_police']            );
        $xo_rm_rel_canonical    = isset($_POST['xo_rm_rel_canonical']       );
        $xo_rm_rel_nav_links    = isset($_POST['xo_rm_rel_nav_links']       );
        $xo_rm_rel_shortlink    = isset($_POST['xo_rm_rel_shortlink']       );
        $xo_rm_rich_edit        = isset($_POST['xo_rm_rich_edit']           );
        $xo_rm_rsd_link         = isset($_POST['xo_rm_rsd_link']            );
        $xo_rm_texturize        = isset($_POST['xo_rm_texturize']           );
        $xo_rm_texturize_cmnt   = isset($_POST['xo_rm_texturize_cmnt']      );
        $xo_rm_wlw_link         = isset($_POST['xo_rm_wlw_link']            );
        $xo_trash_24h           = isset($_POST['xo_trash_24h']              );

        update_option('xo_ajax_libs_google',    $xo_ajax_libs_google    );
        update_option('xo_favicon_link',        $xo_favicon_link        );
        update_option('xo_meta_desc_excerpt',   $xo_meta_desc_excerpt   );
        update_option('xo_meta_desc_home',      $xo_meta_desc_home      );
        update_option('xo_meta_noindex_idx',    $xo_meta_noindex_idx    );
        update_option('xo_meta_nofollow_idx',   $xo_meta_nofollow_idx   );
        update_option('xo_page_excerpt_box',    $xo_page_excerpt_box    );
        update_option('xo_post_revisions',      $xo_post_revisions      );
        update_option('xo_rm_admin_bar',        $xo_rm_admin_bar        );
        update_option('xo_rm_code_edit',        $xo_rm_code_edit        );
        update_option('xo_rm_convert_chars',    $xo_rm_convert_chars    );
        update_option('xo_rm_feed_links',       $xo_rm_feed_links       );
        update_option('xo_rm_feed_links_extra', $xo_rm_feed_links_extra );
        update_option('xo_rm_generator',        $xo_rm_generator        );
        update_option('xo_rm_more_anchor',      $xo_rm_more_anchor      );
        update_option('xo_rm_P_police',         $xo_rm_P_police         );
        update_option('xo_rm_rel_canonical',    $xo_rm_rel_canonical    );
        update_option('xo_rm_rel_nav_links',    $xo_rm_rel_nav_links    );
        update_option('xo_rm_rel_shortlink',    $xo_rm_rel_shortlink    );
        update_option('xo_rm_rich_edit',        $xo_rm_rich_edit        );
        update_option('xo_rm_rsd_link',         $xo_rm_rsd_link         );
        update_option('xo_rm_texturize',        $xo_rm_texturize        );
        update_option('xo_rm_texturize_cmnt',   $xo_rm_texturize_cmnt   );
        update_option('xo_rm_wlw_link',         $xo_rm_wlw_link         );
        update_option('xo_trash_24h',           $xo_trash_24h           );

        echo '<div id="message" class="updated">' . "\n"
        .   '<p>' . __('Changes saved.', 'xo-wp') . '</p>' . "\n"
        .   '</div>' . "\n\n"
        ;
    }

    echo '<div class="wrap">' . "\n\n";

    screen_icon();

    echo '<h2>'
    .   __('Extra Options', 'xo')
    .   '</h2>' . "\n\n"
    ;

    echo '<p>'
    .   '<a' . ' '
    .   'href="http://op111.net/code/xo"' . ' '
    .   'title="' . __('Visit the plugin homepage for explanations and other useful information', 'xo') . '"'
    .   '>'
    .   __('Extra Options Explained', 'xo')
    .   '</a>'
    .   ' &middot; '
    .   '<a' . ' '
    .   'href="http://op111.net/78"' . ' '
    .   'title="' . __('Visit op111.net for questions, suggestions, or to report any issues', 'xo') . '"'
    .   '>'
    .   __('Your Feedback', 'xo')
    .   '</a>'
    .   '</p>' . "\n\n"
    ;

    echo '<form name="xo" method="post" action="">' . "\n\n";

    settings_fields('xo');

    echo '<p class="submit">'
    .   '<input type="submit" name="submit" class="button-primary" value="' . esc_attr(__('Save Changes', 'xo-wp')) . '" />'
    .   '</p>' . "\n\n"
    ;

    #
    #   WRITING
    #

    echo '<h3 class="first">'
    .   __('Writing', 'xo-wp')
    .   '</h3>' . "\n\n"
    ;

    echo '<table class="form-table">' . "\n\n";

    echo '<tbody>' . "\n\n";

    #   Excerpts for Pages / ENABLE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_page_excerpt_box">'
    .   __('Add excerpt metabox for Pages', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_page_excerpt_box" name="xo_page_excerpt_box" type="checkbox" value="1"' . checked($xo_page_excerpt_box, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   Plugin/Theme editor / DISABLE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_code_edit">'
    .   __('Disable plugin/theme editor', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_code_edit" name="xo_rm_code_edit" type="checkbox" value="1"' . checked($xo_rm_code_edit, 1, 0) . '/>'
    .   (($xo_is_file_edit_defined)? $xo_msg_modified_elsewhere: '')
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   Visual editor / DISABLE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_rich_edit">'
    .   __('Disable visual editor', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_rich_edit" name="xo_rm_rich_edit" type="checkbox" value="1"' . checked($xo_rm_rich_edit, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   Admin bar / DISABLE
    if (version_compare($wp_version, '3.0.9', '>')) {
        echo '<tr valign="top">' . "\n"
        .   '<th scope="row">'
        .   '<label for="xo_rm_admin_bar">'
        .   __('Disable admin bar', 'xo')
        .   '</label>'
        .   '</th>' . "\n"
        .   '<td>'
        .   '<input id="xo_rm_admin_bar" name="xo_rm_admin_bar" type="checkbox" value="1"' . checked($xo_rm_admin_bar, 1, 0) . '/>'
        .   '</td>' . "\n"
        .   '</tr>' . "\n\n"
        ;
    }

    #   Trash retention 24 hours / Limit
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_trash_24h">'
    .   __('Limit trash retention to 24 hours', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_trash_24h" name="xo_trash_24h" type="checkbox" value="1"' . checked($xo_trash_24h, 1, 0) . '/>'
    .   (($xo_is_trash_defined)? $xo_msg_modified_elsewhere: '')
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   Post/Page revisions / Limit
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_post_revisions">'
    .   __('Limit number of post/page revisions', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>' . "\n"
    .   '<select id="xo_post_revisions" name="xo_post_revisions">' . "\n"
    ;

    foreach (array('9' => '∞', '0' => '0', '10' => '10', '100' => '100', '1000' => '1000') as $opt => $name) {
        echo '<option value="' . $opt . '"' . selected($xo_post_revisions, $opt, 0) . '>'
        .   esc_html($name)
        .   '</option>' . "\n"
        ;
    }

    echo '</select>' . "\n"
    .   (($xo_is_revisions_defined)? $xo_msg_modified_elsewhere: '')
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    echo '</tbody>' . "\n\n";

    echo '</table>' . "\n\n";

    #
    #   SEO
    #
    
    echo '<h3>'
    .   __('SEO', 'xo')
    .   '</h3>' . "\n\n"
    ;
    
    echo '<table class="form-table">' . "\n\n";

    echo '<tbody>' . "\n\n";

    #   META descriptions from excerpts / ENABLE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_meta_desc_excerpt">'
    .   __('Make <code>meta</code> descriptions from excerpts', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_meta_desc_excerpt" name="xo_meta_desc_excerpt" type="checkbox" value="1"' . checked($xo_meta_desc_excerpt, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   Home META description from tagline / ENABLE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_meta_desc_home">'
    .   __('Make home <code>meta</code> description from tagline', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_meta_desc_home" name="xo_meta_desc_home" type="checkbox" value="1"' . checked($xo_meta_desc_home, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   noindex / ENABLE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_meta_noindex_idx">'
    .   __('Add <code>noindex</code> where appropriate', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_meta_noindex_idx" name="xo_meta_noindex_idx" type="checkbox" value="1"' . checked($xo_meta_noindex_idx, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   nofollow / ENABLE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_meta_nofollow_idx">'
    .   __('Add <code>nofollow</code> where appropriate', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_meta_nofollow_idx" name="xo_meta_nofollow_idx" type="checkbox" value="1"' . checked($xo_meta_nofollow_idx, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    echo '</tbody>' . "\n\n";

    echo '</table>' . "\n\n";

    #
    #   CONTENT FILTERS
    #
    
    echo '<h3>'
    .   __('Content filters', 'xo')
    .   '</h3>' . "\n\n"
    ;
    
    echo '<table class="form-table">' . "\n\n";

    echo '<tbody>' . "\n\n";

    #   WP Texturize / DISABLE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_texturize">'
    .   __('Disable WP Texturize in <em>main content</em>', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_texturize" name="xo_rm_texturize" type="checkbox" value="1"' . checked($xo_rm_texturize, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   WP Texturize comments / DISABLE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_texturize_cmnt">'
    .   __('Disable WP Texturize in <em>comments</em>', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_texturize_cmnt" name="xo_rm_texturize_cmnt" type="checkbox" value="1"' . checked($xo_rm_texturize_cmnt, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   convert_chars() / DISABLE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_convert_chars">'
    .   __('Disable <code>convert_chars()</code>', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_convert_chars" name="xo_rm_convert_chars" type="checkbox" value="1"' . checked($xo_rm_convert_chars, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   Capital P Police / DISABLE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_P_police">'
    .   __('Disable Capital P Police', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_P_police" name="xo_rm_P_police" type="checkbox" value="1"' . checked($xo_rm_P_police, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    echo '</tbody>' . "\n\n";

    echo '</table>' . "\n\n";

    #
    #   Document HEAD
    #
    
    echo '<h3>'
    .   __('Document HEAD', 'xo')
    .   '</h3>' . "\n\n"
    ;
    
    echo '<table class="form-table">' . "\n\n";

    echo '<tbody>' . "\n\n";

    #   Favicon link / ADD
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_favicon_link">'
    .   __('Add favicon link', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_favicon_link" name="xo_favicon_link" type="checkbox" value="1"'
        .   checked($xo_favicon_link, 1, 0)
        .   disabled(!is_readable($_SERVER['DOCUMENT_ROOT'] . '/favicon.ico'), 1, 0)
        .   '/>' . ' '
    .   (is_readable($_SERVER['DOCUMENT_ROOT'] . '/favicon.ico')? '': $xo_msg_favicon_missing)
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   Generator / REMOVE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_generator">'
    .   __('Remove WordPress info', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_generator" name="xo_rm_generator" type="checkbox" value="1"' . checked($xo_rm_generator, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   Navigation links / REMOVE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_rel_nav_links">'
    .   __('Remove navigation links', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_rel_nav_links" name="xo_rm_rel_nav_links" type="checkbox" value="1"' . checked($xo_rm_rel_nav_links, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   Canonical link / REMOVE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_rel_canonical">'
    .   __('Remove canonical link', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_rel_canonical" name="xo_rm_rel_canonical" type="checkbox" value="1"' . checked($xo_rm_rel_canonical, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   Shortlink link / REMOVE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_rel_shortlink">'
    .   __('Remove shortlink link', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_rel_shortlink" name="xo_rm_rel_shortlink" type="checkbox" value="1"' . checked($xo_rm_rel_shortlink, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   WLW manifest link / REMOVE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_wlw_link">'
    .   __('Remove WLW manifest link', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_wlw_link" name="xo_rm_wlw_link" type="checkbox" value="1"' . checked($xo_rm_wlw_link, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   RSD link / REMOVE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_rsd_link">'
    .   __('Remove RSD link', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_rsd_link" name="xo_rm_rsd_link" type="checkbox" value="1"' . checked($xo_rm_rsd_link, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   Feed links / REMOVE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_feed_links">'
    .   __('Remove feed links', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_feed_links" name="xo_rm_feed_links" type="checkbox" value="1"' . checked($xo_rm_feed_links, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   Feed extra links / REMOVE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_feed_links_extra">'
    .   __('Remove extra feed links', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_feed_links_extra" name="xo_rm_feed_links_extra" type="checkbox" value="1"' . checked($xo_rm_feed_links_extra, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    echo '</tbody>' . "\n\n";

    echo '</table>' . "\n\n";

    #
    #   MISCELLANEOUS
    #
    
    echo '<h3>'
    .   __('Miscellaneous', 'xo')
    .   '</h3>' . "\n\n"
    ;
    
    echo '<table class="form-table">' . "\n\n";

    echo '<tbody>' . "\n\n";

    #   Google AJAX CDN / ADD
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_ajax_libs_google">'
    .   __('Get AJAX libraries from Google', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_ajax_libs_google" name="xo_ajax_libs_google" type="checkbox" value="1"' . checked($xo_ajax_libs_google, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    #   More anchor / REMOVE
    echo '<tr valign="top">' . "\n"
    .   '<th scope="row">'
    .   '<label for="xo_rm_more_anchor">'
    .   __('Remove anchor in <i>Read more...</i> links', 'xo')
    .   '</label>'
    .   '</th>' . "\n"
    .   '<td>'
    .   '<input id="xo_rm_more_anchor" name="xo_rm_more_anchor" type="checkbox" value="1"' . checked($xo_rm_more_anchor, 1, 0) . '/>'
    .   '</td>' . "\n"
    .   '</tr>' . "\n\n"
    ;

    echo '</tbody>' . "\n\n";
            
    echo '</table>' . "\n\n";

    #
    #   SAVE CHANGES
    #
    
    echo '<p class="submit">'
    .   '<input type="submit" name="submit" class="button-primary" value="' . esc_attr(__('Save Changes')) . '"' . ' ' . '/>'
    .   '</p>' . "\n\n"
    ;

    echo '</form>' . "\n\n";  # (@xo)
    
    echo '</div>' . "\n\n";  # (.wrap)
}

#   EOF