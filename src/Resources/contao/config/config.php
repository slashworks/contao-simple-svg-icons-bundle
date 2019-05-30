<?php

/**
 * Back end form fields
 */

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
if (TL_MODE === 'BE') {
    $GLOBALS['TL_CSS'][] = 'bundles/contaosimplesvgicons/backend/backend.css|static';
}
