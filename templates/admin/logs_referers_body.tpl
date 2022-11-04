{LOG_MENU}{UTILS_MENU}{DB_MENU}{LANG_MENU}
</div></td>
<td valign="top" width="78%">

<h1>{L_HTTP_REFERERS_TITLE}</h1>

<p>{L_HTTP_REFERERS_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{U_CONFIG_ACTION}" method="post">	
<tr>
	<th class="thHead" colspan="2">{L_CONFIGURATION}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_ENABLE_REFERERS}:</b></td>
	<td class="row2"><input type="radio" name="enable_http_referrers" value="1" {ENABLE_REFERERS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_http_referrers" value="0" {ENABLE_REFERERS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="config" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />

<table width="100%" cellpadding="2" cellspacing="2" align="center">
<tr>
	<form action="{U_LIST_ACTION}" method="post">
	<td><input type="submit" onclick="return (confirm('{L_CONFIRM_DELETE_REFERERS}'));" value="{L_DELETE_ALL}" name="delete" class="liteoption" /></td>
	</form>
	<form action="{U_SHOW_URLS_ACTION}" method="post">
	<td><input type="submit" value="{L_DO_SHOW_URLS}" class="liteoption" /></td>
	</form>
	<form action="{U_LIST_ACTION}" method="post">
	<td width="100%" align="right" class="genmed">{L_SELECT_SORT_METHOD} <select name="sort">
		<option value="referer_host" {REFERER_SELECTED}>{L_REFERER}</option>
		<option value="referer_hits" {HITS_SELECTED}>{L_HITS}</option>
		<option value="referer_firstvisit" {FIRSTVISIT_SELECTED}>{L_FIRSTVISIT}</option>
		<option value="referer_lastvisit" {LASTVISIT_SELECTED}>{L_LASTVISIT}</option>
	</select> {L_ORDER} <select name="order">
		<option value="" {ASC_SELECTED}>{L_SORT_ASCENDING}</option>
		<option value="DESC" {DESC_SELECTED}>{L_SORT_DESCENDING}</option>
	</select>
	<!-- BEGIN switch_show_ref_urls -->
	<input type="hidden" name="mode" value="showurls">
	<!-- END switch_show_ref_urls -->
	<input type="submit" value="{L_SORT}" class="liteoption" /></td>
	</form>
</tr>
</table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thCornerL">&nbsp;{L_REFERER}&nbsp;</th>
	<!-- BEGIN switch_show_ref_urls -->
	<th class="thTop">&nbsp;{L_REFERER_URL}&nbsp;</th>
	<th class="thTop">&nbsp;{L_REFERER_IP}&nbsp;</th>
	<!-- END switch_show_ref_urls -->
	<th class="thTop">&nbsp;{L_FIRSTVISIT}&nbsp;</th>
	<th class="thTop">&nbsp;{L_LASTVISIT}&nbsp;</th>
	<th class="thTop">&nbsp;{L_HITS}&nbsp;</th>
	<th class="thCornerR" width="15%">&nbsp;{L_ACTION}&nbsp;</th>
</tr>
<!-- BEGIN refererrow_with_ref_urls -->
<tr>
	<td class="{refererrow_with_ref_urls.ROW_CLASS}" nowrap="nowrap"><span class="gensmall"><a href="{refererrow_with_ref_urls.U_REFERER}" target="_blank">{refererrow_with_ref_urls.REFERER}</a></span></td>
	<td class="{refererrow_with_ref_urls.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall"><a href="{refererrow_with_ref_urls.U_URL}"{refererrow_with_ref_urls.URL_TITLE} target="_blank">{refererrow_with_ref_urls.URL}</a></span></td>
	<td class="{refererrow_with_ref_urls.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall"><a href="{refererrow_with_ref_urls.U_IP}">{refererrow_with_ref_urls.L_IP}</a></span></td>
	<td class="{refererrow_with_ref_urls.ROW_CLASS}" align="center" nowrap="nowrap"><span class="postdetails">{refererrow_with_ref_urls.FIRSTVISIT}</span></td>
	<td class="{refererrow_with_ref_urls.ROW_CLASS}" align="center" nowrap="nowrap"><span class="postdetails">{refererrow_with_ref_urls.LASTVISIT}</span></td>
	<td class="{refererrow_with_ref_urls.ROW_CLASS}" align="center">{refererrow_with_ref_urls.HITS}</td>
	<td class="{refererrow_with_ref_urls.ROW_CLASS}" align="right" nowrap="nowrap"><span class="gensmall"><a onclick="return (confirm('{L_CONFIRM_DELETE_REFERER}'));" href="{refererrow_with_ref_urls.U_DELETE}">{L_DELETE}</a></span></td>
</tr>
<!-- END refererrow_with_ref_urls -->
<!-- BEGIN refererrow -->
<tr>
	<td class="{refererrow.ROW_CLASS}" nowrap="nowrap"><span class="gensmall"><a href="{refererrow.U_REFERER}" target="_blank">{refererrow.REFERER}</a></span></td>
	<td class="{refererrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall">{refererrow.FIRSTVISIT}</span></td>
	<td class="{refererrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall">{refererrow.LASTVISIT}</span></td>
	<td class="{refererrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall">{refererrow.HITS}</span></td>
	<td class="{refererrow.ROW_CLASS}" align="right" nowrap="nowrap"><span class="gensmall"><a onclick="return (confirm('{L_CONFIRM_DELETE_REFERER}'));" href="{refererrow.U_DELETE}">{L_DELETE}</a></span></td>
</tr>
<!-- END refererrow -->
</table>

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr>
	<td valign="middle" nowrap="nowrap" class="nav">{PAGE_NUMBER}</td>
	<td align="right" valign="middle" class="nav">{PAGINATION}</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Admin HTTP Referers 0.3.7 beta &copy; 2002, {COPYRIGHT_YEAR} <a href="http://cybot.eu.org" target="_blank" class="copyright">CyBot</a></div>