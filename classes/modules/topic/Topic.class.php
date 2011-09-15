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
 * Модуль Topic плагина PluginUsewatermark
 */
class PluginUsewatermark_ModuleTopic extends PluginUsewatermark_Inherit_ModuleTopic
{

    /**
     * Загрузка изображений при написании топика
     *
     * @param  array $aFile
     * @param  ModuleUser_EntityUser $oUser
     * @return string|bool
     */
    public function UploadTopicImageFile($aFile, $oUser) {
        if (getRequest('add_watermark')) {
            return $this->_uploadTopicImageFileWithOriginal($aFile, $oUser);
        } else {
            return parent::UploadTopicImageFile($aFile, $oUser);
        }
    }

    protected function _uploadTopicImageFileWithOriginal($aFile, $oUser) {
        if (!is_array($aFile) || !isset($aFile['tmp_name'])) {
            return false;
        }

        $sFileTmp = Config::Get('sys.cache.dir') . func_generator();
        if (!move_uploaded_file($aFile['tmp_name'], $sFileTmp)) {
            return false;
        }

        $sFileDest = func_generator(6);
        //Сохраняем оригинал
        $uploadPath = Config::Get('path.uploads.images');
        if (Config::Get('plugin.usewatermark.path_image_original')) {
            Config::Set('module.image.topic.watermark_use', false);
            Config::Set('path.uploads.images', Config::Get('plugin.usewatermark.path_image_original'));
            $sDirUpload = $this->Image_GetIdDir($oUser->getId());
            $aParams = $this->Image_BuildParams('topic');
            if (!($this->Image_Resize($sFileTmp, $sDirUpload, $sFileDest, Config::Get('view.img_max_width'), Config::Get('view.img_max_height'), null, null, true, $aParams))) {
                @unlink($sFileTmp);
                return false;
            }
        }

        //Сохраняем с ватермарком
        Config::Set('path.uploads.images', $uploadPath);
        Config::Set('module.image.topic.watermark_use', true);
        $sDirUpload = $this->Image_GetIdDir($oUser->getId());
        $aParams = $this->Image_BuildParams('topic');

        if ($sFileImage = $this->Image_Resize($sFileTmp, $sDirUpload, $sFileDest, Config::Get('view.img_max_width'), Config::Get('view.img_max_height'), Config::Get('view.img_resize_width'), null, true, $aParams)) {
            @unlink($sFileTmp);
            return $this->Image_GetWebPath($sFileImage);
        }
        @unlink($sFileTmp);
        return false;
    }

    /**
     * Загрузка изображений по переданному URL
     *
     * @param  string          $sUrl
     * @param  ModuleUser_EntityUser $oUser
     * @return (string|bool)
     */
    public function UploadTopicImageUrl($sUrl, $oUser) {
        if (isset($_POST['add_watermark']) && $_POST['add_watermark']) {
            return $this->_uploadTopicImageUrlWithOriginal($sUrl, $oUser);
        } else {
            return parent::UploadTopicImageUrl($sUrl, $oUser);
        }
    }

    protected function _uploadTopicImageUrlWithOriginal($sUrl, $oUser) {
        /**
         * Проверяем, является ли файл изображением
         */
        if (!@getimagesize($sUrl)) {
            return ModuleImage::UPLOAD_IMAGE_ERROR_TYPE;
        }
        /**
         * Открываем файловый поток и считываем файл поблочно,
         * контролируя максимальный размер изображения
         */
        $oFile = fopen($sUrl, 'r');
        if (!$oFile) {
            return ModuleImage::UPLOAD_IMAGE_ERROR_READ;
        }

        $iMaxSizeKb = 500;
        $iSizeKb = 0;
        $sContent = '';
        while (!feof($oFile) and $iSizeKb < $iMaxSizeKb) {
            $sContent.=fread($oFile, 1024 * 1);
            $iSizeKb++;
        }

        /**
         * Если конец файла не достигнут,
         * значит файл имеет недопустимый размер
         */
        if (!feof($oFile)) {
            return ModuleImage::UPLOAD_IMAGE_ERROR_SIZE;
        }
        fclose($oFile);

        /**
         * Создаем tmp-файл, для временного хранения изображения
         */
        $sFileTmp = Config::Get('sys.cache.dir') . func_generator();

        $fp = fopen($sFileTmp, 'w');
        fwrite($fp, $sContent);
        fclose($fp);

        $sFileDest = func_generator(6);

        //Сохраняем оригинал
        $uploadPath = Config::Get('path.uploads.images');
        if (Config::Get('plugin.usewatermark.path_image_original')) {
            Config::Set('module.image.topic.watermark_use', false);
            Config::Set('path.uploads.images', Config::Get('plugin.usewatermark.path_image_original'));
            $sDirSave = $this->Image_GetIdDir($oUser->getId());
            $aParams = $this->Image_BuildParams('topic');
            if (!($this->Image_Resize($sFileTmp, $sDirSave, $sFileDest, Config::Get('view.img_max_width'), Config::Get('view.img_max_height'), null, null, true, $aParams))) {
                @unlink($sFileTmp);
                return ModuleImage::UPLOAD_IMAGE_ERROR;
            }
        }


        //Сохраняем с ватермарком
        Config::Set('path.uploads.images', $uploadPath);
        Config::Set('module.image.topic.watermark_use', true);
        $sDirSave = $this->Image_GetIdDir($oUser->getId());
        $aParams = $this->Image_BuildParams('topic');

        /**
         * Передаем изображение на обработку
         */
        if ($sFileImg = $this->Image_Resize($sFileTmp, $sDirSave, $sFileDest, Config::Get('view.img_max_width'), Config::Get('view.img_max_height'), Config::Get('view.img_resize_width'), null, true, $aParams)) {
            @unlink($sFileTmp);
            return $this->Image_GetWebPath($sFileImg);
        }

        @unlink($sFileTmp);
        return ModuleImage::UPLOAD_IMAGE_ERROR;
    }

}

?>