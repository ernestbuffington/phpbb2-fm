{STYLE_MENU}{TPL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_XS_SETTINGS}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<!-- BEGIN switch_xs_warning -->
<table width="100%" align="center" class="forumline" cellpadding="4" cellspacing="1">
<tr>
	<td class="row1">{L_XS_WARNING}<br /><br /><span class="gensmall">{L_XS_WARNING_EXPLAIN}</span></td>
</tr>
</table>
<br />
<!-- END switch_xs_warning -->

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="admin_styles_config.{PHP}" method="post">
<tr>
  	<th class="thHead" colspan="2">{L_XS_SETTINGS}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_DEFAULT_STYLE}:</b></td>
	<td class="row2">{STYLE_SELECT}</td>
</tr>
<tr>
	<td class="row1"><b>{L_OVERRIDE_STYLE}:</b><br /><span class="gensmall">{L_OVERRIDE_STYLE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="override_user_style" value="1" <?php echo $this->vars['OVERRIDE_STYLE'] ? 'checked="checked" ' : ''; ?>/> {L_YES}&nbsp;&nbsp;<input type="radio" name="override_user_style" value="0" <?php echo !$this->vars['OVERRIDE_STYLE'] ? 'checked="checked" ' : ''; ?>/> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_XS_DEF_TEMPLATE}:</b><br /><span class="gensmall">{L_XS_DEF_TEMPLATE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="xs_def_template" value="{XS_DEF_TEMPLATE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_XS_CHECK_SWITCHES}:</b><br /><span class="gensmall">{L_XS_CHECK_SWITCHES_EXPLAIN}</span></td>
	<td class="row2">&nbsp;<input type="radio" name="xs_check_switches" value="0" <?php echo !$this->vars['XS_CHECK_SWITCHES'] ? 'checked="checked" ' : ''; ?>/> {L_DISABLED}<br />&nbsp;<input type="radio" name="xs_check_switches" value="2" <?php echo $this->vars['XS_CHECK_SWITCHES'] == 2 ? 'checked="checked" ' : ''; ?>/> {L_XS_CHECK_SWITCHES_2}<br />&nbsp;<input type="radio" name="xs_check_switches" value="1" <?php echo $this->vars['XS_CHECK_SWITCHES'] == 1 ? 'checked="checked" ' : ''; ?>/> {L_XS_CHECK_SWITCHES_1}</td>
</tr>
<tr>
	<td class="row1"><b>{L_XS_USE_ISSET}:</b></td>
	<td class="row2"><input type="radio" name="xs_use_isset" value="1" <?php echo $this->vars['XS_USE_ISSET'] ? 'checked="checked" ' : ''; ?>/> {L_YES}&nbsp;&nbsp;<input type="radio" name="xs_use_isset" value="0" <?php echo !$this->vars['XS_USE_ISSET'] ? 'checked="checked" ' : ''; ?>/> {L_NO}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_VIEWTOPIC_STYLE}:</b></td> 
	<td class="row2"><input type="radio" name="viewtopic_style" value="1" <?php echo $this->vars['VIEWTOPIC_STYLE'] ? 'checked="checked" ' : ''; ?>/> {L_YES}&nbsp;&nbsp;<input type="radio" name="viewtopic_style" value="0" <?php echo !$this->vars['VIEWTOPIC_STYLE'] ? 'checked="checked" ' : ''; ?>/> {L_NO}</td> 
</tr>
<tr>
	<th class="thHead" colspan="2">{L_XS_SETTINGS_CACHE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_XS_USE_CACHE}:</b><br /><span class="gensmall">{L_XS_CACHE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="xs_use_cache" value="1" <?php echo $this->vars['XS_USE_CACHE'] ? 'checked="checked" ' : ''; ?>/> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="xs_use_cache" value="0" <?php echo !$this->vars['XS_USE_CACHE'] ? 'checked="checked" ' : ''; ?>/> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_XS_CACHE_DIR}:</b><br /><span class="gensmall">{L_XS_CACHE_DIR_EXPLAIN}</span></td>
	<td class="row2">&nbsp;<input class="post" type="text" name="xs_cache_dir" value="{XS_CACHE_DIR}" /><br /><br />&nbsp;<input type="radio" name="xs_cache_dir_absolute" value="1" <?php echo $this->vars['XS_CACHE_DIR_ABSOLUTE'] ? 'checked="checked" ' : ''; ?>/> {L_XS_DIR_ABSOLUTE}<br /><span class="gensmall">{L_XS_DIR_ABSOLUTE_EXPLAIN}</span><br /><br />&nbsp;<input type="radio" name="xs_cache_dir_absolute" value="0" <?php echo !$this->vars['XS_CACHE_DIR_ABSOLUTE'] ? 'checked="checked" ' : ''; ?>/> {L_XS_DIR_RELATIVE}<br /><span class="gensmall">{L_XS_DIR_RELATIVE_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_XS_AUTO_COMPILE}:</b><br /><span class="gensmall">{L_XS_AUTO_COMPILE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="xs_auto_compile" value="1" <?php echo $this->vars['XS_AUTO_COMPILE'] ? 'checked="checked" ' : ''; ?>/> {L_YES}&nbsp;&nbsp;<input type="radio" name="xs_auto_compile" value="0" <?php echo !$this->vars['XS_AUTO_COMPILE'] ? 'checked="checked" ' : ''; ?>/> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_XS_AUTO_RECOMPILE}:</b><br /><span class="gensmall">{L_XS_AUTO_RECOMPILE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="xs_auto_recompile" value="1" <?php echo $this->vars['XS_AUTO_RECOMPILE'] ? 'checked="checked" ' : ''; ?>/> {L_YES}&nbsp;&nbsp;<input type="radio" name="xs_auto_recompile" value="0" <?php echo !$this->vars['XS_AUTO_RECOMPILE'] ? 'checked="checked" ' : ''; ?>/> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_XS_SEPARATOR}:</b><br /><span class="gensmall">{L_XS_SEPARATOR_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" name="xs_separator" value="{XS_SEPARATOR}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_XS_PHP}:</b><br /><span class="gensmall">{L_XS_PHP_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" name="xs_php" value="{XS_PHP}" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<tr>
	<th class="thHead" colspan="2">{L_XS_DEBUG_HEADER}</th>
</tr>
<tr>
	<td colspan="2" class="row2"><span class="gensmall">{L_XS_DEBUG_EXPLAIN}</span></td>
</tr>
<tr>
	<th class="thHead" colspan="2">{L_XS_DEBUG_VARS}</th>
</tr>
<tr>
	<td class="row1" width="38%"><b>{<i></i>TEMPLATE<i></i>}:</b></td>
	<td class="row2">{TEMPLATE}</td>
</tr>
<tr>
	<td class="row1"><b>{<i></i>PHP<i></i>}:</b></td>
	<td class="row2">{PHP}</td>
</tr>
<tr>
	<td class="row1"><b>{<i></i>TEMPLATE_NAME<i></i>}:</b></td>
	<td class="row2">{TEMPLATE_NAME}</td>
</tr>
<tr>
	<td class="row1"><b>{<i></i>LANG<i></i>}:</b></td>
	<td class="row2">{LANG}</td>
</tr>
<tr>
	<th class="thHead" colspan="2">{XS_DEBUG_HDR1}</th>
</tr>
<tr>
	<td class="row1"><b>{L_XS_DEBUG_TPL_NAME}:</b></td>
	<td class="row2">{XS_DEBUG_FILENAME1}</td>
</tr>
<tr>
	<td class="row1"><b>{L_XS_DEBUG_CACHE_FILENAME}:</b></td>
	<td class="row2">{XS_DEBUG_FILENAME2}</td>
</tr>
<tr>
	<td class="row1"><b>{L_XS_DEBUG_DATA}:</b></td>
	<td class="row2"><span class="gensmall">{XS_DEBUG_DATA}</span></td>
</tr>
<tr>
	<th class="thHead" colspan="2">{XS_DEBUG_HDR2}</th>
</tr>
<tr>
	<td class="row1"><b>{L_XS_DEBUG_TPL_NAME}:</b></td>
	<td class="row2">{XS_DEBUG_FILENAME3}</td>
</tr>
<tr>
	<td class="row1"><b>{L_XS_DEBUG_CACHE_FILENAME}:</b></td>
	<td class="row2">{XS_DEBUG_FILENAME4}</td>
</tr>
<tr>
	<td class="row1"><b>{L_XS_DEBUG_DATA}:</b></td>
	<td class="row2"><span class="gensmall">{XS_DEBUG_DATA2}</span></td>
</tr>
</table>
<br />
<div align="center" class="copyright">eXtreme Styles 1.01 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.trushkin.net/phpbbmods.php" target="_blank" class="copyright">Vjacheslav Trushkin</a></div>

