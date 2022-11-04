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
  $Language_Javascript = array('Kontrollerer dokument...','Ingen stavefejl fundet','OK','Afbryd','Stavekontrol fuldf�rt','Korrig�r','Alle','Ignor�r','L�r','Foresl�','Definition','Ordbog','Ord korrektion','Ingen forslag');

  // ---------------------------------------
  // PSPELL Support - Use Danish Dictionary
  // ---------------------------------------
  $Spell_Config["PSPELL_LANGUAGE"] = 'da';

  // --------------------------------------------------------------------
  // Example translation table:
  //     $Translation_Table = array("�", "�", "�");
  //     $Replacement_Table = array("a", "an", "sth");
  //     $Language_Translation_Character_List = "���";
  // --------------------------------------------------------------------
  // for every "�" it finds in a word it will replace it with a "a"
  // for every "�" it finds it will replace it with a "sth"
  // for every "�" it finds it will replace it with a "an"
  // --------------------------------------------------------------------
  // Put the character(s) to be translated into the Translation_Table
  // Put the replacement character(s) into the replacement table
  // --------------------------------------------------------------------
  // The replacement string should be equivelent to the ENGLISH PHONETIC
  // sound.  So if you were to take a word with "�" in it; how would you
  // phonetically spell the word in english.  If the "�" sounds like a "A"
  // in english then "A" would be the replacement character.
  // If it sounds like "th" then you would use "th" as the characters.
  // always replace Larger groups first.  (i.e. if "��" sounds differently
  // than "�" then in the translation table you would have the "��" listed
  // before the "�".  So that way when it would replaced the "��" before it
  // replaced it twice with "�".
  // --------------------------------------------------------------------
  // Any letters you do not translate will be IGNORED for
  // when it attempts to find spelling matches!!!
  // --------------------------------------------------------------------
  $Translation_Table = array();
  $Replacement_Table = array();

  // --------------------------------------------------------------------
  // Put the list of valid characters in your language in this list
  // --------------------------------------------------------------------
  $Language_Character_List = "abcdefghijklmnopqrstuvwxyz���'";
  $Language_Common_Words = ",den,det,er,var,af,og,en,et,i,til,har,han,hun,den,at,ham,hende,dens,dets,jeg,mig,min,mit,dem,deres,ikke,nej,ja,for,du,med,p�,denne,dette,disse,se,g�r,vi,os,ved,men,fra,som,der,vil,ville,hvad,der,kan,s�,g�,gik,mere,andre,anden,en,et,s�,ved,";

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