{CONFIG_MENU}{USERCOM_MENU}{SERVER_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{THIS_PAGE_NAME}</h1>

<p>{THIS_PAGE_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="2" cellspacing="2">
<tr>
	<form action="{S_ACTION}" method="post">
	<td class="genmed">{L_COUNT_VIEWS} {CB_COUNT_VIEWS} <input class="liteoption" type="submit" name="switch" value="{L_SUBMIT}" /></td>
	</form>
	<form action="{S_ACTION}" method="post">
	<td align="right">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_ADD}" class="liteoption" />&nbsp;&nbsp;<input type="submit" name="disable" value="{L_UPDATE_SELECTED_PAGES}" class="liteoption" />&nbsp;&nbsp;<input type="submit" name="cache" value="{L_REBUILD_CACHE}" class="liteoption" /></td>
</tr>
</table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thCornerL" nowrap="nowrap">&nbsp;{L_PAGE_NAME}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_GUEST_VIEWS}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_GUEST_VIEWS_PCT}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_MEMBER_VIEWS}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_MEMBER_VIEWS_PCT}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_PAGE_VIEWS}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_PAGE_VIEWS_PCT}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_DISABLE_PAGE}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_AUTH_LEVEL}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_MIN_POST_COUNT}&nbsp;</th>
	<th class="thCornerR" colspan="2" nowrap="nowrap">&nbsp;{L_ACTION}&nbsp;</th>
</tr>
<tr>
	<td class="row3" align="right" nowrap="nowrap"><b>{L_TOTAL_PAGE_VIEWS}</b></td>
	<td class="row3" align="right"><b>{TOTAL_GUEST_VIEWS}</b></td>
	<td class="row3" align="right"><b>{TOTAL_GUEST_PCT}%</b></td>
	<td class="row3" align="right"><b>{TOTAL_MEMBER_VIEWS}</b></td>
	<td class="row3" align="right"><b>{TOTAL_MEMBER_PCT}%</b></td>
	<td class="row3" align="right"><b>{TOTAL_PAGE_VIEWS}</b></td>
	<td class="row3" colspan="6">&nbsp;</td>
</tr>
<!-- BEGIN rowdata -->
<tr>
	<td class="{rowdata.ROW_CLASS}">{rowdata.PAGE_NAME}</td>
	<td class="{rowdata.ROW_CLASS}" align="right">{rowdata.GUEST_VIEWS}</td>
	<td class="{rowdata.ROW_CLASS}" align="right">{rowdata.GUEST_VIEWS_PCT}%</td>
	<td class="{rowdata.ROW_CLASS}" align="right">{rowdata.MEMBER_VIEWS}</td>
	<td class="{rowdata.ROW_CLASS}" align="right">{rowdata.MEMBER_VIEWS_PCT}%</td>
	<td class="{rowdata.ROW_CLASS}" align="right">{rowdata.PAGE_VIEWS}</td>
	<td class="{rowdata.ROW_CLASS}" align="right">{rowdata.PAGE_VIEWS_PCT}%</td>
	<td class="{rowdata.ROW_CLASS}" align="center">{rowdata.CB_DISABLE_PAGE}{rowdata.DISABLE_PAGE}</td>
	<td class="{rowdata.ROW_CLASS}" align="center">{rowdata.AUTH_LEVEL}</td>
	<td class="{rowdata.ROW_CLASS}" align="center">{rowdata.MIN_POST_COUNT} / {rowdata.MAX_POST_COUNT}</td>
	<td class="{rowdata.ROW_CLASS}" nowrap="nowrap" align="right"><a href="{rowdata.U_EDIT}">{L_EDIT}</a> <a href="{rowdata.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END rowdata -->
</form></table>
<br />
<div align="center" class="copyright">Page Permissions 1.2.2 &copy; {COPYRIGHT_YEAR} <a href="http://www.phpbbdoctor.com/" target="_blank" class="copyright">drathbun</a></div>