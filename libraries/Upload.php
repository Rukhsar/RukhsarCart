<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/** 
 * Upload class
 */ 
class Upload {

	/**
	 * Config
	 *
	 * @access private 
	 */	
	private static $config;

	/**
	 * Error
	 *
	 * @access private 
	 */		
	private $error;
		
	/**
	 * Constructor
	 * 
	 * @access public 
	 */	  
	public function __construct() {
		
		self::$config = config_load('upload');

		$this->error = new Error();
		
	}

	/**
	 * Upload image
	 * 
	 * @access public 
	 */	 
	public function upload_image($file, $width, $height, $crop = false) {
		
		if (is_array($file)) {
			
			$ext = substr(strrchr($file['name'], '.'), 1);
			
			if ($file['size'] > self::$config['max_filesize'])
				$this->error->set_error('The file you attempted to upload is too large.');
				
		} else {
			
			$ext = substr(strrchr($_FILES[$file]['name'], '.'), 1);
			
			if ($_FILES[$file]['size'] > self::$config['max_filesize'])
				$this->error->set_error('The file you attempted to upload is too large.');
				
		}
		
		if (!in_array($ext, array('jpg', 'jpeg', 'png', 'gif')))
			$this->error->set_error('The file you attempted to upload is not allowed.');

		if (!is_writable(self::$config['upload_path'] . 'images/'))
			$this->error->set_error('The folder ' . self::$config['upload_path'] . ' images/ is not writeable.');

		if (!$this->error->has_errors()) {
				
			$file_name =  time() . md5(uniqid(mt_rand())) . '.' . $ext;
			
			if (is_array($file))
				copy($file['tmp_name'], self::$config['upload_path'] . 'images/' . $file_name);
			else
				copy($_FILES[$file]['tmp_name'], self::$config['upload_path'] . 'images/' . $file_name);	
			
			if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif'))) {
				
				if ($ext == 'jpg' || $ext == 'jpeg' )
					$image = imagecreatefromjpeg(self::$config['upload_path'] . 'images/' . $file_name);
				elseif($ext == 'png')
					$image = imagecreatefrompng(self::$config['upload_path'] . 'images/' . $file_name);
				elseif($ext == 'gif')
					$image = imagecreatefromgif(self::$config['upload_path'] . 'images/' . $file_name);
				
				$old_width = imagesx($image);	
				$old_height = imagesy($image);
				
				if ($crop) {

					$scale = max($width / $old_width, $height / $old_height);
					$new_width = ceil($scale * $old_width);
					$new_height = ceil($scale * $old_height);
										
					$tmp_img = imagecreatetruecolor($new_width, $new_height);

					if ($ext == 'png' || $ext == 'gif') {
						
						imagealphablending($tmp_img, false);
						imagesavealpha($tmp_img, true);
						$transparent = imagecolorallocatealpha($tmp_img, 255, 255, 255, 127);
						imagefilledrectangle($tmp_img, 0, 0, $new_width, $new_height, $transparent);
						
					}
					
					imagecopyresampled($tmp_img, $image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
					
					if ($new_width == $width) {
						
						$src_x = 0;
						$src_y = ($new_height / 2) - ($height / 2);

					} else if ($new_height == $height) {
						
						$src_x = ($new_width / 2) - ($width / 2);
						$src_y = 0;
						
					}
					
					$new_image = imagecreatetruecolor($width, $height);

					if ($ext == 'png' || $ext == 'gif') {
						
						imagealphablending($new_image, false);
						imagesavealpha($new_image, true);
						$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
						imagefilledrectangle($new_image, 0, 0, $width, $height, $transparent);
						
					}
					
					imagecopyresampled($new_image, $tmp_img, 0, 0, $src_x, $src_y, $width, $height, $width, $height);
					imagedestroy($tmp_img);
					
				} else {

					$scale = min($width / $old_width, $height / $old_height);
					$new_width = ceil($scale * $old_width);
					$new_height = ceil($scale * $old_height);
					
					$new_image = imagecreatetruecolor($new_width, $new_height);

					if ($ext == 'png' || $ext == 'gif') {
						
						imagealphablending($new_image, false);
						imagesavealpha($new_image, true);
						$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
						imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);
						
					}
					
					imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
										
				}

				if ($ext == 'jpg' || $ext == 'jpeg' )
					imagejpeg($new_image, self::$config['upload_path'] . 'images/' . $file_name, 75);
				elseif($ext == 'png')
					 imagepng($new_image, self::$config['upload_path'] . 'images/' . $file_name);
				elseif($ext == 'gif')
					imagegif($new_image, self::$config['upload_path'] . 'images/' . $file_name);

				imagedestroy($image);
				imagedestroy($new_image);
				
			}
				
			return $file_name;	
			
		}
	}

	/**
	 * Upload file
	 * 
	 * @access public 
	 */	 
	public function upload_file($field_name) {
		
		$ext = pathinfo($_FILES[$field_name]['name']);
		
		if (!in_array($ext['extension'], self::$config['allowed_filetypes']))
			$this->error->set_error('The file you attempted to upload is not allowed.');

		if ($_FILES[$field_name]['size'] > self::$config['max_filesize'])
			$this->error->set_error('The file you attempted to upload is too large.');
		 
		if (!is_writable(self::$config['upload_path'] . 'files/'))
			$this->error->set_error('The folder ' . self::$config['upload_path'] . ' files/ is not writeable.');
						
		if (!$this->error->has_errors()) {
			
			$file_name =  time() . '.' . $ext['extension'];
			
			copy($_FILES[$field_name]['tmp_name'], self::$config['upload_path'] . 'files/' . $file_name);
				
			return $file_name;	
			
		}		
	}
		
}

?>
