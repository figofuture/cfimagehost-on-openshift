<?php if(!defined('cfih') or !cfih) exit("Direct access not permitted.");
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
 *   Used For:     Web site Header
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

// check if admin is login
	if($checklogin = checklogin()){
		add_code_line('header','<link rel="stylesheet" href="'.$settings['SET_SITEURL'].'/css/adminbar.css" type="text/css" />');
		$adminMenu ='<div id="admin_bar">
		<div class="title">Admin Menu</div>
		<ul class="nav">
			<li><a href="'.$settings['SET_SITEURL'].'/admin.php?act=logout" title="'._T("admin_menu_logout").'">'._T("admin_menu_logout").'</a></li>
			<li><a href="'.$settings['SET_SITEURL'].'/admin.php?act=set" title="'._T("admin_menu_settings").'">'._T("admin_menu_settings").'</a></li>
			<li><a href="'.$settings['SET_SITEURL'].'/admin.php?act=ban" title="'._T("admin_menu_banned").'">'._T("admin_menu_banned").'</a></li>
			<li><a href="'.$settings['SET_SITEURL'].'/admin.php?act=db" title="'._T("admin_menu_database").'">'._T("admin_menu_database").'</a></li>
			<li><a href="'.$settings['SET_SITEURL'].'/admin.php?act=images" title="'._T("admin_menu_image_list").'">'._T("admin_menu_image_list").'</a></li>
			<li><a href="'.$settings['SET_SITEURL'].'/admin.php" title="'._T("admin_menu_home").'">'._T("admin_menu_home").'</a></li>
		</ul>
	</div>';
	}

// 404 page error
	if (isset($_GET['err'])){
		$page_error = error_header($_GET['err']);
		user_feedback('error',$page_error,'page_error');
	}

?>
<!doctype html>
<html lang="<?php echo _T("site_lang_code");?>">
<head>
	<meta charset="<?php echo _T("site_charset");?>">
	<title><?php get_site('title');?> &lt; <?php get_page('title');?></title>
	<meta name="description" content="<?php get_page('description');?>" />
	<meta name="keywords" content="<?php get_page('keywords'); ?>"/>
	<meta name="robots" content="index,follow"/>
	<link href="<?php get_url('favicon.ico');?>" rel="icon" type="image/x-icon" />
	<link rel="alternate" type="application/rss+xml" title="<?php get_site('title');?> Rss Feed" href="<?php get_url('feed.php');?>" />
	<link rel="stylesheet" href="<?php get_theme_url('css');?>" type="text/css" />
	<?php exec_action('header');?>
</head>
<body id="<?php get_page('id');?>">
<?php echo (isset($adminMenu)?$adminMenu:''); ?>
<div id="wrap">
	<div id="header" class="clear_both">
		<div id="logo">
			<h1><a href="<?php get_url('index.php');?>" title="<?php get_site('title');?> <?php echo _T("site_menu_home");?>"><?php get_site('title');?></a></h1>
			<h2><?php get_site('slogan');?></h2>
		</div>

<?php	if ($listLanguages = listLanguages()){?>
		<div class="languages"<?php if(theme_setting('list_languages_over',true)){ ?> onMouseOver="document.getElementById('language').style.display='block'" onMouseOut="document.getElementById('language').style.display='none'"<?php } ?>>
			<div class="lan_on"><span><?php echo _T("site_language");?></span><img src="<?php echo get_url('languages/',false).CFUSERLANG;?>.png" width="23" height="15" alt="<?php echo CFUSERLANG;?>" title="<?php echo CFUSERLANG;?>" /></div>
			<div id="language" class="language" ><?php echo $listLanguages;?></div>
			<div class="clear_both"></div>
		</div>
<?php } ?>

	<?php if($settings['SET_HIDE_SEARCH']){?>
		<div id="search">
			<form method="get" action="<?php get_url('gallery.php');?>">
				<input type="text" size="28" name="search" id="searchBox" class="text_input" onblur="if(this.value=='')value='<?php echo _T("site_search_text");?>';" onfocus="if(this.value=='<?php echo _T("site_search_text");?>')value=''" value="<?php echo _T("site_search_text");?>" /><input type="submit" value="<?php echo _T("site_search_button");?>" class="button" />
			</form>
		</div>
	<?php } ?>
		<div id="nav">
			<?php get_page_menu();?>
		</div>
		<div class="clear_both"></div>
	</div>
	<div id="content">
<?php
	get_ad('header','top_ad');// check for and output ads
	show_feedback();// check for any success/errors notes and output them
?>
<div id="msg"></div>