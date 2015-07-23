<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

$config['upload_path'] = '__DIR_APPLICATION__uploads/';

$config['allowed_filetypes'] = array('png', 'jpg', 'gif', 'pdf', 'zip', 'rar');

$config['max_filesize'] = 1048576;

$config['max_width_thumbnail'] = 100;

$config['max_height_thumbnail'] = 100;

$config['max_width'] = 500;

$config['max_height'] = 500;

$config['crop_thumbnail'] = true;


?>
