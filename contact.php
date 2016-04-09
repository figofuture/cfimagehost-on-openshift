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
 *   Used For:     Contact Us Form
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

 	define('cfih', 'contact');
	require './inc/cfih.php';

// check to see if this page has been hidden (set in the admin panel), if so then send user to the index/home page
	if(!$settings['SET_HIDE_CONTACT']){
		header('Location: index.php');
		exit();
	}

// Set page variable values
	$errors = array();
	$emailSent = false;

// check to see if the contact form has been posted
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Clean input variable
		$name = isset($_POST['name'])?cl($_POST['name']):'';
		$email = isset($_POST['email'])?cl($_POST['email']):'';
		$comment = isset($_POST['comment'])?cl($_POST['comment']):'';
		$captcha = isset($_POST['captcha'])?cl($_POST['captcha']):'';

	// check for a name
		if(empty($name)) $errors['name'] = _T("site_contact_err_name_blank");

	// check for a email address
		if(empty($email)){
			$errors['email'] = _T("site_contact_err_email_blank");
		}elseif(!check_email_address($email)){
			$errors['email'] = _T("site_contact_err_email_invalid");
		}

	// check for the email comment
		if(empty($comment)) $errors['comment'] = _T("site_contact_err_comment_blank");

	// Set the captcha Error Messages (check_captcha())
		define('ERROR_MESSAGE_CAPTCHA'			, _T("site_contact_err_captcha_blank"));
		define('ERROR_MESSAGE_CAPTCHA_INVALID'	, _T("site_contact_err_captcha_invalid"));
		define('ERROR_MESSAGE_CAPTCHA_COOKIE'	, _T("site_contact_err_captcha_cookie"));

	// check for captcha errors
		require CFLIBPATH.'captcha.class.php';
		check_captcha($_POST['captcha'],false);
		if(isset($error_captcha)) $errors['captcha'] = $error_captcha;

	// if no errors send email
		if( !is_errors() ){

			if(strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) $ent = "\r\n";
			elseif(strtoupper(substr(PHP_OS, 0, 3) == 'MAC')) $ent = "\r";
			else $ent = "\n";

			$comment  = ' Name: '.$name.$ent.' E-mail: '.$email.$ent.' Comment: '.$ent.$comment;
			$boundary = '----=_NextPart_' . md5(rand());
			$headers  = 'From: ' . $name . '<' . $email . '>' . $ent;
			$headers .= 'X-Mailer: PHP/' . phpversion() . $ent;
			$headers .= 'MIME-Version: 1.0' . $ent;
			$headers .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . $ent . $ent;
			$message  = '--' . $boundary . $ent;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . $ent;
			$message .= 'Content-Transfer-Encoding: base64' . $ent . $ent;
			$message .= chunk_split(base64_encode($comment));

			ini_set('sendmail_from', $email);
			mail($settings['SET_CONTACT'], 'Email from contact form on '.$settings['SET_TITLE'], strip_tags(html_entity_decode($message)), $headers);
			$emailSent = true;
		}
	}


// header page var
	$pageSet['id'] = 'contact_page';
	$pageSet['title'] =  ' - '._T("site_menu_contact");
	$pageSet['description'] = _T("site_menu_contact").' page for  '.$settings['SET_TITLE'];
	$pageSet['keywords'] = '';

	$captcha_light = (theme_setting('captcha_light')?'&amp;bg':'');

 // load header
	require CFROOTPATH.'header.php';
?>
		<div class="contentBox">
			<div id="contact">
				<h2><?php echo _T("site_menu_contact");?></h2>
				<div class="contact_box">
<?php if($emailSent){ // if email is sent say thanks ?>
				<p class="teaser"><?php echo _T("site_contact_thank_you",$name);?></p>
<?php }else{ // show contact form ?>
				<p class="teaser"><?php echo _T("site_contact_des",'<a href="faq.php" title="'._T("site_faq_title").'">'._T("site_faq_title").'</a>');?></p>
				<div id="form">
					<form method="post" action="contact.php">
						<?php echo is_errors('name');?>
						<div class="code_box">
							<label for="name"><?php echo _T("site_contact_form_name");?></label>
							<input name="name" type="text" id="name" class="text_input" size="24" value="<?php echo err_ReSet('name');?>" />
						</div>
						<?php echo is_errors('email');?>
						<div class="code_box">
							<label for="email"><?php echo _T("site_contact_form_email");?></label>
							<input name="email" type="text" id="email" class="text_input" size="24" value="<?php echo err_ReSet('email');?>" /><br/>
						</div>
						<?php echo is_errors('comment');?>
						<div class="code_box contact">
							<label for="comment"><?php echo _T("site_contact_form_comment");?></label>
							<textarea name="comment" id="comment" rows="8" cols="5" class="text_input long"><?php echo err_ReSet('comment');?></textarea>
						</div>
						<?php echo is_errors('captcha');?>
						<div class="code_box">
							<label for="captcha"><?php echo _T("site_contact_form_captcha");?></label>
							<input name="captcha" type="text" id="captcha" size="24" class="text_input" />
						</div>
						<div class="code_box"><label><?php echo _T("site_contact_form_captcha_img");?></label><a href="#Reload Captcha" onclick="document.getElementById('captchaImg').src = 'img/captcha.img.php?img<?php echo $captcha_light;?>&amp' + Math.random(); return false" class="creload"><img src="img/captcha.img.php?img<?php echo $captcha_light;?>&amp<?php echo time();?>" alt="captcha" title="<?php echo _T("site_contact_form_captcha_image_title");?>" class="captcha" id="captchaImg" /></a></div>
						<div class="code_box"><label></label><input type="submit" class="button" value="<?php echo _T("site_contact_form_send");?>" /></div>
					</form>
				</div>
<?php } // end show contact form/thanks  ?>
			</div>
			</div>
		</div>
<?php
	require CFROOTPATH.'footer.php';

	/***
	 * is_errors($name=null)
	 * Checks to see if $name has a error and if so returns the error in a html span
	 */
	function is_errors($name=null){
		global $errors;
		if(is_null($name) && count($errors)>0)return true;
		if(isset($errors[$name]))
			return '<span class="error_message">'.$errors[$name].'</span>';
		return '';
	}

	/***
	 * err_ReSet($name)
	 * Checks to see if there is a error and $name is not empty, if so returns $_POST[$name]
	 */
	function err_ReSet($name){
		global $errors;
		if(count($errors)>0 && isset($_POST[$name]))
			return cl($_POST[$name]);
		return '';
	}
