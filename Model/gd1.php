<?php
/**
 * Created by PhpStorm.
 * User: kison
 * Date: 2015/12/23
 * Time: 9:55
 */
function h24($str){

    $hour = explode(",",$str);
    $hmax = max($hour);
    $ppix = 150/$hmax;

    //计算柱状图高度
    $h0 = 190-$hour[0]*$ppix;
    $h1 = 190-$hour[1]*$ppix;
    $h2 = 190-$hour[2]*$ppix;
    $h3 = 190-$hour[3]*$ppix;
    $h4 = 190-$hour[4]*$ppix;
    $h5 = 190-$hour[5]*$ppix;
    $h6 = 190-$hour[6]*$ppix;
    $h7 = 190-$hour[7]*$ppix;
    $h8 = 190-$hour[8]*$ppix;
    $h9 = 190-$hour[9]*$ppix;
    $h10 = 190-$hour[10]*$ppix;
    $h11 = 190-$hour[11]*$ppix;
    $h12 = 190-$hour[12]*$ppix;
    $h13 = 190-$hour[13]*$ppix;
    $h14 = 190-$hour[14]*$ppix;
    $h15 = 190-$hour[15]*$ppix;
    $h16 = 190-$hour[16]*$ppix;
    $h17 = 190-$hour[17]*$ppix;
    $h18 = 190-$hour[18]*$ppix;
    $h19 = 190-$hour[19]*$ppix;
    $h20 = 190-$hour[20]*$ppix;
    $h21 = 190-$hour[21]*$ppix;
    $h22 = 190-$hour[22]*$ppix;
    $h23 = 190-$hour[23]*$ppix;

    //创建一个img
    $img = imagecreate(755,210);
    //背景
    $bgc = imagecolorallocate ($img, 245, 250, 254);
    //黑色
    $bc = imagecolorallocate($img,0,0,0);
    //画竖轴
    imageline($img,15,30,15,189, $bc);
    //画横轴
    imageline($img,15,190,750,190, $bc);

    //画竖轴点
    for($i=39,$j=10;$i<189;$i=$i+15,$j--){
        imageline($img,13,$i,15,$i, $bc);
        imagestring($img,1,1,$i-4,$j."x", $bc);
    }

    //画横轴点
    $t = true;
    for($i=31,$j=29;$i<750;$i=$j+1,$j=$j+15){
        if($t){
            $x=$i;
            $t=false;
        }else{
            $x=$i+1;
            $t=true;
        }
        imageline($img,$x,190,$x,192, $bc);
    }
    //竖轴标记
    $x = ceil($hmax/10);
    imagestring($img,2,10,15,"X=".$x,$bc);
    //竖轴标记

    //0点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,31,$h0,45,189,$color);
    imagestring($img,1,31,$h0-10,$hour[0],$color);
    imagechar($img,1,36,195,0,$bc);

    //1点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,61,$h1,75,189,$color);
    imagestring($img,1,61,$h1-10,$hour[1],$color);
    imagechar($img,1,66,195,1,$bc);

    //2点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,91,$h2,105,189,$color);
    imagestring($img,1,91,$h2-10,$hour[2],$color);
    imagechar($img,1,96,195,2,$bc);

    //3点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,121,$h3,135,189,$color);
    imagestring($img,1,121,$h3-10,$hour[3],$color);
    imagechar($img,1,126,195,3,$bc);

    //4点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,151,$h4,165,189,$color);
    imagestring($img,1,151,$h4-10,$hour[4],$color);
    imagechar($img,1,156,195,4,$bc);

    //5点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,181,$h5,195,189,$color);
    imagestring($img,1,181,$h5-10,$hour[5],$color);
    imagechar($img,1,186,195,5,$bc);

    //6点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,211,$h6,225,189,$color);
    imagestring($img,1,211,$h6-10,$hour[6],$color);
    imagechar($img,1,216,195,6,$bc);

    //7点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,241,$h7,255,189,$color);
    imagestring($img,1,241,$h7-10,$hour[7],$color);
    imagechar($img,1,246,195,7,$bc);

    //8点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,271,$h8,285,189,$color);
    imagestring($img,1,271,$h8-10,$hour[8],$color);
    imagechar($img,1,276,195,8,$bc);

    //9点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,301,$h9,315,189,$color);
    imagestring($img,1,301,$h9-10,$hour[9],$color);
    imagechar($img,1,306,195,9,$bc);

    //10点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,331,$h10,345,189,$color);
    imagestring($img,1,331,$h10-10,$hour[10],$color);
    imagestring($img,1,334,195,10,$bc);

    //11点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,361,$h11,375,189,$color);
    imagestring($img,1,361,$h11-10,$hour[11],$color);
    imagestring($img,1,364,195,11,$bc);

    //12点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,391,$h12,405,189,$color);
    imagestring($img,1,391,$h12-10,$hour[12],$color);
    imagestring($img,1,394,195,12,$bc);

    //13点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,421,$h13,435,189,$color);
    imagestring($img,1,421,$h13-10,$hour[13],$color);
    imagestring($img,1,424,195,13,$bc);

    //14点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,451,$h14,465,189,$color);
    imagestring($img,1,451,$h14-10,$hour[14],$color);
    imagestring($img,1,454,195,14,$bc);

    //15点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,481,$h15,495,189,$color);
    imagestring($img,1,481,$h15-10,$hour[15],$color);
    imagestring($img,1,481,195,15,$bc);

    //16点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,511,$h16,525,189,$color);
    imagestring($img,1,511,$h16-10,$hour[16],$color);
    imagestring($img,1,511,195,16,$bc);

    //17点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,541,$h17,555,189,$color);
    imagestring($img,1,541,$h17-10,$hour[17],$color);
    imagestring($img,1,544,195,17,$bc);

    //18点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,571,$h18,585,189,$color);
    imagestring($img,1,571,$h18-10,$hour[18],$color);
    imagestring($img,1,571,195,18,$bc);

    //19点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,601,$h19,615,189,$color);
    imagestring($img,1,601,$h19-10,$hour[19],$color);
    imagestring($img,1,604,195,19,$bc);

    //20点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,631,$h20,645,189,$color);
    imagestring($img,1,631,$h20-10,$hour[20],$color);
    imagestring($img,1,634,195,20,$bc);

    //21点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,661,$h21,675,189,$color);
    imagestring($img,1,661,$h21-10,$hour[21],$color);
    imagestring($img,1,664,195,21,$bc);

    //22点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,691,$h22,705,189,$color);
    imagestring($img,1,691,$h22-10,$hour[22],$color);
    imagestring($img,1,694,195,22,$bc);

    //23点
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagefilledrectangle($img,721,$h23,735,189,$color);
    imagestring($img,1,721,$h23-10,$hour[23],$color);
    imagestring($img,1,724,195,23,$bc);

    //加个边框 加了之后不好看
    //imagerectangle($img, 0, 0, 754, 209, $bc);

    imagepng($img);
    imagedestroy($img);
}
$str = isset($_GET['str'])?$_GET['str']:"";
if($str){
    h24($str);
}
?>