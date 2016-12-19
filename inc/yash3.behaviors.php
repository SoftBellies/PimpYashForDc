<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of yash3, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul and contributors
# carnet.franck.paul@gmail.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

class yash3Behaviors
{
	public static function adminPostEditor($editor='',$context='',array $tags=array(),$syntax='')
	{
		global $core;

		if ($editor != 'dcLegacyEditor') return;

		return
		dcPage::jsLoad(urldecode(dcPage::getPF('yash3/js/post.js')),$core->getVersion('yash')).
		'<script type="text/javascript">'."\n".
		"//<![CDATA[\n".
		dcPage::jsVar('jsToolBar.prototype.elements.yash3.title',__('Highlighted Code')).
		"\n//]]>\n".
		"</script>\n";
	}

	public static function coreInitWikiPost($wiki2xhtml)
	{
		$wiki2xhtml->registerFunction('macro:yash3',array('yash3Behaviors','transform'));
	}

	public static function transform($text,$args)
	{
		$text = trim($text);
		$real_args = explode(' ',$args);
		$class = empty($real_args[1])?'plain':$real_args[1];
		return '<pre class="brush: '.$class.'">'.htmlspecialchars($text).'</pre>';
	}
}
