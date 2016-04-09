<?php if(!defined('cfih') or !cfih or cfih!='admin') die('This file cannot be directly accessed.');
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
 *   Used For:     Admin page header
 *   Last edited:  08/01/2013
 *
 *************************************************************************************************************/
?>
<!doctype html>
<html lang="<?php echo _T("site_lang_code");?>">
<head>
	<meta charset="<?php echo _T("site_charset");?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo $settings['SET_TITLE'];?> <?php echo $page['title'];?></title>
	<meta name="Description" content="<?php echo $page['description'];?>" />
	<meta name="author" content="codefuture.co.uk">
	<link rel="stylesheet" href="css/admin.css?<?php echo $settings['SET_VERSION'];?>" type="text/css" />
	<link href="<?php echo $settings['SET_SITEURL'];?>favicon.ico" rel="icon" type="image/x-icon" />
<?php if(isset($page['lightbox'])){?>
	<link rel="stylesheet" href="<?php echo $settings['SET_SITEURL'];?>js/lightbox/lightbox.css" />
<?php } ?>
	<?php exec_action('admin-header');?>
</head>
<body id="<?php echo $page['id'];?>">
<?php 
// loged in top
	if($page['id']!= 'logon'){?>
<div class="logo"><a href="http://www.pledgie.com/campaigns/11487"><img border="0" src="img/pledgie.png" alt="Click here to lend your support to: CF Image Hosting Donation and make a donation at www.pledgie.com !" title="Click here to lend your support to: CF Image Hosting Donation and make a donation at www.pledgie.com !"></a></div>
<div id="admin_bar">
	<div class="sitelink"><a href="<?php echo $settings['SET_SITEURL'];?>" title="<?php echo _T("admin_menu_visitsite");?>"><?php echo _T("admin_menu_visitsite");?></a></div>
	<ul class="nav">
		<li><a <?php echo ($page['id']=="" ? ' class="current" ':'');?>href="<?php echo $settings['SET_SITEURL'];?>admin.php?act=logout" title="<?php echo _T("admin_menu_logout");?>"><?php echo _T("admin_menu_logout");?></a></li>
		<li><a <?php echo ($page['id']=="images" ? ' class="current" ':'');?> href="<?php echo $settings['SET_SITEURL'];?>admin.php?act=images" title="<?php echo _T("admin_menu_image_list");?>"><?php echo _T("admin_menu_image_list");?></a></li>
		<li><a <?php echo ($page['id']=="ads" ? ' class="current" ':'');?> href="<?php echo $settings['SET_SITEURL'];?>admin.php?act=ads" title="<?php echo _T("admin_menu_ads");?>"><?php echo _T("admin_menu_ads");?></a></li>
		<li><a <?php echo ($page['id']=="db" ? ' class="current" ':'');?> href="<?php echo $settings['SET_SITEURL'];?>admin.php?act=db" title="<?php echo _T("admin_menu_database");?>"><?php echo _T("admin_menu_database");?></a></li>
		<li><a <?php echo ($page['id']=="set" ? ' class="current" ':'');?> href="<?php echo $settings['SET_SITEURL'];?>admin.php?act=set" title="<?php echo _T("admin_menu_settings");?>"><?php echo _T("admin_menu_settings");?></a></li>
		<li><a <?php echo ($page['id']=="ban" ? ' class="current" ':'');?> href="<?php echo $settings['SET_SITEURL'];?>admin.php?act=ban" title="<?php echo _T("admin_menu_banned");?>"><?php echo _T("admin_menu_banned");?></a></li>
		<li><a <?php echo ($page['id']=="bulkupload" ? ' class="current"':'');?> href="<?php echo $settings['SET_SITEURL'];?>admin.php?act=bulk" title="<?php echo _T("admin_menu_bulk");?>"><?php echo _T("admin_menu_bulk");?></a></li>
		<li><a <?php echo ($page['id']=="home" ? ' class="current"':'');?> href="<?php echo $settings['SET_SITEURL'];?>admin.php" title="<?php echo _T("admin_menu_home");?>"><?php echo _T("admin_menu_home");?></a></li>
	</ul>
</div>
<?php }?>
<div id="wrap">
	<div id="content">
		<div class="box">
		<div id="msg"></div>
		<?php if($page['id'] != 'logon') show_feedback();?>
<!-- admin header end -->