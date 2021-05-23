<?php
/**
 * This file provides the english translation of the multilingual array for this Tool
 * 
 * 
 * @platform    WebsiteBaker Community Edition
 * @package     wbSeoTool
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

// Deutsche Modulbeschreibung
$module_description = 'This Admin-Tool provides a good way to work with reoccurring, global contents. 
						It is also good for multi lingual websites.';
						
// Modul Sprachvariablen
$TOOL_TEXT['FIELDTYPE_NOT_SELECTED']  = 'String-Type was not selected.';
$TOOL_TEXT['NOSCRIPT_MESSAGE']        = "JavaScript disabled in your browser. In order for this Admin-Tool to work properly, JavaScript should be activated.";
$TOOL_TEXT['STRING']                  = "String";
$TOOL_TEXT['MANAGE_STRINGS']          = "Manage GlobalStrings";
$TOOL_TEXT['NAME_WRONG_REGEX']        = 'String-Name should not have any empty spaces in it and no special characters. <br>Only <b>a-z</b>, <b>A-Z</b>, <b>0-9</b>, Underscore (<b>_</b>) and Minus sign (<b>-</b>) are allowed. Name must consist of at least 3 characters.';
$TOOL_TEXT['UNKNOWN_FIELDTYPE']       = 'Unknown String-Type (error)';
$TOOL_TEXT['UPDATE_SUCCESS']          = 'Updated Successfully.';
$TOOL_TEXT['CONTENT_UPDATED']         =  $TOOL_TEXT['UPDATE_SUCCESS'];
$TOOL_TEXT['CONFIG_UPDATE_SUCCESS']   = 'Settings updated successfully.';
$TOOL_TEXT['FIELD_NAME_IN_USE']       = '<b>The String-Name is already in use.</b><br>Please choose a distinct String-Name.';
$TOOL_TEXT['FIELD_ADD_SUCCESS']       = 'String has been added successfully.';
$TOOL_TEXT['CHECK_RESTRICTED']        = 'Restrictions per String';
$TOOL_TEXT['RESTRICTED_INFO']         = 'Only users of the group *Admin* may edit the content of selected String(s). (Visibility in Frontend as usual.)';
$TOOL_TEXT['UNRESTRICTED_INFO']       = 'Anyone with access rights to this Admin-Tool will be able to edit.';
$TOOL_TEXT['CLICK2EDIT']              = 'click to edit';
$TOOL_TEXT['ADVANCED_SETTINGS']       = 'Advanced Settings';
$TOOL_TEXT['TOOL_CONFIG']             = 'Admin-Tool configuration';
$TOOL_TEXT['USE']                     = 'use';
$TOOL_TEXT['LANGUAGES']               = 'Languages';
$TOOL_TEXT['COMA_SEPARATION']         = 'Language Codes need to be comma-separated, e.g.: EN, DE, NL etc.';
$TOOL_TEXT['LANGCODE']                = 'Language Code';
$TOOL_TEXT['DROPLET_REINSTALLED']     = 'Droplet has been restored successfully!';
$TOOL_TEXT['SUCCESS_RESTORE_TRASHED'] = 'String has been restored successfully.';
$TOOL_TEXT['SUCCESSFULLY_TRASHED']    = 'String has been successfully moved to the recycle bin.';
$TOOL_TEXT['SUCCESSFULLY_DELETED']    = 'String has been irrevocably removed from the system.';
$TOOL_TEXT['ALL_LANG_INFO']           = 'List of all the languages being in use on this CMS installation.';
$TOOL_TEXT['FE_EDIT_INFO']            = 'When enabled, you will see a link in the Fronted along any String with which you can get 
										 to the backend where you than can edit the Strings content.';
$TOOL_TEXT['TRASH_INFO']              = 'When trash disabled your Strings will be irrevocably removed from the system!';
$TOOL_TEXT['COPY_CLIPBOARD']          = 'Copy to clipboard';
$TOOL_TEXT['NAME']					  = 'Name';
$TOOL_TEXT['TYPE']					  = 'Type';
$TOOL_TEXT['DATE_CREATED']			  = 'Date created';
$TOOL_TEXT['DATE_MODIFIED']			  = 'Date modified';

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//                        Order by
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$TOOL_TEXT['ORDER_BY']       = 'Order by';
$TOOL_TEXT['name-asc']       = "by Name (ascending)";
$TOOL_TEXT['name-desc']      = "by Name (descending)";
$TOOL_TEXT['type-asc']       = "by String-Type (ascending)";
$TOOL_TEXT['type-desc']      = "by String-Type (descending)";
$TOOL_TEXT['add_when-asc']   = "by Creation-Time (newest first)";
$TOOL_TEXT['add_when-desc']  = "by Creation-Time (oldest first)";
$TOOL_TEXT['edit_when-asc']  = "newest edited first";
$TOOL_TEXT['edit_when-desc'] = "latest edited first";