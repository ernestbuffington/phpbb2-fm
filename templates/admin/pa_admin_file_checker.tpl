{DOWNLOAD_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_FILE_CHECKER}</h1>

<p>{L_FCHECKER_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_CHECKER_FILE_ACTION}" method="post">
  <tr>
	<th colspan="2" class="thHead">{L_FILE_CHECKER}</th>
  </tr>
<!-- BEGIN check -->
<tr>
    <td class="row1"><b>{L_FILE_CHECKER_SP1}</b></td>
</tr>

<!-- BEGIN check_step1 -->
<tr>
    <td class="row1"><span class="gensmall">{check.check_step1.DEL_DURL}</span></td>
</tr>
<!-- END check_step1 -->
<tr>
    <td class="row1"><b>{L_FILE_CHECKER_SP2}</b></td>
</tr>
<!-- BEGIN check_step2 -->
<tr>
    <td class="row1"><span class="gensmall">{check.check_step2.DEL_SSURL}</span></td>
</tr>
<!-- END check_step2 -->
<tr>
    <td class="row1"><b>{L_FILE_CHECKER_SP3}</b></td>
</tr>
<!-- BEGIN check_step3 -->
<tr>
    <td class="row1"><span style="color: #FF0000" class="gensmall">{check.check_step3.DEL_FILE}</span></td>
</tr>
<!-- END check_step3 -->
<tr>
    <td class="catBottom" align="center"><b>{L_FILE_CHECKER_SAVED}:</b> {SAVED}</td>
</tr>
<!-- END check -->

<!-- BEGIN perform -->
  <tr>
	<td class="row1">{L_FILE_SAFTEY}</td>
  </tr>
  <tr>
	<td align="center" class="catBottom"><input class="mainoption" type="submit" value="{L_FILE_PERFORM}" name="B1" /><input type="hidden" name="safety" value="1"></td>
  </tr>
<!-- END perform -->
</form></table>
<br />
<div align="center" class="copyright">paFileDB phpBB Integration 0.0.9d &amp; <a href="http://www.mx-system.com/" target="_blank" class="copyright">MX Addon 1.2</a> &copy; 2003, {COPYRIGHT_YEAR} Mohd & Haplo</a></div>
