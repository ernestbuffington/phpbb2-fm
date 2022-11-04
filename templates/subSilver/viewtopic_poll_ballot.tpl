<script type="text/javascript">
<!--
if(config)
{
	config['AJAXed_Poll_counted'] = '{AJAXED_POLL_OPTION_COUNT}';
        config['AJAXed_Poll_View'] = true;
}
//-->
</script>

<table width="100%" cellpadding="0" cellspacing="0"> 
<tr>
	<td class="row2" colspan="2" ondblclick="{AJAXED_POLL_MENU}" id="poll" name="poll"><br /><form method="post" action="{S_POLL_ACTION}"><table cellspacing="0" cellpadding="4" align="center">
	<tr>
		<td align="center"><b class="gen"><div id="poll_title" style="display: inline;">{POLL_QUESTION}</div><div id="poll_edit_title" style="display: none;" onMouseOver="th(true);" onMouseOut="th(false);"><input type="text" name="poll_titled" id="poll_titled" class="post" size="40" maxlength="255" value="{POLL_QUESTION}" /> <input class="mainoption" type="button" value=" {SAVE} " onClick="td(); oc('poll_title', 'poll_edit_title'); return false;" /><input class="mainoption" type="button" value=" {CANCEL} " onClick="oc('poll_title', 'poll_edit_title'); return false;" /></div></b><br /><span class="gensmall">({VOTE_END})</span></td>
	</tr>
	<tr>
		<td align="center" id="poll_option"><table cellspacing="0" cellpadding="2">
		<!-- BEGIN poll_option -->
		<tr>
			<td><input type="radio" name="vote_id" id="vote_id" value="{poll_option.POLL_OPTION_ID}" />&nbsp;</td>
			<td class="gen">{poll_option.POLL_OPTION_CAPTION}</td>
		</tr>
		<!-- END poll_option -->
		</table></td>
	</tr>
	<tr>
		<td align="center"><input type="submit" name="submit" value="{L_SUBMIT_VOTE}" onClick="tl(); return false;" class="liteoption" /></td>
	</tr>
	<tr>
		<td align="center"><b class="gensmall"><a onClick="ti(); return false;" href="{U_VIEW_RESULTS}" class="gensmall">{L_VIEW_RESULTS}</a>
		<!-- BEGIN switch_null_vote -->
		<br /><a href="{U_POLL_NULL_VOTE}" class="gensmall">{L_NULL_VOTE}</a>
		<!-- END switch_null_vote -->
		</b></td>
	</tr>
	</table>{S_HIDDEN_FIELDS}</form></td>
</tr>
</table>