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
  $Language_Text = array('Scannet %d ord.    Fandt %d ord der skulle korrigeres.');
  $Language_Javascript = array('Kontrollerer dokument...','Ingen stavefejl fundet','OK','Afbryd','Stavekontrol fuldfшrt','Korrigйr','Alle','Ignorйr','Lжr','Foreslе','Definition','Ordbog','Ord korrektion','Ingen forslag');

  // ---------------------------------------
  // PSPELL Support - Use Danish Dictionary
  // ---------------------------------------
  $Spell_Config["PSPELL_LANGUAGE"] = 'da';

  // --------------------------------------------------------------------
  // Example translation table:
  //     $Translation_Table = array("А", "Ж", "З");
  //     $Replacement_Table = array("a", "an", "sth");
  //     $Language_Translation_Character_List = "АЖЗ";
  // --------------------------------------------------------------------
  // for every "А" it finds in a word it will replace it with a "a"
  // for every "З" it finds it will replace it with a "sth"
  // for every "Ж" it finds it will replace it with a "an"
  // --------------------------------------------------------------------
  // Put the character(s) to be translated into the Translation_Table
  // Put the replacement character(s) into the replacement table
  // --------------------------------------------------------------------
  // The replacement string should be equivelent to the ENGLISH PHONETIC
  // sound.  So if you were to take a word with "А" in it; how would you
  // phonetically spell the word in english.  If the "А" sounds like a "A"
  // in english then "A" would be the replacement character.
  // If it sounds like "th" then you would use "th" as the characters.
  // always replace Larger groups first.  (i.e. if "сс" sounds differently
  // than "с" then in the translation table you would have the "сс" listed
  // before the "с".  So that way when it would replaced the "сс" before it
  // replaced it twice with "с".
  // --------------------------------------------------------------------
  // Any letters you do not translate will be IGNORED for
  // when it attempts to find spelling matches!!!
  // --------------------------------------------------------------------
  $Translation_Table = array();
  $Replacement_Table = array();

  // --------------------------------------------------------------------
  // Put the list of valid characters in your language in this list
  // --------------------------------------------------------------------
  $Language_Character_List = "abcdefghijklmnopqrstuvwxyzжше'";
  $Language_Common_Words = ",den,det,er,var,af,og,en,et,i,til,har,han,hun,den,at,ham,hende,dens,dets,jeg,mig,min,mit,dem,deres,ikke,nej,ja,for,du,med,pе,denne,dette,disse,se,gшr,vi,os,ved,men,fra,som,der,vil,ville,hvad,der,kan,sе,gе,gik,mere,andre,anden,en,et,sе,ved,";

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