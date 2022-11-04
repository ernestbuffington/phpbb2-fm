{STYLE_MENU}{TPL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<?php

$override = $board_config['override_user_style'];
$style = $board_config['default_style'];

?>

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline">
<tr>
	<th class="thCornerL" align="center" nowrap="nowrap">{L_XS_STYLES_STYLE}</th>
	<th class="thTop" align="center" nowrap="nowrap" width="50">{L_XS_STYLES_TYPE}</th>
	<th class="thTop" align="center" nowrap="nowrap">{L_XS_STYLES_TEMPLATE}</th>
	<th class="thTop" align="center" nowrap="nowrap">{L_XS_STYLES_USER}</th>
	<th class="thCornerR" colspan="2" align="center" nowrap="nowrap" width="15%">{L_XS_STYLES_OPTIONS}</th>
</tr>
<!-- BEGIN styles -->
<?php

$id = $styles_item['ID'];
$users = $styles_item['TOTAL'];
$default = ($style == $id) ? 1 : 0;
if($default)
{
	$row1 = $row2 = $row3 = $row_total = 'row3';
}
else
{
	$row1 = 'row1';
	$row2 = 'row2';
	$row3 = 'row3';
	$row_total = ($users > 0) ? 'row3' : 'row1';
}
?>
<tr>
	<td class="<?php echo $row1; ?>" nowrap="nowrap">{styles.STYLE}</td>
	<td class="<?php echo $row2; ?>" nowrap="nowrap" align="center">{styles.TYPE}</td>
	<td class="<?php echo $row2; ?>" nowrap="nowrap">{styles.TEMPLATE}</td>
	<td class="<?php echo $row_total; ?>" align="center">{styles.TOTAL}</td>
	<td class="<?php echo $row1; ?>" align="center" valign="middle" nowrap="nowrap">
<?php

if(!$default) 
{
?>
	[<a href="{SCRIPT}&amp;setdefault={styles.ID}">{L_XS_STYLES_SET_DEFAULT}</a>]<br />
	<?php } else if($override) { ?>
	[<a href="{SCRIPT}&amp;setoverride=0">{L_XS_STYLES_NO_OVERRIDE}</a>]<br />
	<?php } else { ?>
	[<a href="{SCRIPT}&amp;setoverride=1">{L_XS_STYLES_DO_OVERRIDE}</a>]<br />
	<?php } ?>
	[<a href="{SCRIPT}&amp;moveusers={styles.ID}">{L_XS_STYLES_SWITCH_ALL}</a>]
	</span></td>
	<td class="<?php echo $row1; ?>" nowrap="nowrap" align="right" valign="middle">
	<?php if($users) { ?>
	<form action="{SCRIPT}" method="post" name="select_{styles.ID}" onsubmit="if(document.select_{styles.ID}.style.value == -1){return false;}">{S_HIDDEN_FIELDS}<input type="hidden" name="moveaway" value="{styles.ID}" />
	<select name="style" onchange="document.select_{styles.ID}.submit();">
	<option value="">{L_XS_STYLES_SWITCH_ALL2}</option>
	<option value="0">{L_XS_STYLES_DEFSTYLE}</option>
	<optgroup label="{L_XS_STYLES_AVAILABLE}">
	<?php
		for($i = 0; $i < $styles_count; $i++)
		if($i != $styles_i)
		{
			$item = &$this->_tpldata['styles.'][$i];
			echo '<option value="', $item['ID'], '">', $item['STYLE'], '</option>';
		}
	?>
	</optgroup>
	</select>

	<?php } else { } ?>
	&nbsp;<a href="{styles.U_STYLES_EDIT}">{L_EDIT}</a> <a href="{styles.U_STYLES_DELETE}">{L_DELETE}</a></td>
	</form>
</tr>
<!-- END styles -->
</table>
<br />
<div align="center" class="copyright">eXtreme Styles 1.01 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://www.trushkin.net/phpbbmods.php" target="_blank" class="copyright">Vjacheslav Trushkin</a></div>
