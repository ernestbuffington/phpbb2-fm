<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>
<table class="forumline" align="center" width="100%" cellpadding="4" cellspacing="1"><form name="add_email" action="{S_HELPDESK}" method="post">
<tr>
	<th class="thHead" colspan="2">&nbsp;{L_TITLE}&nbsp;</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td>
</tr>
<tr>
	<td class="row1" width="38%"><b class="gen">{L_SUBJECT}: *</b></td>		
	<td class="row2"><input type="text" class="post" name="subject" size="50" maxlength="60" /></td>
</tr>	
<tr>
	<td class="row1"><b class="gen">{L_MAIN_1}:</b><br /><span class="gensmall">{L_MAIN_1_EXPLAIN}</span></td>
	<td class="row2"><select name="user_selected">
		<!-- BEGIN emails -->
		<option value="{emails.VAL}">{emails.SRT}</option>
		<!-- END emails -->
	</select></td>
</tr>	
<tr>
	<td class="row1"><b class="gen">{L_MAIN_2}:</b><br /><span class="gensmall">{L_MAIN_2_EXPLAIN}</span></td>
	<td class="row2"><select name="user_importance">
		<!-- BEGIN importance -->
		<option value="{importance.VAL}{importance.SPACE}{importance.DAT}">{importance.VAL}{importance.SPACE}{importance.DAT}</option>
		<!-- END importance -->			
	</select></td>
</tr>	
<tr>
	<td class="row1"><b class="gen">{L_MAIN_3}:</b><br /><span class="gensmall">{L_MAIN_3_EXPLAIN}</span></td>
	<td class="row2"><select name="user_reply">
		<!-- BEGIN reply -->
		<option value="{reply.VAL}">{reply.DAT}</option>
		<!-- END reply -->			
	</select></td>
</tr>
<tr>
	<td class="row1"><b class="gen">{L_ID}:</b></td>	
	<td class="row2"><input type="text" class="post" name="id" size="50" maxlength="100" /></td>
</tr>
<tr>
	<td class="row1"><b class="gen">{L_BODY}: *</b></td>	
	<td class="row2"><textarea class="post" name="body" cols="50" rows="10" /></textarea></td>
</tr>	
<!-- BEGIN switch_vipcode -->
<tr> 
	<td class="row1"><b class="gen">{L_VIP_CODE}: *</b><br /><span class="gensmall">{L_VIP_CODE_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="vip_code" size="10" maxlength="10" value="" /></td>
</tr>
<!-- END switch_vipcode -->
<tr>	
	<td align="center" class="catBottom" colspan="2"><input type="hidden" name="mode" value="send_this"><input type="submit" class="mainoption" value="{L_SUBMIT}" onchange="document.add_email.submit()">&nbsp;&nbsp;<input type="reset" name="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>	
<br />
<table width="100%" cellspacing="2"  align="center"> 
<tr> 
	<td align="right" valign="middle">{JUMPBOX}</td> 
</tr> 
</table> 
<br />
<div align="center" class="copyright">Helpdesk 1.0.1 &copy; 2003, {COPYRIGHT_YEAR} <a href="mailto:austin_inc@hotmail.com" class="copyright" target="_blank">aUsTiN</a></div>
