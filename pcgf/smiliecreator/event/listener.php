<?php

/**
 * @author    MarkusWME <markuswme@pcgamingfreaks.at>
 * @copyright 2016 MarkusWME
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace pcgf\smiliecreator\event;

use phpbb\controller\helper;
use phpbb\template\template;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/** @version 1.0.0 */
class listener implements EventSubscriberInterface
{
    /** @var user $user The user object */
    protected $user;

    /** @var template $template The template object */
    protected $template;

    /** @var helper $helper The helper object */
    protected $helper;

    /**
     * Constructor
     *
     * @access public
     * @since  1.0.0
     *
     * @param user     $user     The user object
     * @param template $template The template object
     * @param helper   $helper   The helper object
     */
    public function __construct(user $user, template $template, helper $helper)
    {
        $this->user = $user;
        $this->template = $template;
        $this->helper = $helper;
    }

    /**
     * Function that returns the subscribed events
     *
     * @access public
     * @since  1.0.0
     *
     * @return array The subscribed event list
     */
    static public function getSubscribedEvents()
    {
        return array(
            'core.posting_modify_template_vars' => 'setup_template_data',
            'core.ucp_pm_compose_modify_data'   => 'setup_template_data',
        );
    }

    /**
     * Function that set's the template variables for the extension
     *
     * @access public
     * @since  1.0.0
     */
    public function setup_template_data()
    {
        $this->user->add_lang_ext('pcgf/smiliecreator', 'smiliecreator');
        $this->template->assign_vars(array(
            'PCGF_SMILIECREATOR_LINK' => $this->helper->route('pcgf_smiliecreator_get_creator'),
        ));
    }
}
