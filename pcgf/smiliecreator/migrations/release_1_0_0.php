<?php

/**
 * @author    MarkusWME <markuswme@pcgamingfreaks.at>
 * @copyright 2016 MarkusWME
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace pcgf\smiliecreator\migrations;

use phpbb\db\migration\migration;

/** @version 1.0.0 */
class release_1_0_0 extends migration
{
    /**
     * Function that checks if the extension has been effectively installed
     *
     * @access public
     * @since  1.0.0
     *
     * @return bool If the extension has been installed effectively
     */
    public function effectively_installed()
    {
        global $db;
        $query = 'SELECT *
                  FROM ' . BBCODES_TABLE . '
                  WHERE bbcode_tag = "shield"';
        $result = $db->sql_query($query);
        return $db->sql_fetchrow($result) !== false;
    }

    /**
     * Function for building the dependency tree
     *
     * @access public
     * @since  1.0.0
     *
     * @return array Dependency data
     */
    static public function depends_on()
    {
        return array('\phpbb\db\migration\data\v31x\v311');
    }

    /**
     * Function that updates needed extension data
     *
     * @access public
     * @since  1.0.0
     *
     * @return array Update information array
     */
    public function update_data()
    {
        return array(
            array('custom', array(array($this, 'update_bbcode_data'))),
        );
    }

    /**
     * Function that inserts BBCode data for the extension
     *
     * @access public
     * @since  1.0.0
     */
    public function update_bbcode_data()
    {
        global $db, $phpbb_root_path;
        // Get the highest bbcode_id
        $query = 'SELECT MAX(bbcode_id)
                  FROM ' . BBCODES_TABLE;
        $result = $db->sql_query($query);
        $id = $db->sql_fetchrow($result);
        if ($id !== false)
        {
            $id = $id['MAX(bbcode_id)'] + 1;
            while ($id <= NUM_CORE_BBCODES)
            {
                $id++;
            }
        }
        else
        {
            $id = NUM_CORE_BBCODES + 1;
        }
        $db->sql_freeresult($result);
        // Insert BBCode data into the database
        $insert_data = array(
            array(
                'bbcode_id'           => $id++,
                'bbcode_tag'          => 'shield',
                'display_on_posting'  => 0,
                'bbcode_match'        => '[shield]{INTTEXT}[/shield]',
                'bbcode_tpl'          => '<img src="' . $phpbb_root_path . 'app.php/pcgf/getsmilieshield?text={INTTEXT}" alt="{L_IMAGE}"/>',
                'first_pass_match'    => '!\[shield\]([\p{L}\p{N}\-+,_. ]+)\[/shield\]!iu',
                'first_pass_replace'  => '[shield:$uid]${1}[/shield:$uid]',
                'second_pass_match'   => '!\[shield:$uid\]([\p{L}\p{N}\-+,_. ]+)\[/shield:$uid\]!su',
                'second_pass_replace' => '<img src="' . $phpbb_root_path . 'app.php/pcgf/getsmilieshield?text=${1}" alt="{L_IMAGE}"/>',
            ),
            array(
                'bbcode_id'           => $id,
                'bbcode_tag'          => 'shield=',
                'display_on_posting'  => 0,
                'bbcode_match'        => '[shield={SIMPLETEXT1},{NUMBER},{SIMPLETEXT2},{SIMPLETEXT3}]{TEXT}[/shield]',
                'bbcode_tpl'          => '<img src="' . $phpbb_root_path . 'app.php/pcgf/getsmilieshield?smilie={SIMPLETEXT1}&shadow={NUMBER}&color={SIMPLETEXT2}&scolor={SIMPLETEXT3}&text={TEXT}" alt="{L_IMAGE}"/>',
                'first_pass_match'    => '!\[shield\=([a-zA-Z0-9-+.,_ ]+),([0-9]+),([a-zA-Z0-9-+.,_ ]+),([a-zA-Z0-9-+.,_ ]+)\](.*?)\[/shield\]!ies',
                'first_pass_replace'  => '\'[shield=${1},${2},${3},${4}:$uid]\'.str_replace(array("\r\n", \'\\"\', \'\\\'\', \'(\', \')\'), array("\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'), trim(\'${5}\')).\'[/shield:$uid]\'',
                'second_pass_match'   => '!\[shield\=([a-zA-Z0-9-+.,_ ]+),([0-9]+),([a-zA-Z0-9-+.,_ ]+),([a-zA-Z0-9-+.,_ ]+):$uid\](.*?)\[/shield:$uid\]!s',
                'second_pass_replace' => '<img src="' . $phpbb_root_path . 'app.php/pcgf/getsmilieshield?smilie=${1}&shadow=${2}&color=${3}&scolor=${4}&text=${5}" alt="{L_IMAGE}"/>',
            ),
        );
        $db->sql_multi_insert(BBCODES_TABLE, $insert_data);
    }
}
