<table width="100%" cellspacing="0" cellpadding="2">
  <tr>
	<td><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
  	<form name="search" action="album_search.php">
	<td align="right"><span class="gensmall">{L_SEARCH}:&nbsp;
	<select name="mode">
		<option>{L_USERNAME}</option>
		<option>{L_PIC_TITLE}</option>
		<option>{L_PIC_DESC}</option>
	</select>
	&nbsp;{L_THAT_CONTAINS}:&nbsp; 
	<input class="post" type="text" name="search" maxlength="20">&nbsp;<input type="submit" class="liteoption" value="{L_GO}">
	</span></td>
	</form>
  </tr>
</table>

<table width="100%" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
	<th width="60%" class="thCornerL" height="25" nowrap="nowrap">&nbsp;{L_CATEGORY}&nbsp;</th>
	<th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_PICS}&nbsp;</th>
	<th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_COMMENTS}&nbsp;</th>
	<th width="15%" class="thTop" nowrap="nowrap">&nbsp;{L_LAST_COMMENT}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_LAST_PIC}&nbsp;</th>
  </tr>
  <tr>
	<td class="catLeft" colspan="5"><span class="cattitle">{L_PUBLIC_CATS}</span></td>
  </tr>
  <!-- BEGIN catrow -->
  <tr>
	<td class="row1" height="50" onMouseOver="this.style.backgroundColor='{T_TR_COLOR2}'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="{T_TR_COLOR1}" onclick="window.location.href='{catrow.U_VIEW_CAT}'"><span class="forumlink"> <a href="{catrow.U_VIEW_CAT}" class="forumlink">{catrow.CAT_TITLE}</a><br />
	  </span> <span class="genmed">{catrow.CAT_DESC}<br />
	  {catrow.SLIDE_CAT}
	  </span><span class="gensmall">{catrow.L_MODERATORS} {catrow.MODERATORS}</span></td>
	<td class="row2" align="center"><span class="gensmall">{catrow.PICS}</span></td>
	<td class="row1" align="center"><span class="gensmall">{catrow.COMMENTS}</span></td>
	<td class="row2" align="center" nowrap="nowrap"><span class="gensmall">{catrow.LAST_COMMENT_INFO}</span></td>
	<td class="row1" align="center" nowrap="nowrap"><span class="gensmall">{catrow.LAST_PIC_INFO}</span></td>
  </tr>
  <!-- END catrow -->
  <tr>
	<td class="catBottom" colspan="5"><span class="cattitle"><a href="{U_USERS_PERSONAL_GALLERIES}" class="cattitle">{L_USERS_PERSONAL_GALLERIES}</a>&nbsp;&raquo;&nbsp;<a href="{U_YOUR_PERSONAL_GALLERY}" class="cattitle">{L_YOUR_PERSONAL_GALLERY}</a></span></td>
  </tr>
</table>
<br />

<!-- BEGIN recent_pics_block -->
<table width="100%" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
	<th class="thTop" colspan="{S_COLS}" nowrap="nowrap">{L_RECENT_PUBLIC_PICS}</th>
  </tr>
  <!-- BEGIN no_pics -->
  <tr>
	<td class="row1" align="center" colspan="{S_COLS}" height="50"><span class="gen">{L_NO_PICS}</span></td>
  </tr>
  <!-- END no_pics -->
  <!-- BEGIN recent_pics -->
  <tr>
  <!-- BEGIN recent_col -->
	<td class="row1" width="{S_COL_WIDTH}" align="center"><a href="{recent_pics_block.recent_pics.recent_col.U_PIC}" {TARGET_BLANK}><img src="{recent_pics_block.recent_pics.recent_col.THUMBNAIL}" alt="{recent_pics_block.recent_pics.recent_col.DESC}" title="{recent_pics_block.recent_pics.recent_col.DESC}" vspace="10" /></a></td>
  <!-- END recent_col -->
  </tr>
  <tr>
  <!-- BEGIN recent_detail -->
    	<td class="row2"><span class="gensmall">
	<b>{L_PIC_TITLE}:</b> {recent_pics_block.recent_pics.recent_detail.TITLE}<br />
  	<b>{L_POSTER}:</b> {recent_pics_block.recent_pics.recent_detail.POSTER}<br />
	<b>{L_POSTED}:</b> {recent_pics_block.recent_pics.recent_detail.TIME}<br />
  	<b>{L_VIEW}:</b> {recent_pics_block.recent_pics.recent_detail.VIEW}<br />
	{recent_pics_block.recent_pics.recent_detail.RATING}{recent_pics_block.recent_pics.recent_detail.IP}
	</span></td>
  <!-- END recent_detail -->
  </tr>
  <!-- END recent_pics -->
</table>
<br />
<!-- END recent_pics_block -->

<!-- BEGIN highest_pics_block -->
<table width="100%" cellpadding="2" cellspacing="1" class="forumline"> 
  <tr> 
   <th class="thTop" colspan="{S_COLS}" nowrap="nowrap">{L_HIGHEST_RATED_PICS}</th> 
  </tr> 
  <!-- BEGIN no_pics --> 
  <tr> 
   <td class="row1" align="center" colspan="{S_COLS}" height="50"><span class="gen">{L_NO_PICS}</span></td> 
  </tr> 
  <!-- END no_pics --> 
  <!-- BEGIN highest_pics --> 
  <tr> 
  <!-- BEGIN highest_col --> 
   <td class="row1" width="{S_COL_WIDTH}" align="center"><a href="{highest_pics_block.highest_pics.highest_col.U_PIC}" {TARGET_BLANK}><img src="{highest_pics_block.highest_pics.highest_col.THUMBNAIL}" alt="{highest_pics_block.highest_pics.highest_col.DESC}" title="{highest_pics_block.highest_pics.highest_col.DESC}" vspace="10" /></a></td> 
  <!-- END highest_col --> 
  </tr> 
  <tr> 
  <!-- BEGIN highest_detail --> 
    	<td class="row2"><span class="gensmall">
	<b>{L_PIC_TITLE}:</b> {highest_pics_block.highest_pics.highest_detail.H_TITLE}<br /> 
     	<b>{L_POSTER}:</b> {highest_pics_block.highest_pics.highest_detail.H_POSTER}<br />
	<b>{L_POSTED}:</b> {highest_pics_block.highest_pics.highest_detail.H_TIME}<br /> 
     	<b>{L_VIEW}:</b> {highest_pics_block.highest_pics.highest_detail.H_VIEW}<br />
	{highest_pics_block.highest_pics.highest_detail.H_RATING}{highest_pics_block.highest_pics.highest_detail.H_IP}
	</span></td> 
  <!-- END highest_detail --> 
  </tr> 
  <!-- END highest_pics --> 
</table> 
<br />
<!-- END highest_pics_block -->

<!-- BEGIN random_pics_block -->
<table width="100%" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
	<th class="thTop" colspan="{S_COLS}" nowrap="nowrap">{L_RANDOM_PICS}</th>
  </tr>
  <!-- BEGIN no_pics -->
  <tr>
	<td class="row1" align="center" colspan="{S_COLS}" height="50"><span class="gen">{L_NO_PICS}</span></td>
  </tr>
  <!-- END no_pics -->
  <!-- BEGIN rand_pics -->
  <tr>
  <!-- BEGIN rand_col -->
	<td class="row1" width="{S_COL_WIDTH}" align="center"><a href="{random_pics_block.rand_pics.rand_col.U_PIC}" {TARGET_BLANK}><img src="{random_pics_block.rand_pics.rand_col.THUMBNAIL}" alt="{random_pics_block.rand_pics.rand_col.DESC}" title="{random_pics_block.rand_pics.rand_col.DESC}" vspace="10" /></a></td>
  <!-- END rand_col -->
  </tr>
  <tr>
  <!-- BEGIN rand_detail -->
    	<td class="row2"><span class="gensmall">
	<b>{L_PIC_TITLE}:</b> {random_pics_block.rand_pics.rand_detail.TITLE}<br />
  	<b>{L_POSTER}:</b> {random_pics_block.rand_pics.rand_detail.POSTER}<br />
	<b>{L_POSTED}:</b> {random_pics_block.rand_pics.rand_detail.TIME}<br />
  	<b>{L_VIEW}:</b> {random_pics_block.rand_pics.rand_detail.VIEW}<br />
	{random_pics_block.rand_pics.rand_detail.RATING}{random_pics_block.rand_pics.rand_detail.IP}
	</span></td>
  <!-- END rand_detail -->
  </tr>
  <!-- END rand_pics -->
</table>
<br />
<!-- END random_pics_block -->

<!-- BEGIN switch_user_logged_out -->
<form method="post" action="{S_LOGIN_ACTION}">
  <table width="100%" cellpadding="3" cellspacing="1" class="forumline">
	<tr>
	  <td class="catHead" height="28"><a name="login"></a><span class="cattitle">{L_LOGIN_LOGOUT}</span></td>
	</tr>
	<tr>
	  <td class="row1" align="center" height="28"><span class="gensmall">{L_USERNAME}: <input class="post" type="text" name="username" size="10" maxlength="{LIMIT_USERNAME_MAX_LENGTH}" />&nbsp;&nbsp;&nbsp;
		{L_PASSWORD}: <input class="post" type="password" name="password" size="10" maxlength="32" />&nbsp;&nbsp; &nbsp;&nbsp;
		<!-- BEGIN switch_allow_autologin -->
		{L_AUTO_LOGIN} <input class="text" type="checkbox" name="autologin"{AUTOLOGIN_CHECKED} />&nbsp;&nbsp;&nbsp;
		<!-- END switch_allow_autologin -->
		<input type="submit" class="mainoption" name="login" value="{L_LOGIN}" /><input type="hidden" name="redirect" value="{U_ALBUM}" /></span></td>
	</tr>
  </table>
</form>
<!-- END switch_user_logged_out -->
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>

