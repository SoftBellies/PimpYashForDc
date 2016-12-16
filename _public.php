<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of YASH3, a plugin for DotClear2.
# Copyright (c) 2016 Gnieark https://blog-du-grouik.tinad.fr
#
# Forked and pimped from  Yash Copyright (c) 2008 Pep and contributors 
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

class dcYASH
{
	public static function publicHeadContent()
	{
		global $core;

		$core->blog->settings->addNamespace('yash');
		if ($core->blog->settings->yash->yash_active)
		{
			$custom_css = $core->blog->settings->yash->yash_custom_css;
			if (!empty($custom_css)) {
				if (strpos('/',$custom_css) === 0) {
					$css = $custom_css;
				}
				else {
					$css =
						$core->blog->settings->system->themes_url."/".
						$core->blog->settings->system->theme."/".
						$custom_css;
				}
			}
			else {
				$theme = (string)$core->blog->settings->yash->yash_theme;
				if ($theme == '') {
					$css = $core->blog->getPF('yash3/syntaxhighlighter/css/shThemeDefault.css');
				} else {
					$css = $core->blog->getPF('yash3/syntaxhighlighter/css/shTheme'.$theme.'.css');
				}
			}
			echo
				dcUtils::cssLoad($core->blog->getPF('yash3/syntaxhighlighter/css/shCore.css')).
				dcUtils::cssLoad($css);
		}
	}

	public static function publicFooterContent()
	{
		global $core;

		$core->blog->settings->addNamespace('yash');
		if ($core->blog->settings->yash->yash_active){
			//to do differents files if  dotclear is path info or not
			echo dcUtils::jsLoad($core->blog->getPF('yash3/syntaxhighlighter/js/shALLMinified.js'));
		}
	}
}
