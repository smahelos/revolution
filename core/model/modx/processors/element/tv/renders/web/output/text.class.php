<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
class modTemplateVarOutputRenderText extends modTemplateVarOutputRender {
    public function process($value,array $params = array()) {
        $value= $this->tv->parseInput($value);
        if ($this->tv->get('type') == 'checkbox' || $this->tv->get('type') == 'listbox-multiple') {
            // remove delimiter from checkbox and listbox-multiple TVs
            $value= str_replace('||', '', $value);
        }
        $o= (string) $value;
        return $o;
    }
}
return 'modTemplateVarOutputRenderText';
