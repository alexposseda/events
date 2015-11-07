<?php
class Image{
    protected $_orig_img;
    protected $_save_path;
    protected $_new_img_name;
    public $w;
    public $h;

    public function __construct($uploaded_img_name, $save_path, $name){

        $this->_save_path = $save_path;
        if(!is_dir($this->_save_path)){
            mkdir($this->_save_path);
        }
        $this->_new_img_name = $name.'.jpg';
        $this->_orig_img = imageCreateFromJpeg($uploaded_img_name);
        $this->w = imagesx($this->_orig_img);
        $this->h = imagesy($this->_orig_img);
    }

    public function createImg($big_img_size){
        $new_img = imageCreateTrueColor($big_img_size['width'], $big_img_size['height']);
        //TODO сохранить пропорции
        imageAntiAlias($new_img, true);
        imagecopyresized(
            $new_img,
            $this->_orig_img,
            0, 0, 0, 0,
            $big_img_size['width'], $big_img_size['height'],
            imagesx($this->_orig_img), imagesy($this->_orig_img)
        );
        imageJPEG($new_img, $this->_save_path.$this->_new_img_name, 100);

        imagedestroy($new_img);
        return $this;
    }
    public function createThumbs($thumb_size){
        $thumb = imageCreateTrueColor($thumb_size['width'], $thumb_size['height']);
        imageAntiAlias($thumb, true);
        imagecopyresized(
            $thumb,
            $this->_orig_img,
            0, 0, 0, 0,
            $thumb_size['width'], $thumb_size['height'],
            imagesx($this->_orig_img), imagesy($this->_orig_img)
        );
        imageJPEG($thumb, $this->_save_path.'ava_'.$this->_new_img_name, 100);
        imagedestroy($thumb);
        return $this;
    }
    public function __destroy(){
        imagedestroy($this->_orig_img);
    }
}