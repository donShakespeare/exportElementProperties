<?php
/**
 * plugins transport file for exportElementProperties extra
 *
 * Copyright 2016 by donShakespeare 
 * Created on 10-24-2016
 *
 * @package exportelementproperties
 * @subpackage build
 */

if (! function_exists('stripPhpTags')) {
    function stripPhpTags($filename) {
        $o = file_get_contents($filename);
        $o = str_replace('<' . '?' . 'php', '', $o);
        $o = str_replace('?>', '', $o);
        $o = trim($o);
        return $o;
    }
}
/* @var $modx modX */
/* @var $sources array */
/* @var xPDOObject[] $plugins */


$plugins = array();

$plugins[1] = $modx->newObject('modPlugin');
$plugins[1]->fromArray(array (
  'id' => 1,
  'property_preprocess' => false,
  'name' => 'exportElementProperties',
  'description' => 'Add parameter &p to any element url. For PropertySet Page add name of desired pset, e.g  &p=myOwnSet. You can also disable the need for the parameter (check this plugin\'s properties)',
  'disabled' => false,
), '', true, true);
$plugins[1]->setContent(file_get_contents($sources['source_core'] . '/elements/plugins/exportelementproperties.plugin.php'));


$properties = include $sources['data'].'properties/properties.exportelementproperties.plugin.php';
$plugins[1]->setProperties($properties);
unset($properties);

return $plugins;
