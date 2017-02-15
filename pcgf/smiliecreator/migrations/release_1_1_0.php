<?php

/**
 * @author    MarkusWME <markuswme@pcgamingfreaks.at>
 * @copyright 2016 MarkusWME
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace pcgf\smiliecreator\migrations;

use phpbb\db\migration\migration;

/** @version 1.1.0 */
class release_1_1_0 extends migration
{
    /**
     * Function for building the dependency tree
     *
     * @access public
     * @since  1.1.0
     *
     * @return array Dependency data
     */
    static public function depends_on()
    {
        return array('\pcgf\smiliecreator\migrations\release_1_0_1');
    }

    /**
     * Function that updates needed extension data
     *
     * @access public
     * @since  1.1.0
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
     * Function that reverts needed extension data
     *
     * @access public
     * @since  1.1.0
     *
     * @return array Revert information array
     */
    public function revert_data()
    {
        return array(
            array('custom', array(array($this, 'delete_bbcode_data'))),
        );
    }

    /**
     * Function that inserts BBCode data for the extension
     *
     * @access public
     * @since  1.1.0
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
                'bbcode_tag'          => 'speechbubble',
                'display_on_posting'  => 0,
                'bbcode_match'        => '[speechbubble]{TEXT}[/speechbubble]',
                'bbcode_tpl'          => '<img src="' . $phpbb_root_path . 'app.php/pcgf/getsmilieshield?style=bubble&text={TEXT}" alt="{L_IMAGE}"/>',
                'first_pass_match'    => '!\[speechbubble\](.*?)\[/speechbubble\]!ies',
                'first_pass_replace'  => '\'[speechbubble:$uid]\'.str_replace(array("\r\n", \'\"\', \'\\\'\', \'(\', \')\'), array("\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'), trim(\'${1}\')).\'[/speechbubble:$uid]\'',
                'second_pass_match'   => '!\[speechbubble:$uid\](.*?)\[/speechbubble:$uid\]!s',
                'second_pass_replace' => '<img src="' . $phpbb_root_path . 'app.php/pcgf/getsmilieshield?style=bubble&text=${1}" alt="{L_IMAGE}"/>',
            ),
            array(
                'bbcode_id'           => $id,
                'bbcode_tag'          => 'speechbubble=',
                'display_on_posting'  => 0,
                'bbcode_match'        => '[speechbubble={SIMPLETEXT1},{NUMBER},{SIMPLETEXT2},{SIMPLETEXT3}]{TEXT}[/speechbubble]',
                'bbcode_tpl'          => '<img src="' . $phpbb_root_path . 'app.php/pcgf/getsmilieshield?style=bubble&smilie={SIMPLETEXT1}&shadow={NUMBER}&color={SIMPLETEXT2}&scolor={SIMPLETEXT3}&text={TEXT}" alt="{L_IMAGE}"/>',
                'first_pass_match'    => '!\[speechbubble\=([a-zA-Z0-9-+.,_ ]+),([0-9]+),([a-zA-Z0-9-+.,_ ]+),([a-zA-Z0-9-+.,_ ]+)\](.*?)\[/speechbubble\]!ies',
                'first_pass_replace'  => '\'[speechbubble=${1},${2},${3},${4}:$uid]\'.str_replace(array("\r\n", \'\"\', \'\\\'\', \'(\', \')\'), array("\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'), trim(\'${5}\')).\'[/speechbubble:$uid]\'',
                'second_pass_match'   => '!\[speechbubble\=([a-zA-Z0-9-+.,_ ]+),([0-9]+),([a-zA-Z0-9-+.,_ ]+),([a-zA-Z0-9-+.,_ ]+):$uid\](.*?)\[/speechbubble:$uid\]!s',
                'second_pass_replace' => '<img src="' . $phpbb_root_path . 'app.php/pcgf/getsmilieshield?style=bubble&smilie=${1}&shadow=${2}&color=${3}&scolor=${4}&text=${5}" alt="{L_IMAGE}"/>',
            ),
        );
        $db->sql_multi_insert(BBCODES_TABLE, $insert_data);
        // Update old shield BBCode to use TEXT instead of INTTEXT
        $update_data = array(
            'bbcode_match'        => '[shield]{TEXT}[/shield]',
            'bbcode_tpl'          => '<img src="' . $phpbb_root_path . 'app.php/pcgf/getsmilieshield?text={TEXT}" alt="{L_IMAGE}"/>',
            'first_pass_match'    => '!\[shield\](.*?)\[/shield\]!ies',
            'first_pass_replace'  => '\'[shield:$uid]\'.str_replace(array("\r\n", \'\"\', \'\\\'\', \'(\', \')\'), array("\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'), trim(\'${1}\')).\'[/shield:$uid]\'',
            'second_pass_match'   => '!\[shield:$uid\](.*?)\[/shield:$uid\]!s',
            'second_pass_replace' => '<img src="' . $phpbb_root_path . 'app.php/pcgf/getsmilieshield?text=${1}" alt="{L_IMAGE}"/>',
        );
        $query = 'UPDATE ' . BBCODES_TABLE . '
                  SET ' . $db->sql_build_array('UPDATE', $update_data) . '
                  WHERE bbcode_tag = "shield"';
        $db->sql_query($query);
    }

    /**
     * Function that deletes BBCode data of the extension
     *
     * @access public
     * @since  1.1.0
     */
    public function delete_bbcode_data()
    {
        global $db;
        // Delete the BBCodes
        $query = 'DELETE
                  FROM ' . BBCODES_TABLE . '
                  WHERE bbcode_tag = "speechbubble"
                        OR bbcode_tag = "speechbubble="';
        $db->sql_query($query);
    }
}
