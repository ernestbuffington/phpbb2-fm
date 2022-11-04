<?php
/** 
*
* @package lang_english
* @version $Id: lang_admin_faq_editor.php,v 1.35.2.9 2003/06/10 00:31:19 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

$lang['faq_editor_explain'] = 'This module allows you to edit and re-arrange your BBCode, Board FAQ, and Moderator FAQ. You <u>should not</u> remove or alter the section entitled <b>phpBB 2 Issues</b> or <b>phpBB FullyModded Issues</b>.';

$lang['faq_block_delete'] = 'Are you sure you want to delete this block?';
$lang['faq_quest_delete'] = 'Are you sure you wish to delete this question (and its answer)?';

$lang['faq_quest_edit'] = 'Edit Question &amp; Answer';
$lang['faq_quest_create'] = 'Create New Question &amp; Answer';

$lang['faq_quest_edit_explain'] = 'Edit the question and answer. Change the block if you wish.';
$lang['faq_quest_create_explain'] = 'Type the new question and answer and press submit.';

$lang['faq_block'] = 'Block';
$lang['faq_quest'] = 'Question';
$lang['faq_answer'] = 'Answer';

$lang['faq_block_name'] = 'Block Name';
$lang['faq_block_rename'] = 'Rename a block';
$lang['faq_block_rename_explain'] = 'Change the name of a block in the file.';

$lang['faq_block_add'] = 'Add Block';
$lang['faq_quest_add'] = 'Add Question';

$lang['faq_no_quests'] = 'No questions in this block. This will prevent any blocks after this one being displayed. Delete the block or add one or more questions.';
$lang['faq_no_blocks'] = 'No blocks defined. Add a new block by typing a name below.';

$lang['faq_write_file'] = 'Could not write to the language file!';
$lang['faq_write_file_explain'] = 'You must make the language file in language/lang_english/ or equivilent <i>writeable</i> to use this control panel. On UNIX, this means running <code>chmod 0666 filename</code>. Most FTP clients can do through the properties sheet for a file, otherwise you can use telnet or SSH.';

?>