{USER_MENU}{CUSTOM_PROFILE_MENU}{PERMS_MENU}{BAN_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_RECENT_LOGINS}</h1> 

<p>{L_RECENT_LOGIN_EXPLAIN}</p> 

<table width="50%" align="center" cellspacing="1" cellpadding="4" class="forumline"> 
<tr> 
	<th class="thCornerL">&nbsp;#&nbsp;</th> 
     	<th class="thTop">&nbsp;{L_USERNAME}&nbsp;</th> 
     	<th class="thCornerR">&nbsp;{L_DAYS_SINCE_LOGIN}&nbsp;</th> 
</tr> 
<!-- BEGIN user_row --> 
<tr> 
	<td class="{user_row.ROW_CLASS}" align="center">{user_row.COUNT}</td> 
	<td class="{user_row.ROW_CLASS}">{user_row.USERNAME}</td> 
	<td class="{user_row.ROW_CLASS}" align="center">{user_row.DAYS_SINCE_LOGIN}</td> 
</tr> 
<!-- END user_row --> 
</table>
<br />
<div align="center" class="copyright">Recent Logins v 1.0.0 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://rapiddr3am.slackslash.net" target="_blank" class="copyright">Antony Bailey</a></div>
