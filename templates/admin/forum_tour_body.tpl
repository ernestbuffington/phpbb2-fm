{FORUM_MENU}
</div></td>
<td valign="top" width="78%">

<script language="JavaScript" type="text/javascript">
<!--
function tour() 
{
	window.open("../tour.php", "_tour", "width=800,height=600,scrollbars,resizable=yes");
}
//-->
</script>

<h1>{L_PAGE_NAME}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_ACTION_ADD}">
<tr>
	<td><a href="javascript:tour()">{L_PREVIEW}</a></td>
	<td align="right"><input type="submit" name="edit" value="{L_NEW_SITE}" class="liteoption" /></td>
</tr>
</form></table>
	
<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thCornerL">&nbsp;{L_SUBJECT}&nbsp;</th>
	<th class="thTop">&nbsp;{L_ACCESS}&nbsp;</th>
	<th class="thCornerR" width="15%">&nbsp;{L_ACTION}&nbsp;</th>
</tr>
<!-- BEGIN forum_tour_pages -->
<tr>
  	<td class="{forum_tour_pages.ROW_CLASS}">{forum_tour_pages.SUBJECT}</span></td>
  	<td class="{forum_tour_pages.ROW_CLASS}" align="center">{forum_tour_pages.PAGE_ACCESS}</span></td>
  	<td class="{forum_tour_pages.ROW_CLASS}" align="right" nowrap="nowrap"><a href="{forum_tour_pages.S_MOVE_UP}">{L_MOVE_UP}</a> <a href="{forum_tour_pages.S_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{forum_tour_pages.U_EDIT}">{L_EDIT}</a> <a href="{forum_tour_pages.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END forum_tour_pages -->
</table>
<br />
<div align="center" class="copyright">Forum Tour 1.1.2 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.oxpus.de" target="_blank" class="copyright">OXPUS</a></div>