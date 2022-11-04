<script language="javascript" type="text/javascript">
<!--
function showmalescreate() 
{
	document.images.male_imgs.src='images/activity/characters/male/' + document.creation_page.mchar.options[document.creation_page.mchar.selectedIndex].value
}
function showfemalescreate() 
{
	document.images.female_imgs.src='images/activity/characters/female/' + document.creation_page.fchar.options[document.creation_page.fchar.selectedIndex].value
}
function showmalesedit() 
{
	document.images.male_imgs.src='images/activity/characters/male/' + document.edit_page.mchar.options[document.edit_page.mchar.selectedIndex].value
}
function showfemalesedit() 
{
	document.images.female_imgs.src='images/activity/characters/female/' + document.edit_page.fchar.options[document.edit_page.fchar.selectedIndex].value
}
//-->
</script>

<!-- BEGIN create_char -->
<table align="center" width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_ACTIVITY}" class="nav">{L_ACTIVITY}</a></td>
</tr>
</table>

<table align="center" width="100%" class="forumline" cellpadding="4" cellspacing="1"><form name="creation_page" method="post" action="{S_SUBMIT}">
<tr>
	<th class="thHead" colspan="2">{CHAR_OPTIONS}</th>
</tr>
<tr>
	<td width="50%" class="catLeft" align="center"><span class="cattitle">{CHAR_OPTIONS_M}</span></td>
	<td width="50%" class="catRight" align="center"><span class="cattitle">{CHAR_OPTIONS_F}</span></td>		
</tr>
<tr>
	<td align="center" class="row1">
	<!-- BEGIN mdefault -->				
	<img src="images/activity/characters/male/{create_char.mdefault.VALUE}" name="male_imgs" alt="" title="" /><br />
	<!-- END mdefault -->			
	<select name="mchar" onchange="showmalescreate()">
		<!-- BEGIN males -->
		<option value="{create_char.males.VALUES}">{create_char.males.NAMES}</option>
		<!-- END males -->
	</select></td>
	<td align="center" class="row1">
	<!-- BEGIN fdefault -->
	<img src="images/activity/characters/female/{create_char.fdefault.VALUE}" name="female_imgs" alt="" title="" /><br />
	<!-- END fdefault -->
	<select name="fchar" onchange="showfemalescreate()">
		<!-- BEGIN females -->
		<option value="{create_char.females.VALUES}">{create_char.females.NAMES}</option>							
		<!-- END females -->
	</select></td>		
</tr>	
<tr>
	<td class="row1"><b>{CHAR_NAME}: *</b></td>
	<td class="row2"><input type="text" class="post" value="" name="name" size="25" /></td>		
</tr>
<tr>
	<td class="row1"><b>{CHAR_GENDER}: *</b></td>
	<td class="row2"><input type="radio" value="1" name="gender"> {CHAR_GENDER_M}&nbsp;&nbsp;<input type="radio" value="2" name="gender"> {CHAR_GENDER_F}</td>		
</tr>
<tr>
	<td class="row1"><b>{CHAR_AGE}: *</b><br /><span class="gensmall">{CHAR_AGE_EXP}</span></td>
	<td class="row2"><input type="text" value="" size="10" name="age" class="post" /></td>		
</tr>
<tr>
	<td class="row1"><b>{CHAR_TITLE}:</b></td>
	<td class="row2"><input type="text" class="post" value="" name="title" size="25" /></td>		
</tr>		
<tr>
	<td class="row1"><b>{CHAR_SAYING}:</b></td>
	<td class="row2"><input type="text" class="post" value="" name="saying" size="25" /></td>		
</tr>	
<tr>
	<td class="row1"><b>{CHAR_INTRESTS}:</b></td>
	<td class="row2"><input type="text" value="" size="40" name="intrests" class="post" /></td>		
</tr>
<tr>
	<td class="row1"><b>{CHAR_FROM}:</b></td>
	<td class="row2"><input type="text" value="" size="40" name="from" class="post" /></td>		
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="hidden" value="save_char" name="action"><input type="hidden" value="create_char" name="mode"><input type="submit" value="{CHAR_SUBMIT}" class="mainoption" onclick="document.creation_page.submit()" /></td>		
</tr>							
</form></table>
<!-- END create_char -->

<!-- BEGIN edit_char -->
<table align="center" width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_ACTIVITY}" class="nav">{L_ACTIVITY}</a></td>
	<td align="right">{USERS}</td>
</tr>	
</table>

<!-- BEGIN view -->
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead" colspan="2">{edit_char.view.EDIT_EXP}</th>
</tr>
<tr>
	<td colspan="2" class="row1">{edit_char.view.CHAR_PROFILE}</td>
</tr>
<!-- END view -->
<!-- BEGIN edit -->
<tr>
	<td class="catBottom" colspan="2" align="center"><span class="nav">{edit_char.edit.CHR_EDIT_EXP}</span></td>
</tr>
</table>
<br />

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form name="edit_page" method="post" action="{S_SUBMIT}">
<tr>
	<th class="thHead" colspan="2">{CHAR_OPTIONS}</th>
</tr>
<tr>
	<td align="center" width="50%" class="catLeft"><span class="cattitle">{CHAR_OPTIONS_M}</span></td>
	<td align="center" width="50%" class="catRight"><span class="cattitle">{CHAR_OPTIONS_F}</span></td>		
</tr>
<tr>
	<td align="center" valign="top" class="row2">
	<!-- BEGIN off -->
	{edit_char.edit.off.ERROR}
	<!-- END off -->
	<!-- BEGIN mdefault -->				
	<img src="images/activity/characters/male/{edit_char.edit.mdefault.VALUE}" name="male_imgs" alt="" title="" /><br />
	<!-- END mdefault -->			
	<!-- BEGIN on -->
	<select name="mchar" onchange="showmalesedit()">
	<!-- END on -->
		<!-- BEGIN males -->
		<option value="{edit_char.edit.males.VALUES}">{edit_char.edit.males.NAMES}</option>							
		<!-- END males -->
	<!-- BEGIN on -->
	</select>
	<!-- END on -->
	</td>
	<td align="center" valign="top" width="50%" class="row2">
	<!-- BEGIN off -->
	{edit_char.edit.off.ERROR}
	<!-- END off -->
	<!-- BEGIN fdefault -->
	<img src="images/activity/characters/female/{edit_char.edit.fdefault.VALUE}" name="female_imgs" alt="" title="" /><br />
	<!-- END fdefault -->
	<!-- BEGIN on -->		
	<select name="fchar" onchange="showfemalesedit()">
	<!-- END on -->			
		<!-- BEGIN females -->
		<option value="{edit_char.edit.females.VALUES}">{edit_char.edit.females.NAMES}</option>							
		<!-- END females -->
	<!-- BEGIN on -->				
	</select>
	<!-- END on -->			
	</td>		
</tr>
<!-- BEGIN on -->	
<tr>
	<td class="row1"><b>{CHAR_CHANGE_CHECK}</b></td>
	<td class="row2"><input type="radio" name="change" value="1"> {CHAR_CHANGE_CHECK_Y}&nbsp;&nbsp;<input type="radio" name="change" value="2"> {CHAR_CHANGE_CHECK_N}</td>		
</tr>
<!-- END on -->
<!-- BEGIN values -->	
<tr>
	<td class="row1"><b>{CHAR_NAME}:</b></td>
	<td class="row2">{edit_char.edit.values.CHR_CHNG_NAME}</td>		
</tr>
<tr>
	<td class="row1"><b>{CHAR_TITLE}:</b></td>
	<td class="row2">{edit_char.edit.values.CHR_CHNG_TITLE}</td>		
</tr>		
<tr>
	<td class="row1"><b>{CHAR_GENDER}:</b></td>
	<td class="row2">{edit_char.edit.values.CHR_CHNG_GENDER}</td>		
</tr>
<tr>
	<td class="row1"><b>{CHAR_AGE}:</b><br /><span class="gensmall">{CHAR_AGE_EXP}</span></td>
	<td class="row2">{edit_char.edit.values.CHR_CHNG_AGE}</td>		
</tr>
<tr>
	<td class="row1"><b>{CHAR_INTRESTS}:</b></td>
	<td class="row2">{edit_char.edit.values.CHR_CHNG_INTRESTS}</td>		
</tr>
<tr>
	<td class="row1"><b>{CHAR_SAYING}:</b></td>
	<td class="row2">{edit_char.edit.values.CHR_CHNG_SAYING}</td>		
</tr>	
<tr>
	<td class="row1"><b>{CHAR_FROM}:</b></td>
	<td class="row2">{edit_char.edit.values.CHR_CHNG_FROM}</td>		
</tr>
<tr>
	<td align="center" class="catBottom" colspan="2"><table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50%" align="right"><input type="hidden" value="save_char" name="action"><input type="hidden" value="edit_char" name="mode"><input type="submit" value="{L_SUBMIT}" class="mainoption" onclick="document.edit_page.submit()" />&nbsp;</td>
		</form>
		<form name="delete_char" action="activity_char.php" method="get">
		<td width="50%">&nbsp;<input type="hidden" name="mode" value="del_char"><input type="submit" value="{L_DELETE}" class="liteoption" onclick="document.delete_char.submit()" /></td>
		</form>
	</tr>
	</table></td>		
</tr>							
</table>
<!-- END values -->
<!-- END edit -->
<!-- END edit_char -->

<!-- BEGIN profile_char -->
<!-- BEGIN data -->
<table width="100%" align="center" cellpadding="2" cellspacing="2">				
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_ACTIVITY}" class="nav">{L_ACTIVITY}</a></td>
</tr>
<table>				
{profile_char.data.CHAR_PROFILE}
<!-- END data -->
<!-- END profile_char -->
<br />
<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td> 
</tr> 
</table> 
<br />
<div align="center" class="copyright">Activity Mod Plus v1.1.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://phpbb-amod.com" target="_blank" class="copyright">aUsTiN</a></div>
