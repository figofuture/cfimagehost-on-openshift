<?php if(!defined('cfih_i') or !cfih_i) die('This file cannot be directly accessed.');
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
	// Check for extension
			echo '<div class="ibox"><h2>Checking for PHP Extensions</h2>';

		// GD
			if (!extension_loaded('gd') || !function_exists('gd_info')) {
				user_note('error: You need the GD extension installed for this script work. <a href="http://php.net/manual/en/book.image.php">http://php.net/manual/en/book.image.php</a>',1);
				$Err_found = true;
			}else{
				user_note('GD extension looks to be installed',2);
			}
			flushNow();

		//cURL
			if (!extension_loaded("curl") || !function_exists('curl_init')){
				user_note('error: cURL extension is not installed. So we have tune off url shortening service, if you install this extension at a later date you can tune it on in settings. <a href="http://php.net/manual/en/book.curl.php">http://php.net/manual/en/book.curl.php</a>',1);
				$settings['SET_SHORT_URL_ON'] = 0;
			}else{
				user_note('cURL extension looks to be installed<br/>',2);
			}
			flushNow();

			echo  '</div>';
			flushNow();


	// setup folders
		if(!$Err_found){
			echo  '<div class="ibox"><h2>Checking folders</h2>';

		// upload folder
                        if (!is_dir(CFUPLOADPATH)){
                                $res=mkdir(CFUPLOADPATH,0777,true);
                        }
			if(rmkdir(CFUPLOADPATH,false)){
				user_note(CFUPLOADPATH.' - ok',2);
				flushNow();
			}
                        if (!is_dir(CFIMAGEPATH)){
                                $res=mkdir(CFIMAGEPATH,0777,true);
                        }
			if(rmkdir(CFIMAGEPATH)){
				user_note(CFIMAGEPATH.' - ok',2);
				flushNow();
			}
                        if (!is_dir(CFTHUMBPATH)){
                                $res=mkdir(CFTHUMBPATH,0777,true);
                        }
			if(rmkdir(CFTHUMBPATH)){
				user_note(CFTHUMBPATH.' - ok',2);
				flushNow();
			}
                        if (!is_dir(CFSMALLTHUMBPATH)){
                                $res=mkdir(CFSMALLTHUMBPATH,0777,true);
                        }
			if(rmkdir(CFSMALLTHUMBPATH)){
				user_note(CFSMALLTHUMBPATH.' - ok',2);
				flushNow();
			}
                        if (!is_dir(CFDATAPATH)){
                                $res=mkdir(CFDATAPATH,0777,true);
                        }
			if(rmkdir(CFDATAPATH)){
				user_note(CFDATAPATH.' - ok',2);
				flushNow();
			}
                        if (!is_dir(CFBANDWIDTHPATH)){
                                $res=mkdir(CFBANDWIDTHPATH,0777,true);
                        }
			if(rmkdir(CFBANDWIDTHPATH)){
				user_note(CFBANDWIDTHPATH.' - ok',2);
				flushNow();
			}
                        if (!is_dir(CFIMGCACHEPATH)){
                                $res=mkdir(CFIMGCACHEPATH,0777,true);
                        }
			if(rmkdir(CFIMGCACHEPATH)){
				user_note(CFIMGCACHEPATH.' - ok',2);
				flushNow();
			}
                        if (!is_dir(CFIMGTEMPPATH)){
                                $res=mkdir(CFIMGTEMPPATH,0777,true);
                        }
			if(rmkdir(CFIMGTEMPPATH)){
				user_note(CFIMGTEMPPATH.' - ok',2);
				flushNow();
			}
                        if (!is_dir(CFBULKUPLOADPATH)){
                                $res=mkdir(CFBULKUPLOADPATH,0777,true);
                        }
			if(rmkdir(CFBULKUPLOADPATH)){
				user_note(CFBULKUPLOADPATH.' - ok',2);
				flushNow();
			}

			if(rmkdir(CFINCPATH)){
				user_note(CFINCPATH.' - ok',2);
				flushNow();
			}
                        if (!is_dir(CFBACKUPPATH)){
                                $res=mkdir(CFBACKUPPATH,0777,true);
                        }
			if(rmkdir(CFBACKUPPATH)){
				user_note(CFBACKUPPATH.' - ok',2);
				flushNow();
			}

			echo '</div>';
			flushNow();
		}

	// Check for extension
		if(!$Err_found){
			echo '<form method="post" action="?i=db" class="ins">
				<input type="submit" value="Next Database Checks" class="button" name="checks" />
			</form>';
		}else{
			echo '<div class="ibox">';
			user_note('please Fix the errors above before moving on',1);
			echo '</div>';
		}

