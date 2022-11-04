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
  $Meta_Language = 'iso-8859-2';

  // Language Text
  $Language_Text = array('Przeskanowano %d s��w.    Znaleziono %d s��w do poprawienia.');
  $Language_Javascript = array('Sprawdznie dokumentu...','Nie znaleziono','OK','Anuluj','Sprawdzanie s�ownika zako�czone','Popraw','Wszystkie','Ignoruj','Naucz','Sugeruj','Definicja','S�ownik','Poprawienie wyrazu','Brak sugestii');

  // ---------------------------------------
  // PSPELL Support - Use Polish Dictionary
  // ---------------------------------------
  $Spell_Config["PSPELL_LANGUAGE"] = 'pl';
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
  $Language_Character_List = "a�bc�de�fghijkl�mn�o�pqrs�tuvwxyz��'";
  $Language_Common_Words = ",one,jeden,dwa,trzy,cztery,pi��,sze��,siedem,osiem,dziewi��,dziesi��,wy,jest,by�,b�d�,s�,byli,by�y,b�d�c,jestem,z,i,w,wewn�trzny,do,mie�,ma,mia�,maj�c,on,jego,to,ja,m�j,oni,ich,nic,nie,dla,ty,tw�j,ona,jej,dalej,�e,te,tu,zr�b,zrobi�y,robi,zrobiony,robi�c,m�j,my,za,o,pod,przed,nasi,oko�o,oko,ale,od,kto,jako,kt�ry,albo,powiedzie�,g�osy,m�wi�c,co,tam,je�eli,mo�e,tak,idzie,posz�y,poszed�,wi�cej,inny,jeden,widzi,widzia�,widz�c,wiedzie�,wiedzia�,rozpoznany,wie,wiedz�c,tam,";

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
        $Data = strtr($Data, "��ʣ�Ӧ��", "����󶿼");
        return(strtolower($Data));
        }

  function Language_Upper(&$Data)
        {
        $Data = strtr($Data, "����󶿼", "��ʣ�Ӧ��");
        return(strtoupper($Data));
        }

?>