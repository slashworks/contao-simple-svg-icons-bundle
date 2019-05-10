<?php

/*
 * This file is part of Contao Simple SVG Icons Bundle.
 *
 * (c) slashworks
 *
 * @license LGPL-3.0-or-later
 */

namespace Slashworks\ContaoSimpleSvgIconsBundle\Hook;

use Contao\BackendTemplate;
use Contao\FrontendTemplate;
use Contao\Template;

class ParseTemplate
{
    /**
     * Replace link title of hyperlink elements when using svg insert tags.
     *
     * @param FrontendTemplate|BackendTemplate $template
     */
    public function replaceLinkTitleWithSvgInsertTag(Template $template)
    {
        if (false === strpos($template->getName(), 'ce_hyperlink')) {
            return;
        }

        if (false === strpos($template->linkTitle, '{{svg::')) {
            return;
        }

        $template->linkTitle = $template->href;
    }
}
