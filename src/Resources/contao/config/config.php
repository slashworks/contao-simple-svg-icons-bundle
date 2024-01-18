<?php

/**
 * Back end form fields
 */

use Contao\System;

$GLOBALS['BE_FFL']['svgiconselect'] = \Slashworks\ContaoSimpleSvgIconsBundle\Widget\SvgIconSelect::class;


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array(
    \Slashworks\ContaoSimpleSvgIconsBundle\Hook\ReplaceInsertTags::class,
    'replaceSvgInsertTags',
);
$GLOBALS['TL_HOOKS']['parseTemplate'][] = array(
    \Slashworks\ContaoSimpleSvgIconsBundle\Hook\ParseTemplate::class,
    'replaceLinkTitleWithSvgInsertTag',
);


/**
 * Back end assets
 */
$request = System::getContainer()->get('request_stack')->getCurrentRequest();
if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)) {
    $GLOBALS['TL_CSS'][] = 'bundles/contaosimplesvgicons/backend/backend.css|static';
}
