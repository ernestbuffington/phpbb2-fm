{DB_MENU}{UTILS_MENU}{LOG_MENU}{LANG_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script type="text/javascript">
<!--
function selectValueFromSelect(/*HTMLSelectElement*/ sel, /*String*/ val) 
{
	for(var i=0;sel.options[i].value != val;i++) void(0);
  	sel.selectedIndex = i;
}
// -->
</script>

<body onload='selectValueFromSelect(document.getElementsByName("minute")[0], "{MINUTES}"); selectValueFromSelect(document.getElementsByName("hour")[0], "{HOURS}"); selectValueFromSelect(document.getElementsByName("day")[0], "{DAYS}"); selectValueFromSelect(document.getElementsByName("month")[0], "{MONTHS}"); selectValueFromSelect(document.getElementsByName("weekday")[0], "{WEEKDAYS}");'>

<h1>{L_AUTOMATIC_BACKUP}</h1>

<p>{L_BACKUP_EXPLAIN}</p>

<p>{L_FORM_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form method="post" action="{FORM_ACTION}">
<tr>
	<th class="thHead" colspan="2">{L_CONFIGURATION}</th>
</tr>
<tr> 
   	<td class="row1" width="38%"><b>{L_ENABLE_AUTOBACKUP}:</b></td> 
   	<td class="row2"><input type="radio" name="enable_autobackup" value="1" {ENABLE_AUTOBACKUP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_autobackup" value="0" {ENABLE_AUTOBACKUP_NO} /> {L_NO}</td> 
</tr> 
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form method="post" action="{FORM_ACTION}">
<input type="hidden" name="perform" value="backup" /><input type="hidden" name="drop" value="1" /><input type="hidden" name="perform" value="backup" />
<tr>
	<th class="thHead" colspan="2">{L_AUTOMATIC_BACKUP}</th>
</tr>
<tr> 
	<td class="row1" width="38%"><b>{L_BACKUP_TYPE}:</b></td>
	<td class="row2"><input type="radio" name="backup_type" value="full" {FULL_BACKUP} /> {L_FULL_BACKUP}&nbsp;&nbsp;<input type="radio" name="backup_type" value="structure" {STRUCTURE_BACKUP} /> {L_STRUCTURE_BACKUP}&nbsp;&nbsp;<input type="radio" name="backup_type" value="data" {DATA_BACKUP} /> {L_DATA_BACKUP}</td>
</tr>
<tr> 
  	<td class="row1"><b>{L_PHPBB_ONLY}:</b></td>
  	<td class="row2"><input type="checkbox" name="phpbb_only" value="1" {PHPBB_ONLY_YES} /></td>
</tr>
<tr> 
  	<td class="row1"><b>{L_NO_SEARCH}:</b></td>
  	<td class="row2"><input type="checkbox" name="no_search" value="1" {NO_SEARCH} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_IGNORE_TABLES}:</b><br /><span class="gensmall">{L_IGNORE_TABLES_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="ignore_tables" size="40" value="{IGNORE_TABLES}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_DELAY_TIME}:</b></td>
	<td class="row2"><input class="post" type="text" name="delay_time" size="5" value="{DELAY_TIME}" /></td>
</tr>
<tr> 
	<td class="row1" rowspan="2"><b>{L_EMAIL_TRUE}:</b><br /><span class="gensmall">{L_EMAIL_TRUE_EXPLAIN}</span></td>
	<td class="row2" valign="middle"><input type="checkbox" name="email_true" value="1" {EMAIL_TRUE} /></td>
</tr>
<tr>
	<td class="row2"><input class="post" type="text" name="email" size="40" maxlength="255" value="{EMAIL}" /></td>
</tr>
<tr> 
	<td class="row1" rowspan="2"><b>{L_FTP_TRUE}:</b></td>
	<td class="row2"><input type="checkbox" name="ftp_true" value="1" {FTP_TRUE} /></td>
</tr>
<tr>
	<td class="row2"><table cellspacing="1" cellpadding="2">
	<tr> 
		<td colspan="2">
	</tr>
	<tr> 
		<td><b>{L_FTP_SERVER}:</b></td>
		<td><input class="post" type="text" name="ftp_server" size="20" value="{FTP_SERVER}" /></td>
	</tr>
	<tr> 
		<td><b>{L_FTP_USER}:</b></td>
		<td><input class="post" type="text" name="ftp_user_name" size="20" value="{FTP_USER}" /></td>
	</tr>
	<tr> 
		<td><b>{L_FTP_PASS}:</b></td>
		<td><input class="post" type="password" name="ftp_user_pass" size="20" value="{FTP_PASS}" /></td>
	</tr>
	<tr> 
		<td><b>{L_FTP_DIRECTORY}:</b></td>
		<td><input class="post" type="text" name="ftp_directory" size="20" value="{FTP_DIRECTORY}" /></td>
	</tr>
	</table></td>
</tr>
<tr> 
  	<td class="row1"><b>{L_WRITE_BACKUPS_TRUE}:</b></td>
  	<td class="row2"><input type="checkbox" name="write_backups_true" value="1" {WRITE_BACKUPS_TRUE} /></td>
</tr>
<tr>
	<td class="row1"><b>{L_FILES_TO_KEEP}:</b><br /><span class="gensmall">{L_FILES_TO_KEEP_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="files_to_keep" size="5" value="{FILES_TO_KEEP}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_LEVEL}:</b></td>
	<td class="row2"><input type="radio" name="backup_skill" value="0"{SKILL_BASIC} /> {L_BASIC_BACKUP}&nbsp;&nbsp;<input type="radio" name="backup_skill" value="1"{SKILL_ADVANCED} /> {L_ADVANCED_BACKUP}</td>
</tr>
<tr>
	<th class="thHead" colspan="2">{L_BASIC_BACKUP}</th>
</tr>
<tr> 
	<td class="row1" colspan="2"><table cellpadding="4" cellspacing="4" align="center">
	<tr>
		<td><b>{L_MINUTES}:</b><br /><select name="minute" size="10">
			<option value="*"> {L_EVERY_MINUTE}</option>
			<option value="*/2"> {L_EVERY_OTHER_MINUTES}</option>
			<option value="*/5"> {L_EVERY_FIVE_MINUTES}</option>
			<option value="*/10"> {L_EVERY_TEN_MINUTES}</option>
			<option value="*/15"> {L_EVERY_FIFTEEN_MINUTES}</option>
			<option value="0"> 0</option>
			<option value="1"> 1</option>
			<option value="2"> 2</option>
			<option value="3"> 3</option>
			<option value="4"> 4</option>
			<option value="5"> 5</option>
			<option value="6"> 6</option>
			<option value="7"> 7</option>
			<option value="8"> 8</option>
			<option value="9"> 9</option>
			<option value="10"> 10</option>
			<option value="11"> 11</option>
			<option value="12"> 12</option>
			<option value="13"> 13</option>
			<option value="14"> 14</option>
			<option value="15"> 15</option>
			<option value="16"> 16</option>
			<option value="17"> 17</option>
			<option value="18"> 18</option>
			<option value="19"> 19</option>
			<option value="20"> 20</option>	
			<option value="21"> 21</option>
			<option value="22"> 22</option>
			<option value="23"> 23</option>
			<option value="24"> 24</option>
			<option value="25"> 25</option>
			<option value="26"> 26</option>
			<option value="27"> 27</option>
			<option value="28"> 28</option>
			<option value="29"> 29</option>
			<option value="30"> 30</option>
			<option value="31"> 31</option>
			<option value="32"> 32</option>
			<option value="33"> 33</option>
			<option value="34"> 34</option>
			<option value="35"> 35</option>	
			<option value="36"> 36</option>	
			<option value="37"> 37</option>
			<option value="38"> 38</option>
			<option value="39"> 39</option>
			<option value="40"> 40</option>
			<option value="41"> 41</option>
			<option value="42"> 42</option>
			<option value="43"> 43</option>
			<option value="44"> 44</option>
			<option value="45"> 45</option>
			<option value="46"> 46</option>
			<option value="47"> 47</option>
			<option value="48"> 48</option>
			<option value="49"> 49</option>
			<option value="50"> 50</option>
			<option value="51"> 51</option>
			<option value="52"> 52</option>
			<option value="53"> 53</option>
			<option value="54"> 54</option>
			<option value="55"> 55</option>
			<option value="56"> 56</option>
			<option value="57"> 57</option>
			<option value="58"> 58</option>
			<option value="59"> 59</option>
		</select><br /><br /></td>
		<td><b>{L_HOURS}:</b><br /><select name="hour" size="5">
			<option value="*"> {L_EVERY_HOUR}</option>
			<option value="*/2"> {L_EVERY_OTHER_HOUR}</option>
			<option value="*/4"> {L_EVERY_FOUR_HOURS}</option>
			<option value="*/6"> {L_EVERY_SIX_HOURS}</option>
			<option value="0"> 0 = 12 AM/{L_MIDNIGHT}</option>
			<option value="1"> 1 = 1 AM</option>
			<option value="2"> 2 = 2 AM</option>
			<option value="3"> 3 = 3 AM</option>
			<option value="4"> 4 = 4 AM</option>
			<option value="5"> 5 = 5 AM</option>
			<option value="6"> 6 = 6 AM</option>
			<option value="7"> 7 = 7 AM</option>
			<option value="8"> 8 = 8 AM</option>
			<option value="9"> 9 = 9 AM</option>
			<option value="10"> 10 = 10 AM</option>
			<option value="11"> 11 = 11 AM</option>
			<option value="12"> 12 = 12 PM/{L_NOON}</option>
			<option value="13"> 13 = 1 PM</option>
			<option value="14"> 14 = 2 PM</option>
			<option value="15"> 15 = 3 PM</option>
			<option value="16"> 16 = 4 PM</option>
			<option value="17"> 17 = 5 PM</option>
			<option value="18"> 18 = 6 PM</option>
			<option value="19"> 19 = 7 PM</option>
			<option value="20"> 20 = 8 PM</option>
			<option value="21"> 21 = 9 PM</option>
			<option value="22"> 22 = 10 PM</option>
			<option value="23"> 23 = 11 PM</option>
		</select><br /><br /><b>{L_DAYS}:</b><br /><select name="day" size="5">
			<option value="*"> {L_EVERY_DAY}</option>
			<option value="1"> 1</option>
			<option value="2"> 2</option>
			<option value="3"> 3</option>
			<option value="4"> 4</option>
			<option value="5"> 5</option>
			<option value="6"> 6</option>
			<option value="7"> 7</option>
			<option value="8"> 8</option>
			<option value="9"> 9</option>
			<option value="10"> 10</option>
			<option value="11"> 11</option>
			<option value="12"> 12</option>
			<option value="13"> 13</option>
			<option value="14"> 14</option>
			<option value="15"> 15</option>
			<option value="16"> 16</option>
			<option value="17"> 17</option>
			<option value="18"> 18</option>
			<option value="19"> 19</option>
			<option value="20"> 20</option>
			<option value="21"> 21</option>
			<option value="22"> 22</option>
			<option value="23"> 23</option>
			<option value="24"> 24</option>
			<option value="25"> 25</option>
			<option value="26"> 26</option>
			<option value="27"> 27</option>
			<option value="28"> 28</option>
			<option value="29"> 29</option>
			<option value="30"> 30</option>
			<option value="31"> 31</option>
		</select><br /><br /></td>
		<td><b>{L_MONTHS}:</b><br /><select name="month" size="5">
			<option value="*"> {L_EVERY_MONTH}</option>
			<option value="1"> {L_JANUARY}</option>
			<option value="2"> {L_FEBRUARY}</option>
			<option value="3"> {L_MARCH}</option>
			<option value="4"> {L_APRIL}</option>
			<option value="5"> {L_MAY}</option>
			<option value="6"> {L_JUNE}</option>
			<option value="7"> {L_JULY}</option>
			<option value="8"> {L_AUGUST}</option>
			<option value="9"> {L_SEPTEMBER}</option>
			<option value="10"> {L_OCTOBER}</option>
			<option value="11"> {L_NOVEMBER}</option>
			<option value="12"> {L_DECEMBER}</option>
		</select><br /><br /><b>{L_WEEKDAYS}:</b><br /><select name="weekday" size="5">
			<option value="*"> {L_EVERY_WEEKDAY}</option>
			<option value="Sun"> {L_SUNDAY}</option>
			<option value="Mon"> {L_MONDAY}</option>
			<option value="Tue"> {L_TUESDAY}</option>
			<option value="Wed"> {L_WEDNESDAY}</option>
			<option value="Thu"> {L_THURSDAY}</option>
			<option value="Fri"> {L_FRIDAY}</option>
			<option value="Sat"> {L_SATURDAY}</option>
		</select></td>
	</tr>
	</table></td>
</tr>
<tr>
	<th class="thHead" colspan="2">{L_ADVANCED_BACKUP}</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_ADVANCED_BACKUP_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1" colspan="2"><table cellpadding="2" cellspacing="1" align="center">
	<tr>
		<td><b>{L_MINUTES}</td>
		<td><b>{L_HOURS}</td>
		<td><b>{L_DAYS}</td>
		<td><b>{L_MONTHS}</td>
		<td><b>{L_WEEKDAYS}</td>
	</tr>
	<tr>
        	<td><input class="post" type="text" name="advanced_minute" size="5" value="{MINUTES}"></td>
        	<td><input class="post" type="text" name="advanced_hour" size="5" value="{HOURS}"></td>
        	<td><input class="post" type="text" name="advanced_day" size="5" value="{DAYS}"></td>
        	<td><input class="post" type="text" name="advanced_month" size="5" value="{MONTHS}"></td>
        	<td><input class="post" type="text" name="advanced_weekday" size="5" value="{WEEKDAYS}"></td>
	</tr>
	</table></td>
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Automatic Database Backup 1.0.0 &copy; 2006, {COPYRIGHT_YEAR} <a href="http://phpbb-login.strangled.net/" class="copyright" target="_blank">kkroo</a></div>
