<?php

namespace App\Controller;

use App\Controller\AppController;

class TuviController extends AppController
{

    const SELECT = [
        "http://vansu.net/content/{DMY}",
        "https://tuvivannien.com/tinh-viec-dai-su/ngay-{DMY}-cong-viec-{POS}.html",
        "http://vansu.net/xem-boi-ngay-sinh.html",
        "https://lichvannien365.com/boi-tinh-yeu",
        "http://vansu.net/xem-tuoi-sinh-con.html",
        "http://vansu.net/xem-huong-nha.html",
        "http://vansu.net/xem-tuoi-vo-chong.html",
        "https://huyenbi.net/Xem-boi-Ai-cap.html",
        "https://www.blogphongthuy.com/xem-tuoi-xay-nha-chon-nam-xay-nha-theo-nam-sinh-hop-tuoi.html",
        "http://vansu.net/thoi-trang-theo-phong-thuy.html",
        "http://vansu.net/xem-sao-chieu-menh.html",
        "http://vansu.net/sim-phong-thuy.html"
    ];

    public function index($selectId, ...$params)
    {
        $commonStyle = '
            <style>
                    .adsbygoogle {
                        display: none !important;
                    }
            </style>
        ';
        $content = '';
        $url = self::SELECT[$selectId];
        switch ($selectId) {
            case 0:
                $date = $params[0] ?? "";
                if (!$date) {
                    $date = date('dmY');
                }
                $url = preg_replace('/{DMY}/', $date, $url);


                $style = '
                    <style>
                    .zone-branding-wrapper,
                     .block-description-function.month-calendar,
                     #block-system-main-menu, .block-lich,
                     .block-block,
                     .block-views,
                     footer
                     {
                        display: none !important;
                    }
                    </style>
                ';
                $content = $style . file_get_contents($url);
                break;
            case 1:
                $pos = $params[0];
                $date = $params[1];
                $date = date_create_from_format('dmY', $date);
                $date = $date->format('d-m-Y');

                $url = preg_replace('/{DMY}/', $date, $url);
                $url = preg_replace('/{POS}/', $pos, $url);
                $style = '
                    <style>
                    .header-area,
                    .col-12.col-lg-4,
                    .newspaper-post-like,
                    .newspaper-post-like.d-flex.align-items-center.justify-content-between,
                    .comment_area,.footer-area,
                    .addthis-smartlayers,
                    .addthis-smartlayers-desktop
                     {
                        display: none !important;
                    }
                    </style>
                ';
                $content = $style . file_get_contents($url);
                break;

            case 2 :
                $date = $params[0];
                $date = date_create_from_format('dmY', $date);
                $response = $this->_curl($url, true, [
                    'ngay' => $date->format('d'),
                    'thang' => $date->format('m'),
                    'namsinh' => $date->format('Y'),
                    'form_id' => 'tracnghiem_xem_boi_ngay_sinh_form'
                ]);

                $response = str_replace('<center><span>Ngày sinh của bạn: </span>//</center>', '<center><span>Ngày sinh của bạn: </span>' . $date->format('d') . '/' . $date->format('m') . '/' . $date->format('Y') . '</center>', $response);

                $style = '
                    <style>
                    .zone-branding-wrapper,
                     .block-description-function.month-calendar,
                     #block-system-main-menu, .block-lich,
                     .block-block,
                     .block-views,
                     footer,
                     .trac-nghiem-thu-vien,
                     .view-thu-vien,
                     .body-trac-nghiem-node .pane-title,
                     #section-header
                     {
                        display: none !important;
                    }
                    </style>
                ';
                $content = $style . $response;
                break;
            case 3 :
                $url = 'https://lichvannien365.com/boi-tinh-yeu-cho-{DMY1}-va-{DMY2}';

                $date1 = $params[0];
                $date1 = date_create_from_format('dmY', $date1);
                $date1 = $date1->format('d-m-Y');
                $url = preg_replace('/{DMY1}/', $date1, $url);

                $date2 = $params[1];
                $date2 = date_create_from_format('dmY', $date2);
                $date2 = $date2->format('d-m-Y');

                $url = preg_replace('/{DMY2}/', $date2, $url);
                $style = '
                    <style>
                    header,nav,.lvn-hnavbar-bottom,
                    #boxAlert,.lvn-xemtv-form,.lvn-breadcrumbs,
                    #fb-root,
                    iframe,
                    .alert-box,.lvn-main-title,
                    .lvn-comment-fb,
                    .col-md-9 .row,
                    .lvn-sub-abouttab,
                    .lvn-dnews-some,
                    .lvn-library,
                    footer,
                    .lvn-backtotop  ,
                    .lvn-main-title + .lvn-main-subnews
                     {
                        display: none !important;
                    }
                    
                    </style>
                ';
                $content = $style . file_get_contents($url);

                break;
            case 4 :
                $date1 = $params[0];
                $date2 = $params[1];
                $date3 = $params[2];
//                $date = date_create_from_format('dmY', $date);
                $response = $this->_curl($url, true, [
                    'tuoi_bo' => $date1,
                    'tuoi_me' => $date2,
                    'tuoi_con' => $date3,
                    'form_id' => 'lich_xem_tuoi_sinh_con_form'
                ]);

                $response = str_replace('<font color="red">Xem năm khác</font>', '', $response);

//                $response = str_replace('<center><span>Ngày sinh của bạn: </span>//</center>', '<center><span>Ngày sinh của bạn: </span>'.$date->format('d'). '/'.$date->format('m').'/'.$date->format('Y').'</center>', $response);

                $style = '
                    <style>
                    .zone-branding-wrapper,
                     .block-description-function.month-calendar,
                     #block-system-main-menu, .block-lich,
                     .block-block,
                     .block-views,
                     footer,
                     .trac-nghiem-thu-vien,
                     .view-thu-vien,
                     .body-trac-nghiem-node .pane-title,
                     #section-header,
                     .trac-nghiem-list,
                     .danh-muc-trac-nghiem-panel,
                     .pane-block-105    
                     {
                        display: none;
                    }
                    </style>
                ';
                $content = $style . $response;
                break;
            case 5 :
                $date1 = $params[0];
                $huong = $params[1];
                $gt = $params[2];
                $response = $this->_curl($url, true, [
                    'namsinh' => $date1,
                    'huong' => $huong,
                    'gioitinh' => $gt,
                    'form_id' => 'phongthuy_xem_huong_nha_form'
                ]);

                $response = str_replace('<font color="red">Xem năm khác</font>', '', $response);

//                $response = str_replace('<center><span>Ngày sinh của bạn: </span>//</center>', '<center><span>Ngày sinh của bạn: </span>'.$date->format('d'). '/'.$date->format('m').'/'.$date->format('Y').'</center>', $response);

                $style = '
                    <style>
                    .zone-branding-wrapper,
                     .block-description-function.month-calendar,
                     #block-system-main-menu, .block-lich,
                     .block-block,
                     .block-views,
                     footer,
                     .trac-nghiem-thu-vien,
                     .view-thu-vien,
                     .body-trac-nghiem-node .pane-title,
                     #section-header,
                     .trac-nghiem-list,
                     .danh-muc-trac-nghiem-panel,
                     .pane-block-105,
                     .trac-nghiem-thu-vien
                     {
                        display: none !important;
                    }
                    </style>
                ';
                $content = $style . $response;
                break;
            case 6 :
                $tennam = $params[0];
                $namnam = $params[1];
                $tennu = $params[2];
                $namnu = $params[3];
                $response = $this->_curl($url, true, [
                    'tennam' => $tennam,
                    'namsinh' => $namnam,
                    'tennu' => $tennu,
                    'namsinh_nk' => $namnu,
                    'form_id' => 'xemtuoi_xem_tuoi_vo_chong_form'
                ]);

                $response = str_replace('<font color="red">Xem năm khác</font>', '', $response);

//                $response = str_replace('<center><span>Ngày sinh của bạn: </span>//</center>', '<center><span>Ngày sinh của bạn: </span>'.$date->format('d'). '/'.$date->format('m').'/'.$date->format('Y').'</center>', $response);

                $style = '
                    <style>
                    .zone-branding-wrapper,
                     .block-description-function.month-calendar,
                     #block-system-main-menu, .block-lich,
                     .block-block,
                     .block-views,
                     footer,
                     .trac-nghiem-thu-vien,
                     .view-thu-vien,
                     .body-trac-nghiem-node .pane-title,
                     #section-header,
                     .trac-nghiem-list,
                     .danh-muc-trac-nghiem-panel,
                     .pane-block-105,
                     .trac-nghiem-thu-vien
                     {
                        display: none !important;
                    }
                    </style>
                ';
                $content = $style . $response;
                break;
            case 7 :
                $name = $params[0];

                $response = $this->_curl($url, true, [
                    'hoten' => $name,
                ], true);


                var_dump($response);
                die;


                $style = '
                    <style>
                    
                     .trac-nghiem-thu-vien
                     {
                        display: none !important;
                    }
                    </style>
                ';
                $content = $style . $response;
                break;
            case 8 :
                $url = 'https://www.blogphongthuy.com/dataphongthuy/tuoixaynha.php';
                $nam1 = $params[0];
                $nam2 = $params[1];

                $response = $this->_curl($url, true, [
                    'year_birth' => $nam1,
                    'year_build' => $nam2,
                ]);

                $style = '
                    <style>
                    
                     .trac-nghiem-thu-vien
                     {
                        display: none !important;
                    }
                    </style>
                ';
                $content = $style . $response;
                break;
            case 9 :
                $url = 'http://vansu.net/system/ajax';
                $date = $params[0];
                $date = date_create_from_format('dmY', $date);
                $ngay = $date->format('d');
                $thang = $date->format('m');
                $nam = $date->format('Y');
                $gioitinh = $params[1];


                $response = $this->_curl($url, true, [
                    'ngay' => $ngay,
                    'thang' => $thang,
                    'namsinh' => $nam,
                    'gioitinh' => $gioitinh,
                    'form_build_id' => 'form-qssNbLOcOKwUokLgltZhYJlZqBbJypbgXkHRMioL5mo',
                    'form_id' => 'xemtuoi_thoi_trang_phong_thuy_form',
                ]);


                var_dump(json_decode(stripslashes($response)));die;

                $style = '
                    <style>
                    
                     .trac-nghiem-thu-vien
                     {
                        display: none !important;
                    }
                    </style>
                ';
                $content = $style . $response;
                break;
            case 10:
                $year = $params[0];
                $gioitinh = $params[1];

//                $url = 'http://vansu.net/tu-vi-canh-ngo-2019-'.$gioitinh.'-mang-'.$year.'.html';
//                echo $url;die;

                $response = $this->_curl($url, true, [
                    'namsinh' => $year,
                    'gioitinh' => $gioitinh,
                    'form_id' => 'tuvi_2017_form',
//                    'form_build_id' => 'form-rfhcZIU4sCKdSlT9OAxCNU7t0evYAtiV4ZmyEQe9IDU'
                ]);

                $response = str_replace('<font color="red">Xem năm khác</font>', '', $response);

//                $response = str_replace('<center><span>Ngày sinh của bạn: </span>//</center>', '<center><span>Ngày sinh của bạn: </span>'.$date->format('d'). '/'.$date->format('m').'/'.$date->format('Y').'</center>', $response);

                $style = '
                    <style>
                    .zone-branding-wrapper,
                     .block-description-function.month-calendar,
                     #block-system-main-menu, .block-lich,
                     .block-block,
                     .block-views,
                     footer,
                     .trac-nghiem-thu-vien,
                     .view-thu-vien,
                     .body-trac-nghiem-node .pane-title,
                     #section-header,
                     .trac-nghiem-list,
                     .danh-muc-trac-nghiem-panel,
                     .pane-block-105,
                     .trac-nghiem-thu-vien
                     {
                    }
                    </style>
                ';
                $content = $style . $response;
                break;

            default:
                break;
        }


        $this->response->withStringBody($commonStyle . $content)->withStatus(200)->send();
        die;
    }


    public $cookies = [];

    private function _curl($url, $post = false, $params = [], $useShell = false)
    {
        if ($useShell) {
            return shell_exec('curl --data "hoten=hoa" ' . $url);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_COOKIEFILE, "");


        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, $post);
        if ($post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

}
