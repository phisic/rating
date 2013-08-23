<?php
 ini_set('display_errors', 1);
// change the following paths if necessary
    $yii = 'Yii/yii.php';
    $config = dirname(__FILE__) . '/protected/config/main.php';

// remove the following lines when in production mode
    defined('YII_DEBUG') or define('YII_DEBUG', true);
// specify how many levels of call stack should be shown in each log message
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

    require_once($yii);

    require_once('protected/components/WebApplication.php');
    Yii::createApplication('WebApplication', $config);
    
$width = array(200,300,500);
if (isset($_GET['id']) && isset($_GET['width']) && isset($_GET['height'])) {
    if(!in_array($_GET['width'], $width))
            exit;
    $c = new CDbCriteria();
    $c->addColumnCondition(array('Id' => $_GET['id']));
    if ($r = Yii::app()->db->getCommandBuilder()->createFindCommand('item', $c)->queryRow()) {
        $path = 'rt/p' . $r['Id'] . '-' . $_GET['width'] . 'x' . $_GET['height'] . '.jpg';
        imageResize($r['Image'], $_GET['width'], $path);
        header('Content-Type: image/jpeg');
        readfile($path);
    }
}

if (isset($_GET['rid']) && isset($_GET['width']) && isset($_GET['height'])) {
    if(!in_array($_GET['width'], $width))
            exit;
    $rid = (int)$_GET['rid'];
    $items = Yii::app()->helper->getItems(array($rid));
    
    if (isset($items[$rid][0]['Image'])) {
        $path = 'rt/r' . $rid . '-' . $_GET['width'] . 'x' . $_GET['height'] . '.jpg';
        $img = $items[$rid][0]['Image'];
        imageResize($img, $_GET['width'], $path);
        header('Content-Type: image/jpeg');
        readfile($path);
    }
}

function imageResize($image, $thumb_width, $new_filename) {
    $max_width = $thumb_width;
    //Check if GD extension is loaded
    if (!extension_loaded('gd') && !extension_loaded('gd2')) {
        trigger_error("GD is not loaded", E_USER_WARNING);
        return false;
    }
    //Get Image size info
    list($width_orig, $height_orig, $image_type) = getimagesize($image);
    switch ($image_type) {
        case 1:
            $im = imagecreatefromgif($image);
            break;
        case 2:
            $im = imagecreatefromjpeg($image);
            break;
        case 3:
            $im = imagecreatefrompng($image);
            break;
        default:
            trigger_error('Unsupported filetype!', E_USER_WARNING);
            break;
    }
    //calculate the aspect ratio
    $aspect_ratio = (float) $height_orig / $width_orig;
    //calulate the thumbnail width based on the height
    $thumb_height = round($thumb_width * $aspect_ratio);
    while ($thumb_height > $max_width) {
        $thumb_width -= 10;
        $thumb_height = round($thumb_width * $aspect_ratio);
    }
    $new_image = imagecreatetruecolor($thumb_width, $thumb_height);

    imagecopyresampled($new_image, $im, 0, 0, 0, 0, $thumb_width, $thumb_height, $width_orig, $height_orig);
    //Generate the file, and rename it to $new_filename

    imagejpeg($new_image, $new_filename);

    return $new_filename;
}