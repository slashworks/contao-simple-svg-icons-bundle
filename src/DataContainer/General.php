<?php

namespace Slashworks\ContaoSimpleSvgIconsBundle\DataContainer;

use Contao\Backend;
use Slashworks\ContaoSimpleSvgIconsBundle\SimpleSvgIcons;

class General extends Backend
{

    public function getIcons()
    {
        $icons = array();

        $iconFiles = SimpleSvgIcons::getSvgIconFiles();
        if (empty($iconFiles)) {
            return $icons;
        }

        foreach ($iconFiles as $iconFile) {
            foreach ($iconFile['symbols'] as $symbol) {
                $icons[$symbol] = $symbol;
            }
        }

        return $icons;
    }

}
