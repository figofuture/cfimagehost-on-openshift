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
 *   Used For:     THUMB PAGE
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

 	define('cfih', 'thumb');
	require './inc/cfih.php';

// find image id 
	if(isset($_GET['pt'])||isset($_GET['pm'])){
		unset($_SESSION['upload']);
		$_SESSION['upload'][0]['id'] = isset($_GET['pt'])?$_GET['pt']:$_GET['pm'];
	}

// if no image found and no upload array found send user back to index page
	elseif(!isset($_SESSION['upload'])){
		header('Location: index.php');
		exit();
	}

// check what thumb to show!
	$showImage = isset($_GET['pt'])?1:0;

// count number of thumb's on page
	$countThumb = 0;

// image loop
	foreach($_SESSION['upload'] as $k=>$uploadimage){

		if(isset($uploadimage['id'])) $img_id = $uploadimage['id'];
		if(isset($uploadimage['did'])) $delete_id = $uploadimage['did'];

	//check image id is valid
		if(isset($img_id) && preg_replace("/[^0-9A-Za-z]/","",$img_id) != $img_id){
			$countThumb++;
			continue;
		}

	//see if image exists
		if (!$image = db_get_image($img_id)){
		//	user_feedback('error',_T("site_index_thumbs_page_err"),'thumbs_page');
			$countThumb++;
			continue;
		}

	//only count image if not on upload page
		if(!isset($delete_id)){
			db_imageCounterSave($image,4);
		}

	// check for cache 
		if(!isset($delete_id)){// && !checklogin()){
			unset($_SESSION['upload']);
			cache_cheack($img_id.'_thumb'.($showImage?'_small':''),(60*60*24*30));
		}

	// hold thumbnail page html
		if(!isset($thumbHtml))$thumbHtml = '';


	// Thumbnail page variables
		$thumb_link		= imageAddress(3,$image,'pt');
		$thumb_url		= imageAddress(3,$image,'dt');
		$thumb_mid_link	= imageAddress(2,$image,'pm');
		$thumb_mid_url	= imageAddress(2,$image,'dm');
		$imgurl			= imageAddress(1,$image,'di');
		$alt			= $image['alt'];
		$shorturl		= $image['shorturl'];
		$thumb_show		= $showImage?$thumb_url:$thumb_mid_url;

	// make image links
		$links[$countThumb] = array(
							'thumb_bbcode'		=> imageLinkCode('bbcode',$thumb_url,$thumb_link),
							'thumb_html'		=> imageLinkCode('html',$thumb_url,$thumb_link,$alt),
							'thumb_mid_bbcode'	=> imageLinkCode('bbcode',$thumb_mid_url,$thumb_mid_link),
							'thumb_mid_html'	=> imageLinkCode('html',$thumb_mid_url,$thumb_mid_link,$alt),
							'image_bbcode'		=> imageLinkCode('bbcode',$imgurl),
							'image_direct'		=> $imgurl,
							'delete_url'		=> (isset($delete_id)?$settings['SET_SITEURL'].'?d='.$delete_id:'')
							);
	// comments layout
		$layout = ' full';
		
	// AdSense
		$thumb_Ad_html = (!isset($countThumb) || $countThumb < 2)?get_ad('thumb','thumb_Ad',false):'';

	//image box
		$thumbHtml .= '<div class="img_ad_box '.(isset($countThumb) && $countThumb > 0?' nextbox':'').'">
							<div class="img_box'.(!empty($thumb_Ad_html)?' left':'').'">
								<a href="'.$imgurl.'" title="'.$alt.'" class="lightbox" ><img src="'.$thumb_show.'" alt="'.$alt.'" /><br/>
								<span>'.$alt.'</span></a>
							</div>
							'.$thumb_Ad_html.'
							<div class="clear_both"></div>
						</div>';

	// AdSense
		$thumbHtml .= (!isset($delete_id)?get_ad('gallery','thumb_Ad2',false):'');

	//image links
		$thumbHtml .= '<div id="links" class="boxpanel'.$layout.'">
							<h2 class="boxtitle">'._T("site_index_hide_link").'</h2>
							<div class="code_box"><label id="toplabel">'._T("site_index_social_networks").':</label>'.bookmarking(($shorturl ==null?$thumb_mid_link:$shorturl),$alt).'</div>';
	// Short URL
		if ($shorturl != null && !empty($shorturl))	$thumbHtml .= '
							<div class="code_box"><label for="shorturl">'._T("site_index_short_url_link").':</label> <input type="text" id="codehtml" value="'.$shorturl.'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>';

	// Image Links
		$thumbHtml .= '
				<h3>'._T("site_index_small_thumbnail_link").'</h3>
					<div class="code_box"><label for="codelbb">'._T("site_index_bbcode").':</label> <input type="text" id="codelbb" value="'.$links[$countThumb]['thumb_bbcode'].'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>
					<div class="code_box"><label for="codehtml"><a href="'.$thumb_link.'" title="'.$alt.'" >'._T("site_index_html_code").'</a> :</label> <input type="text" id="codehtml" value="'.$links[$countThumb]['thumb_html'].'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>
				<h3>'._T("site_index_thumbnail_link").'</h3>
					<div class="code_box"><label for="codelbb">'._T("site_index_bbcode").':</label> <input type="text" id="codelbb" value="'.$links[$countThumb]['thumb_mid_bbcode'].'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>
					<div class="code_box"><label for="codehtml"><a href="'.$thumb_mid_link.'" title="'.$alt.'" >'._T("site_index_html_code").'</a> :</label> <input type="text" id="codehtml" value="'.$links[$countThumb]['thumb_mid_html'].'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>
				<h3>'._T("site_index_image_link").'</h3>
				<div class="code_box"><label for="codebb">'._T("site_index_bbcode").':</label> <input type="text" id="codebb" value="'.$links[$countThumb]['image_bbcode'].'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>
				<div class="code_box"><label for="codedirect">'._T("site_index_direct_link").'</label> <input type="text" id="codedirect" value="'.$links[$countThumb]['image_direct'].'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>';

		if(isset($delete_id)){
			$thumbHtml .= '
			<h3>Delete Image</h3>
				<div class="code_box"><label for="deletecode">'._T("site_index_delete_url").':</label> <input type="text" id="deletecode" value="'.$links[$countThumb]['delete_url'].'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>
				<p class="teaser">'._T("site_index_delete_url_des").'</p>';
		}
		$thumbHtml .= '</div>';
		$thumbHtml .= '<div style="clear: both;"></div>';


		$countThumb++;
	}// end uploaded loop

// unset upload array
	if(isset($_SESSION['upload']))
		unset($_SESSION['upload']);

////////////////////////////////////////////////////////////////////////////////////
// MAKE PAGE

// error send back to home page and show the error
	if(!isset($thumbHtml)){
		header('Location: '.$settings['SET_SITEURL'].'index.php?err=404');
		exit();
	}

// set any header hooks
	add_code_line('header','<link rel="stylesheet" type="text/css" href="'.$settings['SET_SITEURL'].'js/lightbox/lightbox.css" media="screen" />
	<script type= "text/javascript">
		var homeUrl = "'.$settings['SET_SITEURL'].'";
	</script>');

// set any footer hooks
	add_code_line('footer','<script type="text/javascript" src="'.$settings['SET_SITEURL'].'js/lightbox/lightbox.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".lightbox").lightbox({
				fitToScreen: true,
				imageClickClose: true,
				displayDownloadLink: true
			});
		});
	</script>');

// header page var
	$pageSet['id'] = 'thumb';
	$pageSet['title'] =  ' - '.(isset($alt)?$alt:' No Image Found');
	$pageSet['description'] = 'Thumb page for '.$alt.' image on '.$settings['SET_TITLE'];
	$pageSet['keywords'] = 'Thumb image, gallery, images, photos, image gallery, photo gallery, image hosting';
	$pageSet['image_widgit'] = true;

	require CFROOTPATH.'header.php';
	echo $thumbHtml;// print Thumbnail page
	require CFROOTPATH. 'footer.php';

// end cache
	if(!isset($delete_id)) cache_end();

	exit;
