<?php if(!defined('cfih') or !cfih) exit("Direct access not permitted.");
/**************************************************************************************************************
 *
 *   CF Image Hosting 
 *   ---------------------
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
 *   Used For:     carbonfiber theme Settings file
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

// theme design by footer link
	$theme['designby']	= 'codefuture.co.uk';
	$theme['url']		= 'http://codefuture.co.uk';
	$theme['linktitle']	= 'codefuture.co.uk - online webmaster tools,code Generators';

// AdSense color settings
	$theme['ads_header_bg'] = '191919';
	$theme['ads_index_bg'] = '191919';
	$theme['ads_thumb_bg'] = '191919';
	$theme['ads_gallery_bg'] = '191919';

// gallery thumb setup(small)
	$theme['thumb_option'] = 'crop'; 
	$theme['thumb_max_width'] = 284;
	$theme['thumb_max_height'] = 150;

	$theme['gallery_rows'] = 3; // set number of rows
	$theme['gallery_row_no'] = 3;// number of images per row

// number of images shown on the widgit
	$theme['widgit_row'] = 3;

// pagination number of links to show(gallery/search)
	$theme['pagination_link_no'] = 17;// int 1-99

// set the contact page captcha to use light color for the text
	$theme['captcha_light'] = true; // true|false


