<?php

/**
 * @author    MarkusWME <markuswme@pcgamingfreaks.at>
 * @copyright 2016 MarkusWME
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace pcgf\smiliecreator\migrations;

use phpbb\db\migration\migration;

/** @version 1.0.1 */
class release_1_0_1 extends migration
{
    /**
     * Function for building the dependency tree
     *
     * @access public
     * @since  1.0.1
     *
     * @return array Dependency data
     */
    static public function depends_on()
    {
        return array('\pcgf\smiliecreator\migrations\release_1_0_0');
    }

    /**
     * Function that reverts needed extension data
     *
     * @access public
     * @since  1.0.1
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
     * Function that deletes BBCode data of the extension
     *
     * @access public
     * @since  1.0.1
     */
    public function delete_bbcode_data()
    {
        global $db;
        // Delete the BBCodes
        $query = 'DELETE
                  FROM ' . BBCODES_TABLE . '
                  WHERE bbcode_tag = "shield"
                        OR bbcode_tag = "shield="';
        $db->sql_query($query);
    }
}
