<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of YASH, a plugin for DotClear2.
# Copyright (c) 2008 Pep and contributors. All rights
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

if (!defined('DC_RC_PATH')) { return; }

$this->registerModule(
	/* Name */		"YASH Fork",
	/* Description*/	"Fork of Yet Another Syntax Highlighter. Modification: Juste one javascript called on public pages for yash3",
	/* Author */		"Forked and modified by Gnieark, origin Pep and contributors",
	/* Version */		'0.7.1',
	array(
		/* Dependencies */	'requires' =>		array(array('core','2.9')),
		/* Permissions */	'permissions' =>	'contentadmin',
		/* Priority */		'priority' =>		1001,	// Must be higher than dcLegacyEditor priority (ie 1000)
		/* Type */		'type' =>		'plugin'
	)
);
