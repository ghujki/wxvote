<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/5/6
 * Time: 13:52
 */
class Image_driver_ModuleController_module extends CI_Module
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index () {

    }

    public function exam() {
        $data['title'] = "高考准考证生成器";
        $name = $this->input->get('name');
        $sex = $this->input->get('sex');
        $school = $this->input->get('school');

        if ($name) {
            $data['img'] = "/index.php/module/image_driver/ModuleController/licence?name=$name&sex=$sex&school=$school";
        }
        $this->render("exam_index",$data);
    }

    public function exam2 () {
        $this->load->helper(array('form', 'url'));
        $data['title'] = "高考准考证生成器";
        $name = $this->input->post('name');

        if ($name) {
            $sex = $this->input->post('sex');
            $school = $this->input->post('school');
            $subject = $this->input->post("subject");

//            $file = $this->upload("file");
//            if ($file['error']) {
//                die(json_encode($file));
//            }
            $file = "./upload/image_driver/".time().mt_rand().".jpg";
            file_put_contents($file,base64_decode($this->input->post('file')));
            $conf = array(
                array("text"=>$name,"font-size"=>16,"fong-angle"=>0,"x"=>270,"y"=>350),
                array("text"=>$sex,"font-size"=>16,"fong-angle"=>0,"x"=>270,"y"=>432),
                array("text"=>$school,"font-size"=>16,"fong-angle"=>0,"x"=>270,"y"=>557),
                array("text"=>$subject,"font-size"=>16,"fong-angle"=>0,"x"=>270,"y"=>390)
            );
            $basefile = APPPATH."modules/image_driver/views/images/exam_base2.jpg";
            $output = $this->create_image($basefile,"./upload/image_driver_1/",APPPATH."../".str_replace("./","",$file),$conf);
            $data['img'] = str_replace("./","/",$output);
        }
            $data['jspath'] = array("js/iscroll-zoom.js","js/hammer.js","js/lrz.all.bundle.js","js/jquery.photoClip.min.js","js/exam2.js");

        $this->render("exam_index2",$data);
    }

    public function create_image($base_file,$save_path,$headimg,$conf) {

        $screenWidth = 640;

        $arr = getimagesize($base_file);

        $im = imagecreatetruecolor($arr[0], $arr[1]);

        imagealphablending($im, true);

        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);

        //设置透明底色
        $transparent = imagecolortransparent($im,$white);
        imagefill($im,100,100,$transparent);

        $font = APPPATH.'modules/image_driver/views/font/simsun.ttf';

        //imageloadfont($font);

        foreach ($conf as $item ) {
            imagettftext($im, $item['font-size'], $item['font-angle'], $item['x'], $item['y'], $black, $font, $item['text']);
        }

        imagesavealpha($im,true);

        $im2 = imagecreatefromjpeg($base_file);
        $im3_src = imagecreatefromjpeg($headimg);
        $size_src=getimagesize($headimg);
        $w=$size_src['0'];
        $h=$size_src['1'];

         //指定缩放出来的最大的宽度（也有可能是高度）
        $max=150;
        //根据最大值为100，算出另一个边的长度，得到缩放后的图片宽度和高度
       if($w > $h){
            $w=$max;
            $h=$h*($max/$size_src['0']);
         }else{
           $h=$max;
           $w=$w*($max/$size_src['1']);
        }
        $im3 = imagecreatetruecolor($w, $h);
        imagecopyresampled($im3, $im3_src, 0, 0, 0, 0, $w, $h, $size_src['0'], $size_src['1']);

        imagecopymerge($im2, $im3, 450, 330, 0, 0, 150, 150, 100);

        // Using imagepng() results in clearer text compared with imagejpeg()
        imagecopymerge($im2, $im, 5, 5, 0, 0, imagesx($im), imagesy($im), 60);


        $size = $this->calcWidth($arr[0],$arr[1],$screenWidth);
        //modify the picture size to output
        $image = imagecreatetruecolor($size[0], $size[1]);
        imagecopyresampled($image, $im2, 0, 0, 0, 0,$size[0], $size[1],$arr[0], $arr[1]);
        imagesavealpha($image,true);

        $file_name = $save_path."/".time().mt_rand().".png";
        imagepng($image,$file_name);
        imagedestroy($image);
        return $file_name;
    }


    public function upload($filename) {
        $config['upload_path']      = "./upload/image_driver/";
        $config['allowed_types']    = 'gif|jpg|png|jpeg|bmp';
        $config['max_size']     = 20000;
        $config['file_name'] = time();

        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path']);
        }

        $this->load->library('upload', $config);

        $token_value = $this ->security->get_csrf_hash();

        if ( ! $this->upload->do_upload($filename))
        {

            return array("error"=>$this->upload->display_errors("",""),"hash"=>$token_value);
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());

            return array("file"=> $data['upload_data']['file_name'],"hash"=>$token_value);
        }
    }

    public function licence() {
        $name = $this->input->get('name');
        $sex = $this->input->get('sex');
        $school = $this->input->get('school');
        $room = mt_rand(1,45);
        $seat  = mt_rand(1,30);
        $screenWidth = 640;

        if ($seat < 10) {
            $seat  = "0".$seat;
        }
        if ($room < 10) {
            $room  = "0".$room;
        }

        header("content-type:image/png");
        $im = imagecreatetruecolor(800, 1000);

        imagealphablending($im, true);

        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);

        //设置透明底色
        $transparent = imagecolortransparent($im,$white);
        imagefill($im,100,100,$transparent);

        $font = APPPATH.'modules/image_driver/views/font/simsun.ttf';
        
        //imageloadfont($font);
        
        imagettftext($im, 23, 0, 400, 412, $black, $font, $name);
        imagettftext($im, 23, 0, 630, 412, $black, $font, $sex);
        imagettftext($im, 23, 0, 400, 482, $black, $font, $school);

        imagettftext($im, 23, 0, 220, 790, $black, $font, $room);
        imagettftext($im, 23, 0, 220, 870, $black, $font, $seat);
        imagettftext($im, 24, 0, 220, 790, $black, $font, $room);
        imagettftext($im, 24, 0, 220, 870, $black, $font, $seat);

        imagesavealpha($im,true);

        $im2 = imagecreatefromjpeg(APPPATH."modules/image_driver/views/images/exam_base.jpg");
        $arr = getimagesize(APPPATH."modules/image_driver/views/images/exam_base.jpg");

        // Using imagepng() results in clearer text compared with imagejpeg()
        imagecopymerge($im2, $im, 5, 5, 0, 0, imagesx($im), imagesy($im), 100);

        $size = $this->calcWidth($arr[0],$arr[1],$screenWidth);
        //modify the picture size to output
        $image = imagecreatetruecolor($size[0], $size[1]);
        imagecopyresampled($image, $im2, 0, 0, 0, 0,$size[0], $size[1],$arr[0], $arr[1]);
        imagesavealpha($image,true);

        imagepng($image);
        imagedestroy($image);
    }



    function calcWidth($srcWidth,$srcHeight,$screenWidth) {
        $size = array();
        if ($srcWidth <= $screenWidth || $screenWidth == 0) {
            $size[0] = $srcWidth;
            $size[1] = $srcHeight;
        } else {
            $size[0] = $screenWidth;
            $size[1] = $srcHeight * ($screenWidth / $srcWidth);
        }
        return $size;
    }

    public function render($file,$data = array()) {
        $content = $this->load->view($file,$data,TRUE);
        $data['content'] = $content;
        $this->load->view("layout",$data);
    }
}