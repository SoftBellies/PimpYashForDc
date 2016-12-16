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

if (!defined('DC_RC_PATH')) { return; }

$__autoload['yash3Behaviors'] = dirname(__FILE__).'/inc/yash3.behaviors.php';

$core->addBehavior('coreInitWikiPost',array('yash3Behaviors','coreInitWikiPost'));
