<?php

require_once (dirname(__FILE__) . '/autoload.php');

global $mosConfig_mailfrom;
$mosConfig_mailfrom = A25_DI::PlatformConfig()->sendFromEmail;

global $mosConfig_MetaAuthor;
$mosConfig_MetaAuthor = '1';
global $mosConfig_MetaTitle;
$mosConfig_MetaTitle = '1';
global $mosConfig_admin_expired;
$mosConfig_admin_expired = '1';
global $mosConfig_allowUserRegistration;
$mosConfig_allowUserRegistration = '0';
global $mosConfig_back_button;
$mosConfig_back_button = '0';
global $mosConfig_cachetime;
$mosConfig_cachetime = '900';
global $mosConfig_caching;
$mosConfig_caching = '0';
global $mosConfig_dbprefix;
$mosConfig_dbprefix = 'jos_';


// Database settings
global $mosConfig_db;
$mosConfig_db = ServerConfig::dbName;
global $mosConfig_host;
$mosConfig_host = ServerConfig::dbHost;
global $mosConfig_user;
$mosConfig_user = ServerConfig::dbUser;
global $mosConfig_password;
$mosConfig_password = ServerConfig::dbPassword;

/**
 * Are debug messages on?
 * @global boolean $mosConfig_debug
 * @name $mosConfig_debug
 */
global $mosConfig_debug;
$mosConfig_debug = false;
/**
 * Are debug email messages on?
 * @global boolean $mosConfig_debug_email
 * @name $mosConfig_debug_email
 */
global $mosConfig_debug_email;
$mosConfig_debug_email = false;
global $mosConfig_debug_user_id;
$mosConfig_debug_user_id = '64';
global $mosConfig_dirperms;
$mosConfig_dirperms = '';
global $mosConfig_editor;
$mosConfig_editor = 'jce';
global $mosConfig_enable_log_items;
$mosConfig_enable_log_items = '0';
global $mosConfig_enable_log_searches;
$mosConfig_enable_log_searches = '0';
global $mosConfig_enable_stats;
$mosConfig_enable_stats = '0';
global $mosConfig_error_message;
$mosConfig_error_message = 'This site is temporarily unavailable.<br /> Please notify the System Administrator';
global $mosConfig_error_reporting;
$mosConfig_error_reporting = '-1';
global $mosConfig_favicon;
$mosConfig_favicon = 'favicon.ico';
global $mosConfig_fileperms;
$mosConfig_fileperms = '';
global $mosConfig_frontend_login;
$mosConfig_frontend_login = '0';
global $mosConfig_frontend_userparams;
$mosConfig_frontend_userparams = '0';
global $mosConfig_gzip;
$mosConfig_gzip = '0';
global $mosConfig_helpurl;
$mosConfig_helpurl = 'http://help.joomla.org';
global $mosConfig_hideAuthor;
$mosConfig_hideAuthor = '1';
global $mosConfig_hideCreateDate;
$mosConfig_hideCreateDate = '1';
global $mosConfig_hideEmail;
$mosConfig_hideEmail = '1';
global $mosConfig_hideModifyDate;
$mosConfig_hideModifyDate = '1';
global $mosConfig_hidePdf;
$mosConfig_hidePdf = '1';
global $mosConfig_hidePrint;
$mosConfig_hidePrint = '1';
global $mosConfig_hits;
$mosConfig_hits = '0';
global $mosConfig_icons;
$mosConfig_icons = '0';
global $mosConfig_item_navigation;
$mosConfig_item_navigation = '0';
global $mosConfig_lang;
$mosConfig_lang = 'english';
global $mosConfig_lifetime;
$mosConfig_lifetime = '10800';
global $mosConfig_link_titles;
$mosConfig_link_titles = '0';
/**
 * The maximum number of rows to display.
 * @global integer $mosConfig_list_limit
 * @name $mosConfig_list_limit
 */
global $mosConfig_list_limit;
$mosConfig_list_limit = 25;
global $mosConfig_absolute_path;
$mosConfig_absolute_path = ServerConfig::webRoot;
global $mosConfig_cachepath;
$mosConfig_cachepath = $mosConfig_absolute_path . '/cache';
global $mosConfig_sitename;
$mosConfig_sitename = PlatformConfig::siteTitleHtml();
global $mosConfig_MetaDesc;
$mosConfig_MetaDesc = PlatformConfig::courseTitleHtml();
global $mosConfig_fromname;
$mosConfig_fromname = PlatformConfig::agency;
global $mosConfig_MetaKeys;
$mosConfig_MetaKeys = '';

//admin https redirect
global $port;
$port = $_SERVER["SERVER_PORT"];
global $host;
$host = $_SERVER["HTTP_HOST"];
global $uri;
$uri = $_SERVER["REQUEST_URI"];

global $mosConfig_live_site_ssl;
$mosConfig_live_site_ssl = ServerConfig::httpsUrlWithoutSlash();
global $mosConfig_live_site_nossl;
$mosConfig_live_site_nossl = ServerConfig::httpUrlWithoutSlash();
global $mosConfig_live_site;
$mosConfig_live_site = ServerConfig::currentUrl();

global $mosConfig_locale;
$mosConfig_locale = 'en_GB';
global $mosConfig_mailer;
$mosConfig_mailer = 'mail';
global $mosConfig_ml_support;
$mosConfig_ml_support = '0';
global $mosConfig_multipage_toc;
$mosConfig_multipage_toc = '1';
global $mosConfig_offline;
$mosConfig_offline = '0';
global $mosConfig_offline_message;
$mosConfig_offline_message = 'This site is down for maintenance.<br /> Please check back again soon.';
global $mosConfig_offset;
$mosConfig_offset = '6';
global $mosConfig_offset_user;
$mosConfig_offset_user = '0';
global $mosConfig_pagetitles;
$mosConfig_pagetitles = '1';
global $mosConfig_readmore;
$mosConfig_readmore = '1';
global $mosConfig_secret;
$mosConfig_secret = 'AAGscujHILICgkm4';
global $mosConfig_sef;
$mosConfig_sef = '1';
global $mosConfig_sendmail;
$mosConfig_sendmail = '/usr/sbin/sendmail';
global $mosConfig_session_life_admin;
$mosConfig_session_life_admin = '10800';
global $mosConfig_session_type;
$mosConfig_session_type = '0';
global $mosConfig_shownoauth;
$mosConfig_shownoauth = '0';
global $mosConfig_smtpauth;
$mosConfig_smtpauth = '0';
global $mosConfig_smtphost;
$mosConfig_smtphost = 'localhost';
global $mosConfig_smtppass;
$mosConfig_smtppass = '';
global $mosConfig_smtpuser;
$mosConfig_smtpuser = '';
global $mosConfig_uniquemail;
$mosConfig_uniquemail = '0';
global $mosConfig_useractivation;
$mosConfig_useractivation = '1';
global $mosConfig_vote;
$mosConfig_vote = '0';
setlocale (LC_TIME, $mosConfig_locale);

?>
