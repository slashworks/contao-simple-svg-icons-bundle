<?php

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
        if (strpos($template->getName(), 'ce_hyperlink') === false) {
            return;
        }

        if (strpos($template->linkTitle, '{{svg::') === false) {
            return;
        }

        $template->linkTitle = $template->href;
    }
}
