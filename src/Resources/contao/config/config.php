<?php

// Hooks
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array(
    \Slashworks\ContaoSimpleSvgIconsBundle\Hook\ReplaceInsertTags::class,
    'replaceSvgInsertTags',
);
$GLOBALS['TL_HOOKS']['parseTemplate'][] = array(
    \Slashworks\ContaoSimpleSvgIconsBundle\Hook\ParseTemplate::class,
    'replaceLinkTitleWithSvgInsertTag',
);
