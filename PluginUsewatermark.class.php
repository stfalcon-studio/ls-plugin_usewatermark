<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Use Watermark
 * @Plugin Id: usewatermark
 * @Plugin URI:
 * @Description: The user can choose to use watermark or not
 * @Author: stfalcon-studio
 * @Author URI: http://stfalcon.com
 * @LiveStreet Version: 0.4.2
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

/**
 * Deny direct access to this file
 */
if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

class PluginUsewatermark extends Plugin
{

    public $aInherits = array(
        'module' => array('ModuleTopic'),
    );

    public function Activate() {
        $this->Cache_Clean();
        return true;
    }

    public function Init() {

    }

}