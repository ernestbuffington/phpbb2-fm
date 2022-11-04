{TOPLIST_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="Javascript" type="text/javascript">
function check_del(message,link)
{
	if(confirm(message))
	{
		document.location=link;
	}
}
</script>

<h1>{L_CONFIGURATION_TITLE}</h1>

<p>{L_CONFIGURATION_EXPLAIN}</p>

<!-- BEGIN main -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thCornerL" colspan="{main.COLSPAN}">{SITENAME} {main.HEAD}</th>
	<th class="thCornerR" colspan="{main.COLSPAN3}">{main.HEAD2}</th>
</tr>
<tr>
	<td class="catLeft" align="center" nowrap="nowrap"><span class="cattitle">{RANK}</span></td>
	<td class="cat" align="center" nowrap="nowrap"><span class="cattitle">{SITE}</span></td>
  	<!-- BEGIN switch_toplist_view_in_hits_l -->
	<td width="75" class="cat" align="center" nowrap="nowrap"><span class="cattitle">{IN_HITS}</span></td>
  	<!-- END switch_toplist_view_in_hits_l -->
  	<!-- BEGIN switch_toplist_view_out_hits_l -->
	<td width="75" class="cat" align="center" nowrap="nowrap"><span class="cattitle">{OUT_HITS}</span></td>
	<!-- END switch_toplist_view_out_hits_l -->
	<!-- BEGIN switch_toplist_view_img_hits_l -->
	<td width="75" class="catRight" align="center" nowrap="nowrap"><span class="cattitle">{IMG_HITS}</span></td>
	<!-- END switch_toplist_view_img_hits_l -->
</tr>
<!-- BEGIN toplist -->
<tr>
	<td class="row1" align="center">{main.toplist.R}</td>
	<td width="80%" class="row1"><a href="{LOCATION}go.php?id={main.toplist.ID}" class="forumlink" target="_blank">{main.toplist.NAM}</a><br /><span class="gensmall">{main.toplist.INF}</span><br /><a href="{LOCATION}go.php?id={main.toplist.ID}" target="_blank"><img src="{main.toplist.BAN}" alt="{main.toplist.NAM}" title="{main.toplist.NAM}" /></a>
	<!-- BEGIN switch_edit_del -->
	<br /> <a href="#" onClick="alert('{main.toplist.switch_edit_del.IP}');return false;"><img src="{IP_IMG}" alt="{L_IP}" title="{L_IP}" /></a> <a href="{main.toplist.switch_edit_del.EDIT_URL}"><img src="{EDIT_IMG}" alt="{L_EDIT_SITE}" title="{L_EDIT_SITE}" /></a> <a href="#" onClick="check_del('{main.toplist.switch_edit_del.DEL_EXPL}','{main.toplist.switch_edit_del.DEL_URL}')"><img src="{DEL_IMG}" alt="{L_DELETE}" title="{L_DELETE}" /></a>
  	<!-- END switch_edit_del -->
	</td>
	<!-- BEGIN switch_toplist_view_in_hits -->
	<td class="row2" align="center"><span class="genmed">{main.toplist.switch_toplist_view_in_hits.HIN}</span></td>
	<!-- END switch_toplist_view_in_hits -->
	<!-- BEGIN switch_toplist_view_out_hits -->
	<td class="row2" align="center"><span class="genmed">{main.toplist.switch_toplist_view_out_hits.OUT}</span></td>
	<!-- END switch_toplist_view_out_hits -->
	<!-- BEGIN switch_toplist_view_img_hits -->
	<td class="row2" align="center"><span class="genmed">{main.toplist.switch_toplist_view_img_hits.IMG}</span></td>
	<!-- END switch_toplist_view_img_hits -->
</tr>
<!-- END toplist -->
<tr>
	<td class="row1" align="left"><a href="{BUTTON1_L}" target="_blank"><img src="{BUTTON1}" width="88" height="31" alt="{SITENAME}" title="{SITENAME}" /></a></td>
	<!-- BEGIN switch_toplist_view_middle -->
	<td class="row1"><b><blink>{main.switch_toplist_view_middle.NEED}</blink></b></td>
	<!-- END switch_toplist_view_middle -->
	<td colspan="3" class="row1" align="right"><a href="{BUTTON2_L}" target="_blank"><img src="{BUTTON2}" width="88" height="31" alt="{SITENAME}" title="{SITENAME}" /></a></td>
</tr>
<tr>
	<td class="catBottom" colspan="5" align="center"><span class="cattitle">{main.XTRASTUFF}</span></td>
</tr>
</table>
<!-- END main -->

<!-- BEGIN editlogin -->
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
	<th class="thHead" colspan="2">{INFO}</th>
</tr>
<!-- BEGIN data -->
<tr>
	<td class="row1">{editlogin.data.NAM}<br /><img src="{editlogin.data.BAN}" alt="{editlogin.data.NAM}" title="{editlogin.data.NAM}" /></td>
	<td class="row2" width="10%"><table width="100%">
	<tr>
		<form action="{editlogin.data.ACTION}" method="post">
		<td align="right"><input type="hidden" name="iduser" value="{editlogin.data.ID}"><input type="hidden" name="what" value="edit"><input type="submit" value="Edit" class="mainoption"></td>
		</form>
		<form action="{editlogin.data.ACTION}" method="post" name="site{editlogin.data.ID}del">
		<td><input class="liteoption" type="button" value="Delete" onClick="if(confirm('{editlogin.data.DEL}'))document.site{editlogin.data.ID}del.submit();" ><input type="hidden" name="iduser" value="{editlogin.data.ID}"><input type="hidden" name="what" VALUE="Del"><input type="hidden" name="sure" VALUE="yes">
		</td>
		</form>
	</tr>
	</table></td>
{editlogin.data.ADMIN}
</tr>
<!-- END data -->
</table>
<!-- END editlogin -->

<!-- BEGIN edit -->
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
	<th class="thHead" colspan="2">{L_EDIT_SITE}</th>
</tr>
<!-- BEGIN data -->
<tr>
<form action="{edit.data.ACTION}" method="post">
	<td class="row1" width="38%"><b>{NAME}:</b></td>
	<td class="row2"><input class="post" size="40" type="text" name="nam" value="{edit.data.NAM}"></td>
</tr>
<tr>
	<td class="row1"><b>{INFO}:</b></td>
	<td class="row2"><input class="post" size="40" type="text" name="inf" value="{edit.data.INF}"></td>
</tr>
<tr>
	<td class="row1"><b>{SURL}:</b></td>
	<td class="row2"><input class="post" size="40" type="text" name="lin" value="{edit.data.LIN}"></td>
</tr>
<tr>
	<td class="row1"><b>{BURL}:</b></td>
	<td class="row2"><input class="post" size="40" type="text" name="ban" value="{edit.data.BAN}"></td>
</tr>
<tr>
	<td class="row1"><b>{L_RESEND_HTML}:</b></td>
	<td class="row2"><input type="checkbox" name="resend" value="1"></td>
</tr>
<tr>
	<td colspan="2" class="catBottom" align="center"><input type="hidden" name="what" value="signch"><input type="hidden" name="iduser" value="{edit.data.ID}"><input type="submit" value="{L_SUBMIT}" class="mainoption"></td>
</tr>
</table>
<!-- BEGIN stuff -->
<br />
<table cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
	<th class="thHead" colspan="3">{edit.data.stuff.X}</th>
</tr>
<tr>
	<td width="5%" align="center" class="row1">{L_SELECT}:</td>
	<td class="row2">{L_PLAIN}: <input {edit.data.stuff.P_SELECTED}type="radio" name="imgfile_type" value="plain"></td>
	<td class="row2">{L_INCLUDE_RANK}: <input {edit.data.stuff.G_SELECTED}type="radio" name="imgfile_type" value="gdlib"></td>
</tr>
<!-- BEGIN image -->
<tr>
	<td width="5%" align="center" class="row1"><input {edit.data.stuff.image.SELECTED}type="radio" name="imgfile" value="{edit.data.stuff.image.FILENAME}"></td>
	<td class="row2"><img src="{LOCATION_IMG}{edit.data.stuff.image.FILENAME}" alt="" title="" /></td>
	<td class="row2"><img src="{LOCATION}image.php?imgfile={edit.data.stuff.image.FILENAME}" alt="" title="" /></td>
</tr>
<!-- END image -->
</table>
<!-- END stuff -->
</form>
<!-- END data -->
<!-- END edit -->

<!-- BEGIN new -->
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form action="{new.ACTION}" method="post">
<input type="hidden" name="f" value="toplistnewadd">
<tr>
	<th class="thHead" colspan="4">{L_ADD_SITE}</th>
</tr>
<tr>
	<td class="row1"><b>{NAME}:</b></td>
	<td class="row2"><input class="post" size="50" type="text" name="nam"></td>
	<td class="row1"><b>{INFO}:</b></td>
	<td class="row2"><input class="post" size="50" type="text" name="inf"></td>
</tr>
<tr>
	<td class="row1"><b>{SURL}:</b></td>
	<td class="row2"><input class="post" type="text" size="50" name="lin" value="http://"></td>
	<td class="row1"><b>{BURL}:</b></td>
	<td class="row2"><input class="post" type="text" size="50" name="ban" value="http://"></td>
</tr>
<!-- BEGIN stuff -->
<tr>
	<th class="thSides" colspan="4">{new.stuff.X}</th>
</tr>
<!-- BEGIN image -->
<tr>
	<td class="row1" align="center"><input {new.data.stuff.image.SELECTED}type="radio" name="imgfile" value="{new.stuff.image.FILENAME}"></td>
	<td class="row2" colspan="3"><img src="{LOCATION_IMG}{new.stuff.image.FILENAME}" alt="" title="" /></td>
</tr>
<!-- END image -->
<!-- END stuff -->
<tr>
	<td colspan="4" align="center" class="catBottom"><input type="submit" value="{L_SUBMIT}" class="mainoption" /></td>
</tr>
</form></table>
<!-- END new -->
<br />
<div align="center" class="copyright">Toplist 1.3.8 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.wyrihaximus.net" target="_blank" class="copyright">WyriHaximus</a></div>