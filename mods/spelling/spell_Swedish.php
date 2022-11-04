<?php
  // --------------------------------------------------------------------
  // phpSpell Language Template
  //
  // This is (c)Copyright 2002, 2006 Team phpSpell.
  // --------------------------------------------------------------------

  // --------------------------
  // Table Name
  // --------------------------
  $DB_TableName = $table_prefix . 'spelling_words';

  // Language Text
  $Language_Text = array('Scanned %d words.    Found %d words to be corrected.');
  $Language_Javascript = array('Checking Document...','No misspellings found','OK','Cancel','Spell Check Completed','Correct','All','Ignore','Learn','Suggest','Definition','Thesaurus','Word correction','No Suggestions');

  // ---------------------------------------
  // PSPELL Support - Use English Dictionary
  // ---------------------------------------
  $Spell_Config["PSPELL_LANGUAGE"] = 'swe';

  // --------------------------------------------------------------------
  // Example translation table:
  //     $Translation_Table = array("À", "Æ", "Ç");
  //     $Replacement_Table = array("a", "an", "sth");
  //     $Language_Translation_Character_List = "ÀÆÇ";
  // --------------------------------------------------------------------
  // for every "À" it finds in a word it will replace it with a "a"
  // for every "Ç" it finds it will replace it with a "sth"
  // for every "Æ" it finds it will replace it with a "an"
  // --------------------------------------------------------------------
  // Put the character(s) to be translated into the Translation_Table
  // Put the replacement character(s) into the replacement table
  // --------------------------------------------------------------------
  // The replacement string should be equivelent to the ENGLISH PHONETIC
  // sound.  So if you were to take a word with "À" in it; how would you
  // phonetically spell the word in english.  If the "À" sounds like a "A"
  // in english then "A" would be the replacement character.
  // If it sounds like "th" then you would use "th" as the characters.
  // always replace Larger groups first.  (i.e. if "ññ" sounds differently
  // than "ñ" then in the translation table you would have the "ññ" listed
  // before the "ñ".  So that way when it would replaced the "ññ" before it
  // replaced it twice with "ñ".
  // --------------------------------------------------------------------
  // Any letters you do not translate will be IGNORED for
  // when it attempts to find spelling matches!!!
  // --------------------------------------------------------------------
  $Translation_Table = array();
  $Replacement_Table = array();

  // --------------------------------------------------------------------
  // Put the list of valid characters in your language in this list
  // --------------------------------------------------------------------
  $Language_Character_List = "abcdefghijklmnopqrstuvwxyzåäö'";
  $Language_Common_Words = ",i,och,att,det,som,en,på,är,av,för,med,till,den,har,de,inte,om,ett,han,men,var,jag,sig,från,vi,så,kan,man,när,år,säger,hon,under,också,efter,eller,nu,sin,där,vid,mot,ska,skulle,kommer,ut,får,finns,vara,hade,alla,andra,mycket,än,här,då,sedan,över,bara,in,blir,upp,även,vad,";

  // --------------------------------------------------------------------
  // Translation function
  // --------------------------------------------------------------------
  function Translate_Word($Word) {
    return ($Word);
  }

  // --------------------------------------------------------------------
  // Phonetic work function
  // --------------------------------------------------------------------
  function Word_Sound_Function($Word) {
    return (metaphone($Word));
  }


  function Language_Decode(&$Data)
  {
    // MS Internet Explorer Hack -- IE sends utf8-unicode for upper (ascii 128+) characters
     if (strpos(@$_SERVER['HTTP_USER_AGENT'], 'MSIE') > 0 || strpos(@$_SERVER['ALL_HTTP'], 'MSIE') > 0) {
       if (function_exists('utf8_decode')) $Data = utf8_decode($Data);
     }
     return ($Data);
  }

  function Language_Encode(&$Data)
  {
    global $Spell_Config;
    if (!$Spell_Config['IE_UTF_Encode']) return ($Data);
     if (strpos(@$_SERVER['HTTP_USER_AGENT'], 'MSIE') > 0 || strpos(@$_SERVER['ALL_HTTP'], 'MSIE') > 0) {
       if (function_exists('utf8_encode')) $Data = utf8_encode($Data);
     }
    return ($Data);
  }

  function Language_Lower(&$Data)
  {
    return(strtolower($Data));
  }

  function Language_Upper(&$Data)
  {
    return(strtoupper($Data));
  }

?>
