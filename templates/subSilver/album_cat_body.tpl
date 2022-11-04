<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td><a class="maintitle" href="{U_VIEW_CAT}">{CAT_TITLE}</a><br /><span class="gensmall"><b>{L_MODERATORS}: {MODERATORS}</b></span><br />&nbsp;</td>
	<td align="right" valign="bottom" class="nav" nowrap="nowrap">{PAGINATION}</td>
 </tr>
</table>

{WAITING}

<table width="100%" cellspacing="2" cellpadding="2"><form action="{S_ALBUM_SEARCH_ACTION}" method="post">
  <tr>
	<td><a href="{U_UPLOAD_PIC}"><img src="{UPLOAD_PIC_IMG}" alt="{L_UPLOAD_PIC}" title="{L_UPLOAD_PIC}"/></a>{SLIDE_PIC_IMG_SPACER}<a href="{U_SLIDE_PIC}"><img src="{SLIDE_PIC_IMG}" alt="{L_SLIDE_PIC}" title="{L_SLIDE_PIC}" /></a></td>
	<td width="100%" class="nav">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{U_ALBUM}">{L_ALBUM}</a> -> <a class="nav" href="{U_VIEW_CAT}">{CAT_TITLE}</a></td>
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
	<th class="thHead" colspan="{S_COLS}" nowrap="nowrap">{L_CATEGORY} :: {CAT_TITLE}</th>
  </tr>
  <!-- BEGIN no_pics -->
  <tr>
	<td class="row1" align="center" height="50"><span class="gen">{L_NO_PICS}</span></td>
  </tr>
  <!-- END no_pics -->
  <!-- BEGIN picrow -->
  <tr>
  <!-- BEGIN piccol -->
	<td align="center" width="{S_COL_WIDTH}" class="row1"><span class="genmed"><a href="{picrow.piccol.U_PIC}" {TARGET_BLANK}><img src="{picrow.piccol.THUMBNAIL}" alt="{picrow.piccol.DESC}" title="{picrow.piccol.DESC}" vspace="10" /></a><br />{picrow.piccol.APPROVAL}</span></td>
  <!-- END piccol -->
  <!-- BEGIN nopiccol -->
	<td align="center" width="{S_COL_WIDTH}" class="row1">&nbsp;</td>
  <!-- END nopiccol -->
  </tr>
  <tr>
  <!-- BEGIN pic_detail -->
	<td class="row2"><span class="gensmall">
	<b>{L_PIC_TITLE}:</b> {picrow.pic_detail.TITLE}<br />
	<b>{L_POSTER}:</b> {picrow.pic_detail.POSTER}<br />
	<b>{L_POSTED}:</b> {picrow.pic_detail.TIME}<br />
	<b>{L_VIEW}:</b> {picrow.pic_detail.VIEW}<br />
	{picrow.pic_detail.RATING}
	{picrow.pic_detail.COMMENTS}
	{picrow.pic_detail.IP}
	{picrow.pic_detail.EDIT} {picrow.pic_detail.DELETE}{picrow.pic_detail.LOCK}{picrow.pic_detail.MOVE}
	</span></td>
  <!-- END pic_detail -->
  <!-- BEGIN picnodetail -->
	<td class="row2">&nbsp;</td>
  <!-- END picnodetail -->
  </tr>
  <!-- END picrow -->
  <tr>
	<form action="{S_ALBUM_ACTION}" method="post">
	<td class="catBottom" colspan="{S_COLS}" align="center">
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
			<option {SORT_ASC} value="SC">{L_ASC}</option>
			<option {SORT_DESC} value="DESC">{L_DESC}</option>
		</select>
		&nbsp;<input type="submit" name="submit" value="{L_GO}" class="liteoption" /></span>
	</td>
	</form>
  </tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td><a href="{U_UPLOAD_PIC}"><img src="{UPLOAD_PIC_IMG}" alt="{L_UPLOAD_PIC}" title="{L_UPLOAD_PIC}" /></a>{SLIDE_PIC_IMG_SPACER}<a href="{U_SLIDE_PIC}"><img src="{SLIDE_PIC_IMG}" alt="{L_SLIDE_PIC}" title="{L_SLIDE_PIC}" /></a></td>
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