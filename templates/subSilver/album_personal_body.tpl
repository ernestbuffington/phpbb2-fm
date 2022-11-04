<table width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td width="100%"><a class="maintitle" href="{U_PERSONAL_GALLERY}">{L_PERSONAL_GALLERY_OF_USER}</a><br />
	<span class="genmed">{L_PERSONAL_GALLERY_EXPLAIN}</span></td>
	<td align="right" valign="bottom" nowrap="nowrap"><span class="gensmall"><b>{PAGINATION}</b></span></td>
</tr>
</table>
<table width="100%" cellspacing="2" cellpadding="2"><form name="search" action="album_search.php">
<tr>
	<!-- BEGIN your_personal_gallery -->
	<td><a href="{U_UPLOAD_PIC}"><img src="{UPLOAD_PIC_IMG}" alt="{L_UPLOAD_PIC}" title="{L_UPLOAD_PIC}" /></a>&nbsp;&nbsp;&nbsp;</td>
	<!-- END your_personal_gallery -->
	<td class="nav" width="100%"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{U_ALBUM}">{L_ALBUM}</a> -> <a class="nav" href="{U_PERSONAL_GALLERY}">{L_PERSONAL_GALLERY_OF_USER}</a></span></td>
	<td nowrap align="right"><span class="gensmall">{L_SEARCH}:&nbsp; <select name="mode">
		<option>{L_USERNAME}</option>
		<option>{L_PIC_TITLE}</option>
		<option>{L_PIC_DESC}</option>
	</select> &nbsp;{L_THAT_CONTAINS}:&nbsp; <input class="post" type="text" name="search" maxlength="20" />&nbsp;<input type="submit" class="liteoption" value="{L_GO}"></span></td>
</tr>
</form></table>

<!-- BEGIN your_personal_gallery -->
{CPL_MENU_OUTPUT}
<!-- END your_personal_gallery -->

<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form action="{U_PERSONAL_GALLERY}" method="post">
<tr>
	<th class="thHead" colspan="{S_COLS}">{L_PERSONAL_GALLERY_OF_USER}</th>
</tr>
<!-- BEGIN no_pics -->
<tr>
	<td class="row1" height="50" align="center" colspan="{S_COLS}"><span class="gen">{L_PERSONAL_GALLERY_NOT_CREATED}</span></td>
</tr>
<!-- END no_pics -->
<!-- BEGIN picrow -->
<tr>
<!-- BEGIN piccol -->
	<td align="center" width="{S_COL_WIDTH}" class="row1"><span class="genmed"><a href="{picrow.piccol.U_PIC}" {TARGET_BLANK}><img src="{picrow.piccol.THUMBNAIL}" alt="{picrow.piccol.DESC}" title="{picrow.piccol.DESC}" vspace="10" /></a></span></td>
<!-- END piccol -->
<!-- BEGIN nopiccol -->
	<td align="center" width="{S_COL_WIDTH}" class="row1">&nbsp;</td>
<!-- END nopiccol -->
</tr>
<tr>
	<!-- BEGIN pic_detail -->
	<td class="row2"><span class="gensmall">
	<b>{L_PIC_TITLE}:</b> {picrow.pic_detail.TITLE}<br />
	<b>{L_POSTED}:</b> {picrow.pic_detail.TIME}<br />
	<b>{L_VIEW}:</b> {picrow.pic_detail.VIEW}<br />
	{picrow.pic_detail.RATING}
	{picrow.pic_detail.COMMENTS}
	{picrow.pic_detail.IP}
	{picrow.pic_detail.EDIT} {picrow.pic_detail.DELETE}{picrow.pic_detail.LOCK}</span>
	</td>
	<!-- END pic_detail -->
	<!-- BEGIN picnodetail -->
	<td class="row2">&nbsp;</td>
	<!-- END picnodetail -->
</tr>
<!-- END picrow -->
<tr>
	<td class="catBottom" colspan="{S_COLS}" align="center"><span class="genmed">{L_SELECT_SORT_METHOD}: <select name="sort_method">
		<option {SORT_TIME} value='pic_time'>{L_TIME}</option>
		<option {SORT_PIC_TITLE} value='pic_title'>{L_PIC_TITLE}</option>
		<option {SORT_VIEW} value='pic_view_count'>{L_VIEW}</option>
		{SORT_RATING_OPTION}
		{SORT_COMMENTS_OPTION}
		{SORT_NEW_COMMENT_OPTION}
	</select>&nbsp;{L_ORDER}: <select name="sort_order">
		<option {SORT_ASC} value='ASC'>{L_ASC}</option>
		<option {SORT_DESC} value='DESC'>{L_DESC}</option>
	</select> &nbsp;<input type="submit" name="submit" value="{L_SORT}" class="liteoption" /></span></td>
</tr>
</form></table>

<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<!-- BEGIN your_personal_gallery -->
	<td><a href="{U_UPLOAD_PIC}"><img src="{UPLOAD_PIC_IMG}" alt="{L_UPLOAD_PIC}" title="{L_UPLOAD_PIC}" /></a>&nbsp;&nbsp;&nbsp;</td>
	<!-- END your_personal_gallery -->
	<td width="100%"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{U_ALBUM}">{L_ALBUM}</a> -> <a class="nav" href="{U_PERSONAL_GALLERY}">{L_PERSONAL_GALLERY_OF_USER}</a></span></td>
	<td align="right" nowrap="nowrap"><span class="nav">{PAGINATION}</span></td>
  </tr>
  <tr>
	<td colspan="3"><span class="nav">{PAGE_NUMBER}</span></td>
  </tr>
</table>
<!-- BEGIN your_personal_gallery -->
	</td>
</tr>
</table>
<!-- END your_personal_gallery -->

<br />
<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td align="right">{JUMPBOX}</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Photo Album {ALBUM_VERSION} &copy; 2002, {COPYRIGHT_YEAR} <a href="http://smartor.is-root.com" class="copyright" target="_blank">Smartor</a></div>

