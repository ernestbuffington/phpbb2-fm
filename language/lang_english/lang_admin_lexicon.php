<?php 
/** 
*
* @package lang_english
* @version $Id: lang_admin_lexicon.php, v 0.0.1.22 2005/06/29 19:49:00 AmigaLink Exp $
* @copyright (c) 2005 AmigaLink
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

$lang['Explanation'] = 'Explanation';
$lang['Keyword'] = 'Keyword';

// Messages
$lang['Keyword_caused'] = 'Keyword "<b>%s</b>" Added Successfully to the "<b>%s</b>" category.';
$lang['Keyword_worked_on'] = 'Keyword "<b>%s</b>" Updated Successfully.';
$lang['Keyword_deleted'] = 'Keyword "<b>%s</b>" Deleted Successfully.';
$lang['Explanation_empty'] = 'The description must have a minimum of ten (10) characters.';
$lang['Keyword_empty'] = 'You must enter a keyword.';
$lang['Categorie_empty'] = 'You must enter a category title.';
$lang['Categorie_already_exists'] = 'A <b>%s</b> Category Already Exists.';
$lang['Categorie_caused'] = 'Category Added Successfully.';
$lang['Categorie_deleted'] = 'Category %s Deleted Successfully.<br />All references in this category have been moved to the \'<i>default</i>\' category.';

//
// Manage Keywords
//
$lang['Keyword_administration'] = 'Manage keywords';
$lang['Keyword_admin_explain'] = 'From this panel you can manage all the keywords in the lexicon. The category titles are the original database record names and <b>not</b> the names defined in the PHP language file.';
$lang['Keyword_administration_delete'] = 'Delete keyword?';
$lang['Keyword_admin_delete_explain'] = 'The keyword <b>AND</b> the description will be deleted, there is no undo function. The category titles are the original database record names and <b>not</b> the names defined in the PHP language file.';
$lang['Keyword_administration_edit'] = 'Edit keyword';
$lang['Keyword_admin_edit_explain'] = 'From this panel you can edit this lexicon keywords. The category titles are the original database record names and <b>not</b> the names defined in the PHP language file.';
$lang['Keyword_administration_new'] = 'Add keyword';
$lang['Keyword_admin_new_explain'] = 'A new keyword can be entered in the lexicon from here. The category titles are the original database record names and <b>not</b> the names defined in the PHP language file.';

//
// Manage Categories
//
$lang['Configuration_cat_explain'] = 'This is an overview of all lexicon categories. You can change, delete and add categories from here. The default category name <b>cannot</b> be changed or deleted. A change of the default category title is possible in the language file (PHP). The name of the default category is set in the language files, if there is a <span style="color: #FF0000">red *</span> added then the content of the default category is empty.';
$lang['Categorie_lang'] = 'In language file';
$lang['no_entry'] = 'no entry';
$lang['Categorie_edit_explain'] = 'From this panel you can change the name of an existing category. The category name is a database record name and <b>not</b> the name defined in the langauge files (PHP), if you change the name the old name will become invalid.';
$lang['Categorie_worked_on'] = 'Category Updated Successfully to <b>%s</b>.';
$lang['Add_categorie'] = 'Enter new category';
$lang['Delete_categorie'] = 'Delete category %s ?';

//
// Config
$lang['Lexicon_titel'] = 'Lexicon title';
$lang['Lexicon_titel_explain'] = 'The general name of the lexicon can be changed here.';
$lang['Lexicon_description'] = 'Lexicon description';
$lang['Lexicon_description_explain'] = 'Describe the purpose of the lexicon.';

?>