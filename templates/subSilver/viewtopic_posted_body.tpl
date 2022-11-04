<table cellpadding="4" cellspacing="1" width="100%" class="forumline"> 
  <tr> 
	<td colspan="2" class="catHead">{L_TOTAL_POSTS}: <b>{TOTAL_POSTS}</b></td>
  </tr>
  <tr>
	<th class="thLeft" nowrap="nowrap" width="80%">&nbsp;{L_AUTHOR}&nbsp;</th>
	<th class="thRight" nowrap="nowrap" width="20%">&nbsp;{L_POSTS}&nbsp;</th>
  </tr>
  <!-- BEGIN whoposted -->
  <tr> 
	<td class="{whoposted.ROWCLASS}">{whoposted.POSTER}</td>
	<td class="{whoposted.ROWCLASS}" align="center"><span class="genmed">{whoposted.POSTS}</span></td>
  </tr>
  <!-- END whoposted -->
  <tr>
	<td colspan="2" class="catBottom" align="center"><span class="nav"><a href="javascript:opener.location=('viewtopic.php?t={TOPIC_ID}'); self.close();">{L_CLOSE}</a></span></td>
  </tr>
</table>