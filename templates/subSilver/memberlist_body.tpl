
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
{S_LETTER_SELECT}{S_LETTER_HIDDEN}	
</tr>
</table>
<br />
	
<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="gensmall" valign="bottom"><b>{PAGE_NUMBER}</b> [ <b>{TOTAL_USERS}</b> ]<span class="nav"><br /><br /><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
	<td valign="top" align="right" class="nav">{PAGINATION}</td>
</tr>
</table>
	
<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{S_MODE_ACTION}">
<tr> 
	  <th class="thCornerL" nowrap="nowrap">{L_PM}</th>
	  <th class="thTop" nowrap="nowrap">{L_USERNAME}</th>
	  <th class="thTop" nowrap="nowrap">{L_LEVEL}</th>
	  <th class="thTop" nowrap="nowrap">{L_USER_RANK}</th> 
	  <th class="thTop" nowrap="nowrap">{L_FROM}</th>
	  <th class="thTop" nowrap="nowrap">{L_JOINED}</th>
	  <th class="thTop" nowrap="nowrap">{L_LOGON}<br />{L_POST_TIME}</th> 
	  {L_KARMA}
	  <th class="thTop" nowrap="nowrap">{L_POSTS}<br />{L_POINTS}</th>
	  <th class="thCornerR" nowrap="nowrap">{L_EMAIL}<br />{L_WEBSITE}</th>
</tr>
<!-- BEGIN memberrow -->
<tr> 
	  <td class="{memberrow.ROW_CLASS}" nowrap="nowrap"><span class="gensmall">{memberrow.PM_IMG}</span></td>
	  <td class="{memberrow.ROW_CLASS}" nowrap="nowrap">{memberrow.VIEW_ONLINE} <a href="{memberrow.U_VIEWPROFILE}" class="genmed">{memberrow.USERNAME}</a> {memberrow.POSTER_GENDER} {memberrow.PHOTO}<span class="gensmall">{memberrow.POSTER_AGE}</span> {memberrow.ZODIAC_IMG} {memberrow.CHINESE_IMG}</td>
	  <td class="{memberrow.ROW_CLASS}" align="center" valign="middle"><span class="gensmall">{memberrow.LEVEL} </span></td>
	  <td class="{memberrow.ROW_CLASS}" align="center" valign="middle"><span class="gensmall">{memberrow.USER_RANK_IMG}{memberrow.USER_RANK}</span></td> 
	  <td class="{memberrow.ROW_CLASS}" align="center" valign="middle"><span class="gensmall">{memberrow.FROM} {memberrow.FLAG}</span></td>
	  <td class="{memberrow.ROW_CLASS}" align="center" valign="middle" nowrap="nowrap"><span class="gensmall">{memberrow.JOINED}</span></td>
	  <td class="{memberrow.ROW_CLASS}" align="center" valign="middle" nowrap="nowrap"><span class="gensmall">{memberrow.LAST_LOGON}<br />{memberrow.LAST_POST_TIME}</span></td> 
	  {memberrow.KARMA}
 	  <td class="{memberrow.ROW_CLASS}" align="center" valign="middle"><span class="gensmall">{memberrow.POSTS}<br />{memberrow.POINTS}</span></td>
	  <td class="{memberrow.ROW_CLASS}" align="center"><span class="gensmall">&nbsp;{memberrow.EMAIL_IMG}&nbsp;<br />&nbsp;{memberrow.WWW_IMG}&nbsp;</span></td>
</tr>
<!-- END memberrow -->
<tr> 
	  <td class="catBottom" colspan="10" align="center"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp; <input type="submit" name="submit" value="{L_SORT}" class="mainoption" /></span></td>
</tr>
</table>
<table width="100%" cellspacing="2" cellpadding="2">
  <tr> 
	<td class="gensmall"><b>{PAGE_NUMBER}</b> [ <b>{TOTAL_USERS}</b> ]</td>
	<td align="right" class="nav">{PAGINATION}</td>
  </tr>
</form></table>

<table width="100%" cellspacing="2" align="center">
  <tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
  </tr>
</table>
