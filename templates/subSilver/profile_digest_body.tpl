<script type="text/javascript"> 
<!-- Hide Javascript from validators
function excludeAllForums()
{
	// Do not allow All Forums to be selected if Exclude option is selected
	  if (document.subscribe.digest_include_forum[1].checked)
	    document.subscribe.all_forums.checked = false;

	  var isW3C = false;
      var isIE = false;
         
      if (!(document.all)) isW3C = true;
      if (document.all) isIE = true;

      var element_name = new String();
      if (isIE)
	  {
        for (i = 0; i<dynamicforums.children.length; i++)
		{
          thisobject = dynamicforums.children[i];
          element_name = thisobject.name;
          if (element_name != null)
		  {
            if (element_name.substr(0, 5) == "forum")
               thisobject.checked = false;
          }
        }
      }

	  if (isW3C)
	  { 
        var x = document.getElementById('dynamicforums');
        for (i = 0; i<x.childNodes.length; i++)
		{
           thisobject = x.childNodes[i];
           element_name = thisobject.name;
           if (element_name != null)
		   {
             if (element_name.substr(0, 5) == "forum")
                thisobject.checked = false;
           }
         }
       }
	  return;
	}

    function toggleAllForums ()
	{
      // If any particular forum is selected, this will unselect or select the all forums checkbox

      if (document.subscribe.all_forums.checked)
        document.subscribe.all_forums.checked = false;
      return;
    }

    function unCheckSubscribedForums (checkbox)
	{
      // If all forums checkbox is checked, must uncheck all the individual forums. This involves some fancy Javascript due to IE's proprietary DOM, darn them.

      var isW3C = false;
      var isIE = false;
      is_checked = checkbox.checked;
         
      if (!(document.all)) isW3C = true; /* standard compliant DOM, probably */
      if (document.all) isIE = true;

      // Check/Uncheck for IE DOM

      var element_name = new String();
      if (isIE)
	  {
        for (i = 0; i<dynamicforums.children.length; i++)
		{
          thisobject = dynamicforums.children[i];
          element_name = thisobject.name;
          if (element_name != null)
		  {
            if (element_name.substr(0, 5) == "forum")
               thisobject.checked = is_checked;
          }
        }
      }

	  if (isW3C)
	  { 
		// Check/Uncheck for Mozilla / W3C Compatible DOM

        var x = document.getElementById('dynamicforums');
        for (i = 0; i<x.childNodes.length; i++)
		{
           thisobject = x.childNodes[i];
           element_name = thisobject.name;
           if (element_name != null)
		   {
             if (element_name.substr(0, 5) == "forum")
                thisobject.checked = is_checked;
           }
         }
       }
	 excludeAllForums()  
     return;
    }

    function unsubscribeCheck()
	{
      // If all forums is unchecked and none of the elected forums are checked, this means unsubscribe. An unsubscribe message will occur on form submittal in this case, unless the user cancels the confirm.

      var isW3C = false;
      var isIE = false;
      var num_checked;
      var process_form;
         
      if (!(document.all)) isW3C=true; /* standard compliant DOM, probably */
      if (document.all) isIE=true;
      num_checked = 0;
      process_form = true;

      // Check/Uncheck for IE DOM

      var element_name = new String();
      if (isIE)
	  {
        for (i = 0; i<dynamicforums.children.length; i++)
		{
          thisobject = dynamicforums.children[i];
          element_name = thisobject.name;
          if (element_name != null)
		  {
            if (element_name.substr(0, 5) == "forum")
			{
               if (thisobject.checked == true)
                 num_checked++;
            }
          }
        }
      }

	  if (isW3C)
	  { 
		// Check/Uncheck for Mozilla / W3C Compatible DOM

        var x = document.getElementById('dynamicforums');
        for (i = 0; i<x.childNodes.length; i++)
		{
           thisobject = x.childNodes[i];
           element_name = thisobject.name;
           if (element_name != null)
		   {
             if (element_name.substr(0, 5) == "forum")
               if (thisobject.checked == true)
                 num_checked++;
           }
         }
      }

      // If no forums were checked but the user did not request to cancel subscription then this probably means to cancel the subscription. Confirm this is the case. If the user cancels the form will not be submitted.

      if ((num_checked == 0) && (document.subscribe.all_forums.checked == false) && (document.subscribe.digest_type[0].checked == false))
	  {
        process_form = confirm("{NO_FORUMS_SELECTED}");
        if (process_form)
          document.subscribe.digest_type[0].checked = true; // set "None" radio button for digest_type
      }       
      return process_form;
    }

    // End hiding Javascript -->
</script>

        
<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

{CPL_MENU_OUTPUT}

{ERROR_BOX}

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form name="subscribe" action="{S_POST_ACTION}" method="post" onsubmit="return unsubscribeCheck();">

<tr>
	<th class="thHead" colspan="2">{PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{DIGEST_EXPLANATION}</span></td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_DIGEST_DELETE}:</b><br /><span class="gensmall">{L_DIGEST_DELETE_EXPLAIN}</span></td>
	<td class="row2"><input type="checkbox" name="digest_delete" value="1"></td>
</tr>
<tr>
	<td class="row1"><b>{L_DIGEST_NAME}:</b><br /><span class="gensmall">{L_DIGEST_NAME_EXPLAIN}</span></td>
	<td class="row2"><input type="text" name="digest_name" size="25" maxlength="25" value="{DIGEST_NAME}"></td>
</tr>
<tr>
	<td class="row1"><b>{L_DIGEST_FREQUENCY}:</b><br /><span class="gensmall">{L_DIGEST_FREQUENCY_DESC}</span></td>
	<td class="row2">{S_DIGEST_FREQUENCY}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ACTIVITY}</b></td>
	<td class="row2"><input type="radio" name="digest_activity" {ACTIVITY_YES_CHECKED} value="{S_TRUE}" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="digest_activity" {ACTIVITY_NO_CHECKED} value="{S_FALSE}" /> {L_NO}</td>
</tr>
<!-- BEGIN allow_admin -->
<tr>
	<td class="row1"><b>{L_SEND_HOUR}:</b></td>
	<td class="row2"><input class="post" type="text" name="digest_send_hour" size="2" maxlength="2" value="{DIGEST_SEND_HOUR}" /> : 00</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_SEND_DATE}:</b></td>
	<td class="row2"><input class="post" type="text" name="digest_send_day" size="2" maxlength="2" value="{DIGEST_SEND_DAY}" /> - <input class="post" type="text" name="digest_send_month" size="2" maxlength="2" value="{DIGEST_SEND_MONTH}" /> - <input class="post" type="text" name="digest_send_year" size="4" maxlength="4" value="{DIGEST_SEND_YEAR}" /></td>
</tr>
<!-- END allow_admin -->
<!-- BEGIN allow_moderator -->
<tr>
	<td class="row1"><b>{L_DIGEST_MODERATOR}:</b></td>
	<td class="row2"><input type="radio" name="digest_moderator" {DIGEST_MODERATOR_YES_CHECKED} value="{S_TRUE}" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="digest_moderator" {DIGEST_MODERATOR_NO_CHECKED} value="{S_FALSE}" /> {L_NO}</td>
</tr>
<!-- END allow_moderator -->
<tr>
	<td class="row1"><b>{L_FORMAT}:</b><br /><span class="gensmall">{L_FORMAT_DESC}</span></td>
	<td class="row2" valign="middle"><input type="radio" name="digest_format" {HTML_CHECKED} value="{S_HTML}" /> {L_HTML}&nbsp;&nbsp;<input type="radio" name="digest_format" {TEXT_CHECKED} value="{S_TEXT}" /> {L_TEXT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_TEXT}:</b></td>
	<td class="row2"><input type="radio" name="digest_show_text" {SHOW_TEXT_FULL} value="-1" /> {L_FULL}&nbsp;&nbsp;<input type="radio" name="digest_show_text" {SHOW_TEXT_SHORT} value="1" /> {L_SHORT}&nbsp;&nbsp;<input type="radio" name="digest_show_text" {SHOW_TEXT_NONE} value="0" /> {L_NO_TEXT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SHOW_MINE}:</b></td>
	<td class="row2"><input type="radio" name="digest_show_mine" {SHOW_MINE_YES_CHECKED} value="{S_TRUE}" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="digest_show_mine" {SHOW_MINE_NO_CHECKED} value="{S_FALSE}" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_NEW_ONLY}:</b><br /><span class="gensmall">{L_NEW_ONLY_DESC}</span></td>
	<td class="row2"><input type="radio" name="digest_new_only" {NEW_ONLY_YES_CHECKED} value="{S_TRUE}" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="digest_new_only" {NEW_ONLY_NO_CHECKED} value="{SFALSE}" /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_SEND_ON_NO_MESSAGES}:</b></td>
	<td class="row2"><input type="radio" name="digest_send_on_no_messages" {SEND_ON_NO_MESSAGES_YES_CHECKED} value="{S_TRUE}" /> {L_YES}&nbsp;&nbsp;<input type="radio" name="digest_send_on_no_messages" {SEND_ON_NO_MESSAGES_NO_CHECKED} value="{S_FALSE}" /> {L_NO}</td>
</tr>
<!-- BEGIN allow_exclude_forums -->
<tr>
	<td class="row1"><b>{L_DIGEST_INCLUDE}:</b><br /><span class="gensmall">{L_DIGEST_INCLUDE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="digest_include_forum" {INCLUDE_FORUM_YES_CHECKED} value="{S_TRUE}" /> {L_INCLUDE}&nbsp;&nbsp;<input type="radio" name="digest_include_forum" {INCLUDE_FORUM_NO_CHECKED} value="{S_FALSE}" onclick="excludeAllForums();"/> {L_EXCLUDE}</td>
</tr>
<!-- END allow_exclude_forums -->
<tr>
	<td class="row1"><b>{L_FORUM_SELECTION}:</b></td>
	<td class="row2"><input type="checkbox" name="all_forums" {ALL_FORUMS_CHECKED} onclick="unCheckSubscribedForums(this);" /> {L_ALL_SUBSCRIBED_FORUMS}<br />
	<div id="dynamicforums">
	<!-- BEGIN forums -->
	<input type="checkbox" name="{forums.FORUM_NAME}" {forums.CHECKED} onclick="toggleAllForums ();" /> <a onfocus="this.blur()"; title="{forums.FORUM_DESCRIPTION}">{forums.FORUM_LABEL}</a><br />
        <!-- END forums -->
        </div></td>
</tr>
<tr>
	<td colspan="2" class="row2"><span class="gensmall">{L_DIGEST_ACCEPT}</span></td>
</tr>
<tr>
	<td colspan="2" align="center" class="catBottom"><input type="hidden" name="create_new" value="{DIGEST_CREATE_NEW_VALUE}" /><input type="hidden" name="digest_id" value="{DIGEST_ID}" /><input type="hidden" name="digest_type" value="{DIGEST_TYPE}" /><input type="hidden" name="last_digest" value="{LAST_DIGEST}" /><button type="submit" class="mainoption"><span class="gen">{L_SUBMIT}</span></button>&nbsp;<button type="reset" class="liteoption"><span class="gen">{L_RESET}</span></button></td>
</tr>
</form></table>
	</td>
</tr>
</table>
<br />
<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td align="right">{JUMPBOX}</td>
</tr>
</table>
