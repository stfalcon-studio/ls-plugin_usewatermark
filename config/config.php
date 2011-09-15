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

//Config::Set('module.image.default.watermark_use', true);
//Config::Set('module.image.default.watermark_type', 'text');
//Config::Set('module.image.default.watermark_position', '-0,-0');
//Config::Set('module.image.default.watermark_font', 'verdana');
//Config::Set('module.image.default.watermark_font_color', '255,255,255');
//Config::Set('module.image.default.watermark_font_size', '12');
//Config::Set('module.image.default.watermark_font_alfa', '0');
//Config::Set('module.image.default.watermark_back_color', '0,0,0');
//Config::Set('module.image.default.watermark_back_alfa', '50');
//Config::Set('module.image.default.watermark_min_width', 200);
//Config::Set('module.image.default.watermark_min_height', 130);
//Config::Set('module.image.default.path.fonts', '___path.root.server___/engine/lib/external/LiveImage/fonts/');
//Config::Set('module.image.default.jpg_quality', 100); // Число от 0 до 100

Config::Set('module.image.foto.watermark_use', false);
Config::Set('module.image.topic.watermark_use', true);

$config = array(
    'backup_original' => 1, //делаеть ли бекап оригинала картинки 0/1
    'path_image_original' => '___path.uploads.root___/images_original'  //путь к оригиналу
);

return $config;