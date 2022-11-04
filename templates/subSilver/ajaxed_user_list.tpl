<table width="{WIDTH}" cellpadding="1" cellspacing="0" class="forumline" onClick="uf();">
  <tr>
<!-- BEGIN ajaxednot -->
    <td class="row1"><span class="gensmall">{ajaxednot.NOT_FOUND}</span></td>
<!-- END ajaxednot -->
<!-- BEGIN ajaxed -->
    <td class="row1">
<!-- BEGIN scroll -->
      <div id="user_list_inner" style="height:150px;overflow:auto;">
<!-- END scroll -->
        <table width="100%" cellpadding="1" cellspacing="0" id="The_List_AJAXed">
<!-- BEGIN ajaxedrow -->
	      <tr>
	        <td name="userlist_{ajaxed.ajaxedrow.ID}" id="userlist_{ajaxed.ajaxedrow.ID}" style="background-color:{CLASS_1}" onClick="uc('{ajaxed.ajaxedrow.USERNAME_JAVA}');" onMouseover="uk('userlist_{ajaxed.ajaxedrow.ID}');">
	          <span class="gensmall">
	            <input type="hidden" value="{ajaxed.ajaxedrow.USERNAME_JAVA}" name="userlist_{ajaxed.ajaxedrow.ID}_name" id="userlist_{ajaxed.ajaxedrow.ID}_name" />
	            <a href="javascript://" onClick="uc('{ajaxed.ajaxedrow.USERNAME_JAVA}');" class="gensmall">{ajaxed.ajaxedrow.USERNAME}</a>
	          </span>
	        </td>
	      </tr>
<!-- END ajaxedrow -->
        </table>
<!-- BEGIN scroll -->
      </div>
<!-- END scroll -->
    </td>
<!-- END ajaxed -->
  </tr>
</table>