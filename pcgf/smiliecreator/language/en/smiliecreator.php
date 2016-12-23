<?php

/**
 * @author    MarkusWME <markuswme@pcgamingfreaks.at>
 * @copyright 2016 MarkusWME
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

// Merging language data for autodrafts with the other language data
$lang = array_merge($lang, array(
    'PCGF_SMILIECREATOR'                  => 'Smilie Creator',
    'PCGF_SMILIECREATOR_CHOOSE_SMILIE'    => 'Smilie',
    'PCGF_SMILIECREATOR_CLOSE'            => 'Close',
    'PCGF_SMILIECREATOR_COLOR_DARK_RED'   => 'Dark red',
    'PCGF_SMILIECREATOR_COLOR_RED'        => 'Red',
    'PCGF_SMILIECREATOR_COLOR_ORANGE'     => 'Orange',
    'PCGF_SMILIECREATOR_COLOR_BROWN'      => 'Brown',
    'PCGF_SMILIECREATOR_COLOR_YELLOW'     => 'Yellow',
    'PCGF_SMILIECREATOR_COLOR_GREEN'      => 'Green',
    'PCGF_SMILIECREATOR_COLOR_OLIVE'      => 'Olive',
    'PCGF_SMILIECREATOR_COLOR_CYAN'       => 'Cyan',
    'PCGF_SMILIECREATOR_COLOR_BLUE'       => 'Blue',
    'PCGF_SMILIECREATOR_COLOR_DARK_BLUE'  => 'Dark blue',
    'PCGF_SMILIECREATOR_COLOR_INDIGO'     => 'Indigo',
    'PCGF_SMILIECREATOR_COLOR_VIOLET'     => 'Violet',
    'PCGF_SMILIECREATOR_COLOR_WHITE'      => 'White',
    'PCGF_SMILIECREATOR_COLOR_BLACK'      => 'Black',
    'PCGF_SMILIECREATOR_COLOR_NONE'       => 'No color',
    'PCGF_SMILIECREATOR_CREATE'           => 'Create smilie shield',
    'PCGF_SMILIECREATOR_FONT_COLOR'       => 'Text color',
    'PCGF_SMILIECREATOR_NO_TEXT_GIVEN'    => 'No text has been entered!',
    'PCGF_SMILIECREATOR_PREVIEW'          => 'Preview',
    'PCGF_SMILIECREATOR_RANDOM'           => 'Random smilie',
    'PCGF_SMILIECREATOR_SHADOW_COLOR'     => 'Shadow color',
    'PCGF_SMILIECREATOR_SHIELD_SHADOW'    => 'Shield shadow',
    'PCGF_SMILIECREATOR_SHIELD_SHADOW_ON' => 'Enable',
    'PCGF_SMILIECREATOR_SMILIE'           => 'Smilie',
    'PCGF_SMILIECREATOR_TEXT'             => 'Shield text',
));