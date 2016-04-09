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
 *   Used For:     index/home page
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

 // LOAD IMAGE (need to send old links to image.php)
	if(isset($_GET['di']) || isset($_GET['dm']) || isset($_GET['dt']) || isset($_GET['dl'])){
		require './image.php';
		exit();
	}

// check to see if we need to open a thumbnail page
	if (isset($_GET['pt']) OR isset($_GET['pm'])){
		require './thumbnail.php';
		exit();
	}

 	define('cfih', 'index');
	require './inc/cfih.php';
	
//check for uploaded image
	if(isset($_SESSION['upload'])){
		header('Location: thumbnail.php');
		exit();
	}

// DELETE IMAGE
	removeImage();

// header page var
	$pageSet['id'] = 'home';
	$pageSet['title'] = ' - '.$settings['SET_SLOGAN'];
	$pageSet['description'] = 'Free Image Hosting, Just upload your image and share them around the web';
	$pageSet['keywords'] = 'images, photos, image hosting, photo hosting, free image hosting';
	$pageSet['image_widgit'] = true;

// set any header hooks
	add_code_line('header','<script type= "text/javascript">
		var extArray = new Array("'.implode('","',$imgFormats).'");
		var js_text = {
			t101:"Please only upload files that end in types: '.implode(", ",$imgFormats).'\nPlease select a new image to upload and submit again.",
			t103:'.($settings['SET_MAX_UPLOAD']<1?1:$settings['SET_MAX_UPLOAD']).'
			}
	</script>');

// load header
	require CFROOTPATH.'header.php';

//home page (upload) variables
	$homeVar['inactive_files']		= $settings['SET_AUTO_DELETED']?_T("site_index_auto_deleted",$settings['SET_AUTO_DELETED_TIME']).'<br />':'';
	$homeVar['hot_linking_limit']	= $settings['SET_MAX_BANDWIDTH']? '<b>'._T("site_index_max_bandwidth").':</b> '.(format_size(1048576*$settings['SET_MAX_BANDWIDTH'])).' '._T("site_index_max_bandwidth_per").($settings['SET_AUTO_DELETED_JUMP'] == 'm' ? _T("site_index_max_bandwidth_per_month"):_T("site_index_max_bandwidth_per_week")).'<br />':'';
	$homeVar['max_upload']			= $settings['SET_MAX_UPLOAD']	? '<div class="Upload_Multiple"><span>'._T("site_index_max_upload").' </span><a href="#" class="add_another_file_input"></a><small>('._T("site_index_max_upload_max").' '.$settings['SET_MAX_UPLOAD'].')</small></div>':'';
	$homeVar['hide_tos']			= $settings['SET_HIDE_TOS']?'<p>'._T("site_index_tos_des",'<a href="tos.php" title="'._T("site_menu_tos").'">'._T("site_menu_tos").'</a>').'</p>':'';

	$homeVar['private_img']			= $settings['SET_PRIVATE_IMG_ON']? '<input id="private" name="private[0]" value="1" type="checkbox" /> <label for="private">'._T("site_index_private_img").'</label><br/>':'';
	$homeVar['short_url']			= $settings['SET_SHORT_URL_ON']	? '<input id="shorturl" name="shorturl[0]" value="1" type="checkbox" /> <label for="shorturl">'._T("site_index_short_url").' '.$settings['SET_SHORT_URL_API'].'</label><br/>':'';
	$homeVar['resize_img']			= $settings['SET_RESIZE_IMG_ON']? '<span class="title">'._T("site_index_resize_title").':</span> <label for="new_height">'._T("site_index_resize_height").'</label> <input type="text" maxlength="4" size="4" class="text_input" id="new_height" name="new_height[]"><br/><label for="new_width">'._T("site_index_resize_width").'</label> <input type="text" maxlength="4" size="4" class="text_input" id="new_width" name="new_width[]"><span class="small">'._T("site_index_resize_des").'</span>':'';

?>
		<div class="contentBox">
			<?php if(!$settings['SET_DIS_UPLOAD']||checklogin()){?>
				<p class="teaser"><?php echo _T("site_index_des");?></p>
				<p class="teaser">
					<?php echo $homeVar['inactive_files'];?>
					<?php echo $homeVar['hot_linking_limit'];?>
					<b><?php echo _T("site_index_Image_Formats");?>:</b> <?php echo implode(", ",$imgFormats); ?><br />
					<b><?php echo _T("site_index_maximum_filesize");?>:</b> <?php echo format_size($settings['SET_MAXSIZE']);?>
				</p>
				<?php get_ad('index','ad_index');?>
				<form enctype="multipart/form-data" action="upload.php" method="post" class="upform" name="upload" id="upload">
					<input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="<?php echo $settings['SET_MAXSIZE'];?>" />
					<div class="upload_op">
						<a id="linklocal" class="linklocal show" title="<?php echo _T("site_index_local_image_upload_title");?>"><?php echo _T("site_index_local_image_upload");?></a>
						<a id="linkremote" class="linkremote" title="<?php echo _T("site_index_Remote_image_copy_title");?>"><?php echo _T("site_index_Remote_image_copy");?></a>
					</div>
					<div class="loading">
						<label><?php echo _T("site_index_uploading_image");?></label>
						<div id="uoloadingImage"></div>
					</div>
					<div class="uploadbox file">
					<a class="closeUpload" href="#" title="Close"> </a>
						<div class="upload_form">
							<div id="remote_panel" class="file_url" style="display: none;">
								<label for="imgUrl"><?php echo _T("site_index_Remote_image");?></label>
								<input type="text" name="imgUrl" id="imgUrl"  class="text_input long" />
							</div>
							<div id="local_panel" class="file_upload">
								<label for="file"><?php echo _T("site_index_upload_image");?>: </label>
								<div class="file_input_div"><input type="text" id="fileName" name="fileName[]"  class="text_input long" readonly="readonly" />
									<input type="button" value="<?php echo _T("site_index_upload_browse_button");?>" name="Search files" class="file_input_button button" />
									<input type="file" name="file[]" id="file" class="file_input_hidden" onchange="javascript: copyfileName()" />
								</div>
							</div>
						</div>
						<label for="alt" class="des"><?php echo _T("site_index_upload_description");?></label>
						<input type="text" name="alt[]" id="alt" class="text_input long_des" />

						<?php if($settings['SET_PRIVATE_IMG_ON'] || $settings['SET_SHORT_URL_ON'] || $settings['SET_RESIZE_IMG_ON']){?>
							<div class="pref_title"><?php echo _T("site_index_upload_preferences");?></div>
							<div class="preferences">
								<?php echo $homeVar['private_img'];?>
								<?php echo $homeVar['short_url'];?>
								<?php echo $homeVar['resize_img'];?>
							</div>
						<?php } ?>
					</div>
					<?php echo $homeVar['max_upload'];?>
					<input name="submit" type="submit" id="uploadbutton" value="<?php echo _T("site_index_upload_button");?>" class="uploadbutton button" onclick="return fileExt(extArray)" />
					<div class="clear_both"></div>
					<?php echo $homeVar['hide_tos'];?>
				</form>
				<div class="clear_both"></div>
		<?php }else{//upload disable?>
			<p class="teaser"><b><?php echo _T("site_index_upload_disable");?></b></p>
		<?php } ?>
		</div>
<?php
	require CFROOTPATH.'footer.php';
