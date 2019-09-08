<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Plugin;


use MODX\Revolution\modPlugin;
use MODX\Revolution\modProcessorResponse;
use MODX\Revolution\modX;
use MODX\Revolution\Processors\Element\Plugin\Event\Update;

/**
 * Creates a plugin
 *
 * @property string  $name        The name of the plugin
 * @property string  $plugincode  The code of the plugin.
 * @property string  $description (optional) A description of the plugin.
 * @property integer $category    (optional) The category for the plugin. Defaults to
 * no category.
 * @property boolean $locked      (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @property boolean $disabled    (optional) If true, the plugin does not execute.
 * @property string  $events      (optional) A JSON array of system events to associate
 * this plugin with.
 *
 * @package MODX\Revolution\Processors\Element\Plugin
 */
class Create extends \MODX\Revolution\Processors\Element\Create
{
    public $classKey = modPlugin::class;
    public $languageTopics = ['plugin', 'category', 'element'];
    public $permission = 'new_plugin';
    public $objectType = 'plugin';
    public $beforeSaveEvent = 'OnBeforePluginFormSave';
    public $afterSaveEvent = 'OnPluginFormSave';

    public function beforeSave()
    {
        $isStatic = intval($this->getProperty('static', 0));

        if ($isStatic == 1) {
            $staticFile = $this->getProperty('static_file');

            if (empty($staticFile)) {
                $this->addFieldError('static_file', $this->modx->lexicon('static_file_ns'));
            }
        }

        return parent::beforeSave();
    }

    public function afterSave()
    {
        $this->saveEvents();

        return parent::afterSave();
    }

    /**
     * Save system events
     *
     * @return void
     */
    public function saveEvents()
    {
        $events = $this->getProperty('events', null);
        if ($events != null) {
            $events = is_array($events) ? $events : $this->modx->fromJSON($events);
            foreach ($events as $id => $event) {
                $properties = array_merge($event, [
                    'plugin' => $this->object->get('id'),
                    'event' => $event['name'],
                ]);
                /** @var modProcessorResponse $response */
                $response = $this->modx->runProcessor(Update::class, $properties);
                if ($response->isError()) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, $response->getMessage() . print_r($properties, true));
                }
            }
        }
    }
}
