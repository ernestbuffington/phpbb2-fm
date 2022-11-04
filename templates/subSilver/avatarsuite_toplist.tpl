
<table widt="100%" cellpadding="2" cellspacing="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_LINK_LIST}" class="nav">{L_LINK_LIST}</a></td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" align="center">
  <tr> 
	<td width="200" valign="top">
	
	<table class="forumline" cellpadding="4" cellspacing="1" width="100%">
	<tr>
		<th class="thHead">{L_RANDOM}</th>
	</tr>
	<!-- BEGIN randomunvotedavatar -->
	<tr>
		<td class="{randomunvotedavatar.ROW_CLASS}" style="width:1%; padding:10px;" align="center">
		{randomunvotedavatar.AVATAR}
		{randomunvotedavatar.AVATAR_VOTE}
		</td>
	</tr>
	<!-- END randomunvotedavatar -->
	</table>
	
	</td>
	<td width="10" nowrap>&nbsp;</td>
	<td align="right" valign="top" width="*">

	<table class="forumline" width="100%" cellpadding="4" cellspacing="1">
	<tr>
		<th class="thHead" colspan="4">{TITLE}</th>
	</tr>
	<tr>
		<td class="catLeft" nowrap="nowrap" align="center"><span class="cattitle">#</span></td>
		<td class="cat" nowrap="nowrap" align="center"><span class="cattitle">{L_TOPAVATARS}</span></td>
		<td class="cat" nowrap="nowrap" align="center"><span class="cattitle">{L_USERNAMES}</span></td>
		<td class="catRight" nowrap="nowrap" align="center"><span class="cattitle">{L_COMMENTS}</span></td>
	</tr>
	<!-- BEGIN cycleme -->
	<tr>
		<td class="{cycleme.ROW_CLASS}" style="width:1%; font-size:xx-small;padding:10px; white-space:nowrap"><span style="font-size:large">#{cycleme.POSITION}</span><br />{cycleme.AVERAGEVOTING}&nbsp;{cycleme.L_AVERAGEPOINTS}<br />{cycleme.TOTALVOTERS}&nbsp;{cycleme.L_VOTERS}</td>
		<td class="{cycleme.ROW_CLASS}" style="width:1%; font-size:xx-small;padding:10px" align="center">{cycleme.AVATAR}{cycleme.AVATAR_VOTE}</td>
		<td class="{cycleme.ROW_CLASS}" style="width:1%; font-size:xx-small;padding:10px">{cycleme.LOCATION} {cycleme.USERNAMES}</td>
		<td class="{cycleme.ROW_CLASS}" style="font-size:xx-small;padding:10px">{cycleme.COMMENTS}<br />{cycleme.WHOISWHO}</td>
	</tr>
	<!-- END cycleme -->
	<tr>
		<td class="catBottom" align="center" colspan="4">{REVOTE}</td>
	</tr>
	</table>
	<table width="100%" cellpadding="2" cellspacing="2">
	<tr>
		<td align="center" class="nav">{PREVIOUS} {SHOWALLUSERS} {TOP} {NEXT}</td>
	</tr>
	<tr>
		<td align="center" class="gensmall">{GENDERLINKS}</td>
	</tr>
	</table>
	
	</td>
</tr>
</table>
<br />
<table width="100%" cellspacing="2" align="center"> 
  <tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
  </tr> 
</table> 
<br />
<div align="center" class="copyright">Avatar Suite 1.2.0 &copy; 2005, {COPYRIGHT_YEAR} <a href="http://www.1-4a.com/" class="copyright" target="_blank">knnknn</a></div>
