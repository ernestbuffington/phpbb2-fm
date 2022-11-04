{POST_MENU}{ATTACH_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--
function update_rank(newimage)
{
	document.rank_image.src = "{S_ICON_BASEDIR}/" + newimage;
}
//-->
</script>

<h1>{L_RANKS_TITLE}</h1>

<p>{L_RANKS_TEXT}</p>

<table class="forumline" width="100%" cellpadding="4" cellspacing="1" align="center"><form action="{S_RANK_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_RANKS_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_RANK_TITLE}:</b></td>
	<td class="row2"><input class="post" type="text" name="title" size="35" maxlength="40" value="{RANK}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_RANK_SPECIAL}:</b></td>
	<td class="row2"><input type="radio" name="special_rank" value="1" {SPECIAL_RANK} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="special_rank" value="0" {NOT_SPECIAL_RANK} /> {L_NO}</td>
</tr>
<!-- BEGIN switch_group_rank -->
<tr>
	<td class="row1"><b>{switch_group_rank.L_GROUP_RANK}:</b><br /><span class="gensmall">{switch_group_rank.L_GROUP_RANK_EXPLAIN}</span></td>
	<td class="row2">{switch_group_rank.GROUP_RANK_SELECT}</td>
</tr>
<!-- END switch_group_rank -->
<tr>
	<td class="row1"><b>{L_RANK_MINIMUM}:</b></td>
	<td class="row2"><input class="post" type="text" name="min_posts" size="5" maxlength="10" value="{MINIMUM}" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_RANK_IMAGE}:</b><br /><span class="gensmall">{L_RANK_IMAGE_EXPLAIN}</span></td> 
   	<td class="row2"><select name="rank_image" onchange="update_rank(this.options[selectedIndex].value);">{S_FILENAME_OPTIONS}</select> &nbsp; <img name="rank_image" src="{RANK_IMG}" alt="" title="" /> &nbsp;</td>
</tr> 
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
