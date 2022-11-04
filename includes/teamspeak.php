<?php
// (c) 2003 by Christian Müller
// umgecodet und gestyled von Dooki
// non-commercial use approved, commercial users please contact me at mueller@fmi.uni-passau.de
// i give no guarantees whatsoever about the correct functioning of this script
// use at your own risk
// date: 20/06/03, 23:28
	
class tss2info 
{
/*
	var $serverAddress = "xxx.xxx.xxx.xxx"; // Hier die TeamSpeak IP Adresse eintragen !!wichtig!! (Beispiel: 192.168.7.1)
	var $serverQueryPort = "51234"; // TeamSpeak QueryPort.. Schau in die server.ini von TeamSpeak (Standard 51234)
	var $serverUDPPort = "8767"; // UDP Port für Teamspeak der auch hinter der IP Adresse genutzt wird (Standard 8767)
	var $tabellenbreite = "165"; // Mindestbreite der Teamspeaktabelle (die einbindung mit einem IFRAME sollte 20px mehr betragen)
	var $refreshtime = "10"; // Zeit in Sekunden nach der die Anzeige aktualisiert wird, wenn "auto on" aktiv ist
	var $serverPasswort = "passwort"; // Serverpasswort das bei Serversettings eingestellt wird (wenn kein Passwort erteilt, dann leer lassen)
*/
	//internal
	var $socket;

	// external
	var $serverStatus = 'offline';
	var $playerList = array();
	var $channelList = array();


	// opens a connection to the teamspeak server
	function getSocket($host, $port, $errno, $errstr, $timeout) 
	{
  		unset($socket);
  		$attempts = 1;
  		while($attempts <= 1 and !$this->socket) 
  		{
			$attempts++;
    		@$socket = fsockopen($host, $port, $errno, $errstr, $timeout);
    		$this->errno = $errno;
    		$this->errstr = $errstr;
    		if($socket and fread($socket, 4) == '[TS]') 
    		{
      			fgets($socket, 128);
      			return $socket;
			}
  		}
  		
  		return false;
	}

	// sends a query to the teamspeak server
	function sendQuery($socket, $query) 
	{
  		@fputs($socket, $query . "\n");
	}

	// answer OK?
	function getOK($socket) 
	{
  		$result = @fread($socket, 2);
  		@fgets($socket, 128);
  		
  		return($result == 'OK');
	}

	// closes the connection to the teamspeak server
	function closeSocket($socket) 
	{
		@fputs($socket, 'quit');
  		@fclose($socket);
	}

	// retrieves the next argument in a tabulator-separated string (PHP scanf function bug workaround)
	function getNext($evalString) 
	{
  		$pos = strpos($evalString, "\t");
  		if(is_integer($pos)) 
  		{
    		return substr($evalString, 0, $pos);
  		} 
  		else 
  		{
    		return $evalString;
		}
	}

	// removes the first argument in a tabulator-separated string (PHP scanf function bug workaround)
	function chopNext($evalString) 
	{
  		$pos = strpos($evalString, "\t");
  		if(is_integer($pos)) 
  		{
    		return substr($evalString, $pos + 1);
  		} 
  		else 
  		{
    		return '';
  		}
	}

	// strips the quotes around a string
	function stripQuotes($evalString) 
	{
  		if(strpos($evalString, '"') == 0) 
  		{
  			$evalString = substr($evalString, 1, strlen($evalString) - 1);
  		}
  		if(strrpos($evalString, '"') == strlen($evalString) - 1) 
  		{
  			$evalString = substr($evalString, 0, strlen($evalString) - 1);
		}
  
  		return $evalString;
	}

	// returns the codec name
	function getVerboseCodec($codec) 
	{
  		if($codec == 0) 
  		{
    		$codec = 'CELP 5.1 Kbit';
  		} 
  		else if ($codec == 1) 
  		{
  		  	$codec = 'CELP 6.3 Kbit';
	  	} 
	  	else if ($codec == 2) 
	  	{
	  	  	$codec = 'GSM 14.8 Kbit';
	  	} 
	  	else if ($codec == 3) 
	  	{
	  	  	$codec = 'GSM 16.4 Kbit';
	  	} 
	  	else if ($codec == 4) 
	  	{
	  	  	$codec = 'CELP Windows 5.2 Kbit';
		} 
		else if ($codec == 5) 
		{
  		  	$codec = 'Speex 3.4 Kbit';
  		} 
  		else if ($codec == 6) 
  		{
  		  	$codec = 'Speex 5.2 Kbit';
  		} 
  		else if ($codec == 7) 
  		{
  		  	$codec = 'Speex 7.2 Kbit';
  		} 
  		else if ($codec == 8) 
  		{
  		  	$codec = 'Speex 9.3 Kbit';
  		} 
  		else if ($codec == 9) 
  		{
  		  	$codec = 'Speex 12.3 Kbit';
  		} 
  		else if ($codec == 10) 
  		{
  		  	$codec = 'Speex 16.3 Kbit';
  		} 
  		else if ($codec == 11) 
  		{
  		  	$codec = 'Speex 19.5 Kbit';
  		} 
  		else if ($codec == 12) 
  		{
  		  	$codec = 'Speex 25.9 Kbit';
  		} 
  		else 
  		{
  	  		$codec = 'unknown (' . $codec . ')';
  		}
  
	  	return $codec;
	}

	// ---=== main program ===---
	function getInfo() 
	{
		global $board_config;
		
		// establish connection to teamspeak server
		$this->socket = $this->getSocket($board_config['ts_serveraddress'], $board_config['ts_serverqueryport'], '', '', 0.3);
		if ($this->socket == false) 
		{
  			return;
  			//message_die(GENERAL_MESSAGE, 'No Server');
		} 
		else 
		{
  			$this->serverStatus = 'online';

		  	// select the one and only running server on port 8767
  			$this->sendQuery($this->socket, 'sel ' . $board_config['ts_serverudpport']);

		  	// retrieve answer "OK"
		  	if (!$this->getOK($this->socket)) 
		  	{
		  		//message_die(GENERAL_MESSAGE, 'Server didn\'t answer "OK" after last command. Aborting.');
		  		return;
		  	}
	
			// retrieve player list
	  		$this->sendQuery($this->socket, 'pl');
	
			// read player info
	  		$this->playerList = array();
	  		do 
	  		{
	    		$playerinfo = fscanf($this->socket, '%s %d %d %d %d %d %d %d %d %d %d %d %d %s %s');
	    		list($playerid, $channelid, $receivedpackets, $receivedbytes, $sentpackets, $sentbytes, $paketlost, $pingtime, $totaltime, $idletime, $privileg, $userstatus, $attribute, $s, $playername) = $playerinfo;
	    		if($playerid != 'OK') 
	    		{
	      			$this->playerList[$playerid] = array(
	      				'playerid' => $playerid,
	    	  			'channelid' => $channelid,
	    	  			'receivedpackets' => $receivedpackets,
	    	  			'receivedbytes' => $receivedbytes,
	    	  			'sentpackets' => $sentpackets,
	    	  			'sentbytes' => $sentbytes,
	    		  		'paketlost' => $paketlost / 100,
	      				'pingtime' => $pingtime,
	      				'totaltime' => $totaltime,
	      				'idletime' => $idletime,
	      				'privileg' => $privileg,
	      				'userstatus' => $userstatus,
	      				'attribute' => $attribute,
	      				's' => $s,
	      				'playername' => $this->stripQuotes($playername)
	      			);
	   	 		}
  			} 
  			while($playerid != 'OK');
	
			// retrieve channel list
	  		$this->sendQuery($this->socket, 'cl');
	
			// read channel info
	  		$this->channelList = array();
	  		do 
	  		{
   		 		$channelinfo = '';
   		 		do 
	    		{
	      			$input = fread($this->socket, 1);
	      			if($input != "\n" && $input != "\r") 
	      			{
	      				$channelinfo .= $input;
	      			}
	 			} 
	 			while($input != "\n");		

	    		$channelid = $this->getNext($channelinfo);
	    		$channelinfo = $this->chopNext($channelinfo);
	    		$codec = $this->getNext($channelinfo);
	    		$channelinfo = $this->chopNext($channelinfo);
	    		$parent = $this->getNext($channelinfo);
  	 	 		$channelinfo = $this->chopNext($channelinfo);
  	 	 		$d = $this->getNext($channelinfo);
  	 	 		$channelinfo = $this->chopNext($channelinfo);
  		  		$maxplayers = $this->getNext($channelinfo);
  		  		$channelinfo = $this->chopNext($channelinfo);
  		  		$channelname = $this->getNext($channelinfo);
			    $channelinfo = $this->chopNext($channelinfo);
			    $d = $this->getNext($channelinfo);
			    $channelinfo = $this->chopNext($channelinfo);
			    $d = $this->getNext($channelinfo);
			    $channelinfo = $this->chopNext($channelinfo);
			    $topic = $this->getNext($channelinfo);
	
			    if ($channelid != 'OK') 
			    {
//    	  			$isdefault = ($isdefault == 'Default') ? 1 : 0; 
    	  			
		  			// determine number of players in channel
    	  			$playercount = 0;
    	  			foreach($this->playerList AS $playerInfo) 
    	  			{
    	    			if($playerInfo['channelid'] == $channelid) 
    	    			{
    	    				$playercount++;
    	  				}
    	  			}
	
	      			$this->channelList[$channelid] = array(
	      				'channelid' => $channelid,
	      				'codec' => $codec,
	      				'parent' => $parent,
	      				'maxplayers' => $maxplayers,
	      				'channelname' => $this->stripQuotes($channelname),
	      				'isdefault' => $isdefault,
   		   				'topic' => $this->stripQuotes($topic),
   		   				'currentplayers' => $playercount
   		   			);
   		 		}
  			} 
  			while($channelid != 'OK');
	
			// close connection to teamspeak server
			$this->closeSocket($this->socket);
		}
	}
}

$tss2info = new tss2info;

?>