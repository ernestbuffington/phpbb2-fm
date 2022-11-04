{VOTE_MENU}{POST_MENU}{ATTACH_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_POLL_TITLE}</h1> 

<p>{L_POLL_EXPLAIN}</p> 

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"> 
<tr> 
	<th width="5%" class="thCornerL">&nbsp;{L_NO}&nbsp;</th> 
	<th class="thTop">{L_TOPIC}</th> 
	<th class="thTop">{L_POLL} {L_TITLE}</th> 
	<th class="thTop">{L_DATE}</th> 
	<th width="15%" class="thCornerR">{L_ACTION}</th> 
</tr> 
<!-- BEGIN empty --> 
<tr> 
	<td class="row1" align="center" height="30" colspan="5"><span class="gen">{L_NO_POLLS}</span></td> 
</tr> 
<!-- END empty --> 
<!-- BEGIN sondagerow --> 
<tr> 
	<td width="5%" class="{sondagerow.ROW_CLASS}" align="center">{sondagerow.ID}</td> 
	<td class="{sondagerow.ROW_CLASS}"><a href="{sondagerow.T_ID}" target="_blank" class="topictitle">{sondagerow.TITLE_TOPIC}</a></td> 
	<td class="{sondagerow.ROW_CLASS}"><span class="topictitle">{sondagerow.TITLE_SONDAGE}</span></td> 
	<td class="{sondagerow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="postdetails">{sondagerow.SONDAGE_DATE}</span></td> 
	<td class="{sondagerow.ROW_CLASS}" align="right" nowrap="nowrap"><a href="{sondagerow.U_VIEW}">{VIEW}</a> <a href="{sondagerow.U_DELETE}">{DELETE}</a></td> 
</tr> 
<!-- END sondagerow --> 
</table> 
<br /> 

<!-- BEGIN details --> 
<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"> 
<tr> 
      <th class="thCornerL">{L_POSS_RESPONSES}</th> 
	<th class="thCornerR">{L_NO_RESPONSES}</th> 
</tr> 
<!-- BEGIN answerrow --> 
<tr> 
	<td class="row1" width="40%">&nbsp;{details.answerrow.ID_OPTION} - {details.answerrow.OPTION}</td> 
	<td class="row2" align="center">{details.answerrow.NB_ANSWER}</td> 
</tr> 
<!-- END answerrow --> 
<tr> 
	<th colspan="2" class="thTop">{L_USERS_VOTED}</th> 
</tr> 
<tr> 
	<td colspan="2" class="row1">{details.LISTE_VOTANTS}</td> 
</tr> 
</table> 
<!-- END details --> 