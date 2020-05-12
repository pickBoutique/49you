<?php
//参数说明:
//$max_file_size : 上传文件大小限制, 单位BYTE
//$destination_folder : 上传文件路径
//$watermark   : 是否附加水印(1为加水印,其他为不加水印);
//
//使用说明:
//1. 将PHP.INI文件里面的"extension=php_gd2.dll"一行前面的;号去掉,因为我们要用到GD库;
//2. 将extension_dir =改为你的php_gd2.dll所在目录;
//
//使用例子
//$up = new uploadThumb;
//$up->fileName = $_FILES["upfile"];根据自己的表单来定
//$up->imgpreview=1;是否生成缩略图
//$up->sw=100;缩略图宽度
//$up->sh=150;缩略图高度
//$up->up();
//echo $up->bImg."<br/>"; 返回图片路径
//echo $up->sImg;返回图片路径

class uploadThumb
{
	//上传文件类型列表
	private $uptypes = array (
						'image/jpg',
						'image/jpeg',
						'image/png',
						'image/pjpeg',
						'image/gif',
						//'image/bmp',
						'image/x-png'
						);
	private $max_file_size = 2000000; //上传文件大小限制, 单位BYTE
	
	public $watermark = 0; //是否附加水印(1为加水印,其他为不加水印);
	public $waterstring; //水印字符串
	public $waterimg; //水印图片支持（gif,jpg,png,wbmp）
	public $watertype = 2; //水印类型(1为文字,2为图片)
	public $water_pos;//水印位置，有11种状态，0为随机位置，1为顶端居左，2为顶端居中，3为顶端居右，4为中部居左，5为中部居中，6为中部居右，7为底端居左，8为底端居中，9为底端居右，10为自定义位置（需指定$posx_x,$pos_y）； 
	public $pos_x;//水印x轴坐标
	public $pos_y;//水印y轴坐标
	public $destination_folder = "/uploadfiles"; //上传文件路径
	public $fileName; //文件名称
	public $imgpreview = 0; //是否生成缩略图(1为生成,其他为不生成);
	public $sw; //缩略图宽度
	public $sh; //缩略图高度
	public $bImg; //大图的路径
	public $sImg; //小图的路径
	public $info;
	public $bImgName;
	function up()
	{
		if (!is_uploaded_file($this->fileName[tmp_name]))
		//是否存在文件
		{
			$this->info = "图片不存在!";
			return;
		}
		if ($this->max_file_size < $this->fileName["size"])
		//检查文件大小
		{
			$this->info = "图片太大了超过了$this->max_file_size的限制";
			return;
		}
		//检查文件类型
		if (!in_array($this->fileName["type"], $this->uptypes))
		{
			$this->info = "文件类型错误(只能上传jpg,png,gif格式的图片)";
			return;
		}
		//创建文件目录
		if (!file_exists($this->destination_folder))
		{
			@mkdir($this->destination_folder);
		}
		//上传文件
		$tempName = $this->fileName["tmp_name"];
		$fType = pathinfo($this->fileName["name"]);
		$fType = $fType["extension"];
		if(substr($this->destination_folder,-1) != "/")
		{
			$this->destination_folder .="/";
		}
		$newName = date('Ymdhis');
		for ($i = 0; $i < 6; $i++)
		{
			$newName .= chr(mt_rand(97, 122));
		}
		$this->bImgName = $newName.".".$fType;
		$newName = $this->destination_folder.$newName;
		$sImgName = $newName."_thumb";
		$newName .= "." . $fType;
		$sImgName .= "." . $fType;
		if (!@move_uploaded_file($tempName, $newName)) {
			$this->info = "移动文件出错";
			return;
		}
		else
		{
			//是否生成缩略图
			if($this->imgpreview == 1)
			{
				$data = @getimagesize($newName);////取得GIF、JPEG、PNG或SWF图形的大小
				switch ($data[2])
				{
					case 1 :
						$sImg = @imagecreatefromgif($newName);
						break;
					case 2 :
						$sImg = @imagecreatefromjpeg($newName);
						break;
					case 3 :
						$sImg = @imagecreatefrompng($newName);
						break;
					case 6 :
						$sImg = @imagecreatefromwbmp($newName);
						break;
					default :
						$this->info = "不支持的文件类型";
						return;
				}
	
				//计算缩放比例 
				@$w_ratio = $this->sw/$data[0]; ////$this->sw缩略图的宽度,$data[0]原图的宽度。
				@$h_ratio = $this->sh/$data[1]; ////$this->sh缩略图的高度,$data[1]原图的高度。
				if( ($data[0] <= $this->sw) && ($data[1]<= $this->sh) ) 
				{ 
					$newwidth=$data[0];
					$newhight=$data[1];
				} 
				else if(($w_ratio * $data[1]) < $this->sh) 
				{ 
					$newhight = ceil($w_ratio * $data[1]); 
					$newwidth=$data[0];
				}
				else 
				{ 
					$newwidth = ceil($h_ratio * $data[0]); 
					$newhight=$data[1];
				}
				//生成缩略图的大小   
				if($data[0] > $maxwidth || $data[1] > $maxheight)
				{
					if(intval($this->sw) < 0)
					{
						$ratio = $this->sh/$data[1];
					}
					elseif(intval($this->sh) < 0)
					{
						$ratio = $this->sw/$data[0];
					}
					else
					{
						if($this->sw*$data[1]>$this->sh*$data[0])
						{
							$ratio = $this->sh/$data[1];
						}
						else
						{
							$ratio = $this->sw/$data[0];
						}
					}
					$newwidth = $data[0] * $ratio;
					$newheight = $data[1] * $ratio;
					$sImgDate = @imagecreatetruecolor($newwidth, $newheight);    
					//@imagecopyresampled($sImgDate,$sImg, 0, 0, 0, 0, $newwidth, $newheight, $data[0],$data[1]);
					@imagecopyresampled($sImgDate,$sImg, 0, 0, 0, 0, $newwidth, $newheight, $data[0],$data[1]);
				}
				else
				{
					//决定处理后的图片宽和高 
					$sImgDate = @imagecreatetruecolor($this->sw,$this->sh);//返回一个图像标识符，代表了一幅大小为 x_size 和 y_size 的黑色图像.
					//@imagecopyresampled($sImgDate,$sImg,0,0,0,0,$this->sw,$this->sh,$data[0],$data[1]);
					@imagecopyresampled($sImgDate,$sImg, 0, 0, 0, 0, $newwidth, $newheight, $data[0],$data[1]);
				}
	
				switch ($data[2])
				{
					case 1 :
						@imagejpeg($sImgDate, $sImgName,90);
						break;
					case 2 :
						@imagejpeg($sImgDate, $sImgName,90);
						break;
					case 3 :
						@imagepng($sImgDate, $sImgName,90);
						break;
					case 6 :
						@imagewbmp($sImgDate, $sImgName);
						break;
				}
				@imagedestroy($sImgDate);
				@imagedestroy($sImg);
				$this->sImg=$sImgName;
			}
	
			//是否增加水印
			if ($this->watermark == 1)
			{
				$iinfo = @getimagesize($newName);
				$nimage = @imagecreatetruecolor($iinfo[0], $iinfo[1]);
				$white = @imagecolorallocate($nimage, 255, 255, 255);
				$black = @imagecolorallocate($nimage, 0, 0, 0);
				$red = @imagecolorallocate($nimage, 255, 0, 0);
				@imagefill($nimage, 0, 0, $white);
				switch ($iinfo[2])
				{
					case 1 :
						$simage = @imagecreatefromgif($newName);
						break;
					case 2 :
						$simage = @imagecreatefromjpeg($newName);
						break;
					case 3 :
						$simage = @imagecreatefrompng($newName);
						break;
					case 6 :
						$simage = @imagecreatefromwbmp($newName);
						break;
						default :
						$this->info = "不支持的文件类型";
						return;
				}
	
				@imagecopy($nimage, $simage, 0, 0, 0, 0, $iinfo[0], $iinfo[1]);
				switch ($this->watertype)
				{
					case 1 : //加水印字符串
						//@imagefilledrectangle($nimage, 1, $iinfo[1] - 15, 80, $iinfo[1], $white);
						$waterImg[0] = strlen($this->waterstring)*9;
						$waterImg[1] = 15;
						$this->setPos($this->water_pos,$iinfo,$waterImg);
						@imagestring($nimage, 2, 3, $iinfo[1] - 15, $this->waterstring, $black);
						break;
					case 2 : //加水印图片
						$waterImg = @getimagesize($this->waterimg);
						switch ($waterImg[2])
						{
							case 1 :
								$simage1 = @imagecreatefromgif($this->waterimg);
								break;
							case 2 :
								$simage1 = @imagecreatefromjpeg($this->waterimg);
								break;
							case 3 :
								$simage1 = @imagecreatefrompng($this->waterimg);
								break;
							case 6 :
								$simage1 = @imagecreatefromwbmp($this->waterimg);
								break;
						}						
						$this->setPos($this->water_pos,$iinfo,$waterImg);
						@imagecopy($nimage, $simage1, 0, 0, 0, 0, $waterImg[0], $waterImg[1]);
						@imagedestroy($simage1);
						break;
				}
	
				switch ($iinfo[2])
				{
					case 1 :
						@imagejpeg($nimage, $newName,90);
						break;
					case 2 :
						@imagejpeg($nimage, $newName,90);
						break;
					case 3 :
						@imagepng($nimage, $newName,90);
						break;
					case 6 :
						@imagewbmp($nimage, $newName);
						break;
				}
				//覆盖原上传文件
				@imagedestroy($nimage);
				@imagedestroy($simage);
			}
			$this->bImg=$newName;
			$this->info = "上传成功";
			@chmod($this->bImg,0777);
			@chmod($this->sImg,0777);
		}
	}
	function setPos($water_pos,$source_img,$water_img=array(0,0))
	{
		//加水印图片的位置
		switch($water_pos)
		{
			case 0:
				$this->pos_x = rand(0,$source_img[0]-$water_img[0]);
				$this->pos_y = rand(0,$source_img[1]-$water_img[1]);
				break;
			case 1:
				$this->pos_x = 0;
				$this->pos_y = 0;
				break;
			case 2:
				$this->pos_x = $source_img[0]/2-$water_img[0]/2;
				$this->pos_y = 0;
				break;
			case 3:
				$this->pos_x = $source_img[0]-$water_img[0];
				$this->pos_y = 0;
				break;
			case 4:
				$this->pos_x = 0;
				$this->pos_y = $source_img[1]/2-$water_img[1];
				break;
			case 5:
				$this->pos_x = $source_img[0]/2-$water_img[0]/2;
				$this->pos_y = $source_img[1]/2-$water_img[1]/2;
				break;
			case 6:
				$this->pos_x = $source_img[0];
				$this->pos_y = $source_img[1]/2-$water_img[1]/2;
				break;
			case 7:
				$this->pos_x = 0;
				$this->pos_y = $source_img[1]-$water_img[1];
				break;
			case 8:
				$this->pos_x = $source_img[0]/2-$water_img[0]/2;
				$this->pos_y = $source_img[1]-$water_img[1];
				break;
			case 9:
				$this->pos_x = $source_img[0]-$water_img[0];
				$this->pos_y = $source_img[1]-$water_img[1];
				break;
		}
		echo $this->pos_x."_".$this->pos_y;
	}
}
?>