<script language="JavaScript" type="text/javascript">
<!--
function tour(link) 
{
	window.open(link, "_self", "width=250,height=800,scrollbars,resizable=no");
}
//-->
</script>

<br />
<table width="95%" cellpadding="0" cellspacing="2" align="center">
	<tr>
	<td>
	<table width="100%" cellspacing="1" cellpadding="4" class="forumline">
	<!-- BEGIN forum_tour -->
	<tr>
		<th class="thHead">{forum_tour.SUBJECT}</th>
	</tr>
	<tr>
		<td class="row1" width="100%" height="50"><span class="postbody">{forum_tour.MESSAGE}</span></td>
	</tr>
	<tr>
		<td class="catBottom" align="center">{forum_tour.NAV_PREV}&nbsp;&nbsp;{forum_tour.NAV_FIRST_PAGE}&nbsp;&nbsp;{forum_tour.NAV_NEXT}</td>
	</tr>
	<!-- END forum_tour -->

	<!-- BEGIN switch_no_forum_tour -->
	<tr>
		<th class="thHead">{L_INFORMATION}</th>
	</tr>
	<tr>
		<td class="row1" align="center" height="50"><span class="gen">{switch_no_forum_tour.L_NO_FORUM_TOUR}</span></td>
	</tr>
	<!-- END switch_no_forum_tour -->
	</table>

	<table cellspacing="2" cellpadding="2" align="center" width="100%">
	<!-- BEGIN forum_tour -->
	<tr>
		<td align="center" class="nav">{forum_tour.PAGINATION}</td>
  	</tr>
	<!-- END forum_tour -->
	<tr>
		<td align="center"><a href="#" onClick="javascript:window.close()" class="gensmall">{L_CLOSE_TOUR}</td>
  	</tr>
	</table>
	</td>
</tr>
</table>
<br />
<div align="center" class="copyright">Forum Tour 1.1.2 &copy; 2004 <a href="http://www.oxpus.de" target="_blank" class="copyright">OXPUS</a></div>