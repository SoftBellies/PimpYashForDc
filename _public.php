<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of YASH3, a plugin for DotClear2.
# Copyright (c) 2016 Gnieark https://blog-du-grouik.tinad.fr
#
# Forked and modified from  Yash Copyright (c) 2008 Pep and contributors 
# licensed under GNU/GPL license
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

if (!defined('DC_RC_PATH')) { return; }

$core->addBehavior('publicHeadContent',		array('dcYASH','publicHeadContent'));
$core->addBehavior('publicFooterContent',	array('dcYASH','publicFooterContent'));
$core->url->register('yash3','yash3','^yash3(?:/(.+))?$',array('dcYASH','getVarFile'));

class dcYASH
{
	public static function getVarFile($args)
	{
	  /*
	  * send the css or js file
	  * Don't take care about all path vars
	  */
	  global $core;
	  
	  list($osef,$ext) = explode(".",$args);
	  switch($ext){
	    case "css":
	      $ext = "css";
	      header("Content-type: text/css");
	      break;
	     case "js":
	      header('Content-Type: application/javascript');
	      $ext = "js";
	      break;
	    default:
	      throw new Exception ("Page not found",404);
	      break;
	  }
	  
	  $fileToSend = path::real(DC_VAR)."/yash3/".
			$core->blog->id."/".
			$core->blog->settings->yash3->yash3_concat_version.".".
			$ext;
	  if(file_exists($fileToSend)){
	    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (365*3600*24)));
	    echo file_get_contents($fileToSend);
	  }else{
	    throw new Exception ("Page not found",404);
	  }
	}
        public static function publicFooterContent()
        {
                global $core;
                $core->blog->settings->addNamespace('yash3');
                if ($core->blog->settings->yash3->yash3_active){
                    $cssURL = 	$core->blog->url.$core->url->getBase('yash3')."/".
                                $core->blog->settings->yash3->yash3_concat_version.
                                ".css";
                        echo "<noscript id=\"deferred-yash-style\">\n".
                             " <link rel=\"stylesheet\" type=\"text/css\" href=\"".$cssURL."\"/>\n".
                             "</noscript>\n".
                             "<script>\n".
                             "var loadYashDeferredStyles = function() {\n".
                             "  var addStylesNode = document.getElementById('deferred-yash-style');\n".
                             "  var replacement = document.createElement('div');\n".
                             "  replacement.innerHTML = addStylesNode.textContent;\n".
                             "  document.body.appendChild(replacement)\n".
                             "  addStylesNode.parentElement.removeChild(addStylesNode);\n".
                             "};\n".
                             "var raf = requestAnimationFrame || mozRequestAnimationFrame ||\n".
                             "  webkitRequestAnimationFrame || msRequestAnimationFrame;\n".
                             "if (raf) raf(function() { window.setTimeout(loadYashDeferredStyles, 0); });\n".
                             "else window.addEventListener('load', loadYashDeferredStyles);\n".
                             "</script>";
   
                        $jsURL = $core->blog->url.$core->url->getBase('yash3')."/".
                                 $core->blog->settings->yash3->yash3_concat_version.
                                 ".js";
                        echo '<script async src="'.$jsURL.'"></script>';
                }
        }

}
