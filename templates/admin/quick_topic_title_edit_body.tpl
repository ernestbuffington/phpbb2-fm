{TOPIC_MENU}{FORUM_MENU}{VOTE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="JavaScript" type="text/javascript" src="../templates/js/colorpicker.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
var cp = new ColorPicker();
//-->
</script>

<h1>{ADMIN_TITLE}</h1>

<p>{ADMIN_TITLE_EXPLAIN}</p>

<table align="center" width="100%" class="forumline" cellpadding="4" cellspacing="1" align="center"><form action="{S_TITLE_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">&nbsp;{ADMIN_TITLE}&nbsp;</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></dh>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_TITLE}: *</b></td>
	<td class="row2"><input class="post" type="text" name="title_info" size="35" maxlength="255" value="{TITLE_INFO}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PERM_INFO}:</b><br /><span class="gensmall">{L_PERM_EXPLAIN}</span></td>
	<td class="row2">&nbsp;<input type="checkbox" name="admin_auth"{ADMIN_CHECKED}/> {ADMIN}<br />&nbsp;<input type="checkbox" name="supermod_auth"{SUPERMOD_CHECKED}/> {SUPERMOD}<br />&nbsp;<input type="checkbox" name="mod_auth"{MOD_CHECKED}/> {MODERATOR}<br />&nbsp;<input type="checkbox" name="poster_auth"{POSTER_CHECKED}/> {POSTER}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DATE_FORMAT}:</b><br /><span class="gensmall">{L_DATE_FORMAT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="date_format" size="15" maxlength="255" value="{DATE_FORMAT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_TITLE_POS}:</b><br /><span class="gensmall">{L_TITLE_POS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="title_pos" value="0"{S_POS_RIGHT} /> {L_RIGHT}&nbsp;&nbsp;<input type="radio" name="title_pos" value="1"{S_POS_LEFT} /> {L_LEFT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_COLOR_INFO}:</b><br /><span class="gensmall">{L_COLOR_INFO_EXPLAIN}</span></td>
	<td class="row2">#<input class="post" type="text" name="info_color" size="8" maxlength="6" value="{COLOR_INFO}" />
	<!-- BEGIN no_info_color -->
	<input class="post" type="text" size="1" style="background-color:#{COLOR_INFO}" title="{COLOR_INFO}" disabled="yes" />
	<!-- END no_info_color -->
	<a href="javascript:cp.select(document.forms[0].info_color,'pick');" name="pick" id="pick"><img src="{I_PICK_COLOR}" width="9" height="9" alt="" title="" /></a>
	</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Quick Title Edition 1.5.1a &copy; 2003, {COPYRIGHT_YEAR} <a href="mailto:xavier@2037.biz" class="copyright" target="_blank">Xavier Olive</a></div>

<script language="JavaScript" type="text/javascript">
<!--
cp.writeDiv()
//-->
</script>