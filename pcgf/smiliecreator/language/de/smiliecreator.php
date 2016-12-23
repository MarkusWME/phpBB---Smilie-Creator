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
    'PCGF_SMILIECREATOR_CLOSE'            => 'Schließen',
    'PCGF_SMILIECREATOR_COLOR_DARK_RED'   => 'Dunkelrot',
    'PCGF_SMILIECREATOR_COLOR_RED'        => 'Rot',
    'PCGF_SMILIECREATOR_COLOR_ORANGE'     => 'Orange',
    'PCGF_SMILIECREATOR_COLOR_BROWN'      => 'Braun',
    'PCGF_SMILIECREATOR_COLOR_YELLOW'     => 'Gelb',
    'PCGF_SMILIECREATOR_COLOR_GREEN'      => 'Grün',
    'PCGF_SMILIECREATOR_COLOR_OLIVE'      => 'Olive',
    'PCGF_SMILIECREATOR_COLOR_CYAN'       => 'Cyan',
    'PCGF_SMILIECREATOR_COLOR_BLUE'       => 'Blau',
    'PCGF_SMILIECREATOR_COLOR_DARK_BLUE'  => 'Dunkelblau',
    'PCGF_SMILIECREATOR_COLOR_INDIGO'     => 'Indigo',
    'PCGF_SMILIECREATOR_COLOR_VIOLET'     => 'Violett',
    'PCGF_SMILIECREATOR_COLOR_WHITE'      => 'Weis',
    'PCGF_SMILIECREATOR_COLOR_BLACK'      => 'Schwarz',
    'PCGF_SMILIECREATOR_COLOR_NONE'       => 'Keine Farbe',
    'PCGF_SMILIECREATOR_CREATE'           => 'Smilie-Schild erstellen',
    'PCGF_SMILIECREATOR_FONT_COLOR'       => 'Textfarbe',
    'PCGF_SMILIECREATOR_NO_TEXT_GIVEN'    => 'Es wurde noch kein Text eingegeben!',
    'PCGF_SMILIECREATOR_PREVIEW'          => 'Vorschau',
    'PCGF_SMILIECREATOR_RANDOM'           => 'Zufälliges Smilie',
    'PCGF_SMILIECREATOR_SHADOW_COLOR'     => 'Schattenfarbe',
    'PCGF_SMILIECREATOR_SHIELD_SHADOW'    => 'Schild-Schatten',
    'PCGF_SMILIECREATOR_SHIELD_SHADOW_ON' => 'Aktiviert',
    'PCGF_SMILIECREATOR_SMILIE'           => 'Smilie',
    'PCGF_SMILIECREATOR_TEXT'             => 'Schild-Text',
));