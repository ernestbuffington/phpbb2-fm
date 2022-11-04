<!-- BEGIN input -->
  <tr>
	<td class="row1"><b>{input.FIELD_NAME}:</b><br /><span class="gensmall">{input.FIELD_DESCRIPTION}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="field[{input.FIELD_ID}]" value="{input.FIELD_VALUE}" /></td>
  </tr>
<!-- END input -->
<!-- SPILT -->
<!-- BEGIN textarea -->
  <tr>
	<td class="row1"><b>{textarea.FIELD_NAME}:</b><br /><span class="gensmall">{textarea.FIELD_DESCRIPTION}</span></td>
	<td class="row2"><textarea rows="6" class="post" name="field[{textarea.FIELD_ID}]" cols="32">{textarea.FIELD_VALUE}</textarea></td>
  </tr>
<!-- END textarea -->
<!-- SPILT -->
<!-- BEGIN radio -->
  <tr>
	<td class="row1"><b>{radio.FIELD_NAME}:</b><br /><span class="gensmall">{radio.FIELD_DESCRIPTION}</span></td>
	<td class="row2">
	<!-- BEGIN row -->	
	<input type="radio" name="field[{radio.FIELD_ID}]" value="{radio.row.FIELD_VALUE}" {radio.row.FIELD_SELECTED} /> {radio.row.FIELD_VALUE}&nbsp;
	<!-- END row -->
	</td>
  </tr>	
<!-- END radio -->
<!-- SPILT -->
<!-- BEGIN select -->
  <tr>
	<td class="row1"><b>{select.FIELD_NAME}:</b><br /><span class="gensmall">{select.FIELD_DESCRIPTION}</span></td>
	<td class="row2"><select name="field[{select.FIELD_ID}]">
		<!-- BEGIN row -->	
		<option value="{select.row.FIELD_VALUE}"{radio.row.FIELD_SELECTED} title="{select.row.FIELD_VALUE}">{select.row.FIELD_VALUE}</option>
		<!-- END row -->
	</select></td>
  </tr>	
<!-- END select -->
<!-- SPILT -->
<!-- BEGIN select_multiple -->
  <tr>
	<td class="row1"><b>{select_multiple.FIELD_NAME}:</b><br /><span class="gensmall">{select_multiple.FIELD_DESCRIPTION}</span></td>
	<td class="row2"><select name="field[{select_multiple.FIELD_ID}][]" multiple="multiple" size="4">
		<!-- BEGIN row -->	
		<option value="{select_multiple.row.FIELD_VALUE}"{select_multiple.row.FIELD_SELECTED} title="{select_multiple.row.FIELD_VALUE}">{select_multiple.row.FIELD_VALUE}</option>
		<!-- END row -->
	</select></td>
  </tr>	
<!-- END select_multiple -->
<!-- SPILT -->
<!-- BEGIN checkbox -->
  <tr>
	<td class="row1"><b>{checkbox.FIELD_NAME}:</b><br /><span class="gensmall">{checkbox.FIELD_DESCRIPTION}</span></td>
	<td class="row2">
	<!-- BEGIN row -->	
	<input type="checkbox" name="field[{checkbox.FIELD_ID}][{checkbox.row.FIELD_VALUE}]" value="{checkbox.row.FIELD_VALUE}" {checkbox.row.FIELD_CHECKED}> {checkbox.row.FIELD_VALUE}&nbsp;
	<!-- END row -->
	</td>
  </tr>	
<!-- END checkbox -->