<!-- BEGIN editor -->
<form action="posting.php" method="post" style="display:block" onMouseOver="th(true);" onMouseOut="th(false);">
  <div align="center" style="width:99%" style="z-index:100;">
    <div id="{POST_ID}_preview"></div>
    <div id="{POST_ID}_field">
      <textarea style="margin:0px;padding:4px;width:100%;height:150px" id="{POST_ID}_message" name="{POST_ID}_message" class="post" cols="80" rows="15">{MESSAGE}</textarea>
    </div>
    <div id="{POST_ID}_controls" style="float:right" class="gensmall">
      <input type="hidden" name="mode" value="editpost" /><input type="hidden" name="p" value="{POST_ID}" />
      <input type="checkbox" id="{POST_ID}_addUpdate" name="{POST_ID}_addUpdate" /><label for="{POST_ID}_addUpdate">{L_ADD_UPDATE}&nbsp;&nbsp;</label>
      <input type="button" class="liteoption" value="{L_PREVIEW}" onClick="pz(ad('{POST_ID}_message').value, '{POST_ID}'); return false;" />
      <input type="button" class="mainoption" value="{L_COMPLETE_EDIT}" onclick="ea('{POST_ID}'); return false" />
      <input type="button" class="liteoption" value="{L_CANCEL_EDIT}" onclick="ea({POST_ID},1); return false" />
      <input type="submit" class="mainoption" value="{L_FULL_MODE}" />&nbsp;&nbsp;
    </div>
    <div id="{POST_ID}_sizers" align="left">
      &nbsp;<input type="button" value=" + " onclick="ec(100,'{POST_ID}');" id="rtesizeplus" class="liteoption" />
	  <input type="button" value=" - " onclick="ec(-100,'{POST_ID}');" id="rtesizeminus" class="liteoption" />
      &nbsp;
    </div>
  </div>
</form>
<!-- END editor -->
<!-- BEGIN preview -->
<fieldset>
  <legend><b>{L_PREVIEW}</b> :: <a onClick="ta('{POST_ID}_field');" class="mainmenu">{L_EDITOR}</a> :: <a onClick="aa('{POST_ID}_preview', ''); return false;" class="mainmenu">{L_CLOSE}</b></legend>
  <table width="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td>
        <span class="postbody">{MESSAGE}</span>
      </td>
    </tr>
  </table>
</fieldset>
<div class="gensmall" style="float:right"><a onClick="ta('{POST_ID}_preview');" class="mainmenu">{L_TOP}</a> :: <a onClick="aa('{POST_ID}_preview', ''); return false;" class="mainmenu"><b>{L_CLOSE}</b></a></div>
<br />
<br />
<!-- END preview -->