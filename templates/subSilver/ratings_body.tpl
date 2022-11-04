
<h1>{PAGE_TITLE}</h1>

<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

<table cellpadding="4" cellspacing="1" width="100%" class="forumline"><form method="get" name="ratings_form" action="{U_RATINGS}">
<tr> 
	<th align="center" class="thCornerL">&nbsp;{L_COLUMN1}&nbsp;</th> 
	<th align="center" class="thTop">&nbsp;{L_TOPIC}&nbsp;</th> 
	<th align="center" class="thTop">&nbsp;&nbsp;{L_COLUMN3}&nbsp;&nbsp;</th> 
	<th align="center" class="thTop">&nbsp;{L_COLUMN4}&nbsp;</th> 
	<th align="center" class="thCornerR">&nbsp;{L_COLUMN5}&nbsp;</th> 
</tr> 
<!-- BEGIN rating --> 
<tr> 
	<td class="row1" align="center" valign="middle">{rating.COLUMN1}</td> 
	<td class="row2" valign="middle"><a class="topictitle" href="{rating.COLUMN2}">{rating.TOPIC_TITLE}</a></td> 
	<td class="row1" align="center" valign="middle">{rating.COLUMN3}</td>
	<td class="row2" align="center" valign="middle">{rating.COLUMN4}</td> 
	<td class="row3Right" align="center" valign="middle" nowrap="nowrap">{rating.COLUMN5}</td> 
</tr> 
<!-- END rating --> 
<!-- BEGIN norating --> 
<tr> 
	<td class="row1" colspan="5" height="30" align="center" valign="middle"><span class="gen">{L_NO_RATINGS}</span></td> 
</tr> 
<!-- END norating --> 
<tr>
	<td colspan="5" class="catBottom" align="center"><span class="genmed">{L_SCREEN_TYPE}: <select name="type">
		<!-- BEGIN screen_type --> 
		<option value="{screen_type.VALUE}"{screen_type.SELECTED}>{screen_type.TITLE}</option>
		<!-- END screen_type --> 
	</select> &nbsp;
	{L_FORUM}: <select name="forum_id">
		<!-- BEGIN forums --> 
		<option value="{forums.ID}"{forums.SELECTED}>{forums.TITLE}</option>
		<!-- END forums --> 
	</select> &nbsp;
	{L_INCLUDE_BY}: <select name="ratingsby">
		<!-- BEGIN ratingsby --> 
		<option value="{ratingsby.ID}"{ratingsby.SELECTED}>{ratingsby.TITLE}</option>
		<!-- END ratingsby --> 
	</select> <input type="submit" value="{L_GO}" class="liteoption" /></span></td>
</tr>
</table>
<br />
<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
</tr> 
</table> 
<br />
<div align="center" class="copyright">Rating System 1.1 &copy; 2003, 2005 <a href="http://www.mywebcommunities.com" class="copyright" target="_blank">Gentle Giant</a></div>
