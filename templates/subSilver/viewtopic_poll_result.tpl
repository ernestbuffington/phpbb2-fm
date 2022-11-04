<!-- BEGIN ajax_poll -->
<script type="text/javascript">
<!--
if(config)
{
	config['AJAXed_Poll_counted'] = '{AJAXED_POLL_OPTION_COUNT}';
        config['AJAXed_Poll_View'] = false;
}
//-->
</script>
<!-- END ajax_poll -->

<table width="100%" cellpadding="0" cellspacing="0"> 
<tr> 
	<td class="row2" colspan="2" ondblclick="{AJAXED_POLL_MENU}" id="poll" name="poll"><br /><table cellspacing="0" cellpadding="4" align="center">
	<tr> 
		<td colspan="4" align="center"><b class="gen"><div id="poll_title" style="display: inline;">{POLL_QUESTION}</div><form action="javascript://" method="post" id="poll_edit_title" style="display: none;" onMouseOver="th(true);" onMouseOut="th(false);"><input type="text" name="poll_titled" id="poll_titled" class="post" size="30" maxlength="255" value="{POLL_QUESTION}" /> &nbsp;<input class="mainoption" type="button" value=" {SAVE} " onClick="td(); oc('poll_title', 'poll_edit_title'); return false;" />&nbsp;&nbsp;<input class="liteoption" type="button" value=" {CANCEL} " onClick="oc('poll_title', 'poll_edit_title'); return false;" /></form></b><br /><span class="gensmall">({VOTE_END})</span></td>
	</tr>
	<tr> 
		<td align="center" id="poll_option"><table cellspacing="0" cellpadding="2">
		<!-- BEGIN poll_option -->
		<tr> 
			<td><span class="gen">{poll_option.POLL_OPTION_CAPTION}</span></td>
			<td><table cellspacing="0" cellpadding="0">
			<tr> 
				<td><img src="{poll_option.POLL_OPTION_LCAP}" width="4" alt="" height="12" /></td>
				<td><img src="{poll_option.POLL_OPTION_IMG}" width="{poll_option.POLL_OPTION_IMG_WIDTH}" height="12" alt="{poll_option.POLL_OPTION_PERCENT}" /></td>
				<td><img src="{poll_option.POLL_OPTION_RCAP}" width="4" alt="" height="12" /></td>
			</tr>
			</table></td>
			<td align="center"><b class="gen">&nbsp;{poll_option.POLL_OPTION_PERCENT}&nbsp;</b></td>
			<td align="center" class="gen">[ {poll_option.POLL_OPTION_RESULT} ]</td>
		</tr>
		<!-- END poll_option -->
		</table></td>
	</tr>
	<tr> 
		<td colspan="4" align="center"><b class="gen">{L_TOTAL_VOTES} : {TOTAL_VOTES}</b></td>
	</tr>
	</table><br /></td>
</tr>
</table>