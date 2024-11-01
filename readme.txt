=== Extra Options ===

Contributors:       demetris
Tags:               AJAX,excerpts,favicon,google,meta,more,noindex,nofollow,page,revisions,SEO,settings,wptexturize
Requires at least:  3.0
Tested up to:       3.1
Stable tag:         0.0.4

A collection of tweaks to improve your WordPress experience and your site’s performance.


==  Description ==

Extra Options is a collection of WordPress tweaks to improve your publishing experience and your site’s performance.

It strives to be simple and discreet;
everything in it is optional, it does nothing by default, does not touch your content, and uninstalls cleanly.

Extra Options offers options for the following:

*   Writing
*   Administration
*   Content filters control
*   Document HEAD control
*   Search Engine Optimization (all you need and nothing you don’t)
*   Loading AJAX libraries from the Google CDN
*   Plus a couple of miscellaneous tweaks

See homepage for a detailed presentation:
[op111.net/code/xo](http://op111.net/code/xo "Extra Options for WordPress [op111.net]")

Please note that Extra Options is still very young and **not** considered stable.  Your feedback is appreciated:
[op111.net/78](http://op111.net/78 "Extra Options v0.0.1: A plugin for WordPress [op111.net]")


==  Changelog ==

=   0.0.4 — 18 Nov 2010 =
*   `NEW`.  Option to disable the Admin Bar of WordPress 3.1.
*   `UPD`.  P Police removal modified to accommodate change in WP 3.1.
*   `UPD`.  jQuery version for WordPress ≥ 3.1 raised to 1.4.4.

=   0.0.3 — 18 Oct 2010 =
*   `FIX`.  jQuery UI removed from libraries loadable from Google CDN.
    (Shouldn’t have include it to begin with, as there are differences in packaging.  My apologies!)
*   `UPD`.  jQuery version for WordPress ≥ 3.1 raised to 1.4.3.

=   0.0.2 — 09 Oct 2010 =
*   `FIX`.  HTTPS now used for AJAX libs if SSL is on.
*   `FIX`.  Favicon link element closed, to be valid for both XHTML and HTML5.
*   `NEW`.  Options for upper limit added to Post Revisions setting.
*   `NEW`.  SWFObject added to libraries loadable from Google CDN.
*   `NEW`.  Readme and Feedback links added to settings screen.
*   `UPD`.  jQuery UI version for WordPress ≥ 3.1 raised to 1.8.5.
*   `MSC`.  More backend code moved into xo-adm.php.

=   0.0.1 — 25 Sep 2010 =
*   First public release.


== Upgrade Notice ==

=   0.0.4 =
New option to disable the admin bar of WordPress 3.1, plus a couple more adjustments for WP 3.1.

=   0.0.3 =
jQuery up to 1.4.3 for WordPress ≥ 3.1.  jQuery UI removed from libraries loadable from the Google CDN.

=   0.0.2 =
Fixes a couple of issues, adds options to limit number of post revisions, adds SWFObject to libraries loadable from Google CDN.
