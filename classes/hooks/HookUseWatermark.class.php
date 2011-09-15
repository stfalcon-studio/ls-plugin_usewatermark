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

class PluginUsewatermark_HookUseWatermark extends Hook
{

    /**
     * Register hooks to upload image
     *
     * @return void
     */
    public function RegisterHook() {
        $this->AddHook('engine_init_complete', 'AddWatermark');

        $this->AddHook('template_uploadimg_source', 'AddWatermarkOption');
    }

    /**
     * Add watermark to image
     *
     * @param array $aData
     * @return void
     */
    public function AddWatermark($aData) {
        if (getRequest('add_watermark')) {
//          $watermarkText = Router::GetPath('profile') . $this->User_GetUserCurrent()->getLogin();
//          Config::Set('module.image.default.watermark_text', $watermarkText);
        } else {
            Config::Set('module.image.default.watermark_use', false);
            Config::Set('module.image.topic.watermark_use', false);
            Config::Set('module.image.foto.watermark_use', false);
        }
    }

    /**
     * Добавляет в шаблон загрузчика изображения опцию наложения водяного знака
     *
     * @return type
     */
    public function AddWatermarkOption() {

        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'window_load_img.wattermark.tpl');
    }

}

?>