<?php
/***************************************************************************
 *                             PJIRC MOD mirrorbot.php
 *                            -------------------
 *   begin                : 16. july 2004
 *   copyright            : Midnightz / AlleyKat
 *   email                :
 *
 *   $Id: mirrorbot.php, v 1.0.0 2004/07/16 Midnightz
 ***************************************************************************/

$mirrorbot = "
<param name='descbot:nickresponse' value='Hi \$nick, you are in the \$chan chat room! I am a programmed robot designed to assist you with your questions. :smile: I am not always helpful but I do try my best. To begin, right click on any name and the popmenu will appear. Select Help Topics from the popmenu to retrieve commands that if used, will prompt me to talk to you more. Go ahead and give it a try! :wink: -MirrorBOT'>
<param name='descbot:textresponder1' value='\$me_ABOUT My name is MirrorBOT and I am programmed to help you with common questions or even to entertain, should you be bored. A plugin by Thema powers me but I have been taught to speak by Midnightz. Pay close attention to whenever I say something like !\$me_SOMETHING, it means enter that exact !\$me_SOMETHING for more information. :wink:'>
<param name='descbot:textresponder2' value='\$me_CHAT Yes \$nick, you are in fact chatting with yourself when talking to me. I am not really here unless you are. Do not worry though, I will not tell anyone you like to talk to yourself. :wink: '>
<param name='descbot:textresponder3' value='\$me_HI Hi to you too \$nick! I feel invisible sometimes so feel free to say hi to me anytime! :smile:'>
<param name='descbot:textresponder4' value='\$me_HELPME \$nick, basic IRC chat help can be found by clicking the help button above.'>
<param name='descbot:textresponder5' value='\$me_OPTIONS \$nick, there are chat options galore but I only want to talk about a few. I admit it, I am lazy. To learn about the options I care to discuss, enter a specific key for information. :smile:'>
<param name='descbot:textresponder6' value='\$me_LINKS I advise caution for clicking links provided in the chat room or in IM. \$nick, you could unknowingly accept harmful data but if the source is trusted, (verified nickname via server too) then simply right clicking on a link will open it in a new window. Cool huh?'>
<param name='descbot:textresponder7' value='\$me_SMILIES If enabled, smilies can be a lot of fun! Even if you only see a handful of smilies to choose from, this chat room has been programmed to use ANY smilie from the phpBB system by simply entering the code.'>
<param name='descbot:textresponder8' value='\$me_FONTS Font faces and sizes only affect what you see and will change the entire text within the chat room from its default to whatever you select. This is handy if you have difficulty deciphering the default text.'>
<param name='descbot:textresponder9' value='\$me_BOLD/UNDERLINE These are additional font options and CAN be seen in both the chat room and IM. Use sparingly if at all. Note that with some chat backgrounds, it may be best to bold up your text at all times.'>
<param name='descbot:textresponder10' value='\$me_COLORS Font colors are neat but can be annoying. If this option is enabled, you will see a color picker tool. Right clicking on a color sets the background color and left clicking sets the actual text color. Please be considerate of others and their eyesight when using these options! :wink:'>
<param name='descbot:textresponder11' value='\$me_CLEAR Clear Window is an option to clear the chat window of all text. This only affects what you see.'>
<param name='descbot:textresponder12' value='\$me_IM IM is short for instant message. It is a private chat between you, \$nick and another chatter. Normally in IRC, this would be called private message but since this chat room is designed specifically for the phpBB system, (which already has private messaging) we now call it IM. Try it by double clicking or right clicking on a nickname in the nicklist.'>
<param name='descbot:textresponder13' value='\$me_PROFILE View Profile is an option to view the phpBB profile of a chatter. This is also a handy way of determining if you are actually talking to someone from the web site you are registered with.'>
<param name='descbot:textresponder14' value='\$me_PLAY \$nick, would you like to hear my favorite digitized song? :smile: I must warn you though, I am still waiting for Plouf to add sound support to this dang chat room so Midnightz can code us better options. To try to hear my song, type: /play snd/zww-outset.mid'>
<param name='descbot:textresponder15' value='\$me_QUOTES Since you have expressed an interest, I will gladly share some quotes with you! All you have to do \$nick, is !\$me_ASK me.'>
<param name='descbot:textresponder16' value='\$me_ASK I am so glad you asked... People rarely ask nicely for anything it seems but since you did, here is a memorable quote < Be not astonished at new ideas; for it is well known to you that a thing does not therefore cease to be true because it is not accepted by many. -Spinoza > \$nick, would you like !\$me_ANOTHER quote?'>
<param name='descbot:textresponder17' value='\$me_ANOTHER < Discovery consists in seeing what everyone else has seen and thinking what no one else has thought. -Albert Szent-Gyorgi > Did you like that one? Want !\$me_MORE quotes?'>
<param name='descbot:textresponder18' value='\$me_MORE < Technology is ruled by two types of people: those who manage what they do not understand, and those who understand what they do not manage. -Mike Trout > I am guessing you enjoy these... How about I !\$me_RECITE another?'>
<param name='descbot:textresponder19' value='\$me_RECITE < If USENET is anarchy, IRC is a paranoid schizophrenic after 6 days on speed. -Chris Saunderson > I know, I know... !\$me_GIVE you more, right?'>
<param name='descbot:textresponder20' value='\$me_GIVE < Talking with you is sort of the conversational equivalent of an out of body experience. -Calvin & Hobbes > Here is !\$me_ANOTHER1 for your enjoyment.'>
<param name='descbot:textresponder21' value='\$me_ANOTHER1 < Natives who beat drums to drive off evil spirits are objects of scorn to smart Americans who blow horns to break up traffic jams. -Mary Ellen Kelly > Whew, that was exhausting... !\$me_ENOUGH of that already!'>
<param name='descbot:textresponder22' value='\$me_ENOUGH Well, I have given you a taste of my interest in quotes... now, how about some !\$me_JOKES, hmm?'>
<param name='descbot:textresponder23' value='\$me_JOKES I can not think of any right now, sorry \$nick. Try the guessing game instead by typing: !\$me_GUESS'>
<param name='descbot:textresponder24' value='\$me_GUESS \$nick, can you guess what !\$me_NUMBER I am thinking of?'>
<param name='descbot:textresponder25' value='\$me_NUMBER I was thinking of the number 7. Is that what you guessed? I bet you can not guess which !\$me_NUM I am thinking of now!'>
<param name='descbot:textresponder26' value='\$me_NUM It was 2 - HAH! That was pointless, eh? Kind of like Bush when he !\$me_SAID ...'>
<param name='descbot:textresponder27' value='\$me_SAID < It is not pollution that is harming the environment. It is the impurities in our air and water that are doing it. (Governor George W. Bush, Jr.) > Smart one, he is. LOL'>
<param name='descbot:textresponder28' value='\$me_ACP The Administrator Control Panel options for this MOD are great and even include my own (MirrorBOT) controls. Shut me up if you feel the need or limit what I reply to even. Experiment and have fun! :wink:'>
<param name='descbot:textresponder29' value='\$me_BOT The amazing thing about me \$nick, is that I can be told to say anything you want and to respond to any key command you specify. I have an unlimited vocabularly. I am handy for support sites with staff that do not care to repeat themselves over and over. Just tell me to do your dirty work and uhh, keep it clean! Open the file: mirrorbot.php to get started.'>
<param name='descbot:textresponder30' value='\$me_DID Did you know that this PJIRC for phpBB MOD is Midnightz first official released MOD? It took a lot of work and still gets better every day. It would not have been possible without certain people though, please read the included documentation of credits. :smile:'>
";
?>
