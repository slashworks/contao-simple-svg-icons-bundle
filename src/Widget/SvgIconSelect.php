<?php

namespace Slashworks\ContaoSimpleSvgIconsBundle\Widget;

use Contao\RadioButton;
use Contao\Widget;
use Contao\StringUtil;
use Contao\System;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class SvgIconSelect
 *
 * Provide methods for an svg icon select widget.
 *
 * @package Slashworks\ContaoSimpleSvgIconsBundle\Widget
 */
class SvgIconSelect extends RadioButton
{

    /**
     * Submit user input
     * @var boolean
     */
    protected $blnSubmitInput = true;

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_widget_svgiconselect';

    /**
     * Generate the widget and return it as string
     *
     * @return string
     */
    public function generate()
    {
        $arrOptions = array();

        foreach ($this->arrOptions as $i=>$arrOption)
        {
            if ($arrOption['value'] === '') {
                $svgIcon = '';
            } else {
                $insertTagParser = System::getContainer()->get('contao.insert_tag.parser');
                $svgIcon = $insertTagParser->replace('{{svg::' . $arrOption['value'] . '}}') . ' ';
            }

            $arrOptions[] = sprintf('<div class="item"><input type="radio" name="%s" id="opt_%s" class="tl_radio" value="%s"%s%s onfocus="Backend.getScrollOffset()"> <label for="opt_%s" title="%s">%s</label></div>',
                $this->strName,
                $this->strId.'_'.$i,
                StringUtil::specialchars($arrOption['value']),
                $this->isChecked($arrOption),
                $this->getAttributes(),
                $this->strId.'_'.$i,
                $arrOption['label'],
                $svgIcon);
        }

        // Add a "no entries found" message if there are no options
        if (empty($arrOptions))
        {
            $arrOptions[]= '<p class="tl_noopt">'.$GLOBALS['TL_LANG']['MSC']['noResult'].'</p>';
        }

        return sprintf('<fieldset id="ctrl_%s" class="tl_radio_container tl_iconselect_container%s"><legend>%s%s%s%s</legend><div class="item-container">%s</div></fieldset>%s',
            $this->strId,
            (($this->strClass != '') ? ' ' . $this->strClass : ''),
            ($this->mandatory ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].' </span>' : ''),
            $this->strLabel,
            ($this->mandatory ? '<span class="mandatory">*</span>' : ''),
            $this->xlabel,
            implode('', $arrOptions),
            $this->wizard);
    }

}
