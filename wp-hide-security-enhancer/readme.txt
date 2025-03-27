=== WP Hide & Security Enhancer ===
Contributors: nsp-code, tdgu
Donate link: https://www.nsp-code.com/
Tags: wordpress hide, wp hide, security, security headers, login, 2fa
Requires at least: 2.8
Tested up to: 6.7.2
Stable tag: 2.6.3
License: GPLv2 or later

Protect your website by concealing vulnerable WordPress traces, plugins, themes, login/admin url. 2FA, Captcha, Firewall, Security Headers etc.

== Description ==

Effortlessly conceal your WordPress site from detection! With over 99.99% of hacks targeting specific plugin and theme vulnerabilities, this plugin significantly boosts site security by making it invisible to hackers' web scanners. 

By removing all traces of WordPress, including themes and plugins, potential exploits are rendered harmless. This method ensures that your site is safe without affecting SEO; in fact, it can enhance certain SEO aspects when used strategically.


WP-Hide has launched the **easiest way to completely hide your WordPress** core files, login page, theme and plugins paths from being shown on front side. This is a huge improvement over Site Security, since no one will know whether you are running or not a WordPress. It also provides a simple way to clean up html by removing all WordPress fingerprints.

**No file and directory change!**
No file and directory will be changed anywhere. Everything is processed virtually. The plugin code uses URL rewrite techniques and WordPress filters to apply all internal functionality and features. Everything is done automatically without user intervention required at all.

**Real hide of WordPress core files and plugins**
The plugin not only allows you to change default URLs of you WordPress, but it also hides/blocks such defaults. Other similar plugins, just change the slugs, but the defaults are still accessible, obviously revealing WordPress as CMS.

You can change the default WordPress login URL from wp-admin and wp-login.php to something totally arbitrary. No one will ever know where to try to guess a login and hack into your site. It becomes totally invisible.

[youtube https://www.youtube.com/watch?v=PJstAU34SlQ]

<br />Full plugin documentation available at <a target="_blank" href="https://wp-hide.com/documentation/">WordPress Hide and Security Enhancer Documentation</a>

When testing with WordPress theme and plugins detector services/sites, any setting change may not reflect right away on their reports, since they use cache. So, you may want to check again later, or try a different inner URL. Homepage URL usage is not mandatory.

Being the best content management system, widely used, WordPress is susceptible to a large range of hacking attacks including brute-force, SQL injections, XSS, XSRF etc. Despite the fact the WordPress core is a very secure code maintained by a team of professional enthusiast, the additional plugins and themes make ita vulnerable spot for every website. In many cases, those are created by pseudo-developers who do not follow the best coding practices or simply do not own the experience to create a secure plugin.
Statistics reveal that every day new vulnerabilities are discovered, many affecting hundreds of thousands of WordPress websites.
Over 99,9% of hacked WordPress websites are target of automated malware scripts, which search for certain WordPress fingerprints. This plugin hides or replaces those traces, making the hacking boots attacks useless.

It works well with custom WordPress directory structures,e.g. custom plugins, themes, and upload folders.

Once configured, you need to **clear server cache data and/or any cache plugins** (e.g. W3 Cache), for a new html data to be created. If you use CDN this should be cache clear as well.

**Sample usage**
[vimeo https://vimeo.com/192011678]

**Main plugin functionality:**

* Customizes Admin URL
* Blocks default admin URL
* Blocks any direct folder access to completely hide the structure
* Customize wp-login.php filename
* 2FA - Two-factor Authentication
* 2FA - Two-factor Authentication - Email Verification Code
* 2FA - Two-factor Authentication - Authenticator App
* 2FA - Two-factor Authentication - Recovery Codes
* Google Captcha 
* Blocks default wp-login.php
* Blocks default wp-signup.php
* Blocks XML-RPC API
* Creates New XML-RPC paths
* Adjusts theme URL
* Creates New child Theme URL
* Changes theme style file name
* Cleans any headers for theme style file
* Customizes wp-include 
* Blocks default wp-include paths
* Blocks default wp-content
* Customizes plugins URL
* Changes Individual plugin URL 
* Blocks default plugins paths
* Creates New upload URL
* Blocks default upload URL
* Removes WordPress version
* Blocks Meta Generator
* Disables the emoji and required javascript code
* Removes pingback tag
* Removes wlwmanifest Meta
* Removes rsd_link Meta
* Removes wpemoji
* Minifies Html, Css, JavaScript

* Security Headers

and many more.

**No other plugin functionality will be blocked or interfered in any way by WP-Hide**

This plugin allows to change the default Admin URL from **wp-login.php** and **wp-admin** to something else. All original links turn the default theme to “404 Not Found” page, as if nothing exists there. Besides the huge security advantage, the WP-Hide plugin saves lots of server processing time by reducing php code and MySQL usage since brute-force attacks target the weakURL.

**Important:** Compared to all other similar plugins which mainly use redirects, this plugin turns a default theme to“404 error” page for all **blocked URL** functionalities, without revealing the link existence at all.

Since version 1.2, WP-Hide change individual plugin URLs and made them unrecognizable. For example,the change of the default WooCommerce plugin URL and its dependencies from domain.com/wp-content/plugins/woocommerce/ into domain.com/ecommerce/cdn/ or anything customized.

= Plugin Sections =

**Hide -> Scan

* Exhaustive system security examination with analysis and improvements guidance and fixes


**Hide -> Rewrite > Theme**

* New Theme Path – Changes default theme path
* New Style File Path – Changes default style file name and path
* Remove description header from Style file – Replaces any WordPress metadata information (like theme name, version etc.,) from style file
* Child – New Theme Path – Changes default child theme path
* Child – New Style File Path – Changes child theme style-sheet file path and name
* Child – Remove description header from Style file – Replaces any WordPress metadata information (like theme name, version etc.,) from style file

**Hide -> Rewrite > WP includes**

* New Include Path – Changes default wp-include path/URL
* Block wp-include URL – Blocks default wp-include URL

**Hide -> Rewrite > WP content**

* New Content Path – Change default wp-content path/URL
* Block wp-content URL – Blocks the default content URL

**Hide -> Rewrite > Plugins**

* New Plugin Path – Changes default wp-content/plugins path/URL
* Block plugin URL – Blocks default wp-content/plugins URL
* New path / URL for Every Active Plugin
* Customize path and name for any active plugins

**Hide -> Rewrite > Uploads**

* New Upload Path – Changes default media files path/URL
* Block upload URL – Blocks default media files URL

**Hide -> Rewrite > Comments**

* New wp-comments-post.php Path
* Block wp-comments-post.php

**Hide -> Rewrite > Author**

* New Author Path
* Block default path

**Hide -> Rewrite > Search**

* New Search Path
* Block default path

**Hide -> Rewrite > XML-RPC**

* New XML-RPC Path – Changes default XML-RPC path / URL
* Block default xmlrpc.php – Blocks default XML-RPC URL
* Disable XML-RPC authentication – Filters whether XML-RPC methods require authentication
* Remove pingback – Removes pingback link tag from theme

**Hide -> Rewrite > JSON REST**

* Clean the REST API response
* Disable JSON REST V1 service – Disables an API service for WordPress which is active by default
* Disable JSON REST V2 service – Disables an API service for WordPress which is active by default
* Block any JSON REST calls – Any call for JSON REST API service will be blocked
* Disable output the REST API link tag into page header
* Disable JSON REST WP RSD endpoint from XML-RPC responses
* Disable Sends a Link header for the REST API

**Hide -> Rewrite > Root Files**

* Block license.txt – Blocks access to license.txt root file
* Block readme.html – Blocks access to readme.html root file
* Block wp-activate.php – Blocks access to wp-activate.php file
* Block wp-cron.php – Blocks outside access to wp-cron.php file
* Block wp-signup.php – Blocks default wp-signup.php file
* Block other wp-*.php files – Blocks other wp-.php files within WordPress Root

**Hide -> Rewrite > URL Slash**

* URL’s add Slash – Add a slash to any links without it. This disguisesthe existence of a file, folder or a wrong URL, which will all be slashed.

**Hide -> General / Html > Meta**

* Remove WordPress Generator Meta
* Remove Other Generator Meta
* Remove Shortlink Meta
* Remove DNS Prefetch
* Remove Resource Hints
* Remove wlwmanifest Meta
* Remove feed_links Meta
* Disable output the REST API link tag into page header
* Remove rsd_link Meta
* Remove adjacent_posts_rel Meta
* Remove profile link
* Remove canonical link

**Hide -> General / Block Detectors**

* Block Detectors

**Hide -> General / Emulate CMS**

* Emulate CMS

**Hide -> General / Html > Admin Bar**

* Remove WordPress Admin Bar for specified urser roles

**Hide -> General / Feed**

* Remove feed|rdf|rss|rss2|atom links

**Hide -> General / Robots.txt**

* Disable admin URL within Robots.txt

**Hide -> General / Html > Emoji**

* Disable Emoji
* Disable TinyMC Emoji

**Hide -> General / Html > Styles**

* Remove Version
* Remove ID from link tags

**Hide -> General / Html > Scripts**

* Remove Version

**Hide -> General / Html > Oembed**

* Remove Oembed

**Hide -> General / Html > Headers**

* Remove Link Header
* Remove X-Powered-By Header
* Remove Server Header
* Remove X-Pingback Header

**Hide -> General / Html > HTML**

* Remove HTML Comments
* Minify Html, CSS, JavaScript
* Remove general classes from body tag
* Remove ID from Menu items
* Remove class from Menu items
* Remove general classes from post
* Remove general classes from images

**Hide -> General / Html > User Interactions**

* Disable Mouse right click
* Disable Text Selection
* Disable Copy
* Disable Cut
* Disable Paste
* Disable Print
* Disable Print Screen
* Disable Developer Tools
* Disable View Source
* Disable Drag / Drop

**Hide -> Admin > wp-login.php**

* New wp-login.php – Maps a new wp-login.php instead of the default one
* Block default wp-login.php – Blocks default wp-login.php file from being accessible
* Customize the default login page Logo image 

**Hide -> Admin > Admin URL**

* New Admin URL – Creates a new admin URL instead of the default ”/wp-admin”. This also applies for admin-ajax.php calls
* Disable customized Admin Url redirect to the Login page
* Block default Admin Url – Blocks default admin URL and files from being accessible

**Security -> 2FA**

* Enable 2FA
* Enable the 2FA for specific roles
* Enforce User to Configure 2FA
* Primary option for Two-Factor

**Security -> 2FA Email**

* Activate 2FA Email

**Security -> 2FA Auth App**

* Activate Authenticator app (TOTP)

**Security -> 2FA Recovery Codes**

* Activate 2FA Recovery Codes

**Security -> Captcha**

* Google Captcha V2
* Google Captcha V3
* CloudFlare Turnstile ( PRO )


**Settings -> CDN**  

* CDN Url – Sets-up CDN if applied. Some providers replace site assets with custom URLs.

**Security -> Headers**

HTTP Response Headers are a powerful tool to Harden Your Website Security.
* Cross-Origin-Embedder-Policy (COEP)
* Cross-Origin-Opener-Policy (COOP)
* Cross-Origin-Resource-Policy (CORP)
* Referrer-Policy
* X-Content-Type-Options
* X-Download-Options
* X-Frame-Options (XFO)
* X-Permitted-Cross-Domain-Policies
* X-XSS-Protection

<br />This free version works with Apache and IIS server types. For all server types, check with <a target="_blank" href="https://wp-hide.com/">WP Hide PRO</a>

<br />This is a basic version that can hide everything for basic sites, example <a target="_blank" href="https://demo.wp-hide.com/">https://demo.wp-hide.com/</a>. When using complex plugins and themes, the WP Hide PRO may be required. We provide free assistance to hide everything on your site, along with the commercial product. 

<br />Anything wrong with this plugin on your site? Just use the forum or get in touch with us at <a target="_blank" href="https://wp-hide.com/contact/">Contact</a> and we'll check it out.

<br />A website example can be found at <a target="_blank" href="https://demo.wp-hide.com/">https://demo.wp-hide.com/</a> or our website <a target="_blank" href="https://wp-hide.com/">WP Hide and Security Enhancer</a>

<br />Plugin homepage at <a target="_blank" href="https://wp-hide.com/">WordPress Hide and Security Enhancer</a>

<br />
<br />This plugin is developed by <a target="_blank" href="https://www.nsp-code.com">Nsp-Code</a>

== Installation ==

1. Install the plugin through the WordPress plugins interface or upload the package to `/wp-content/plugins/wp-hide-security-enhancer` directory.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the WP Hide menu screen to configure the plugin.

== Frequently Asked Questions ==

Feel free to contact us at contact@wp-hide.com for fast support.

= Will the Plugin Alter Anything on My Server? =

Absolutely Nothing!
The plugin does not modify any files or directories on your server. All processes are handled virtually using URL rewrite techniques and WordPress filters to seamlessly integrate its functionalities and features. Your server remains completely untouched!

= Is This Plugin Suitable for Someone Without PHP Knowledge? =

Absolutely! No PHP knowledge is required. The plugin’s features and functionalities are applied automatically and can be easily managed through a user-friendly, descriptive admin interface.

= Is There a Demo Available to Explore? =

Yes, you can check out a live demo at <a target="_blank" href="https://demo.wp-hide.com/">https://demo.wp-hide.com/</a>. Additionally, you can visit our official website, <a target="_blank" href="https://wp-hide.com/">WP Hide and Security Enhancer</a>, for more information.

= Can I Use the Plugin on an Nginx Server? =

The free version of the plugin cannot generate the required Nginx rewrite rules for full-stack Nginx servers. However, it is fully compatible with Apache, LiteSpeed, IIS, and Nginx when used as a reverse proxy.
For Nginx conside  <a target="_blank" href="https://wp-hide.com">WP Hide PRO</a>.

= Can I Still Update WordPress, Plugins, and Themes? =

Yes, everything works as usual. The plugin does not interfere with your ability to update WordPress core, plugins, or themes. You can run updates at any time without any issues.

= Does the Plugin Affect My Website’s SEO? =

Not at all. The plugin only modifies asset links (such as CSS, JavaScript, and media files) and does not alter actual content URLs. There is no negative impact on SEO whatsoever.

= Is the Plugin Compatible with My Site’s Cache? =

Yes, the plugin works seamlessly with any caching plugin installed on your site.

= What Are HTTP Security Headers? =

HTTP Security Headers are a powerful tool to enhance your website’s security. The plugin simplifies the process of adding these headers through an intuitive graphical interface—no coding or manual file editing required.

= Which Servers Are Compatible with This Plugin? =

The free version of WP-Hide works seamlessly with Apache and IIS servers, as well as any setup that relies on .htaccess for configuration. For other server types, consider upgrading to the <a target="_blank" href="https://wp-hide.com">WP Hide PRO</a> version.

= How Can I Use the Plugin with My OpenLiteSpeed Server? =

If you’re running OpenLiteSpeed, here are a few steps to ensure compatibility:

* Verify that your server processes the .htaccess file, where rewrite rules are stored. For more details, refer to this <a target="_blank" href="https://www.litespeedtech.com/support/forum/threads/htaccess-is-ignored.15500/">support thread</a>.

* If you’re using the LiteSpeed Cache plugin, disable the CSS/JS Minify option in the Optimization Settings.

* If your server requires rewrite rules to be placed in a different file (e.g., a config file), consider upgrading to the <a target="_blank" href="https://wp-hide.com/wp-hide-pro-now-available/">WP Hide PRO</a> version, which includes a Setup page for generating the necessary rewrite code.

= How Do I Use the Plugin on a Bitnami Setup? =
By default, Bitnami LAMP setups do not process .htaccess files, so rewrites won’t work. To resolve this:

* Update the main config file located at /opt/bitnami/apps/APPNAME/conf/httpd-app.conf.

<pre><code>AllowOverride None</code></pre>
to
<pre><code>AllowOverride All</code></pre>  

* Restart the Apache service through SSH:
<pre><code>sudo /opt/bitnami/ctlscript.sh restart</code></pre>

For more details, refer to the <a href="https://docs.bitnami.com/general/apps/redmine/administration/use-htaccess/">Bitnami documentation</a>.

You can still keep the configuration as it is using the <a target="_blank" href="https://wp-hide.com">WP Hide PRO</a>, more details at <a href="https://wp-hide.com/documentation/setup-the-plugin-on-bitnami-wordpress-lamp-stack/">Setup the plugin on Bitnami WordPress LAMP stack</a>

= .htaccess File Writing Error – Unable to Write Custom Rules =
If you see the error “Unable to write custom rules to your .htaccess. Is this file writable?”, follow these steps:

* Ensure the .htaccess file is writable (typically rw-rw-r– / 0664 permissions) and owned by the same group as PHP.

* Other plugins or themes might interfere with rewrite rules. Temporarily disable them to identify the culprit.

* Deactivate and reactivate the WP Hide plugin—this has resolved the issue for some users.

* Create a backup of your .htaccess file, delete it, and go to Settings > Permalinks to regenerate it. Then, update WP Hide settings to rewrite the .htaccess file.

= Something Went Wrong – How Can I Recover My Site? =
Don’t worry—your site will not be harmed. Here’s how to troubleshoot:

* Log in to your admin dashboard and adjust plugin settings to identify the issue. Report the problem on our forum or contact us for assistance.

* If you can’t access the admin area, use the Recovery Link sent to your email to reset the login to default.

* If the Recovery Link doesn’t work or you can’t find it, delete the plugin from the wp-content/plugins directory and remove any lines in your .htaccess file between:
 BEGIN WP Hide & Security Enhancer
..
 END WP Hide & Security Enhancer 
 
 * Your site should now function as before. If issues persist, contact us at contact@wp-hide.com, and we’ll help you resolve it quickly.

= How Do I Use the Recovery Link? =
The Recovery Link resets all plugin options and restores your site to its default state. To use it:

* Enter the link into your browser’s URL bar.

* After the process completes, you’ll see a message: “The plugin options have been reset successfully.”

* If the message doesn’t appear, clear your site’s cache (usually located in /wp-content/cache/) and reload the Recovery Link.

= What to do if I can’t find a functionality that I’m looking for? =

Please get in touch with us and we’ll do our best to include it inthe next version.

== Screenshots ==

1. Admin Interface.
2. Sample front html code.

== Changelog == 

= 2.6.3 = 
* Fix: Custom login page logo ( remove negate empty on $custom_logo_image_id )

= 2.6.2 =
* New feature - Enable the 2FA for specific roles. Choose the roles for which the 2FA feature will trigger.
* Fill the missing options with the component default value.
* Avoid re-loading the settings if they exists in the WPH class.
* Update the GoogleAuthenticator.php
* Fix: Creation of dynamic property WPH::$_2fa is deprecated

= 2.6.1 =
* Comparison fix within WPForms Lite compatibility file.
* Fix: Missing 2Fa icons and js asset.

= 2.6 =
* New feature - 2FA - Two-Factor Authentication
* 2FA - Email
* 2FA - Auth APP
* 2FA - Recovery Codes
* Minor bug fixes
* Readme content text description updates
* Readme video demo update

= 2.5.8 =
* Separate all module components settings from the components settings description, to ensure the __() and _e() translation functions trigger after the init action. 
* Relocate the filter wp-hide/ignore_ob_start_callback higher in the ob_start_callback method, to allow by-passing the buffer processing. 
* Ignore the comments removal when the content type is application/json
* Use module separate method get_interface_menu_position for setting up the position hierarchy in the menus. 
* Fix: Ensure the security widget is loading the correct data for any users that has access to the dashboard. 
* Fix: avoid calling the get_plugins() as it triggers a rare issue on ceertain servers, when loading over HTTP protocol. 


= 2.5.6 =
* Add separate components description texts, for the translations to be available, after init action ( changed in the WordPress 6.5 )
* Update the Components classes ( rewrites ) to use separate description. 
* Updated the translation PO file.
* Fix: Check if $all_themes has the key, before retrieve the value in is_child_theme()

= 2.5.4 =
* Fix: Remove the protocol from URLs in the theme's style file module, to prevent issues when the site's protocol is inconsistent (e.g., using both HTTP and HTTPS).

= 2.5.2 =
* Fix: Sanitize the replacement_path in the router.
* WordPress 6.7.1 compatibility check and tag update. 

= 2.5.1 =
* Update the compatibility file for WPForms Lite and WPForms PRO

= 2.5 =
* Include a version number for all script and style assets to ensure the correct data loads when cached.
* Load the user interaction JavaScript on the login page as well, to ensure functionality on that page.
* Add submenu items to the main menu for improved accessibility.
* Check if LSWCP_TAG_PREFIX is defined when using LiteSpeed Cache before clearing the caches.
* Clear the Elementor caches, if active, when options change.
* Fix: Use rtrim instead of trim to strip the trailing \/ in the URL.
* Update and check compatibility with WordPress 6.7.

= 2.4.7 =
* Fix: Check if data block is serialized, before applying the revert replacements.
* Compatibility update for WP Job Manager
* WP Rocket: check if contant WP_ROCKET_WHITE_LABEL_FOOTPRINT is already defined before define. 
* Compatibility file for Dokan

= 2.4.4 =
* Prevent redirection to the login page when using GravityForms and use the query gf_page.
* On option_block_revert check if the variable is serialized before processing the reverting for the block. 
* WordPress 6.6.1 compatibility check and tag update. 

= 2.4.2 =
* Undefined function fix.

= 2.4.1 =
* Add self_admin_url filter for components like WordPress update routine.
* Check if the correct page before add the admin_enqueue_scripts action, for the custom logo interface.
* WordPress 6.6 compatibility check and tag update. 

= 2.4 =
* New feature: Block common Theme / Plugin detectors and scanners  https://wp-hide.com/documentation/block-theme-plugin-detectors/
* Fix: Return true when checking the post meta update if not changed. 

= 2.3.9 =
* New feature: Customize the default login page Logo
* Improve the default plugin set-up with more options and include the Headers sample settings.
* Slight visual improvements. 
* Inform to restart the LiteSpeed on certain servers (e.g. Hostinger ).
* Use preg_replace to sanitize the input for security improvements. 
* Compatibility file for WPForms Lite
* WordPress 6.5.3 compatibility check and tag update

= 2.3.8.2 =
* Disable the filter wph/components/rewrite-default/superglobal_variables_replacements and the ignore for _wp_http_referer as produce issues with specific plugins

= 2.3.8.1 =
* Fix Too few arguments to function WPH_module_rewrite_default::_array_replacements_recursivelly()

= 2.3.8 =
* Ignore the _wp_http_referer when reversing urls, to ensure when compared with existing is not failing. 
* Fix for WPForms Lite plugin when using a custom admin URL.

= 2.3.7 =
* Preserve the field types when replacing superglobals data. 

= 2.3.6 =
* Ensure the is_user_logged_in function is available before calling it. 

= 2.3.5 =
* Update the plugin headers
* New module - Disable Admin Url redirect to Login page
* Remove deprecated admin-new-_wp-login_php file
* WordPress 6.5 compatibility check and tag update

= 2.3.1 =
* New filter wp-hide/interface/process/minimum_slug_length for customizing the minimum length of the admin and login slug   https://wp-hide.com/documentation/wp-hide-interface-process-minimum_slug_length/
* Oxygen builder compatibility file updates.
* Add end slash for admin custom slug, into the rewrite, to ensure exact match.
* Add the filter wph/components/force_run_on_admin to more options for allowing to run into the admin https://wp-hide.com/documentation/wph-components-force_run_on_admin/
* WordPress 6.4.2 compatibility check and tag update

= 2.2.9 =
* Allow custom login URL without requiring a PHP extension. 
* Require at least 5 chars for the customization of login and admin URL to avoid words conflicts. 
* Scan XML RPC update, check if the service is disabled to avoid returning false positive. 
* Compatibility with Redirection plugin; show the default redirect URLs within the interfaces. 
* Add FLYING_PRESS_VERSION and LiteSpeed Purge to the internal site_cache_clear()
* WordPress 6.4.1 compatibility tag update

= 2.2.4 =
* Fix Undefined array key "file" warning.
* Ignore wp-admin, wp-content, wp-includes as custom slugs for any of the options, to avoid code conflicts. 

= 2.2.1 =
* Reverse the replacements for $_FILES super global variable too. 
* Adjust the login form width, when using the Google Captcha or Cloudflare Turnstile Captcha
* Use init action, to send the customized login e-mail, to avoid sending multiple time on certain servers environment.
* Use debug_backtrace to avoid looping, in conjunction with certain plugins, for login_url filter.
* Add a filter for site_url to apply the login customisation when the scheme is 'login' or 'login_post'
* Fix reset options form and submit buttons.
* Fix various texts and instances.
* Tested for WordPress 6.4

= 2.1.8 =
* New feature Captcha for Login, Register, Password Forget pages etc. 
* New Captcha - Google Captcha V2
* New Captcha - Google Captcha V3
* Tested for PHP 8.2.4

= 2.1.5 =
* Use transient for domain_get_ip to avoid execution delays with certain hosts.
* Separate options for Copy / Cut / Paste into the User Interactions interface for better control over the options
* Few Typos fix
* Compatibility updates for TranslatePress - Multilingual

= 2.1.1 =
* New filter wph/components/components_run/ignore_component which allows selective disabling for specific components to apply on the front site
https://wp-hide.com/documentation/wph-components-components_run-ignore_component/
* Set minimum required WordPress version as 4.0
* Set minimum required PHP version as 5.4

= 2.1 =
* Relocate the plugins_themes_compatibility prior module components initialization.
* Avoid looping with certain 3rd codes by caching the home url.
* HTML Comments removal regex updates. 
* Compatibility update for qTranslate-XT plugin, when using the option redirect to language and customizing the default login url through WP Hide

= 2.0.6 =
* Use regex patterns for Scan - Replacements, for better accuracy in the identification of the fingerprints proposed to be changed. 
* Deprecated Expect-CT.
* Remove the Expect-CT from the recommended headers. 

= 2.0.4 =
* Suppress the option to block the Developer Tools / Inspect when page/post preview.  
* Add to cache clear for Autoptimize, Perfmatters, Breeze, Site Ground Cache,  when flushing the caches.
* Site Ground Cachepress plugin compatibility update
* WordPress compatibility check for 6.2
* WordPress compatibility tag update.

= 1.9.9 =
* Decrease the Scan progress background AJAX update, to avoid time-outs on slow connections.
* Improvement: When using the Disable Developer Tools option, check if iPhone device and disable, through JavaScript instead PHP, to avoid caching.
* New Screenshot for better pre-visualization of the actual interface. 
* Fix: Scan Admin component, Fix button URL.

= 1.9.7 =
* New Security Headers component - Referrer-Policy.
* Check the post meta and option value if serialized ( double serialization ), before reversing the URLs.
* Code improvements.
* Updated translation PO file.

= 1.9.5 =
* Replaced the deprecated Feature-Policy with Permissions-Policy security header.
* Fix: Scan disable redirects when testing firewall, to ensure correct results
* Fix count() error for not countable variable.
* PO language file updates

= 1.9.3 =
* Add additional description for potentially dangerous files found within WordPress root.
* Typo fix for "Dangerous Files"
* Fix: Tipsy JavaScript error
* Fix: Undefined variable $site_score within render_overview()
* Fix: Divided by zero when calculating the overall scan progress
* Fix: Wrong remote_html variable

= 1.9.1 =
* New feature - Security Scan.
* Security Scan dashboard widget
* Inform on possible LiteSpeed service restart if use such system.
* Check if HTTP_USER_AGENT environment variable exists before making comparison. 
* Fix Oxigen compatibility when using the HTML Minify.
* Fix: Cache Enable static call.

= 1.8.8 =
* New component  Headers -> Remove Server Header.
* Prevent output of "document.addEventListener" unless an user-interaction option is active.
* Add X-XSS-Protection into the headers list, to avoid reporting as not used as security header.
* Code Improvements and clean-up.
* PO language file update.

= 1.8.6 =
* Ignore the "Disable Developer Tools" on iPhone
* WordPress 6.1 compatibility tag
* Fix: Security headers progress comparison step. 
* Slight css changes 

= 1.8.5 =
* Improved Disable Developer Tools feature, by returning an empty page.
* W3 Total Cache - implements support for Push CDN and custom folders
* Compatibility fix with JCH Optimize.
* Ignore invalid SSL certificate when testing rewrites, to allow local instances.
* Fix: static to public functions for a2-optimized compatibility class.
* Fix: use preg_match to ensure the HTML data is valid and avoid faulty code with multiple head tags.
* Slight text changes within some options, for better explanations.

= 1.8.3 =
* New options interface - User Interactions: Disable Mouse right click, Disable Text Selection, Disable Copy / Paste, Disable Print, Disable Print Screen, Disable Developer Tools, Disable View Source, Disable Drag / Drop
* Better accessibility for additional details regarding each of the options.
* Improved progress score calculation for Headers.
* A2 Optimized WP - compatibility fix.
* WordPress 6.0.2 tag compatibility update
* Fix CDN option external help page URL.

= 1.8.1 =
* Improved server environment rewrite test checking routines.
* Separate rewrite tests for static files and PHP files. This avoids reporting issues for servers not supporting rewrites for php-files.

= 1.8 =
* Add a new button to reset the current page options.
* Use regex to sanitize the URL arguments
* Relocated the Reset All Settings button to the bottom of the interface.
* Compatibility for Super Page Cache for Cloudflare
* Slight layout improvements and changes.
* WordPress 6.0.1 compatibilit tag

= 1.7.9.2 =
* Change the advanced_notice class within the interfaces to avoid issues caused by 3rd theme.
* Do not remove comments when json request
* WordPress 6.0 compatibilit tag

= 1.7.8.1 =
* When checking and calculating the the Headers protection score, ignore the SSL verification for the domain, to allow usage of invalid certificates.
* Check if set headers are actually passed-through on the front side, as some servers may block that. 
* Set WP_ROCKET_WHITE_LABEL_FOOTPRINT to remove the footer comment for WP Rocket, when active

= 1.7.8 =
* New Security Functionality - Headers. HTTP Response Headers are a powerful tool to Harden Your Website Security.
* Security Headers - Cross-Origin-Embedder-Policy (COEP), Cross-Origin-Opener-Policy (COOP), Cross-Origin-Resource-Policy (CORP), X-Content-Type-Options, X-Download-Options, X-Frame-Options (XFO), X-Permitted-Cross-Domain-Policies, X-XSS-Protection.
* Security Headers - Protection Level graph
* Security Headers - Sample Setup
* Security Headers - Recovery functionality
* Styles and layout improvements
* Code clean-up
* Fix: Append URL arguments to login URL, if exists

= 1.7.6 =
* Run on revision posts, to match URLs and revert to default WordPress  ( e.g. when using Gutenberg editor )
* Require a .php for the customization of the default wp-login.php to avoid cookie issues on password change area. 
* WooCommerce 5.9 compatibility check and tag.

= 1.7.3 =
* Fix: If Emulate CMS active, ensure the buffer is an HTML content

= 1.7.3 =
* New functionality, block wp-json for everyone or non-logged-in users. 
* Fix Emulate CMS documentation url.
* Removed Twitter share.

= 1.7.1 =
* New plugin feature: Emulate CMS
* Update PO language file
* Skip comment removal when admin dashboard.
* Fix: Ignore comment removal when Gutenberg JSON call for blocks, to avoid formatting issues.

= 1.6.4 =
* Ensure compatibility with PHP 8.0
* Update PO language file
* Update documentation URLs within the plugin interfaces, with the  non-www domain wp-hide.com

= 1.6.3.9 =
* Include the "Clean the REST API response" within Sample Setup. 

= 1.6.3.8 =
* New option for JSON REST module - "Clean the REST API response"
* Relocated Feed tab to Rewrite module 

= 1.6.3.7 =
* Output the help title only if there's an help section available through the module settings
* Fix undefined $found_issues
* WordPress 5.8 compatibility tag

= 1.6.3.6 =
* Add dashboard and cpanel to system reserved to avoid permalinks conflicts
* LiteSpeed Cache compatibility update
* WP-Optimize compatibility update 

= 1.6.3.4 =
* Update compatibility file for TranslatePress - Multilingual

= 1.6.3.3 =
* Fix attachment_url_to_postid
* Fix undefined get_metadata_raw

 

See full list of changelogs at https://wp-hide.com/plugin-changelogs/

== Upgrade Notice ==

Always keep plugin up to date.

== Localization ==
Please help and translate this plugin to your language at <a href="https://translate.wordpress.org/projects/wp-plugins/wp-hide-security-enhancer">https://translate.wordpress.org/projects/wp-plugins/wp-hide-security-enhancer</a>

You are kindly asked to promote this plugin if it comes up to your expectations via an article on your site or any other place. If you liked this code/WP-Hide or if it helped with your project, why not leave a 5 star review on this board.

