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
 *   Used For:     Admin ADs page
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

	if(isset($_POST['changeads'])){
		$makeFile ='<?php if(!defined(\'cfih\') or !cfih) exit("Direct access not permitted.");
	$ads[\'header\'] = \''.safe_slash_html($_POST['ad1']).'\';
	$ads[\'index\'] = "'.safe_slash_html($_POST['ad2']).'";
	$ads[\'thumb\'] = "'.safe_slash_html($_POST['ad3']).'";
	$ads[\'gallery\'] = "'.safe_slash_html($_POST['ad4']).'";
	$ads[\'footer\'] = "'.safe_slash_html($_POST['ad5']).'";';
		if($fp = fopen(CFDATAPATH.'ads.php', 'w+')){
			fwrite($fp, $makeFile);
			fclose($fp);
		}else{
			user_feedback('error','Error: Saving file','admin_ads_saving_file');
		}
	}

// page settings
	$page['id']				= 'ads';
	$page['title']			= _T("admin_ad_page_title");
	$page['description']	= '';
	$page['tipsy']			= true;


	require CFADMINPATH.'admin_page_header.php';
?>
<!-- admin settings -->
		<div id="msg"></div>
					<div class="ibox full">
						<h2><?php echo _T("admin_ad_page_title");?></h2>
						<p class="teaser"><?php echo _T("admin_ad_page_des");?></p>
					</div>
					
				<div class="clear"></div>
		<form method="POST" action="admin.php?act=ads">
			<div class="tabs">
				<ul class="tabs_list tabNavigation">
					<li><a href="#ad_header"><?php echo _T("admin_ad_title_header");?></a></li>
					<li><a href="#ad_index"><?php echo _T("admin_ad_title_index");?></a></li>
					<li><a href="#ad_thumb"><?php echo _T("admin_ad_title_thumb");?></a></li>
					<li><a href="#ad_gallery"><?php echo _T("admin_ad_title_gallery");?></a></li>
					<li><a href="#ad_footer"><?php echo _T("admin_ad_title_footer");?></a></li>
				</ul>

			<!--ad header-->
				<div id="ad_header" class="panel ibox">
					<h2><?php echo _T("admin_ad_title_header");?></h2>
					<div class="code_box"><p class="teaser"><?php echo _T("admin_ad_label_header") ;?> </p></div>
					<div class="code_box"><textarea name="ad1" id="ad1" rows="8" cols="5" class="text_input long"><?php echo get_ad_code('header',false);?></textarea></div>
					<div class="code_box"><input class="button button_cen" type="submit" value="<?php echo _T("admin_set_save_button");?>" name="changeads[]"></div>
				</div>

			<!--ad index-->
				<div id="ad_index" class="panel ibox">
					<h2><?php echo _T("admin_ad_title_index");?></h2>
					<div class="code_box"><p class="teaser"><?php echo _T("admin_ad_label_index") ;?> </p></div>
					<div class="code_box"><textarea name="ad2" id="ad2" rows="8" cols="5" class="text_input long"><?php echo get_ad_code('index',false);?></textarea></div>
					<div class="code_box"><input class="button button_cen" type="submit" value="<?php echo _T("admin_set_save_button");?>" name="changeads[]"></div>
				</div>

			<!--ad thumb-->
				<div id="ad_thumb" class="panel ibox">
					<h2><?php echo _T("admin_ad_title_thumb");?></h2>
					<div class="code_box"><p class="teaser"><?php echo _T("admin_ad_label_thumb") ;?></p></div>
					<div class="code_box"><textarea name="ad3" id="ad3" rows="8" cols="5" class="text_input long"><?php echo get_ad_code('thumb',false);?></textarea></div>
					<div class="code_box"><input class="button button_cen" type="submit" value="<?php echo _T("admin_set_save_button");?>" name="changeads[]"></div>
				</div>

			<!--ad gallery-->
				<div id="ad_gallery" class="panel ibox">
					<h2><?php echo _T("admin_ad_title_gallery");?></h2>
					<div class="code_box"><p class="teaser"><?php echo _T("admin_ad_label_gallery") ;?></p></div>
					<div class="code_box"><textarea name="ad4" id="ad4" rows="8" cols="5" class="text_input long"><?php echo get_ad_code('gallery',false);?></textarea></div>
					<div class="code_box"><input class="button button_cen" type="submit" value="<?php echo _T("admin_set_save_button");?>" name="changeads[]"></div>
				</div>
				
			<!--ad footer-->
				<div id="ad_footer" class="panel ibox">
					<h2><?php echo _T("admin_ad_title_footer");?></h2>
					<div class="code_box"><p class="teaser"><?php echo _T("admin_ad_label_footer") ;?> </p></div>
					<div class="code_box"><textarea name="ad5" id="ad5" rows="8" cols="5" class="text_input long"><?php echo get_ad_code('footer',false);?></textarea></div>
					<div class="code_box"><input class="button button_cen" type="submit" value="<?php echo _T("admin_set_save_button");?>" name="changeads[]"></div>
				</div>

				<div class="clear"></div>
			</div>
		</form>
<?php
	require CFADMINPATH.'admin_page_footer.php';
	die();
	exit;