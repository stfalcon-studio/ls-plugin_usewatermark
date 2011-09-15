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
 * Модуль плагина Usewatermark
 */
class PluginUsewatermark_ModuleUsewatermark extends Module
{

    /**
     * Метод инициализации модуля
     *
     * @return void
     */
    public function Init() {

    }

    /**
     * Возвращает путь к изображению
     *
     * @param type $sPath
     * @return type
     */
    public function getOriginalImage($sPath) {
        $imagePath = Config::Get('path.uploads.images');
        $originalPath = Config::Get('plugin.usewatermark.path_image_original');
        return str_replace($imagePath, $originalPath, $sPath);
    }

}

?>