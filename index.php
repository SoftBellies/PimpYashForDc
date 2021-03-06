<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of YASH3, a plugin for DotClear2.
# Forked by (c) Gnieark https://blog-du-grouik.tinad.fr 2016
# licensed as GPL V2
#
# Original dev is: Yash Copyright (c) 2008 Pep and contributors. All rights
# reserved.
#
# This plugin is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This plugin is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this plugin; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#
# ***** END LICENSE BLOCK *****

if (!defined('DC_CONTEXT_ADMIN')) { exit; }

// Setting default parameters if missing configuration
$core->blog->settings->addNamespace('yash3');
if (is_null($core->blog->settings->yash3->yash3_active)) {
	try {
		// Default state is active if the comments are configured to allow wiki syntax
		$core->blog->settings->yash3->put('yash3_active',false,'boolean',true);
		$core->blog->settings->yash3->put('yash3_theme','Default','string',true);
		$core->blog->settings->yash3->put('yash3_custom_css','','string',true);
		$core->blog->triggerBlog();
		http::redirect($p_url);
	}
	catch (Exception $e) {
		$core->error->add($e->getMessage());
	}
}

// Getting current parameters
$active = (boolean)$core->blog->settings->yash3->yash3_active;
$theme = (string)$core->blog->settings->yash3->yash3_theme;
$customCss = (string)$core->blog->settings->yash3->yash3_custom_css;

if (!empty($_REQUEST['popup'])) {
	$yash3_brushes = array(
		'plain' 		=> __('Plain Text'),
		'applescript'	=> __('AppleScript'),
		'as3'			=> __('ActionScript3'),
		'bash'			=> __('Bash/shell'),
		'cf'			=> __('ColdFusion'),
		'csharp'		=> __('C#'),
		'cpp'			=> __('C/C++'),
		'css'			=> __('CSS'),
		'delphi'		=> __('Delphi'),
		'diff'			=> __('Diff/Patch'),
		'erl'			=> __('Erlang'),
		'groovy'		=> __('Groovy'),
		'haxe'			=> __('Haxe'),
		'js'			=> __('Javascript/JSON'),
		'java'			=> __('Java'),
		'jfx'			=> __('JavaFX'),
		'pl'			=> __('Perl'),
		'php'			=> __('PHP'),
		'ps'			=> __('PowerShell'),
		'python'		=> __('Python'),
		'ruby'			=> __('Ruby'),
		'sass'			=> __('SASS'),
		'scala'			=> __('Scala'),
		'sql'			=> __('SQL'),
		'tap'			=> __('Tap'),
		'ts'			=> __('TypeScript'),
		'vb'			=> __('Visual Basic'),
		'xml' 			=> __('XML/XSLT/XHTML/HTML'),
		'yaml'			=> __('Yaml')
	);

	echo
		'<html>'.
		'<head>'.
	  	'<title>'.__('YASH - Syntax Selector').'</title>'.
	  	dcPage::jsLoad(urldecode(dcPage::getPF('yash3/js/popup.js')),$core->getVersion('yash3')).
		'</head>'.
		'<body>'.
		'<h2>'.__('YASH - Syntax Selector').'</h2>'.
		'<form id="yash3-form" action="'.$p_url.'&amp;popup=1" method="get">'.
		'<p><label>'.__('Select the primary syntax of your code snippet.').
		form::combo('syntax',array_flip($yash3_brushes)).'</label></p>'.
		'<p><a id="yash3-cancel" class="button" href="#">'.__('Cancel').'</a> - '.
		'<strong><a id="yash3-ok" class="button" href="#">'.__('Ok').'</a></strong></p>'.
		'</form>'.
		'</body>'.
		'</html>';
	return;
}

// Saving new configuration
if (!empty($_POST['saveconfig'])) {
	try
	{
		$core->blog->settings->addNameSpace('yash3');
		$active = (empty($_POST['active'])) ? false : true;
		$theme = (empty($_POST['theme'])) ? 'Default' : $_POST['theme'];
		$customCss = (empty($_POST['customCss'])) ? '' : $_POST['customCss'];
		$core->blog->settings->yash3->put('yash3_active',$active,'boolean');
		$core->blog->settings->yash3->put('yash3_theme',$theme,'string');
		$core->blog->settings->yash3->put('yash3_custom_css',$customCss,'string');
		
		
		$new_concat_version = (integer)$core->blog->settings->yash3->yash3_concat_version + 1;
		
		$cssPreviousFileRealPath = path::real(DC_VAR)."/yash3/".
					    $core->blog->id."/".
					    $core->blog->settings->yash3->yash3_concat_version.
					    ".css";
		$cssFutureFileRealPath = path::real(DC_VAR)."/yash3/".
					    $core->blog->id."/".
					    ((integer)$core->blog->settings->yash3->yash3_concat_version + 1).
					    ".css";
		$jsPreviousFileRealPath = path::real(DC_VAR)."/yash3/".
					    $core->blog->id."/".
					    $core->blog->settings->yash3->yash3_concat_version.
					    ".js";
		$jsFutureFileRealPath = path::real(DC_VAR)."/yash3/".
					    $core->blog->id."/".
					    ((integer)$core->blog->settings->yash3->yash3_concat_version + 1).
					    ".js";				
						
		if(!is_dir(dirname($jsFutureFileRealPath))){
		  //probably the first use of the plugin, the pach does no exists. create it:
		  files::makeDir(dirname($jsFutureFileRealPath),true);
		}
		
		
		//Generate the CSS concatened
		if(file_exists($cssPreviousFileRealPath)){
		  //delete It
		  unlink($cssPreviousFileRealPath);
		}
		$custom_css = $core->blog->settings->yash3->yash3_custom_css;
		if (!empty($custom_css)) {	
		  $fContent = $custom_css;
		}
		else {
			$theme = (string)$core->blog->settings->yash3->yash3_theme;
			if ($theme == '') {
				$fContent = file_get_contents(dirname(__FILE__)."/syntaxhighlighter/css/shThemeDefault.css");
			} else {
				$fContent = file_get_contents(dirname(__FILE__)."/syntaxhighlighter/css/shTheme".$theme.".css");
			}
		}
		
		$fContent = file_get_contents(dirname(__FILE__)."/syntaxhighlighter/css/shCore.css")."\n".
			    $fContent;
	
		// Remove comments
		$fContent = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $fContent);
		// Remove space after colons
		$fContent = str_replace(': ', ':', $fContent);
		// Remove whitespace
		$fContent = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $fContent);

		//create the file
		file_put_contents( $cssFutureFileRealPath, $fContent );
		
	
		//Generate the JS
		if(file_exists($jsPreviousFileRealPath)){
		  //delete It
		  unlink($jsPreviousFileRealPath);
		}
		include_once(dirname(__FILE__).'/inc/yash3JSMinifier.php');
		$fContent = yash3JSMinifier::minify(
			      file_get_contents(dirname(__FILE__)."/syntaxhighlighter/js/shCore.js").
			      file_get_contents(dirname(__FILE__)."/syntaxhighlighter/js/shAutoloader.js").
			      "\n var yash3_path=\"".$core->blog->getPF('yash3/syntaxhighlighter/js/')."\";\n".
			      file_get_contents(dirname(__FILE__)."/js/public.js")
			    );
		//write the file
		file_put_contents($jsFutureFileRealPath,$fContent);
		
		
		
		//update concat version
		$core->blog->settings->yash3->put('yash3_concat_version',$new_concat_version,'integer');
		$core->blog->triggerBlog();
		dcPage::addSuccessNotice(__('Configuration successfully updated.'));
		http::redirect($p_url);
	}
	catch (Exception $e)
	{
		$core->error->add($e->getMessage());
	}
}
?>
<html>
<head>
	<title><?php echo __('YASH3'); ?></title>
	
	<script type="text/javascript">
	//I hate jquery
	$(document).ready(function(){
	  $("#theme").change(function(){;
	    $(this).find("option:selected").each(function(){
	      if($(this).attr("value")=="Custom"){
		  $("#pcustomcss").show();
	      }else{
		  $("#pcustomcss").hide();
	      }
	    });
	  }).change();
	 });
	</script>
	
	
</head>

<body>
<?php
	echo dcPage::breadcrumb(
		array(
			html::escapeHTML($core->blog->name) => '',
			__('YASH3') => ''
		));
echo dcPage::notices();

$combo_theme = array(
	__('Default')         	=> 'Default',
	__('Custom css')	=> 'Custom',
	__('Django')          	=> 'Django',
	__('Eclipse')         	=> 'Eclipse',
	__('Emacs')           	=> 'Emacs',
	__('Fade to gray')    	=> 'FadeToGrey',
	__('Material')        	=> 'Material',
	__('MD Ultra')        	=> 'MDUltra',
	__('Midnight')        	=> 'Midnight',
	__('RDark')           	=> 'RDark',
	__('Solarized Dark')  	=> 'SolarizedDark',
	__('Solarized Light') 	=> 'SolarizedLight',
	__('Tomorrow Night')  	=> 'TomorrowNight'
	);
?>

<div id="yash3_options">
	<form method="post" action="<?php http::getSelfURI(); ?>">
		<p>
			<?php echo form::checkbox('active', 1, $active); ?>
			<label class="classic" for="active">&nbsp;<?php echo __('Enable YASH');?></label>
		</p>

		<h3><?php echo __('Options'); ?></h3>
		<p class="field"><label for="theme" class="classic"><?php echo __('Theme:'); ?> </label>
			<?php echo form::combo('theme',$combo_theme,$theme); ?>
		</p>
		<p class="field" id="pcustomcss">
			<label for="custom_css" class="classic"><?php echo __('Use custom CSS:') ; ?> </label>
			<?php echo form::textarea('customCss',80,20, $customCss); ?>
		</p>
		<p class="info">
			<?php echo __('You can use a custom CSS. Select custom CSS and paste it on the textarea'); ?>
		</p>

		<p><input type="hidden" name="p" value="yash3" />
			<?php echo $core->formNonce(); ?>
			<input type="submit" name="saveconfig" value="<?php echo __('Save configuration'); ?>" />
		</p>
	</form>
</div>
</body>
</html>