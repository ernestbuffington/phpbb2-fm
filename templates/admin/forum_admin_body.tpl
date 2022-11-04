{FORUM_MENU}{PERMS_MENU}{TOPIC_MENU}
{MOD_CP_MENU}
</div></td>
<td valign="top" width="78%">

<h1>{L_FORUM_TITLE}</h1>

<p>{L_FORUM_EXPLAIN}</p>

<table width="100%" cellpadding="2" cellspacing="2" align="center"><form method="post" action="{S_FORUM_ACTION}">
<tr>
	<td valign="bottom" class="nav"><a href="{S_FORUM_ACTION}" class="nav">{L_INDEX}</a>
	<!-- BEGIN navrow -->
	<br />-> <a href="{navrow.U_SUBINDEX}" class="nav">{navrow.L_SUBINDEX}</a>
	<!-- END navrow -->
	<!-- BEGIN switch_last_hierarchielevel -->
	<br />-> <a href="{U_DEEPESTNAVINDEX}" class="nav">{L_DEEPESTNAVINDEX}</a>
	<!-- END switch_last_hierarchielevel -->
	</td>
	<td align="right"><input type="submit" class="liteoption" name="addcategory" value="{L_CREATE_CATEGORY}" />&nbsp;&nbsp;<input type="submit" class="liteoption" name="addforum" value="{L_CREATE_FORUM}" /></td>
</tr>
</form></table>
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">
<tr>
	<th class="thHead" colspan="7">{L_FORUM_TITLE}{PARENT_FORUM_TITLE}</th>
</tr>
<!-- BEGIN catrowh0 -->
<tr>
	<td class="cath0" colspan="6">{catrowh0.CAT_ICON} <span class="cattitle"><b><a href="{catrowh0.U_VIEWCAT}">{catrowh0.CAT_DESC}</a></b></span></td>
	<td class="cath0" align="right" valign="middle" nowrap="nowrap"><a href="{catrowh0.U_CAT_MOVE_UP}">{L_MOVE_UP}</a> <a href="{catrowh0.U_CAT_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{catrowh0.U_CAT_EDIT}">{L_EDIT}</a> <a href="{catrowh0.U_CAT_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- BEGIN forumrowh0 -->
<tr>
	<td class="row2h0" align="center" width="5%"><img src="{catrowh0.forumrowh0.FORUM_FOLDER_IMG}" alt="{catrowh0.forumrowh0.FORUM_FOLDER_ALT}" title="{catrowh0.forumrowh0.FORUM_FOLDER_ALT}" /></td>
	<td class="row2h0" width="100%" colspan="3"">{catrowh0.forumrowh0.FORUM_ICON} <span class="gen"><a href="{catrowh0.forumrowh0.U_VIEWFORUM}" target="_new">{catrowh0.forumrowh0.FORUM_NAME}</a> <a href="{catrowh0.forumrowh0.U_FORUM_VIEWASROOT}">{catrowh0.forumrowh0.L_FORUM_VIEWASROOT}</a></span> &nbsp;<span class="gensmall">({catrowh0.forumrowh0.L_FORUM_ID} <b>{catrowh0.forumrowh0.FORUM_ID}</b>)<br />{catrowh0.forumrowh0.FORUM_DESC}</span></td>
	<td class="row1h0" width="50" align="center" valign="middle"><span class="gen">{catrowh0.forumrowh0.NUM_TOPICS}</span></td>
	<td class="row2h0" width="50" align="center" valign="middle"><span class="gen">{catrowh0.forumrowh0.NUM_POSTS}</span></td>
	<td class="row1h0" align="right" valign="middle" nowrap="nowrap" width="150"><a href="{catrowh0.forumrowh0.U_FORUM_MOVE_UP}">{L_MOVE_UP}</a> <a href="{catrowh0.forumrowh0.U_FORUM_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{catrowh0.forumrowh0.U_FORUM_EDIT}">{L_EDIT}</a> <a href="{catrowh0.forumrowh0.U_EDIT_PERMS}">{L_PERMS}</a> <a href="{catrowh0.forumrowh0.U_FORUM_RESYNC}">{L_RESYNC}</a> <a href="{catrowh0.forumrowh0.U_FORUM_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- BEGIN catrowh1 -->
<tr>
	<td class="row2h0" width="5%">&nbsp;</td>
	<td class="cath1" colspan="5"><span class="cattitle">{catrowh0.forumrowh0.catrowh1.CAT_ICON} <span class="cattitle"><b><a href="{catrowh0.forumrowh0.catrowh1.U_VIEWCAT}">{catrowh0.forumrowh0.catrowh1.CAT_DESC}</a></b></span></td>
	<td class="cath1" align="right" valign="middle" nowrap="nowrap"><a href="{catrowh0.forumrowh0.catrowh1.U_CAT_MOVE_UP}">{L_MOVE_UP}</a> <a href="{catrowh0.forumrowh0.catrowh1.U_CAT_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{catrowh0.forumrowh0.catrowh1.U_CAT_EDIT}">{L_EDIT}</a> <a href="{catrowh0.forumrowh0.catrowh1.U_CAT_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- BEGIN forumrowh1 -->
<tr>
	<td class="row2h0" width="5%">&nbsp;</td>
	<td class="row2h1" align="center" width="5%"><img src="{catrowh0.forumrowh0.catrowh1.forumrowh1.FORUM_FOLDER_IMG}" alt="{catrowh0.forumrowh0.catrowh1.forumrowh1.FORUM_FOLDER_ALT}" title="{catrowh0.forumrowh0.catrowh1.forumrowh1.FORUM_FOLDER_ALT}" /></td>
	<td class="row2h1" width="100%" colspan="2">{catrowh0.forumrowh0.catrowh1.forumrowh1.FORUM_ICON} <span class="gen"><a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.U_VIEWFORUM}" target="_new">{catrowh0.forumrowh0.catrowh1.forumrowh1.FORUM_NAME}</a> <a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.U_FORUM_VIEWASROOT}">{catrowh0.forumrowh0.catrowh1.forumrowh1.L_FORUM_VIEWASROOT}</a></span> &nbsp;<span class="gensmall">({catrowh0.forumrowh0.catrowh1.forumrowh1.L_FORUM_ID} <b>{catrowh0.forumrowh0.catrowh1.forumrowh1.FORUM_ID}</b>)<br />{catrowh0.forumrowh0.catrowh1.forumrowh1.FORUM_DESC}</span></td>
	<td class="row1h1" width="50" align="center" valign="middle"><span class="gen">{catrowh0.forumrowh0.catrowh1.forumrowh1.NUM_TOPICS}</span></td>
	<td class="row2h1" width="50" align="center" valign="middle"><span class="gen">{catrowh0.forumrowh0.catrowh1.forumrowh1.NUM_POSTS}</span></td>
	<td class="row1h1" align="right" valign="middle" nowrap="nowrap"><a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.U_FORUM_MOVE_UP}">{L_MOVE_UP}</a> <a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.U_FORUM_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.U_FORUM_EDIT}">{L_EDIT}</a> <a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.U_EDIT_PERMS}">{L_PERMS}</a> <a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.U_FORUM_RESYNC}">{L_RESYNC}</a> <a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.U_FORUM_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- BEGIN catrowh2 -->
<tr>
	<td class="row2h0" width="5%">&nbsp;</td>
	<td class="row2h1" width="5%">&nbsp;</td>
	<td class="cath2" colspan="4">{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.CAT_ICON} <span class="cattitle"><b><a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.U_VIEWCAT}">{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.CAT_DESC}</a></b></span></td>
	<td class="cath2" align="right" valign="middle" nowrap="nowrap"><a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.U_CAT_MOVE_UP}">{L_MOVE_UP}</a> <a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.U_CAT_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.U_CAT_EDIT}">{L_EDIT}</a> <a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.U_CAT_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- BEGIN forumrowh2 -->
<tr>
	<td class="row2h0" width="5%">&nbsp;</td>
	<td class="row2h1" width="5%">&nbsp;</td>
	<td class="row2h2" align="center" width="5%"><img src="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.FORUM_FOLDER_IMG}" alt="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.FORUM_FOLDER_ALT}" title="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.FORUM_FOLDER_ALT}" /></td>
	<td class="row2h2" width="100%">{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.FORUM_ICON} <span class="gen"><a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.U_VIEWFORUM}" target="_new">{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.FORUM_NAME}</a><a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.U_FORUM_VIEWASROOT}">{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.L_FORUM_VIEWASROOT}</a></span> &nbsp;<span class="gensmall">({catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.L_FORUM_ID} <b>{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.FORUM_ID}</b>)<br /><span class="gensmall">{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.FORUM_DESC}</span></td>
	<td class="row1h2" width="50" align="center" valign="middle"><span class="gen">{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.NUM_TOPICS}</span></td>
	<td class="row2h2" width="50" align="center" valign="middle"><span class="gen">{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.NUM_POSTS}</span></td>
	<td class="row1h2" align="right" valign="middle" nowrap="nowrap"><a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.U_FORUM_MOVE_UP}">{L_MOVE_UP}</a> <a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.U_FORUM_MOVE_DOWN}">{L_MOVE_DOWN}</a> <a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.U_FORUM_EDIT}">{L_EDIT}</a> <a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.U_EDIT_PERMS}">{L_PERMS}</a> <a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.U_FORUM_RESYNC}">{L_RESYNC}</a> <a href="{catrowh0.forumrowh0.catrowh1.forumrowh1.catrowh2.forumrowh2.U_FORUM_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END forumrowh2 -->
<tr>
	<td class="row2h0" height="1">&nbsp;</td>
	<td class="row2h1" height="1">&nbsp;</td>
	<td colspan="5" height="1" class="spaceRowh2"><img src="../images/spacer.gif" alt="" title="" width="1" height="1" /></td>
</tr>
<!-- END catrowh2 -->
<!-- END forumrowh1 -->
<tr>
	<td class="row2h0" height="1">&nbsp;</td>
	<td colspan="6" height="1" class="spaceRowh1"><img src="../images/spacer.gif" alt="" title="" width="1" height="1" /></td>
</tr>
<!-- END catrowh1 -->
<!-- END forumrowh0 -->
<tr>
	<td colspan="7" height="1" class="spaceRowh0"><img src="../images/spacer.gif" alt="" title="" width="1" height="1" /></td>
</tr>
<!-- END catrowh0 -->
</table>
<br />

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th colspan="4" class="thHead">{L_HIDDENPOSTS}</th>
</tr>
<!-- BEGIN hiddenpostsrow -->
<tr>
	<td class="row2"><span class="gen"><a href="{hiddenpostsrow.U_VIEWFORUM}" target="_blank">{hiddenpostsrow.FORUM_NAME}</a></span></td>
	<td class="row1" align="center" width="50"><span class="gen">{hiddenpostsrow.NUM_TOPICS}</span></td>
	<td class="row2" align="center" width="50"><span class="gen">{hiddenpostsrow.NUM_POSTS}</span></td>
	<td class="row1" align="right" nowrap="nowrap" width="150"><a href="{hiddenpostsrow.U_POSTS_MOVE}">{L_MOVE_POSTS}</a> <a href="{hiddenpostsrow.U_POSTS_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END hiddenpostsrow -->
<!-- BEGIN switch_nohiddenposts -->
<tr>
	<td colspan="4" class="row1" align="center" height="30"><span class="gen">{L_NOHIDDENPOSTS}</span></td>
</tr>
<!-- END switch_nohiddenposts -->
</table>
