<table cellpadding="4" cellspacing="1" width="100%" class="forumline"> 
  <tr> 
	<td colspan="3" class="catHead" align="center"><span class="cattitle">{L_VIEWERS}<span></td>
  </tr>
  <tr>
	  <th class="thCornerL" nowrap="nowrap">{L_USERNAME}</th>
	  <th class="thTop" nowrap="nowrap">{L_VIEWS}</th>
	  <th class="thCornerR" nowrap="nowrap">{L_DATE}</th>
  </tr>
<!-- BEGIN memberrow -->
  <tr> 
	<td class="{memberrow.ROW_CLASS}">{memberrow.USERNAME}</td>
	<td class="{memberrow.ROW_CLASS}" align="center" valign="middle"><span class="gensmall">{memberrow.VIEWS}</span></td>
 	<td class="{memberrow.ROW_CLASS}" align="center" valign="middle"><span class="gensmall">{memberrow.TIME}</span></td> 
  </tr>
<!-- END memberrow -->  
<tr>
	<td colspan="3" class="catBottom" align="center"><span class="nav"><a href="javascript:opener.location=('viewtopic.php?t={TOPIC_ID}'); self.close();">{L_CLOSE}</a></span></td>
  </tr>
</table>