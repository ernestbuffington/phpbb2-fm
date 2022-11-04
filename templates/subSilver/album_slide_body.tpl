<table width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td><a class="maintitle" href="{U_VIEW_CAT}">{CAT_TITLE}</a><br /><span class="gensmall"><b>{L_MODERATORS}: {MODERATORS}</b></span></td>
	<td align="right" valign="bottom" class="nav" nowrap="nowrap">{PAGINATION}</td>
  </tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2"><form action="{S_ALBUM_SEARCH_ACTION}" method="post">
  <tr>
	<td><a href="{U_UPLOAD_PIC}"><img src="{UPLOAD_PIC_IMG}" alt="{L_UPLOAD_PIC}" title="{L_UPLOAD_PIC}" /></a></td>
	<td class="nav" width="100%">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{U_ALBUM}">{L_ALBUM}</a> -> <a class="nav" href="{U_VIEW_CAT}">{CAT_TITLE}</a></td>
	<td nowrap align="right"><span class="gensmall">{L_SEARCH}:&nbsp;
	<select name="mode">
		<option>{L_USERNAME}</option>
		<option>{L_PIC_TITLE}</option>
		<option>{L_PIC_DESC}</option>
	</select>
	&nbsp;{L_THAT_CONTAINS}:&nbsp; 
	<input class="post" type="text" name="search" maxlength="20">&nbsp;<input type="submit" class="liteoption" value="{L_GO}">
	</span></td>
  </tr>
</form></table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead" nowrap="nowrap">{L_CATEGORY} :: {CAT_TITLE}</th>
</tr>
<tr>
	<td class="row1"><marquee><table cellpadding="2" cellspacing="1" class="forumline">
  <!-- BEGIN no_pics -->
  <tr>
	<td class="row1" align="center" height="50"><span class="gen">{L_NO_PICS}</span></td>
  </tr>
  <!-- END no_pics -->
  <!-- prrtBEGIN picrow -->
  <tr>
  <!-- BEGIN piccol -->
	<td align="center" width="{S_COL_WIDTH}" class="row1"><span class="genmed"><a href="{piccol.U_PIC}" {TARGET_BLANK}><img src="{piccol.THUMBNAIL}" border="0" alt="{piccol.DESC}" title="{piccol.DESC}" vspace="10" /></a><br />{piccol.APPROVAL}</span></td>
  <!-- END piccol -->
  </tr>
  <tr>
  <!-- BEGIN pic_detail -->
	<td class="row2"><span class="gensmall">
	<b>{L_PIC_TITLE}:</b> {pic_detail.TITLE}<br />
	<b>{L_POSTER}:</b> {pic_detail.POSTER}<br />
	<b>{L_POSTED}:</b> {pic_detail.TIME}<br />
	<b>{L_VIEW}:</b> {pic_detail.VIEW}<br />
	{pic_detail.RATING}
	{pic_detail.COMMENTS}
	{pic_detail.IP}
	{pic_detail.EDIT}  {pic_detail.DELETE}  {pic_detail.LOCK}  {pic_detail.MOVE}<br /><br /></span>
	</td>
  <!-- END pic_detail -->
  </tr>
  <!-- prrtEND picrow -->
	</table></marquee>
	</td>
</tr>
  <tr>
	<form action="{S_ALBUM_ACTION}" method="post">
	<td class="catBottom" align="center">
		<span class="gensmall">{L_SELECT_SORT_METHOD}:
		<select name="sort_method">
			<option {SORT_TIME} value="pic_time">{L_TIME}</option>
			<option {SORT_PIC_TITLE} value="pic_title">{L_PIC_TITLE}</option>
			<option {SORT_USERNAME} value="username">{L_USERNAME}</option>
			<option {SORT_VIEW} value="pic_view_count">{L_VIEW}</option>
			{SORT_RATING_OPTION}
			{SORT_COMMENTS_OPTION}
			{SORT_NEW_COMMENT_OPTION}
		</select>
		&nbsp;{L_ORDER}:
		<select name="sort_order">
			<option {SORT_ASC} value="ASC">{L_ASC}</option>
			<option {SORT_DESC} value="DESC">{L_DESC}</option>
		</select>
		&nbsp;<input type="submit" name="submit" value="{L_GO}" class="liteoption" /></span>
	</td>
</form>
  </tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td><a href="{U_UPLOAD_PIC}"><img src="{UPLOAD_PIC_IMG}" alt="{L_UPLOAD_PIC}" title="{L_UPLOAD_PIC}" /></a></td>
	<td width="100%" class="nav">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{U_ALBUM}">{L_ALBUM}</a> -> <a class="nav" href="{U_VIEW_CAT}">{CAT_TITLE}</a></td>
	<td align="right" nowrap="nowrap" class="nav">{PAGINATION}</td>
  </tr>
  <tr>
	<td colspan="3" class="nav">{PAGE_NUMBER}</td>
  </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
	<td align="right" class="gensmall" nowrap="nowrap">{ALBUM_JUMPBOX}</td>
  </tr>
  <tr>
	<td align="right" class="gensmall">{S_AUTH_LIST}</td>
  </tr>
</table>
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>
