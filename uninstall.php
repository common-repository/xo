<?php

/**
 *  Runs automatically upon plugin deletion. 
 *
 *  @uses delete_option()
 *
 *  @since 0.0.1
 */
 
if (defined('ABSPATH') and defined('WP_UNINSTALL_PLUGIN')) {
    delete_option('xo_ajax_libs_google'     );
    delete_option('xo_favicon_link'         );
    delete_option('xo_meta_desc_excerpt'    );
    delete_option('xo_meta_desc_home'       );
    delete_option('xo_meta_noindex_idx'     );
    delete_option('xo_meta_nofollow_idx'    );
    delete_option('xo_page_excerpt_box'     );
    delete_option('xo_post_revisions'       );
    delete_option('xo_rm_admin_bar'         );
    delete_option('xo_rm_code_edit'         );
    delete_option('xo_rm_convert_chars'     );
    delete_option('xo_rm_feed_links'        );
    delete_option('xo_rm_feed_links_extra'  );
    delete_option('xo_rm_generator'         );
    delete_option('xo_rm_more_anchor'       );
    delete_option('xo_rm_P_police'          );
    delete_option('xo_rm_post_revisions'    );
    delete_option('xo_rm_rel_canonical'     );
    delete_option('xo_rm_rel_nav_links'     );
    delete_option('xo_rm_rel_shortlink'     );
    delete_option('xo_rm_rich_edit'         );
    delete_option('xo_rm_rsd_link'          );
    delete_option('xo_rm_texturize'         );
    delete_option('xo_rm_texturize_cmnt'    );
    delete_option('xo_rm_wlw_link'          );
    delete_option('xo_trash_24h'            );
}

#   EOF
