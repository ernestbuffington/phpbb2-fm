<?php
/***************************************************************************
 *                             linkdb_constants
 *                            ------------------
 *   begin                : Thursday, Mar 2, 2003
 *   copyright            : (C) 2003 Mohd
 *   email                : mohdalbasri@hotmail.com
 *   Modified by CRLin
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
 
define('LINKDB_ROOT_CAT', 0);
define('LINK_PINNED', 1);

// Field Types
define('INPUT', 0);
define('TEXTAREA', 1);
define('RADIO', 2);
define('SELECT', 3);
define('SELECT_MULTIPLE', 4);
define('CHECKBOX', 5);

// Tables
define('LINKS_TABLE', $prefix.'links');
define('LINK_CATEGORIES_TABLE', $prefix.'link_categories');
define('LINK_CONFIG_TABLE', $prefix.'link_config');
define('LINK_VOTES_TABLE', $prefix.'link_votes');
define('LINK_COMMENTS_TABLE', $prefix.'link_comments');
define('LINK_CUSTOM_TABLE', $prefix.'link_custom');
define('LINK_CUSTOM_DATA_TABLE', $prefix.'link_customdata');

// Language and Templates path
define('LINKDB_LANG_PATH', "");
//define('LINKDB_LANG_PATH', "linkdb/");
define('LINKDB_TPL_PATH', "");
//define('LINKDB_TPL_PATH', "./../../linkdb/templates/" . $theme['template_name'] . "/");
?>