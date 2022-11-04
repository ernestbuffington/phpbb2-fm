<script language="Javascript" type="text/javascript">
<!--
function who(topicid)
{
        window.open("viewtopic_posted.php?t="+topicid, "whoposted", "toolbar=no,scrollbars=yes,resizable=yes,width=230,height=300");
}

function who_viewed(topicid)
{
        window.open("viewtopic_viewed.php?t="+topicid, "whoviewed", "toolbar=no,scrollbars=yes,resizable=yes,width=460,height=300");
}
-->
</script>

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

{CPL_MENU_OUTPUT}

<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr> 
	<th class="thCornerL" nowrap="nowrap">&nbsp;{L_TOPIC_TITLE}&nbsp;</th>
	<th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_REPLIES}&nbsp;</th>
	<th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_VIEWS}&nbsp;</th>
	<th width="150" class="thCornerR" nowrap="nowrap">&nbsp;{L_POSTED}&nbsp;</th>
</tr>
<tr> 
	<td colspan="4" align="center" class="row3"><table width="100%" cellspacing="1">
	<tr>
		<td class="nav" nowrap="nowrap">{PAGE_NUMBER}</td>
		<td class="gensmall" nowrap="nowrap">&nbsp;[ {TOTAL_TOPICS} ]&nbsp;</td>
  		<td align="right" class="nav" width="100%" nowrap="nowrap">{PAGINATION}</td>
	</tr>
	</table></td>
</tr>
<!-- BEGIN topics --> 
<tr> 
	<td class="{topics.ROW_CLASS}"><a href="{topics.U_VIEWTOPIC}" class="gen">{topics.TOPIC_TITLE}</a><br /><span class="gensmall"><b>{L_FORUM}:</b> <a href="{topics.U_VIEWFORUM}" class="gensmall">{topics.FORUM_NAME}</a></span></td> 
     	<td class="{topics.ROW_CLASS}" align="center">{topics.TOPIC_REPLIES}</td>
  	<td class="{topics.ROW_CLASS}" align="center">{topics.TOPIC_VIEWS}</td>
    	<td class="{topics.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall">{topics.TOPIC_TIME}</span></td> 
</tr> 
<!-- END topics --> 
<!-- BEGIN switch_no_topics -->
<tr>
	<td colspan="4" class="row1" align="center"><b class="gensmall">{switch_no_topics.L_NO_TOPICS}</b></td>
</tr>    
<!-- END switch_no_topics -->
<tr>
     	<td colspan="4" class="catBottom">&nbsp;</td>
</tr>
</table>

	</td>
</tr>
</table>
<br />
<table width="100%" cellspacing="2"  align="center"> 
<tr> 
	<td align="right">{JUMPBOX}</td> 
</tr> 
</table> 
<br />
<div align="center" class="copyright">Topics I've Started 1.0.2 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.johnabela.com/mods/" class="copyright" target="_blank">AbelaJohnB</a></div>