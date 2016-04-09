<?php
/**************************************************************************************************************
 *
 *   CF Image Hosting Script
 *   ---------------------------------
 *
 *   Author:    codefuture.co.uk
 *   Version:   1.6.5
 *
 *   You can download the latest version from: http://codefuture.co.uk/projects/imagehost/
 *
 *   Copyright (c) 2010-2013 CodeFuture.co.uk
 *   This file is part of the CF Image Hosting Script.
 *
 *   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 *   EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *   COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 *   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF
 *   OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *
 *   You may not modify and/or remove any copyright notices or labels on the software on each
 *   page (unless full license is purchase) and in the header of each script source file.
 *
 *   You should have received a full copy of the LICENSE AGREEMENT along with
 *   Codefuture Image Hosting Script. If not, see http://codefuture.co.uk/projects/imagehost/license/.
 *
 *
 *   ABOUT THIS PAGE -----
 *   Used For:     English translation
 *   Last edited:  19/12/12
 *
 *************************************************************************************************************
 *
 *   If you have translated this file into another
 *   language or have corrected typos, we would
 *   appreciate it if you would send us the
 *   translated file.
 *
 *************************************************************************************************************/

	$lang = array();
	$lang["site_charset"] = 'UTF-8';
	$lang["site_lang_code"] = 'en';

/*****************************************************************************
 *
 * Admin Language
 *
 *****************************************************************************/

/*
 * Admin Sitewide
 */
	$lang["admin_footer_powered_by"]	= 'Powered By';
	$lang["admin_footer_version"]		= 'Version';

/*
 * config
 */
	$lang["admin_session_error"]	= 'Cannot start a new PHP session. Please contact server administrator or webmaster!';
	$lang["error_500"]				= '500 Internal Server Error - Internal server error with the page you&apos;ve requested.';
	$lang["error_404"]				= '404 Not Found - The page or file you requested does not exist.';
	$lang["error_403"]				= '403 Forbidden - You do not have permission to access this area.';
	$lang["error_401"]				= '401 Unauthorized - You do not have permission to access this area..';
	$lang["error_400"]				= '400 Bad Request - Your browser sent a request that this server could not understand..';


/*
 * Login Page (log)
 */
 	$lang["admin_log_title"]	= 'Login';
 	$lang["admin_log_out_suc"]	= 'You have been successfully logged out.';
	$lang["admin_log_err"]		= 'You entered the wrong password or user name!';
	// forgot your password - send link
	$lang["admin_log_forgot_password_email_subject"]	= 'Password Reset Link';//SET_TITLE - Password Reset Link  (SET_TITLE is auto set)
	//{user_name} replace with the admin username
	//{reset_url} replace with a link to reset the admin password
	$lang["admin_log_forgot_password_email_body"]		= '
	<div style="width:700px; margin:0 auto;">
		Hi, {user_name}<br/>
		Can&apos;t remember your password, It happens to the best of us. <br/><br/>
		Please click on the link below or copy and paste the URL into your browser: <br/>
		{reset_url}<br/><br/>

		This will reset your password. You can then login and change it to something you&apos;ll remember. <br/>
		This is an automated response, please do not reply!
	</div>';
	$lang["admin_log_forgot_password_suc"]			= 'You have been sent a Email...';
	$lang["admin_log_forgot_password_email_err"]	= 'e-mail address is not not valid!';
	$lang["admin_log_forgot_password_err"]			= 'Error resetting your password......';
	$lang["admin_log_forgot_password"]				= 'Forgot Password';
	$lang["admin_log_username"]						= 'Username';
	$lang["admin_log_password"]						= 'Password';
	$lang["admin_log_remember_me"]					= 'Remember Me';
	$lang["admin_log_button"]						= 'Login';
	$lang["admin_log_your_email"]					= 'Your Email';
	$lang["admin_log_button_submit"]				= 'Submit';
 	$lang["admin_log_forgot_password_title"]		= 'Reset Password';
	$lang["admin_log_forgot_password_update"]		= 'Saved New Password';


/*
 * Admin Menu
 */
	$lang["admin_menu_visitsite"]	= 'Visit site';
 	$lang["admin_menu_logout"]		= 'Logout';
	$lang["admin_menu_image_list"]	= 'Images';
	$lang["admin_menu_settings"]	= 'Settings';
	$lang["admin_menu_banned"]		= 'Ban User';
	$lang["admin_menu_bulk"]		= 'Bulk Upload';
	$lang["admin_menu_home"]		= 'Dashboard';
	$lang["admin_menu_ads"]			= 'Ads';
// not in pro
	$lang["admin_menu_database"]	= 'Database';


/*
 * admin Dashboard/home page
 */
	$lang["admin_home_dashboard_loading"]		= 'If the loading of the dashboard is taking too long you may wish to switch to the dashboard basic view
													as it will load a lot faster but will only show you basic information. To switch to basic view open up
													config.php what can be found in the inc folder, then find "$ADMIN_DASHBOARD_FULL  = turn;" and
													change it to false.';
	$lang["admin_home_overview"]				= 'Overview';
	$lang["admin_home_total_images"]			= 'Total images';
	$lang["admin_home_private_images"]			= 'Private images';
	$lang["admin_home_filespace_used"]			= 'Total Filespace Used';
	$lang["admin_home_total_bandwidth"]			= 'Total Bandwidth';
 	$lang["admin_home_last_backup"]				= 'Last Backup On';
	$lang["admin_home_top_image"]				= 'Top Image';
	$lang["admin_home_by_bandwidth"]			= 'by Bandwidth';
	$lang["admin_home_id"]						= 'ID';
	$lang["admin_home_name"]					= 'Name';
	$lang["admin_home_bandwidth"]				= 'Bandwidth';
	$lang["admin_home_hotlink_views"]			= 'Hotlink Views';
	$lang["admin_home_by_hotlink_views"]		= 'by HotLink Views';
	$lang["admin_home_uploaded_date"]			= 'Upload date';
	$lang["admin_home_total_since_last_reset"]	= 'Total since reset';
	$lang["admin_home_last_reset_date"]			= 'Last reset on';
	$lang["admin_home_next_reset_date"] 		= 'Next reset on';
	// Reported images / some used on image list page to
	$lang["admin_home_reported_images"]		= 'Reported Images';
	$lang["admin_home_tooltip_image_name"]	= 'Image Description/Name';
	$lang["admin_home_image_name"]			= 'Image Description/Name';
	$lang["admin_home_noreported"]			= 'No reported images found';
	$lang["admin_home_report_remove_suc"]	= 'Successfully removed image from reported list.';
	$lang["admin_home_report_remove_err"]	= 'A problem occurred removed image from reported list.';
	$lang["admin_home_report_alt_remove"]	= 'Remove Image from report list';
	$lang["admin_home_report_alt_delete"]	= 'Delete Image';
	$lang["admin_home_report_delete"]		= 'Are you sure you want to remove Image ID "%s"? This cannot be undone!'; // %s = image id
	$lang["admin_home_report_alt_ban"]		= 'Ban user from uploading any images';


/*
 * admin ads
 */
	$lang["admin_ad_page_title"]	= 'Site Ads settings';
	$lang["admin_ad_page_des"]		= 'On this page you can add your ad code/html for your site. The header and the footer are site-wide ads and will be showen on all pages and the others will only be used on the named pages. please note that AdSense ads will be used if you have entered your AdSense code in settings.';
	$lang["admin_ad_title_header"]	= 'Header';
	$lang["admin_ad_title_index"]	= 'Index';
	$lang["admin_ad_title_thumb"]	= 'Thumbnail';
	$lang["admin_ad_title_gallery"]	= 'Gallery';
	$lang["admin_ad_title_footer"]	= 'Footer';
	$lang["admin_ad_label_header"]	= 'The Header ad will be seen on all pages';
	$lang["admin_ad_label_index"]	= 'The Index ad will only be seen on the index page next to the uploader';
	$lang["admin_ad_label_thumb"]	= 'The Thumbnail ad will only be showen on the thumbnail page next to the thumb.';
	$lang["admin_ad_label_gallery"]	= 'The Gallery ad will be seen on the gallery and search results page below the 1st row of images';
	$lang["admin_ad_label_footer"]	= 'The Footer ad will be seen on all pages';


/*
 * admin Database page (not used in pro)
 */
	$lang["admin_db_title_database_setting"]			= 'Database Tools';
	$lang["admin_db_menu_auto"]							= 'Auto Database';
	$lang["admin_db_menu_image"]						= 'Image Database Backup';
	$lang["admin_db_menu_bandwidth"]					= 'Bandwidth Database backup';
	$lang["admin_db_menu_rebuild"]						= 'Rebuild Image Database';
	// Auto Database Setting
	$lang["admin_db_auto_title"]						= 'Auto Database Settings';
	$lang["admin_db_auto_backup"]						= 'Auto Database Backup';
	$lang["admin_db_auto_every"]						= 'Auto Backup Every';
	$lang["admin_db_auto_error"]						= 'If a database error is found use the last backup database';
	$lang["admin_db_auto_rebuild"]						= 'Run the database Rebuild tool if a backup datebase is use ';
	$lang["admin_db_auto_every_6hours"]					= '6 hours';
	$lang["admin_db_auto_every_12hours"]				= '12 hours';
	$lang["admin_db_auto_every_day"]					= 'Once a day';
	$lang["admin_db_auto_every_week"]					= 'Once a week';
	// Image Database Setting
	$lang["admin_db_database_now"]						= 'Now'; // Image and Bandwidth Database Settings
	$lang["admin_db_database_image_title"]				= 'Image database backups';
	$lang["admin_db_database_image_replace"]			= 'Replace the current image database with this backup file (%1$s)?&#x0a;(please note this cannot be undone) ';// %1$s - file name
	$lang["admin_db_database_image_backup"]		 		= 'Backup the Image Database ';
	// Bandwidth Database Settings
	$lang["admin_db_database_bandwidth_title"]		 	= 'Bandwidth database backups ';
	$lang["admin_db_database_bandwidth_replace"]		= 'Replace the current bandwidth database with this backup file (%1$s)?&#x0a;(please note this cannot be undone) ';// %1$s - file name
	$lang["admin_db_database_bandwidth_backup"]			= 'Backup the bandwidth Database ';
	// Image & Bandwidth Database Settings
	$lang["admin_db_database_delete_backup"]			= 'Delete this backup file (%1$s)?&#x0a;(please note this cannot be undone) ';// %1$s - file name
	$lang["admin_db_database_delete_backup_tip"]		= 'Delete this backup file';
	$lang["admin_db_database_download_backup"]			= 'Download this backup ';
	$lang["admin_db_database_image_replace_tip"]		= 'Use this backup file ';
	$lang["admin_db_database_backup_table_date"]		= 'backup date ';
	$lang["admin_db_database_backup_table_date_tip"]	= 'The date the backup was made ';
	$lang["admin_db_database_backup_table_name"]		= 'Bakup file ';
	$lang["admin_db_database_backup_table_name_tip"]	= 'The name of the backup file ';
	// Rebuild Image Database
	$lang["admin_db_rebuild_title"]						= 'Rebuild your image database ';
	$lang["admin_db_rebuild_description"]				= 'The Database check tool will check that all images in the images upload folder are in the database any that are found
															not to be in the database will be added or any image that are in the database but not in the image upload folder will be
															removed from the database. You may need to run this more then once if you have thousands of images not in the database.';
	$lang["admin_db_image_description"]					= 'The Image check tool will check your image upload folder for any missing image, thumb or small thumbs.
															If any are found not to to have a full set (ie. a image, thumb & small thumb) thay will be romved(delete) from the server.
															It&apos;s best to run this check before running the database check.';
	$lang["admin_db_rebuild_check"]						= 'Run Database checks';
	$lang["admin_db_image_check"]						= 'Run Image checks';
	// backup.class.php
	$lang["admin_db_backup_not_found"]					= 'can&apos;t find backup file!';
	$lang["admin_db_backup_deleted"]					= 'The backup file was successfully delete!';
	$lang["admin_db_backup_delete_error"]				= 'Can not delete backup file!';


/*
 * Admin Settings Page
 */
 	$lang["admin_set_save_button"]					= 'Save Changes';
	$lang["admin_set_option_on"]					= 'On';
	$lang["admin_set_option_off"]					= 'Off';
	$lang["admin_set_option_yes"]					= 'Yes';
	$lang["admin_set_option_no"]					= 'No';
	// settings menu
 	$lang["admin_set_title_admin_setting"]			= 'Admin';
	$lang["admin_set_title_site_setting"]			= 'General';
	$lang["admin_set_title_gallery_setting"]		= 'Gallery';
	$lang["admin_set_title_hide_page"]				= 'Show/Hide';
	$lang["admin_set_title_auto_deleted"]			= 'Auto Deleted';
	$lang["admin_set_title_upload_setting"]			= 'Upload';
	$lang["admin_set_watermark_title"]				= 'Watermark';
	$lang["admin_set_title_url_shortener"]			= 'URL Shortener';
	$lang["admin_set_title_google_setting"]			= 'Google';
	$lang["admin_set_title_comment"]				= 'Comments'; // pro only
	// admin setting
	$lang["admin_set_old_password"]					= 'Old Password';
	$lang["admin_set_new_password"]					= 'New Password';
	$lang["admin_set_confirm_new_password"]			= 'Confirm Password';
	$lang["admin_set_admin_username"]				= 'Admin Username';
	$lang["admin_set_email_address"]				= 'Email address';
	// site setting
	$lang["admin_set_script_url"]					= 'Script URL';
	$lang["admin_set_site_title"]					= 'Site Title';
	$lang["admin_set_site_slogan"]					= 'Site Slogan';
	$lang["admin_set_footer_copyright"]				= 'Footer Copyright';
	$lang["admin_set_site_theme"]					= 'Site Theme';
	$lang["admin_set_mod_rewrite"]					= 'Mod Rewrite';
	$lang["admin_set_addthis"]						= 'Your AddThis pubid';
	$lang["admin_set_language"]						= 'Set Site Language';
	// Gallery Settings
	$lang["admin_set_images_gallery_rows"]		= 'Number of rows (4 images per row)';
	$lang["admin_set_images_gallery_rows_no"]	= 'Image Per row';
	$lang["admin_set_report_allow"]				= 'Allow report images';
	$lang["admin_set_report_Send_email"]		= 'Send email on image report';
	// Hide/show Pages
	$lang["admin_set_hide_gallery"]				= 'Gallery Page';
	$lang["admin_set_hide_contact"]				= 'Contact Page';
	$lang["admin_set_hide_tos"]					= 'Terms of Service Page';
	$lang["admin_set_hide_search"]				= 'Search bar';
	$lang["admin_set_hide_faq"]					= 'FAQ Page';
	$lang["admin_set_image_widgit"]				= 'Random Image Widgit';
	$lang["admin_set_hide_feed"]				= 'Rss Feed';
	$lang["admin_set_hide_sitemap"]				= 'Sitemap';
	// Auto Remove images (auto deleted)
	$lang["admin_set_des_auto_deleted"]			= 'Auto delete is a feature that will help you keep your site clean of old and unused images. This has the most use for sites which are general purpose image hosting site, but for those of you who are using this script to share images and photos with your friends and family this feature is best left off as you will be most likely actively administrating your image host.';
	$lang["admin_set_auto_deleted"]				= 'Auto Deleted(unviewed)';
	$lang["admin_set_auto_deleted_for"]			= 'Auto Deleted(unviewed for)';
	$lang["admin_set_auto_deleted_days"]		= 'Days';
	$lang["admin_set_run_auto_deleted"]			= 'Run Auto Deleted';
	$lang["admin_set_run_auto_deleted_day"]		= 'Day';
	$lang["admin_set_run_auto_deleted_week"]	= 'Week';
	$lang["admin_set_run_auto_deleted_Month"]	= 'Month';
	// Upload Settings
	$lang["admin_set_disable_upload"]			= 'Disable Upload';
	$lang["admin_set_max_upload_file_size"]		= 'Max Upload File Size';
	$lang["admin_set_image_max_bandwidth_des"]	= 'The Image Max Bandwidth is the amount bandwidth a image can use for off-site viewing (hotlinking), the amount entered are in megabytes (MB), If you do not wish to set a maximum bandwidth limit, enter 0 (zero) to allow unlimited bandwidth.';
	$lang["admin_set_image_max_bandwidth"]		= 'Image Max Bandwidth (MB)';
	$lang["admin_set_auto_reset_bandwidth"]		= 'Auto reset Bandwidth';
	$lang["admin_set_multiple_upload_max"]		= 'Multiple Upload Max';
	$lang["admin_set_allow_duplicate"]			= 'Stop duplicate images from being uploaded';
	$lang["admin_set_allow_image_resize"]		= 'Allow users to resize Images on upload';
	$lang["admin_set_private_image_upload"]		= 'Private Image Upload';
	// Watermark Settings
	$lang["admin_set_watermark_des"]			= 'The watermark can be placed on the bottom or the top left corner of the image and is only add to the image when viewed from any other site then yours.';
	$lang["admin_set_watermark_text"]			= 'Watermark Text';
	$lang["admin_set_watermark_image"]			= 'Watermark Image Address';
	$lang["admin_set_watermark_position"]		= 'Watermark Position';
	$lang["admin_set_watermark_center"]			= 'center';
	$lang["admin_set_watermark_left"]			= 'left';
	$lang["admin_set_watermark_right"]			= 'right';
	$lang["admin_set_watermark_top"]			= 'top';
	$lang["admin_set_watermark_bottom"]			= 'bottom';
	// URL Shortener Settings
	$lang["admin_set_url_shortener"]			= 'URL Shortener(b54.in)';
	$lang["admin_set_url_short_service"]		= 'URL Shortener Service';
	$lang["admin_set_url_short_api_url"]		= 'API URL <small>(for Yourl only)</small>';
	$lang["admin_set_url_short_api_username"]	= 'API username <small>(for all other then B54)</small>';
	$lang["admin_set_url_short_api_password"]	= 'API Password/Key <small>(for all other then B54)</small>';
	// Google Settings
	$lang["admin_set_google_setting_des"]		= 'Google analytics and adsent will only be added to the site if you enter your code below';
	$lang["admin_set_google_analytics_code"]	= 'Google Analytics code';
	$lang["admin_set_google_channal_code"]		= 'Adsense Custom channels ID';
	$lang["admin_set_google_adsense_code"]		= 'Google AdSense code';
	// Save Errors
	$lang["admin_set_err_password_wrong"]		= 'You entered the wrong password!';
	$lang["admin_set_err_password_both"]		= 'You need to enter both the old and new Passwords!';
	$lang["admin_set_err_username"]				= 'Username is a required field and can&apos;t be blank';
	$lang["admin_set_err_email_invalid"]		= 'The e-mail address that you provided is not valid.';
	$lang["admin_set_err_script_url"]			= 'Script URL is a required field and can&apos;t be blank';
	$lang["admin_set_suc_update"]				= 'Updated Settings!';
	$lang["admin_set_err_saveing_settings"]		= 'A problem occurred during saving of settings';


/*
 * Bulk upload page
 */
	$lang["admin_bulk_title"]			= 'Bulk Image Upload';
	$lang["admin_bulk_des"]				= 'With this tool you can upload images by ftp to the "%1$s" folder, then press the upload images button below to add them to the database.
											There is a maximum of %2$s images that can be uploaded at one time, this can be changed in the config.php file. You can upload
											more images by ftp to the bulk upload folder, but the script will only add %2$s images to the database at a time.';// %1$s = folder name; %2$s = number of images that can be uploaded 
	$lang["admin_bulk_list"]			= '1) Upload images to "%1$s"<br/>
											2) Press the Upload images button Below<br/>
											3) Wait unto the images are uploaded. It may take awhile depending upon the number of pictures and file sizes.<br/>
											4) Back to number 2 if you had more then %2$s images in your bulk upload folder';// %1$s = folder name; %2$s = number of images that can be uploaded 
											
	$lang["admin_bulk_form_button"]		= 'Uplaod Images';
	$lang["admin_bulk_no_image_err"]	= 'No images found in %s to upload';// %s = folder name 
	$lang["admin_bulk_list_show"]		= 'Show images below once uploaded';
	$lang["admin_bulk_upload_success"]	= 'Successfully uploaded %s images';// %s = number of image uploaded 

	
/*
 * Ban User Page
 */
	$lang["admin_ban_suc"]					= 'you have successfully banned the IP: %s from uploading any more images';
	$lang["admin_ban_err_save_db"]			= 'There was an error trying to save to the DB!';
	$lang["admin_ban_err_no_ip"]			= 'You need to enter a IP address to ban!';
	$lang["admin_ban_suc_unbanned"]			= 'You have successfully unbanned the IP: %s';
	$lang["admin_ban_alt_unban"]			= 'Remove Ban';
	$lang["admin_ban_form_title"]			= 'Ban a IP from uploading';
	$lang["admin_ban_form_ip"]				= 'IP';
	$lang["admin_ban_form_reason"]			= 'Reason';
	$lang["admin_ban_form_button"]			= 'Ban IP';
	$lang["admin_ban_list_tt_date_banned"]	= 'Date Added to banned list';
	$lang["admin_ban_list_date_banned"]		= 'Date Banned';
	$lang["admin_ban_list_tt_ip"]			= 'Banned IP address';
	$lang["admin_ban_list_ip"]				= 'IP';
	$lang["admin_ban_list_tt_reason"]		= 'The reason for the ban';
	$lang["admin_ban_list_reason"]			= 'Reason';


/*
 * Image List Page (ilp)
 */
	$lang["admin_ilp_thumb_page_link"]				= 'Link To Thumb Page';
	$lang["admin_ilp_edit_alt"]						= 'Edit image public/private & description/name';
	$lang["admin_ilp_report_alt_delete"]			= 'Delete Image';
	$lang["admin_ilp_report_delete"]				= 'Are you sure you want to remove Image ID "%s"? This cannot be undone!'; // %s = image id
	$lang["admin_ilp_report_alt_ban"]				= 'Ban user from uploading any images';
	$lang["admin_ilp_number_to_list"]				= 'items in list';
	$lang["admin_ilp_number_to_list_all"]			= 'All';
	$lang["admin_ilp_order_list"]					= 'Order By';
	$lang["admin_ilp_order_list_date_added"]		= 'Date Added';
	$lang["admin_ilp_order_list_last_viewed"]		= 'Last Viewed';
	$lang["admin_ilp_order_list_hotlink_views"]		= 'Hotlink Views';
	$lang["admin_ilp_order_list_bandwidth_used"]	= 'Bandwidth Used';
	$lang["admin_ilp_order_list_gallery_clicked"]	= 'Gallery Clicked';
	$lang["admin_ilp_order_list_private"]			= 'Private';
	$lang["admin_ilp_imglist_tt_image_added"]		= 'Date Image was added';
	$lang["admin_ilp_imglist_image_added"]			= 'Date Added';
	$lang["admin_ilp_imglist_tt_image_name"]		= 'Image Description/Name';
	$lang["admin_ilp_imglist_image_name"]			= 'Image Description/Name';
	$lang["admin_ilp_imglist_tt_last_viewed"]		= 'Number of days of inactivity';
	$lang["admin_ilp_imglist_last_viewed"]			= 'Last<br/>Viewed';
	$lang["admin_ilp_imglist_tt_gallery_clicks"]	= 'the number of times a image in the gallery has been clicked on';
	$lang["admin_ilp_imglist_gallery_clicks"]		= 'Gallery<br/>Clicks';
 	$lang["admin_ilp_imglist_tt_hotlink_views"]		= 'Number of times this image has benn viewed externally, (ie not from this site)';
	$lang["admin_ilp_imglist_hotlink_views"]		= 'Hotlink Views';
	$lang["admin_ilp_imglist_tt_bandwidth_used"]	= 'Hot linking bandwidth used';
	$lang["admin_ilp_imglist_bandwidth_used"]		= 'Bandwidth<br/>Used';
	$lang["admin_ilp_imglist_tt_private"]			= 'Is this private (not shown in the gallery)';
	$lang["admin_ilp_imglist_private"]				= 'Private';
	$lang["admin_ilp_ipsearch_alt"]					= 'find all image uploaded from this &apos;%s&apos; user/ip';
	$lang["admin_ilp_st_reset"]						= 'From Last Reset';
	$lang["admin_ilp_st_all"]						= 'For All Time';

/*
 * Image edit Page (iep)
 */
	$lang["admin_iep_suc"]				= 'You have successfully updated the image.';
	$lang["admin_iep_title"]			= 'Edit Image';
	$lang["admin_iep_des_title"]		= 'Description';
	$lang["admin_iep_pp_title"]			= 'Image Public/Private';
	$lang["admin_iep_private"]			= 'Private';
	$lang["admin_iep_public"]			= 'Public';
	$lang["admin_iep_button"]			= 'Update';
	$lang["admin_iep_page_views"]		= 'Page Views';
// pro only
	$lang["admin_comment_edit"]			= 'Edit Comment';
// 1.6
	$lang["admin_iep_img_info"]			= 'Image Info';
	$lang["admin_iep_uploaded"]			= 'Uploaded On';
	$lang["admin_iep_time"]				= 'Time Uploaded';
	$lang["admin_iep_format"]			= 'Format';
	$lang["admin_iep_ip_uploaded"]		= 'IP Uploaded From';
	$lang["admin_iep_ip_find_uploaded"]	= 'Find all images from this user';
	$lang["admin_iep_img_size"]			= 'Image size';
	$lang["admin_iep_thumb_size"]		= 'Thumb size';
	$lang["admin_iep_small_thumb_size"]	= 'Small thumb size';
	$lang["admin_iep_short_url"]		= 'Short URL';
	$lang["admin_iep_last_viewd"	]	= 'Last viewd on';
	$lang["admin_iep_bandwidth_views"]	= 'Bandwidth/Views';
	$lang["admin_iep_since_uploaded"]	= 'Since Uploaded';
	$lang["admin_iep_img_views"]		= 'Image Views';
	$lang["admin_iep_thumb_views"]		= 'Thumb Views';
	$lang["admin_iep_small_thumb_views"]= 'Small thumb Views';
	$lang["admin_iep_gallery_views"]	= 'Gallery Views';
	$lang["admin_iep_bandwidth_used"]	= 'Bandwidth used';
	$lang["admin_iep_from_last_reset"]	= 'From last reset';


/*
 * Site Pagination
 */
	$lang["pagination_next_page_tip"]		= 'Next Page';
	$lang["pagination_previous_page_tip"]	= 'Previous Page';
	$lang["pagination_page_first_tip"]		= 'First Page';
	$lang["pagination_page_last_tip"]		= 'Last Page';
	$lang["pagination_page_tip"]			= 'Page %1$d'; // %1$d - page number
	$lang["pagination_page_of"]				= 'page %1$s of %2$s';// %1$s - page on / %2$s number of pages
	$lang["pagination_page_first"]			= 'First';
	$lang["pagination_page_last"]			= 'Last';


/*****************************************************************************
 *
 * Web Site Language
 *
 *****************************************************************************/


/*
 * Sitewide
 */
	$lang["site_search_text"]	= 'Image Search';
	$lang["site_search_button"]	= 'Search';
	$lang["site_language"]		= 'Site Language :';
	$lang["home_image_widgit"]	= 'Random Image';
	$lang["footer_feed_title"]	= 'image Feed';


/*
 * Site Menu
 */
	$lang["site_menu_home"]		= 'Home';
	$lang["site_menu_gallery"]	= 'Gallery';
	$lang["site_menu_faq"]		= 'FAQ';
	$lang["site_menu_tos"]		= 'Terms of Service';
	$lang["site_menu_contact"]	= 'Contact Us';


 /*
 * Site Feed
 */
	$lang["feed_description"]		= '10 Newest images';
	$lang["feed_language"]			= 'en-gb';
	$lang["feed_image_name"]		= 'Image name';
	$lang["feed_no_images"]			= 'No images upload';


/*
 * Delete Images msg
 */
 	$lang["site_index_delete_image_suc"]					= 'Your image was successfully removed.';
	$lang["site_index_delete_image_err_db"]			= 'A problem occurred during saving of the database!';
	$lang["site_index_delete_image_err_not_found"]	= 'No Image by that name was found!';


/*
 * Upload images msg
 */
 	$lang["upload_duplicate_found"]			= ' that you are uploading looks to be a duplicate of a image what has been uploaded before.';
	$lang["site_upload_url_err_no_image"]	= 'The Image can not be found or the server has denied access to the image.';
	$lang["site_upload_err"]				= 'A problem occurred during file upload!';
	$lang["site_upload_err_no_image"]		= 'Sorry, can not find a image to upload!';
	$lang["site_upload_banned"]				= 'Sorry, but you are banned from uploading images to this site.';
	$lang["site_upload_to_small"]			= 'Sorry, the image size is to small,%s is the min size allowed.';
	$lang["site_upload_to_big"]				= 'Sorry, the image size is to big,%s is the Max size allowed.';
	$lang["site_upload_size_accepted"]		= 'Only images under %s are accepted for upload';
	$lang["site_upload_types_accepted"]		= 'Only %s images are accepted for upload';


/*
 * Index/Home Page
 */
 	$lang["site_index_des"]							= 'Upload your images/photos to our free image hosting servers and share them with your friends, family,and colleagues.';
	$lang["site_index_Image_Formats"]				= 'Supported Image Formats';
	$lang["site_index_maximum_filesize"]			= 'Maximum Filesize';
	$lang["site_index_uploading_image"]				= 'Uploading Image...';
	$lang["site_index_upload_image"]				= 'Image to upload';
	$lang["site_index_upload_browse_button"]		= 'Browse';
	$lang["site_index_upload_description"]			= 'Description: (optional)';
	$lang["site_index_upload_button"]				= 'Upload';
	$lang["site_index_upload_disable"]				= 'Upload has been Disable';
	$lang["site_index_local_image_upload"]			= 'Local';
	$lang["site_index_local_image_upload_title"]	= 'Local Upload Image';
	$lang["site_index_Remote_image_copy"]			= 'Remote';
	$lang["site_index_Remote_image_copy_title"]		= 'Copy Remote Image';
	$lang["site_index_Remote_image"]				= 'Enter the URL of the image you would like to upload';
	$lang["site_index_auto_deleted"]				= '<b>Inactive Files</b> are automatically deleted from the servers after %s days.';
	$lang["site_index_max_bandwidth"]				= 'Hot Linking limit';
	$lang["site_index_max_bandwidth_per"]			= 'of bandwidth per Image/';
	$lang["site_index_max_bandwidth_per_week"] 		= 'Week';
	$lang["site_index_max_bandwidth_per_month"]		= 'month';
	$lang["site_index_max_upload"]					= 'Upload Multiple Images';
	$lang["site_index_max_upload_max"]				= 'max';
	$lang["site_index_tos_des"]						= 'Please note that uploading adult content is not allowed!<br/>Any such content will be deleted. Check our %s for upload rules.';//%s = Terms of Service link 

 	$lang["site_index_upload_preferences"]		= 'preferences';
	$lang["site_index_resize_title"]			= 'Resize your image on upload';
	$lang["site_index_resize_height"]			= 'Height';
	$lang["site_index_resize_width"]			= 'Width';
	$lang["site_index_resize_des"]				= 'If only a height or width is entered, the image will keep it&apos;s aspect ratio. Entering a height and width will resize the image to your given height and width size!';
	$lang["site_index_private_img"]				= 'Private images..';
	$lang["site_index_short_url"]				= 'Create short URLs using';


/*
 * Thumbnail Page
 */
	$lang["site_index_hide_link"]				= 'Image Links';
	$lang["site_index_social_networks"]			= 'Social Networks';
	$lang["site_index_short_url_link"]			= 'Short URL (Twitter)';
	$lang["site_index_bbcode"]					= 'BBCode (Forums)';
	$lang["site_index_html_code"]				= 'HTML Code (Myspace)';
	$lang["site_index_direct_link"]				= 'Direct Link (email &amp; IM)';
	$lang["site_index_small_thumbnail_link"]	= 'Small Thumbnail link';
	$lang["site_index_thumbnail_link"]			= 'Thumbnail link';
	$lang["site_index_image_link"]				= 'Image link';
	$lang["site_index_thumbs_page_err"]			= 'No Image by that name was found!';
	$lang["site_index_delete_url"]				= 'Image Delete URL';
	$lang["site_index_delete_url_des"]			= 'Use this link to remove your image at any time.';


/*
 * Gallery Page
 */
	$lang["site_gallery_report_suc"]			= 'Image has been reported';
	$lang["site_gallery_report_err_reporting"]	= 'Sorry there was a error reporting this image.';
	$lang["site_gallery_report_err_find"]		= 'Can not find the image you are reporting.';
	$lang["site_gallery_report"]				= 'Report';
	$lang["site_gallery_report_title"]			= 'report this image';
	$lang["site_gallery_report_this"]			= 'Are you sure you want to report this image!';
	$lang["site_gallery_page_title"]			= 'Image Gallery Page';
	$lang["site_gallery_err_no_image"]			= 'There are no images in the database!';


/*
 * search Page
 */
	$lang["site_search_err_short"]		= 'Please enter at least 3 characters.';
	$lang["site_search_err_blank"]		= 'you did not enter any think to search for';
	$lang["site_search_page_title"]		= 'Image search for';
	$lang["site_search_results"]		= 'Found %s results for';
	$lang["site_search_no_results"]		= 'Sorry, No Results Found For';
	$lang["site_search_suggestions"]	= 'Suggestions:<br/>Make sure all words in your query are spelled correctly<br/>Try a shorter query, or replace some keywords';


/*
 * Contact Us Page
 */
	$lang["site_contact_thank_you"]					= 'Thank you %s<br/>We have received your information and will try and answer your question or comments ASAP.<br/>All your details are treated in the strictest confidence and we do not share any information with any third parties.';
	$lang["site_contact_des"]						= 'You can contact us through the Easy Contact Form below and we will be happy to get back to you as soon
														as possible. Also, see our %s for quick answers to most questions.<br/><br/>
														Please include a valid email so we can contact you should we need to.<br/>
														Any email addresses submitted via this site will not be distributed.'; // faq link
	$lang["site_contact_form_name"]					= 'Name';
	$lang["site_contact_form_email"]				= 'Email';
	$lang["site_contact_form_comment"]				= 'Comment';
	$lang["site_contact_form_captcha"]				= 'Verification';
	$lang["site_contact_form_captcha_img"]			= 'Click on the image to refresh it';
	$lang["site_contact_form_captcha_image_title"]	= 'click on image to refresh captcha';
	$lang["site_contact_form_send"]					= 'Send';
	$lang["site_contact_err_name_blank"]			= 'Name is a required field and can&apos;t be blank';
	$lang["site_contact_err_email_blank"]			= 'E-mail is a required field and can&apos;t be blank';
	$lang["site_contact_err_email_invalid"]			= 'The e-mail address that you provided is not valid.';
	$lang["site_contact_err_comment_blank"]			= 'Comment is a required field and can&apos;t be blank';
	$lang["site_contact_err_captcha_blank"]			= 'Verification code can&apos;t be blank';
	$lang["site_contact_err_captcha_invalid"]		= 'Verification code is invalid';
	$lang["site_contact_err_captcha_cookie"]		= 'No captcha cookie given. Make sure cookies are enabled.';


/*
 * Terms of Service Page
 */
	$lang["site_tos_title"]						= 'Terms of Service';
	$lang["site_tos_line1"]						= 'reserves the right to disable or delete any file which might compromise the security of our servers, uses excessive bandwidth, violates this Terms of Service or is otherwise considered undesirable (at our sole discretion).';
	$lang["site_tos_line2"]						= 'The image hosting service is provided as-is with no implied warranties of any kind. We can not be held responsible for the loss of data or other damages which may result from (lack of) functionality of our service.';
	$lang["site_tos_line3"]						= 'does not allow the following types of files to be uploaded:';
	$lang["site_tos_line4"]						= 'Images which violate copyrights or patents are not allowed.';
	$lang["site_tos_line5"]						= 'Images which contain adult content such as pornography or excessive nudity.';
	$lang["site_tos_line6"]						= 'Images which contain gruesome scenes, such as dead people or mutilations.';
	$lang["site_tos_line7"]						= 'Images which violate the privacy of the individuals depicted are not allowed.';
	$lang["site_tos_line8"]						= 'Images which are considered illegal in your country.';
	$lang["site_tos_line9"]						= 'Files will not be deleted unless they have not been accessed for some time or violate our Terms of Service.';
	$lang["site_tos_line10"]						= 'reserves the right to deny access to any user who uploads files that compromise the security of our servers, use excessive bandwidth or are otherwise deemed to be misusing the free file hosting service.';
	$lang["site_tos_line11"]						= 'reserves the right to modify these Terms of Service at any time without prior notification.';
	$lang["site_privacy_policy_title"]				= 'Privacy Policy';
	$lang["site_privacy_policy_line1"]				= 'We will never sell, rent, or lease our customer information to third parties.';


/*
 * Frequently Asked Questions Page
 */
	$lang["site_faq_title"]							= 'Frequently Asked Questions';
	$lang["site_faq_q1"]							= 'What is %s? How do I use it?';// %s site title
	$lang["site_faq_a1"]							= 'We are a free image hosting solution. What is designed for you to share your digital pictures with friends and family, email, post images on forums, social networking sites, blogs and Online auctions sites.';
	$lang["site_faq_q2"]							= 'How much does the service cost?';
	$lang["site_faq_a2"]							= 'This website will always be 100&#37; free! %s is supported by our featured advertisers.';// %s site title
	$lang["site_faq_q3"]							= 'Which image file formats are accepted?';
	$lang["site_faq_a3"]							= 'images and photos can be uploaded using our service.'; // file types are listed before this
	$lang["site_faq_q4"]							= 'What kinds of pictures will you host for me?';
	$lang["site_faq_a4"]							= 'Our service will host any legal image, except for adult-rated images. Any files against the law will be deleted and your info will be reported to the appropriate authorities.';
	$lang["site_faq_q5"]							= 'Why did my .BMP and .PSD images change into a .PNG after uploading?';
	$lang["site_faq_a5"]							= 'All Bitmap(.BMP) and Photoshop(.PSD) files are converted to PNG in order to reduce their file size and make them viewable on the web.';
	$lang["site_faq_q6"]							= 'Is direct linking allowed?';
	$lang["site_faq_a6"]							= 'Yes, we allow "direct linking" (also referred to as "hot linking"). It&apos;s best to hotlink to the thumbnails leading to the large images to save on bandwidth as there is a limit of ';// bandwidth limit (set in settings) is added to the end
	$lang["site_faq_q7"]							= 'How long will you store my images?';
	$lang["site_faq_a7_1"]							= 'Barring any unforeseen event, the uploaded images that do not violate our <a href="tos.php" title="%1$s">%1$s</a> are stored on our server for forever.';//%1$s tos- title
	$lang["site_faq_a7_2"]							= 'However, if your image has not been accessed for more than %1$d days, it will be automatically deleted from our server.';//%1$d number of days
	$lang["site_faq_q8"]							= 'What is the max filesize limit?';
	$lang["site_faq_a8"]							= 'The maximum file-size is';// file size added to end (set in settings)
	$lang["site_faq_q9"]							= 'What can I do to help?';
	$lang["site_faq_a9"]							= 'Link to us from your web page or profile, and let other people know about our site!';
	$lang["site_faq_q10"]							= 'Have more questions?';
	$lang["site_faq_a10"]							= 'Please use the <a href="contact.php" title="Contact page">Contact page</a> to e-mail us.';

?>