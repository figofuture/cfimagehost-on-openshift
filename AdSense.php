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
 *   Used For:     Holds the adsense code
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

	$g_channel = !empty($settings['SET_GOOGLE_CHANNAL'])?'google_ad_channel ="'.$settings['SET_GOOGLE_CHANNAL'].'";':'';
	$g_client = 'google_ad_client ="'.$google_ads.'";';

//Header AdSense Code
	$ads['head_bg']	= theme_setting('ads_header_bg','ffffff');
	$ads['head_lc']	= theme_setting('ads_header_link_color','0066CC');
	$ads['head_tc']	= theme_setting('ads_header_text_color','888888');
	$ads['head_uc']	= theme_setting('ads_header_url_color','7F7F7F');
	
	$ads['header'] = '
	<script type="text/javascript">
		<!--
			'.$g_client.'
			'.$g_channel.'
			/* 728x15 */
			google_ad_width = 728;
			google_ad_height = 15;
			google_ad_format = "728x15_0ads_al_s";
			google_color_link = "'.$ads['head_lc'].'";
			google_color_text = "'.$ads['head_tc'].'";
			google_color_url = "'.$ads['head_uc'].'";
			google_color_bg = "'.$ads['head_bg'].'";
			google_color_border = "'.$ads['head_bg'].'";
		//-->
	</script>
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';


// Index page AdSense Code
	$ads['in_mid_bg']	= theme_setting('ads_index_bg','ffffff');
	$ads['in_mid_lc']	= theme_setting('ads_index_link_color','0066CC');
	$ads['in_mid_tc']	= theme_setting('ads_index_text_color','888888');
	$ads['in_mid_uc']	= theme_setting('ads_index_url_color','7F7F7F');

	$ads['index'] = '
	<script type="text/javascript">
		<!--
			'.$g_client.'
			'.$g_channel.'
			/* 728x90 */
			google_ad_width = 728;
			google_ad_height = 90;
			google_ad_format = "728x90_as";
			google_ad_type = "image";
			google_color_link = "'.$ads['in_mid_lc'].'";
			google_color_text = "'.$ads['in_mid_tc'].'";
			google_color_url = "'.$ads['in_mid_uc'].'";
			google_color_bg = "'.$ads['in_mid_bg'].'";
			google_color_border = "'.$ads['in_mid_bg'].'";
			google_ui_features = "rc:0";
		//-->
	</script>
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';


// Thumb page AdSense Code
	$ads['thumb_bg']	= theme_setting('ads_thumb_bg','ffffff');
	$ads['thumb_lc']	= theme_setting('ads_thumb_link_color','0066CC');
	$ads['thumb_tc']	= theme_setting('ads_thumb_text_color','888888');
	$ads['thumb_uc']	= theme_setting('ads_thumb_url_color','7F7F7F');

	$ads['thumb'] = '
	<script type="text/javascript">
		<!--
			'.$g_client.'
			'.$g_channel.'
			google_ad_width = 300;
			google_ad_height = 250;
			google_ad_format = "300x250_as";
			google_ad_type = "text";
			google_color_link = "'.$ads['thumb_lc'].'";
			google_color_text = "'.$ads['thumb_tc'].'";
			google_color_url = "'.$ads['thumb_uc'].'";
			google_color_bg = "'.$ads['thumb_bg'].'";
			google_color_border = "'.$ads['thumb_bg'].'";
			google_ui_features = "rc:0";
		//-->
	</script>
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';


//Gallery Inline AdSense Code
	$ads['gallery_bg']	= theme_setting('ads_gallery_bg','ffffff');
	$ads['gallery_lc']	= theme_setting('ads_gallery_link_color','0066CC');
	$ads['gallery_tc']	= theme_setting('ads_gallery_text_color','888888');
	$ads['gallery_uc']	= theme_setting('ads_gallery_url_color','7F7F7F');

	$ads['gallery'] = '
	<script type="text/javascript">
		<!--
			'.$g_client.'
			'.$g_channel.'
			/* 728x90 */
			google_ad_width = 728;
			google_ad_height = 90;
			google_ad_format = "728x90_as";
			google_ad_type = "text_image";
			google_color_link = "'.$ads['gallery_lc'].'";
			google_color_text = "'.$ads['gallery_tc'].'";
			google_color_url = "'.$ads['gallery_uc'].'";
			google_color_bg = "'.$ads['gallery_bg'].'";
			google_color_border = "'.$ads['gallery_bg'].'";
		//-->
	</script>
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';


//Footer Inline AdSense Code
	$ads['footer'] = '
	<script type="text/javascript">
		<!--
			'.$g_client.'
			'.$g_channel.'
			/* 728x90 */
			google_ad_width = 728;
			google_ad_height = 90;
			google_ad_format = "728x90_as";
			google_ad_type = "image";
		//-->
	</script>
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';