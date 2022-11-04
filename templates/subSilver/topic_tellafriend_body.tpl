
{ERROR_BOX}

<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{LINK}" class="nav">{TOPIC}</td>
  </tr>
</table>

<table cellpadding="4" cellspacing="1" width="100%" class="forumline"><form action="{SUBMIT_ACTION}" method="post">
  <tr>
	<th class="thHead">{L_TELL_FRIEND_TITLE}</th>
  </tr>
  <tr>
	<td class="row1"><table width="70%" align="center">
	  <tr>
		<td>{L_TELL_FRIEND_SENDER_USER}:</td>
		<td><b>{SENDER_NAME}</b></td>
	  </tr>
	  <tr>
		<td>{L_TELL_FRIEND_SENDER_EMAIL}:</td>
		<td><b>{SENDER_MAIL}</b></td>
	  </tr>
	  <tr>
		<td>{L_TELL_FRIEND_RECIEVER_USER}</td>
		<td><input class="post" type="text" name="friendname" size="35" maxlength="100" /></td>
	  </tr>
	  <tr>
		<td>{L_TELL_FRIEND_RECIEVER_EMAIL}</td>
		<td><input class="post" type="text" name="friendemail" size="35" maxlength="100" /></td>
	  </tr>
	  <tr>
		<td valign=top>{L_TELL_FRIEND_MSG}</td>
		<td><textarea class="post" name="message" rows="10" cols="70">{L_TELL_FRIEND_BODY}</textarea></td>
	  </tr>
	  	<input type="hidden" name="topic" value="">
	</table>
	</td>
  </tr>
  <tr>
	<td class="catBottom" align="center"><input type="hidden" name="topic" value="{TOPIC}" /><input type="hidden" name="link" value="{LINK}" /><input type="submit" name="submit" class="mainoption" value="{L_SUBMIT}" /></td>
  </tr>
</form></table>
<br />
<table width="100%" cellspacing="2" align="center"> 
  <tr> 
	<td align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td> 
  </tr> 
</table> 
