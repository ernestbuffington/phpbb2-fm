<?php
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/teamspeak.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

// retrieve server info
$tss2info->getInfo();
$tss2info->userName = $lang['Guest'];
$nickname = $userdata['username'];

$gen_simple_header = TRUE;
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

/*
$template->set_filenames(array( 
      'body' => 'teamspeak_body.tpl')
); 

*/

echo '<meta http-equiv="refresh" content="' . $board_config['ts_refreshtime'] . '; url=' . append_sid('teamspeak.'.$phpEx) . '">	
<table height="100%" width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td class="row1" valign="top">

	<table width="100%" cellpadding="0" cellspacing="0">
  	<tr>
		<td><table width="100%">
		<tr>
			<td width="16"><img src="images/teamspeak/teamspeak.gif" width="16" height="16" alt="' . $board_config['ts_sitetitle'] . '" title="' . $board_config['ts_sitetitle'] . '" /></td>
			<td width="100%"><b class="gensmall">' . $board_config['ts_sitetitle'] . '</b></td>
			<td width="16" align="right"><a href="' . append_sid('teamspeak.'.$phpEx) . '"><img src="images/teamspeak/refresh.gif" width="16" height="16" alt="' . $lang['Refresh'] . '" title="' . $lang['Refresh'] . '" /></a></td>
		</tr>
		</table></td>
	</tr>';

$counter = 0;
foreach($tss2info->channelList as $channelInfo) 
{
	if($channelInfo['parent'] == -1)	
	{
		$channelname = $channelInfo['channelname'];
  		
  		// determine codec (verbose)
		$codec = $tss2info->getVerboseCodec($channelInfo['codec']);
  	
  		// default?
  		//if($channelInfo['isdefault'] == 1) { $isDefault = 'yes'; } else { $isDefault = 'no' };
		if ($channelInfo['channelid'] != 'id') 
		{
			$tmpChanId = $channelInfo['channelid'];
		    echo '<tr>
		   			<td><table width="100%" cellpadding="0" cellspacing="0">
    				<tr>
    					<td width="24"><img width="8" height="16" src="images/spacer.gif" alt="" title="" /><img src="images/teamspeak/channel.gif" width="16" height="16" alt="" title="" /></td>
    	    			<td>&nbsp;<a href="teamspeak://' . $board_config['ts_serveraddress'] . ':' . $board_config['ts_serverudpport'] . '/?channel=' . $channelname . '?password=' . $board_config['ts_serverpasswort'] . '?nickname=' . $nickname . '" title="' . $channelInfo['topic'] . '" class="gensmall">' . $channelname . '</a> (' . $channelInfo['currentplayers'] . ')</td>
					</tr>
					</table></td>
				</tr>';
    
			$counter_player = 0;
    		foreach($tss2info->playerList AS $playerInfo) 
    		{
  				if ($playerInfo['channelid'] == $channelInfo['channelid']) 
  				{
					if ($playerInfo['attribute'] == 0) 
					{
						$playergif = 'player.gif';
					}
					
					if (($playerInfo['attribute'] == 8) || ($playerInfo['attribute'] == 9) || ($playerInfo['attribute'] == 12) || ($playerInfo['attribute'] == 13) || ($playerInfo['attribute'] == 24) || ($playerInfo['attribute'] == 25) || ($playerInfo['attribute'] == 28) || ($playerInfo['attribute'] == 29) || ($playerInfo['attribute'] == 40) || ($playerInfo['attribute'] == 41) || ($playerInfo['attribute'] == 44) || ($playerInfo['attribute'] == 45) || ($playerInfo['attribute'] == 56) || ($playerInfo['attribute'] == 57)) 
    				{
    					$playergif = 'away.gif';
					}
					
					if (($playerInfo['attribute'] == 16) || ($playerInfo['attribute'] == 17) || ($playerInfo['attribute'] == 20) || ($playerInfo['attribute'] == 21)) 
    				{
    					$playergif = 'mutemicro.gif';
					}
						
					if (($playerInfo['attribute'] == 32) || ($playerInfo['attribute'] == 33) || ($playerInfo['attribute'] == 36) || ($playerInfo['attribute'] == 37) || ($playerInfo['attribute'] == 48) || ($playerInfo['attribute'] == 49) || ($playerInfo['attribute'] == 52) || ($playerInfo['attribute'] == 53)) 
    				{
    					$playergif = 'mutespeakers.gif';
    				}
    			
					if ($playerInfo['attribute'] == 4) 
					{
						$playergif = 'player.gif';
					}
					
					if (($playerInfo['attribute'] == 1) || ($playerInfo['attribute'] == 5)) 
					{
						$playergif = 'channelcommander.gif';
					}
					
					if ($playerInfo['userstatus'] < 4) 
					{ 
						$playerstatus = 'U'; 
					} 

					if ($playerInfo['userstatus'] == 4) 
					{ 
						$playerstatus = 'R'; 
					}

					if ($playerInfo['userstatus'] == 5) 
					{ 
						$playerstatus = 'R SA'; 
					} 

					if ($playerInfo['privileg'] == 0) 
					{ 
						$privileg = ''; 
					} 

					if ($playerInfo['privileg'] == 1) 
					{ 
						$privileg = ' CA'; 
					} 

					if ($playerInfo['totaltime'] < 60 ) 
					{
 						$playertotaltime = strftime('%S secs', $playerInfo['totaltime']);
					} 
					else 
					{
 						if ($playerInfo['totaltime'] >= 3600 ) 
 						{
							$playertotaltime = strftime('%H:%M:%S hrs', $playerInfo['totaltime'] - 3600);
				 		} 
				 		else 
				 		{
				   			$playertotaltime = strftime('%M:%S mins', $playerInfo['totaltime']);
				 		}
					}

					if ($playerInfo['idletime'] < 60 ) 
					{
	 					$playeridletime = strftime('%S secs', $playerInfo['idletime']);
					} 
					else 
					{
	 					if ($playerInfo['idletime'] >= 3600 ) 
	 					{
	 				 		$playeridletime = strftime('%H:%M:%S hrs', $playerInfo['idletime'] - 3600);
	 					} 
	 					else 
	 					{
	 				  		$playeridletime = strftime('%M:%S mins', $playerInfo['idletime']);
	 					}
					}	

            		echo '<tr>
            				<td><table width="100%" cellpadding="0" cellspacing="0">
            				<tr>
            					<td width="40"><img src="images/spacer.gif" width="8" height="16" alt="" title="" /><img src="images/teamspeak/gitter3.gif" width="16" height="16" alt="" title="" /><img src="images/teamspeak/' . $playergif . '" width="16" height="16" alt="Time [online: ' . $playertotaltime . ' | idle: ' . $playeridletime . '] Ping: ' . $playerInfo['pingtime'] . 'ms" title="Time [online: ' . $playertotaltime . ' | idle: ' . $playeridletime . '] Ping: ' . $playerInfo['pingtime'] . 'ms" /></td>
            					<td class="gensmall" title="Time [online: ' . $playertotaltime . ' | idle: ' . $playeridletime . '] Ping: ' . $playerInfo['pingtime'] . 'ms">&nbsp;' . $playerInfo['playername'] . ' (' . $playerstatus . $privileg . ')</td>
            				</tr>
            				</table></td>
            			</tr>';

					$counter_player++;
          		}
    		}
  		}
  	
  		$counter++;
  
	  	foreach($tss2info->channelList as $channelInfo) 
	  	{
			if(($channelInfo['parent'] != -1) && ($tmpChanId == $channelInfo['parent']))	
			{
		  		$channelname = $channelInfo['channelname'];
  
  				// determine codec (verbose)
		  		$codec = $tss2info->getVerboseCodec($channelInfo['codec']);
  
  				// default?
  				//if ($channelInfo['isdefault'] == 1) { $isDefault = 'yes'; } else { $isDefault = 'no' };
		  		if ($channelInfo['channelid'] != 'id') 
		  		{
		    		echo '<tr>
		    				<td><table width="100%" cellpadding="0" cellspacing="0">
		    				<tr>
		    					<td width="32" aling="right"><img width="16" height="16" src="images/spacer.gif" alt="" title="" /><img src="images/teamspeak/channel.gif" width="16" height="16" alt="" title="" /></td>
		    					<td class="gensmall">&nbsp;<a href="teamspeak://' . $board_config['ts_serveraddress'] . ':' . $board_config['ts_serverudpport'] . '/?channel=' . $channelname . '?password=' . $board_config['ts_serverpasswort'] . '?nickname=' . $nickname . '" title="' . $channelInfo['topic'] . '">' . $channelname . '</a> (' . $channelInfo['currentplayers'] . ')</td>
		    				</tr>
		    				</table></td>
		    			</tr>';
		    		
					$counter_player = 0;
		    			
		    		foreach($tss2info->playerList AS $playerInfo) 
		    		{
	          			if ($playerInfo['channelid'] == $channelInfo['channelid']) 
	         			{
							if ($playerInfo['attribute'] == 0) 
							{
								$playergif = 'player.gif';
							}
									
							if (($playerInfo['attribute'] == 8) || ($playerInfo['attribute'] == 9) || ($playerInfo['attribute'] == 12) || ($playerInfo['attribute'] == 13) || ($playerInfo['attribute'] == 24) || ($playerInfo['attribute'] == 25) || ($playerInfo['attribute'] == 28) || ($playerInfo['attribute'] == 29) || ($playerInfo['attribute'] == 40) || ($playerInfo['attribute'] == 41) || ($playerInfo['attribute'] == 44) || ($playerInfo['attribute'] == 45) || ($playerInfo['attribute'] == 56) || ($playerInfo['attribute'] == 57))
   							{
   								$playergif = 'away.gif';
							}
							
							if (($playerInfo['attribute'] == 16) || ($playerInfo['attribute'] == 17) || ($playerInfo['attribute'] == 20) || ($playerInfo['attribute'] == 21)) 
		    				{
		    					$playergif = 'mutemicro.gif';
							}
							
							if (($playerInfo['attribute'] == 32) || ($playerInfo['attribute'] == 33) || ($playerInfo['attribute'] == 36) || ($playerInfo['attribute'] == 37) || ($playerInfo['attribute'] == 48) || ($playerInfo['attribute'] == 49) || ($playerInfo['attribute'] == 52) || ($playerInfo['attribute'] == 53)) 
	    					{
	    						$playergif = 'mutespeakers.gif';
							}
				
							if ($playerInfo['attribute'] == 4) 
							{
								$playergif = 'player.gif';
							}
								
							if (($playerInfo['attribute'] == 1) || ($playerInfo['attribute'] == 5)) 
							{
								$playergif = 'channelcommander.gif';
							}
								
							if ($playerInfo['userstatus'] < 4) 
							{ 
								$playerstatus = 'U'; 
							}

							if ($playerInfo['userstatus'] == 4) 
							{ 
								$playerstatus = 'R'; 
							}

							if ($playerInfo['userstatus'] == 5) 
							{ 
								$playerstatus = 'R SA'; 
							}

							if ($playerInfo['privileg'] == 0) 
							{ 
								$privileg = ''; 
							}
							
							if ($playerInfo['privileg'] == 1) 
							{ 
								$privileg = ' CA'; 
							}

							if ($playerInfo['totaltime'] < 60 ) 
							{
			 					$playertotaltime = strftime('%S secs', $playerInfo['totaltime']);
							} 
							else 
							{
						 		if ($playerInfo['totaltime'] >= 3600 ) 
						 		{
						  			$playertotaltime = strftime('%H:%M:%S hrs', $playerInfo['totaltime'] - 3600);
						 		} 
						 		else 
						 		{
						   			$playertotaltime = strftime('%M:%S mins', $playerInfo['totaltime']);
						 		}
							}

							if ($playerInfo['idletime'] < 60 ) 
							{
								$playeridletime = strftime('%S secs', $playerInfo['idletime']);
							} 
							else 
							{
								if ($playerInfo['idletime'] >= 3600 ) 
								{
									$playeridletime = strftime('%H:%M:%S hrs', $playerInfo['idletime'] - 3600);
								} 
								else 
								{
									$playeridletime = strftime('%M:%S mins', $playerInfo['idletime']);
								}
							}

            				echo '<tr>
            						<td><table width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td width="48" align="right"><img src="images/teamspeak/gitter2.gif" width="16" height="16" alt="" title="" /><img src="images/teamspeak/' . $playergif . '" width="16" height="16" alt="Time [online: ' . $playertotaltime . ' | idle: ' . $playeridletime . '] Ping: ' . $playerInfo['pingtime'] . 'ms" title="Time [online: ' . $playertotaltime . ' | idle: ' . $playeridletime . '] Ping: ' . $playerInfo['pingtime'] . 'ms" /></td>
										<td class="gensmall" title="Time [online: ' . $playertotaltime . ' | idle: ' . $playeridletime . '] Ping: ' . $playerInfo['pingtime'] . 'ms">&nbsp;' . $playerInfo['playername'] . ' (' . $playerstatus . $privileg . ')</td>
									</tr>
									</table></td>
								</tr>';

							$counter_player++;
       					}
    				}
  				}
  
 				$counter++;
			}
		}
	}
}

if ($counter == 0) 
{
 	echo '<tr>
 			<td class="gensmall">' . $lang['Connecting'] . '<meta http-equiv="refresh" content="1; url=teamspeak.'.$phpEx.'"></td>
 		</tr>';
}

echo '</table>';
	
//$template->pparse('body'); 

?>