<?php

/**
 * @author    MarkusWME <markuswme@pcgamingfreaks.at>
 * @copyright 2016 MarkusWME
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace pcgf\smiliecreator\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/** @version 1.0.0 */
class listener implements EventSubscriberInterface
{
    /** @var \phpbb\user $user The user object */
    protected $user;

    /** @var \phpbb\template\template $template The template object */
    protected $template;

    /** @var \phpbb\controller\helper $helper The helper object */
    protected $helper;

    /**
     * Constructor
     *
     * @access public
     * @since  1.0.0
     *
     * @param \phpbb\user              $user     The user object
     * @param \phpbb\template\template $template The template object
     * @param \phpbb\controller\helper $helper   The helper object
     *
     * @return \pcgf\smiliecreator\event\listener The listener object of the extension
     */
    public function __construct(\phpbb\user $user, \phpbb\template\template $template, \phpbb\controller\helper $helper)
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
     *
     * @param $event The event parameter array
     *
     * @return null
     */
    public function setup_template_data($event)
    {
        $this->user->add_lang_ext('pcgf/smiliecreator', 'smiliecreator');
        $this->template->assign_vars(array(
            'PCGF_SMILIECREATOR_LINK' => $this->helper->route('pcgf_smiliecreator_get_creator'),
        ));
    }
}
