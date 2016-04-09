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
 *   Used For:     Script Installer
 *   Last edited:  08/01/2013
 *
 *************************************************************************************************************/
	define('cfih_i', 'index');
// load fixed settings
	include_once('./cfinstall.php');

if(file_exists(CFINSTALLED)){
                exit();
        }

// Waht page to load?
	$page_on = isset($_GET['i']) ? cl($_GET['i']):'license';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>CF Image Hosting Installer</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="../css/admin.css?<?php echo $SET_VERSION?>" type="text/css" />
<?php	if(!isset($_GET['i'])){?>
<script type="text/javascript">
	<!--
		window.onload=function() {
			document.license.install.disabled=true;
		}
		function theChecker(){
			if(document.license.accept.checked==false){
				document.license.install.disabled=true;
			}else{
				document.license.install.disabled=false;
			}
		}
	//-->
</script>
<?php }?>
</head>
<body id="install">
<div class="logo"><a href="http://www.pledgie.com/campaigns/11487"><img border="0" src="http://www.pledgie.com/campaigns/11487.png?skin_name=chrome" alt="Click here to lend your support to: CF Image Hosting Donation and make a donation at www.pledgie.com !"></a></div>
<div id="admin_bar">
	<div class="title">CF Image Hosting Installer</div>
</div>
<div id="wrap">
	<div id="content">
		<div class="box installer">
<?php

// load admin page
	switch( $page_on ) {

	// Edit image title/private
		case 'edit':
			require_once('admin/admin_image_edit.php');
			break;

	// settings
		case 'set':
			require_once('install_settings.php');
			break;
			
	// database
		case 'db':
			require_once('install_db.php');
			break;
			
	// checks
		case 'checks':
			require_once('install_checks.php');
			break;

	// License
		case 'license':
		default:
			$lines = file('../license.txt');
			$license = '';
			foreach ($lines as $line_num => $line) {
				$license .= $line;
			}
			?>
			<div class="ibox">
				<h2>License</h2>
				<textarea name="license" id="license" rows="10" cols="50" class="text_input" readonly="true"><?php echo $license;?></textarea>
			</div>
			<div class="ibox">
				<h2>Accept License & Install</h2>
				<form method="post" action="?i=checks" class="license ins" name="license">
				<label for="accept"><input type="checkbox" name="accept" onclick="theChecker()" id="accept"> I accept the terms of the license.</label><br/>
				<input type="submit" value="Start Installer" class="button" name="install">
				</form>
			</div>
			<?php
	}

?>
			<div class="clear"></div>
		</div>
	</div>
</div>
	<div id="footer">
		<span class="copyright">powered by <a href="http://codefuture.co.uk/projects/imagehost/" title="Free PHP Image Hosting Script">CF Image Hosting script</a></span>
		<span class="version"><b>Version:</b> <?php echo $SET_VERSION?></span>
	</div>
</html>
