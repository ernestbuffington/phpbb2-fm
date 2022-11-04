<table width="100" cellspacing="1" cellpadding="4" class="forumline" onClick="uf();">
  <tr>
    <th class="thRight" nowrap="nowrap" onmousedown="xb(ad('misc'))" style="cursor:move;">{L_MENU}</th>
  </tr>
<!-- BEGIN ajaxed_menu -->
  <tr>
    <td class="row1" nowrap="nowrap"><span class="gensmall"><span class="gensmall"><a href="{QOUTE_URL}" class="mainmenu">{L_QOUTE}</a></span></td>
  </tr>
<!-- BEGIN edit -->
<!-- BEGIN quick -->
  <tr>
    <td class="row1" nowrap="nowrap"><span class="gensmall"><span class="gensmall"><a onClick="eb('{POST_ID}');aa('misc',' '); return false;" href="{EDIT_URL}" class="mainmenu">{L_QUICK_EDIT}</a></span></td>
  </tr>
<!-- END quick -->
  <tr>
    <td class="row1" nowrap="nowrap"><span class="gensmall"><span class="gensmall"><a href="{ajaxed_menu.edit.URL}" class="mainmenu">{L_NORMAL_EDIT}</a></span></td>
  </tr>
<!-- END edit -->
<!-- BEGIN delete -->
  <tr>
    <td class="row1" nowrap="nowrap"><span class="gensmall"><span class="gensmall"><a {ajaxed_menu.delete.ONCLICK}href="{ajaxed_menu.delete.URL}" class="mainmenu">{L_DELETE_POST}</a></span></td>
  </tr>
<!-- END delete -->
<!-- BEGIN ip -->
  <tr>
    <td class="row1" nowrap="nowrap"><span class="gensmall"><span class="gensmall"><a {ajaxed_menu.ip.ONCLICK}href="{ajaxed_menu.ip.URL}" class="mainmenu">{L_VIEW_IP}</a></span></td>
  </tr>
<!-- END ip -->
<!-- END ajaxed_menu -->
<!-- BEGIN ajaxed_ip -->
  <tr> 
	<td class="catHead" height="28" nowrap="nowrap"><span class="cattitle"><b>{L_THIS_POST_IP}</b></span></td>
  </tr>
  <tr> 
	<td class="row1" onMouseOver="xe(1);" onMouseOut="xe();"><span class="gen" id="ip">{IP_ADDRESS}</span> <span class="gensmall">[ {POSTS} ]</span></td>
  </tr>
  <tr> 
    <td class="row2" align="center"><span class="gen">[ <a {ONCLICK}href="{U_LOOKUP_IP}" class="mainmenu">{L_LOOKUP_IP}</a> ]</span></td>
  </tr>
  <tr> 
	<td class="catHead" height="28" nowrap="nowrap"><span class="cattitle"><b><a {ONCLICK_BACK}href="javascript://" class="mainmenu">{L_BACK}</a></b></span></td>
  </tr>
<!-- END ajaxed_ip -->
<!-- BEGIN ajaxed_poll -->
<!-- BEGIN view -->
  <tr>
    <td class="row1" nowrap="nowrap"><span class="gensmall"><span class="gensmall"><a id="results" name="results" {ajaxed_poll.view.ONCLICK}href="{ajaxed_poll.view.URL}" class="mainmenu">{L_VIEW_RESULTS}</a></span></td>
  </tr>
<!-- END view -->
<!-- BEGIN edit -->
<!-- BEGIN viewer -->
  <tr>
    <th class="thRight" nowrap="nowrap">{L_POLL_MOD}</th>
  </tr>
<!-- END viewer -->
<!-- BEGIN title -->
  <tr>
    <td class="row1" nowrap="nowrap"><span class="gensmall"><span class="gensmall"><a {ajaxed_poll.edit.title.ONCLICK}href="{U_EDIT}" class="mainmenu">{L_EDIT_TITLE}</a></span></td>
  </tr>
<!-- END title -->
<!-- BEGIN options -->
  <tr>
    <td class="row1" nowrap="nowrap"><span class="gensmall"><span class="gensmall"><a {ajaxed_poll.edit.options.ONCLICK}href="{U_EDIT}" class="mainmenu">{L_EDIT_OPTIONS}</a></span></td>
  </tr>
<!-- END options -->
<!-- END edit -->
<!-- END ajaxed_poll -->
</table>
