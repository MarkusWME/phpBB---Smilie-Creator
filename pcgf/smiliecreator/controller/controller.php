<?php

/**
 * @author    MarkusWME <markuswme@pcgamingfreaks.at>
 * @copyright 2016, 2017 MarkusWME
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace pcgf\smiliecreator\controller;

use phpbb\controller\helper;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use Symfony\Component\HttpFoundation\Response;

/** @version 1.1.2 */
class controller
{
    /** @const Max smilie count for one row */
    const MAX_SMILIE_COLS = 5;

    /** @const Max characters of one row */
    const MAX_CHARACTERS_PER_ROW = 33;

    /** @const Minimum width of the shield */
    const MIN_SHIELD_WIDTH = 60;

    /** @var request $request The request object */
    protected $request;

    /** @var user $user The user object */
    protected $user;

    /** @var template $template The template object */
    protected $template;

    /** @var helper $helper The helper object */
    protected $helper;

    /** @var string $phpbb_root_path Forum root path */
    protected $phpbb_root_path;

    /**
     * Constructor
     *
     * @access public
     * @since  1.0.0
     *
     * @param request  $request         The request object
     * @param user     $user            The user object
     * @param template $template        The template object
     * @param helper   $helper          The helper object
     * @param string   $phpbb_root_path Forum root path
     */
    public function __construct(request $request, user $user, template $template, helper $helper, $phpbb_root_path)
    {
        $this->request = $request;
        $this->user = $user;
        $this->template = $template;
        $this->helper = $helper;
        $this->phpbb_root_path = $phpbb_root_path;
    }

    /**
     * Function that returns the smilie creator form
     *
     * @access public
     * @since  1.0.0
     *
     * @return \Symfony\Component\HttpFoundation\Response Rendered smilie creator page
     */
    public function get_smilie_creator()
    {
        $this->user->add_lang_ext('pcgf/smiliecreator', 'smiliecreator');
        $smilie_handle = opendir($this->phpbb_root_path . 'ext/pcgf/smiliecreator/styles/all/theme/images/');
        $smilie_count = 0;
        $smilie_column = 0;
        // Get GD library info of PHP
        $gd_info = gd_info();
        // Add every smilie with the correct file extension to the form
        while (($smilie = readdir($smilie_handle)) !== false)
        {
            $smilie_name = substr($smilie, 0, -4);
            $smilie_extension = strtolower(substr($smilie, -3));
            if ((($smilie_extension == 'png' && $gd_info['PNG Support'] == true) || ($smilie_extension == 'gif' && $gd_info['GIF Read Support'] == true) || ($smilie_extension == 'jpg' && $gd_info['JPEG Support'] == true)) && strtolower($smilie_name != 'shield'))
            {
                $smilie_count++;
                $next_row = $smilie_column++ >= self::MAX_SMILIE_COLS;
                if ($next_row)
                {
                    $smilie_column = 0;
                }
                $this->template->assign_block_vars('smilie_selection', array(
                    'NEXT_ROW'       => $next_row,
                    'SMILIE'         => $smilie_name,
                    'FILE_EXTENSION' => $smilie_extension,
                ));
            }
        }
        closedir($smilie_handle);
        $smilie_columns = $smilie_count > self::MAX_SMILIE_COLS ? self::MAX_SMILIE_COLS : $smilie_column;
        $this->template->assign_vars(array(
            'PCGF_SMILIECREATOR_SMILIE_PATH'     => $this->phpbb_root_path . '../../ext/pcgf/smiliecreator/styles/all/theme/images/',
            'PCGF_SMILIECREATOR_COLS'            => $smilie_columns,
            'PCGF_SMILIECREATOR_WIDTH'           => $smilie_columns + 1,
            'PCGF_SMILIECREATOR_GET_SHIELD_LINK' => $this->helper->route('pcgf_smiliecreator_get_shield'),
        ));
        // Render the page
        return $this->helper->render('smilie_creator.html', $this->user->lang('PCGF_SMILIECREATOR'), 200);
    }

    /**
     * Function that creates the smilie shield
     *
     * @access public
     * @since  1.0.0
     *
     * @return \Symfony\Component\HttpFoundation\Response Rendered smilie shield image
     */
    public function get_shield()
    {
        $this->user->add_lang_ext('pcgf/smiliecreator', 'smiliecreator');
        // Get the request variables
        $text = htmlspecialchars_decode($this->request->variable('text', '', true));
        $style = $this->request->variable('style', 'shield');
        $smilie = $this->request->variable('smilie', 'random');
        $color = $this->request->variable('color', '000000');
        $shadow_color = $this->request->variable('scolor', '-');
        $shield_shadow = $this->request->variable('shadow', 0);
        $gd_info = gd_info();
        $font_width = 6;
        $font_height = 10;
        if ($gd_info['FreeType Support'] == true)
        {
            $font_width = imagefontwidth(0) + 1;
            $font_height = imagefontheight(0) + 2;
        }
        // Set the text if it is not set and prepare the text for the shield
        if (strlen($text) <= 0)
        {
            $text = $this->user->lang('PCGF_SMILIECREATOR_NO_TEXT_GIVEN');
        }
        $words = explode(' ', $text);
        $lines = array('');
        $current_line = 0;
        while (count($words) > 0)
        {
            $current_word = array_shift($words);
            $word_length = strlen($current_word);
            $line_length = strlen($lines[$current_line]);
            if ($word_length + $line_length < self::MAX_CHARACTERS_PER_ROW)
            {
                // If the current word fits in the line the word gets added
                $lines[$current_line] .= ' ' . $current_word;
            }
            else if ($word_length <= self::MAX_CHARACTERS_PER_ROW)
            {
                // If the word could be added into a new line without getting splitted the word will be in the next line
                $lines[++$current_line] = $current_word;
            }
            else
            {
                // If the word is too long for one line the word gets splitted
                $length = self::MAX_CHARACTERS_PER_ROW - $line_length - 1;
                $lines[$current_line] .= ' ' . substr($current_word, 0, $length) . '-';
                $current_word = substr($current_word, $length);
                $word_length = strlen($current_word);
                $current_line++;
                while ($word_length > self::MAX_CHARACTERS_PER_ROW)
                {
                    $lines[$current_line++] = substr($current_word, 0, self::MAX_CHARACTERS_PER_ROW - 1) . '-';
                    $current_word = substr($current_word, self::MAX_CHARACTERS_PER_ROW - 1);
                    $word_length = strlen($current_word);
                }
                $lines[$current_line] = $current_word;
            }
        }
        $max_character_count = 0;
        foreach ($lines as $line)
        {
            $max_character_count = max($max_character_count, strlen($line));
        }
        $width = ($max_character_count * $font_width) + 40 + ($style == 'bubble' ? 40 : 0);
        if ($width < self::MIN_SHIELD_WIDTH)
        {
            $width = self::MIN_SHIELD_WIDTH;
        }
        $line_count = count($lines);
        $height = ($line_count * $font_height) + 40 + ($style == 'bubble' ? 40 : 0);
        // Load the smilie image
        $smilie_path = $this->phpbb_root_path . 'ext/pcgf/smiliecreator/styles/all/theme/images/';
        if (!file_exists($smilie_path . $smilie))
        {
            // If the selected smilie does not exist load a random one
            $smilies = array();
            $smilie_handle = opendir($smilie_path);
            while (($smilie_file = readdir($smilie_handle)) !== false)
            {
                $smilie_name = substr($smilie_file, 0, -4);
                $smilie_extension = strtolower(substr($smilie_file, -3));
                if ((($smilie_extension == 'png' && $gd_info['PNG Support'] == true) || ($smilie_extension == 'gif' && $gd_info['GIF Read Support'] == true) || ($smilie_extension == 'jpg' && $gd_info['JPEG Support'] == true)) && strtolower($smilie_name != 'shield'))
                {
                    array_push($smilies, $smilie_file);
                }
            }
            closedir($smilie_handle);
            $smilie = $smilies[rand(0, count($smilies) - 1)];
        }
        $smilie_image = imagecreatefrompng($smilie_path . $smilie);
        // Generate the image
        $smilie_shield = imagecreate($width, $height);
        $color_background = imagecolorallocate($smilie_shield, 111, 252, 134);
        $color_text = imagecolorallocate($smilie_shield, hexdec(substr($color, 0, 2)), hexdec(substr($color, 2, 2)), hexdec(substr($color, 4, 2)));
        if ($shadow_color != '-')
        {
            $color_textshadow = imagecolorallocate($smilie_shield, hexdec(substr($shadow_color, 0, 2)), hexdec(substr($shadow_color, 2, 2)), hexdec(substr($shadow_color, 4, 2)));
        }
        $color_border = imagecolorallocate($smilie_shield, 0, 0, 0);
        $color_shield = imagecolorallocate($smilie_shield, 255, 255, 255);
        $color_shieldshadow1 = imagecolorallocate($smilie_shield, 240, 240, 240);
        $color_shieldshadow2 = imagecolorallocate($smilie_shield, 225, 225, 225);
        $color_shieldshadow3 = imagecolorallocate($smilie_shield, 210, 210, 210);
        switch ($style)
        {
            case 'bubble':
                // Copy the smilie to the image
                imagecopy($smilie_shield, $smilie_image, ($width / 4), $height - 24, 0, 0, 23, 23);
                // Draw the main bubble
                imageellipse($smilie_shield, $width / 2, (($height - 60) / 2) + 5, $width - 10, $height - 60, $color_border);
                // Draw the other bubbles
                imageellipse($smilie_shield, ($width / 4) + ($width / 12), $height - 30, 4, 4, $color_border);
                imagefilledellipse($smilie_shield, ($width / 4) + ($width / 12), $height - 30, 2, 2, $color_shield);
                imageellipse($smilie_shield, ($width / 3) + ($width / 12), $height - 36, 8, 8, $color_border);
                imageellipse($smilie_shield, $width / 2, $height - 45, 12, 12, $color_border);
                if ($shield_shadow == true)
                {
                    // Draw the bubble shadow if activated
                    imagefilledellipse($smilie_shield, $width / 2, (($height - 60) / 2) + 5, $width - 12, $height - 62, $color_shieldshadow3);
                    imagefilledellipse($smilie_shield, ($width / 3) + ($width / 12), $height - 36, 6, 6, $color_shieldshadow2);
                    imagefilledellipse($smilie_shield, $width / 2, $height - 45, 10, 10, $color_shieldshadow3);
                }
                else
                {
                    imagefilledellipse($smilie_shield, $width / 2, (($height - 60) / 2) + 5, $width - 12, $height - 62, $color_shield);
                    imagefilledellipse($smilie_shield, ($width / 3) + ($width / 12), $height - 36, 6, 6, $color_shield);
                    imagefilledellipse($smilie_shield, $width / 2, $height - 45, 10, 10, $color_shield);
                }
                // Write the text to the shield
                for ($i = 0; $i < $line_count; $i++)
                {
                    if ($shadow_color != '-')
                    {
                        // Draw a shadow if activated
                        imagestring($smilie_shield, 2, (($width - (strlen($lines[$i]) * $font_width) - 2) / 2) + 1, ($i * $font_height) + 14, $lines[$i], $color_textshadow);
                    }
                    imagestring($smilie_shield, 2, ($width - (strlen($lines[$i]) * $font_width) - 2) / 2, ($i * $font_height) + 13, $lines[$i], $color_text);
                }
            break;
            default:
                $shield_image = imagecreatefrompng($smilie_path . 'shield.png');
                $color_smilie = imagecolorsforindex($smilie_image, imagecolorat($smilie_image, 5, 14));
                // Draw the hand of the smilie
                $smilie_color1 = imagecolorallocate($shield_image, $color_smilie["red"] + 52, $color_smilie["green"] + 59, $color_smilie["blue"] + 11);
                $smilie_color2 = imagecolorallocate($shield_image, $color_smilie["red"] + 50, $color_smilie["green"] + 52, $color_smilie["blue"] + 50);
                $smilie_color3 = imagecolorallocate($shield_image, $color_smilie["red"] + 22, $color_smilie["green"] + 21, $color_smilie["blue"] + 35);
                $smilie_color4 = imagecolorat($smilie_image, 5, 14);
                imagesetpixel($shield_image, 1, 14, $smilie_color1);
                imagesetpixel($shield_image, 2, 14, $smilie_color2);
                imagesetpixel($shield_image, 1, 15, $smilie_color2);
                imagesetpixel($shield_image, 2, 15, $smilie_color3);
                imagesetpixel($shield_image, 1, 16, $smilie_color4);
                imagesetpixel($shield_image, 2, 16, $smilie_color4);
                imagesetpixel($shield_image, 5, 16, $smilie_color3);
                imagesetpixel($shield_image, 6, 16, $smilie_color4);
                imagesetpixel($shield_image, 5, 15, $smilie_color1);
                imagesetpixel($shield_image, 6, 15, $smilie_color2);
                // Copy the smilie image and the shield image
                imagecopy($smilie_shield, $shield_image, ($width / 2) - 3, 0, 0, 0, 6, 4);
                imagecopy($smilie_shield, $shield_image, ($width / 2) - 3, $height - 24, 0, 5, 9, 17);
                imagecopy($smilie_shield, $smilie_image, ($width / 2) + 6, $height - 24, 0, 0, 23, 23);
                // Draw the shield
                imagefilledrectangle($smilie_shield, 0, 4, $width, $height - 25, $color_border);
                imagefilledrectangle($smilie_shield, 1, 5, $width - 2, $height - 26, $color_shield);
                if ($shield_shadow == true)
                {
                    // Draw the shield shadow
                    $bottom = $height - 26;
                    $height5 = ($height - 31) / 5;
                    $width2 = $width / 2;
                    $width6 = $width / 6;
                    $width24 = $width / 24;
                    $polygon = array(
                        1, $bottom,
                        $width - $width6, 5,
                        $width - 2, 5,
                        $width6 + (12 * $width24), $bottom - (4 * $height5),
                        $width6 + (9 * $width24), $bottom - (3 * $height5),
                        $width6 + (6 * $width24), $bottom - (2 * $height5),
                        $width6 + (3 * $width24), $bottom - $height5,
                        $width6, $bottom,
                    );
                    imagefilledpolygon($smilie_shield, $polygon, 8, $color_shieldshadow1);
                    $polygon = array(
                        $width6, $bottom,
                        $width6 + (3 * $width24), $bottom - $height5,
                        $width6 + (6 * $width24), $bottom - (2 * $height5),
                        $width6 + (9 * $width24), $bottom - (3 * $height5),
                        $width6 + (12 * $width24), $bottom - (4 * $height5),
                        $width - 2, 5,
                        $width2 + (8 * $width24), $bottom - (4 * $height5),
                        $width2 + (6 * $width24), $bottom - (3 * $height5),
                        $width2 + (4 * $width24), $bottom - (2 * $height5),
                        $width2 + $width24, $bottom - $height5,
                        $width2, $bottom,
                    );
                    imagefilledpolygon($smilie_shield, $polygon, 11, $color_shieldshadow2);
                    $polygon = array(
                        $width2, $bottom,
                        $width2 + $width24, $bottom - $height5,
                        $width2 + (4 * $width24), $bottom - (2 * $height5),
                        $width2 + (6 * $width24), $bottom - (3 * $height5),
                        $width2 + (8 * $width24), $bottom - (4 * $height5),
                        $width - 2, 5,
                        $width - 2, $bottom,
                    );
                    imagefilledpolygon($smilie_shield, $polygon, 7, $color_shieldshadow3);
                }
                // Write the text to the shield
                for ($i = 0; $i < $line_count; $i++)
                {
                    if ($shadow_color != '-')
                    {
                        // Draw a shadow if activated
                        imagestring($smilie_shield, 2, (($width - (strlen($lines[$i]) * $font_width) - 2) / 2) + 1, ($i * $font_height) + 6, $lines[$i], $color_textshadow);
                    }
                    imagestring($smilie_shield, 2, ($width - (strlen($lines[$i]) * $font_width) - 2) / 2, ($i * $font_height) + 5, $lines[$i], $color_text);
                }
            break;
        }
        // Make the background color transparent
        imagecolortransparent($smilie_shield, $color_background);
        imageinterlace($smilie_shield, 1);
        // Return the image
        $response = new Response(base64_encode(imagepng($smilie_shield)), 200, array('Content-Type' => 'image/png'));
        imagedestroy($smilie_shield);
        return $response;
    }
}
