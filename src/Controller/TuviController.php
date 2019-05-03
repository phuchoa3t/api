<?php

namespace App\Controller;

use App\Controller\AppController;

require_once ROOT . "/vendor/ressio/pharse/pharse.php";

class TuviController extends AppController
{

    const SELECT = [
        "https://huyenbi.net/Xem-ngay-tot-xau.html",
        "https://tuvivannien.com/tinh-viec-dai-su/ngay-{DMY}-cong-viec-{POS}.html",
        "https://huyenbi.net/Tu-vi-theo-ngay-sinh.html",
        "https://lichvannien365.com/boi-tinh-yeu",
        "https://huyenbi.net/Chon-nam-sinh-con.html",
        "https://huyenbi.net/Xem-huong-nha-theo-tuoi.html",
        "https://huyenbi.net/Xem-tuoi-vo-chong.html",
        "https://huyenbi.net/Xem-boi-Ai-cap.html",
        "https://www.blogphongthuy.com/xem-tuoi-xay-nha-chon-nam-xay-nha-theo-nam-sinh-hop-tuoi.html",
        "https://huyenbi.net/Con-so-may-man-ngay-hom-nay.html",
        "https://huyenbi.net/Chon-so-dep-theo-phong-thuy.html",
    ];

    public function index($selectId, ...$params)
    {
        $commonStyle = '
            <style>
                    .adsbygoogle, .fb-comments{
                        display: none !important;
                    }
            </style>
        ';
        $script = '
            <script>
                var aTags = document.getElementsByTagName("a"),
                    atl = aTags.length,
                    i;

                for (i = 0; i < atl; i++) {
                    aTags[i].href="javascript:void(0)"
                }
            </script>
        ';
        $content = '';
        $url = self::SELECT[$selectId];
        switch ($selectId) {
            case 0:
                $date = $params[0] ?? "";
                if (!$date) {
                    $date = date('d-m-Y');
                } else {
                    $date = date_create_from_format('dmY', $date);
                    $date = $date->format('d-m-Y');
                }


                $response = $this->_curl($url, true, [
                    'today' => $date,
                    'xemngay' => '',
                ], true);

                $style = '
                    <style>
                         #ccr-right-section,
                         #ccr-header,
                         .ccr-last-update,
                         #calemdar tbody tr:first-child,
                         .bottom-border,
                         #ccr-footer-sidebar,
                         footer,
                         #scrollUp
                         {
                            display: none !important;
                         }
                         #ccr-left-section {
                            border-right: none !important;
                         }
                         #ccr-left-section.col-md-8 {
                            width: 100%;
                         }
                         #ccr-left-section  section:nth-child(1) {
                            display: block !important;
                         }
                    </style>
                ';

                $script .= '
                    <script>
                        $(function() {
                          $("#calemdar tr").each(function() {
                                if (
                                    $(this).html().includes("Xem tiếp các bài")
                                     || $(this).html().includes("<center>")
                                     || $(this).html().includes("fb-like")
                                ) {
                                    $(this).remove();
                                }
                          });
                          
                          $("#ccr-left-section section").each(function() {
                            if (
                                $(this).html().includes("Tin bài liên quan")
                                || $(this).html().includes("ccr-thumbnail")
                            ) {
                                $(this).remove();
                            }
                          })
                        })
                    </script>
                ';

                $baseURL = '<base href="https://huyenbi.net" />';

                $content = $baseURL . $style . $response . $script;
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
                    .addthis-smartlayers-desktop,
                    .mcwidget-overlay
                     {
                        display: none !important;
                    }
                    </style>
                ';
                $content = $style . file_get_contents($url);
                break;

            case 2 :


                $date = $params[0] ?? "";
                if (!$date) {
                    $date = date('dmY');
                }
                $date = date_create_from_format('dmY', $date);

                $response = $this->_curl($url, true, [
                    'ngay' => (int)$date->format('d'),
                    'thang' => (int)$date->format('m'),
                    'nam' => (int)$date->format('Y'),
                    'namsinh' => '',
                ], true);

                $style = '
                    <style>
                         #ccr-right-section,
                         #ccr-header,
                         .ccr-last-update,
                         #calemdar tbody tr:first-child,
                         .bottom-border,
                         #ccr-footer-sidebar,
                         footer,
                         #scrollUp,
                         #calendar form
                         {
                            display: none !important;
                         }
                         #ccr-left-section {
                            border-right: none !important;
                         }
                         #ccr-left-section.col-md-8 {
                            width: 100%;
                         }
                         #ccr-left-section  section:nth-child(1) {
                            display: block !important;
                         }
                    </style>
                ';

                $script .= '
                    <script>
                        $(function() {
                            
                          
                          $(".fb-like").each(function() {
                                $(this).closest("tr").remove();
                          });
                          
                          
                          
                          $("#ccr-left-section section").each(function() {
                            if (
                                $(this).html().includes("Tin bài liên quan")
                                || $(this).html().includes("Bài liên quan")
                                || $(this).html().includes("ccr-thumbnail")
                            ) {
                                $(this).remove();
                            }
                          })
                          $("#ccr-left-section table").each(function() {
                            if (
                                $(this).html().includes("Xem tử vi theo ngày sinh")
//                                || $(this).html().includes("ccr-thumbnail")
                            ) {
                                $(this).remove();
                            }
                            
                            $(this).find("tr").each(function() {
                                if (
                                    $(this).html().includes("Xem tiếp các bài")
//                                     || $(this).html().includes("<center>")
//                                     || $(this).html().includes("fb-like")
                                ) {
                                    $(this).remove();
                                }
                          });
                          })
                        })
                    </script>
                ';

                $baseURL = '<base href="https://huyenbi.net" />';

                $content = $baseURL . $style . $response . $script;
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
                    .lvn-main {
                        margin-top: 0px !important;
                    }
                    .col-md-9 div:nth-child(2) {
                        padding: 0px !important;
                    }

                    </style>
                ';
                $content = $style . file_get_contents($url);

                break;
            case 4 :
                $date1 = $params[0];
                $date2 = $params[1];
                $date3 = $params[2];
                $gt = $params[3];

                $response = $this->_curl($url, true, [
                    'tuoiban' => (int)$date1,
                    'tuoikhac' => (int)$date2,
                    'tuoicon' => (int)$date3,
                    'r1' => (int)$gt,
                    'xemtuoi' => ''
                ], true);


                $style = '
                    <style>
                         #ccr-right-section,
                         #ccr-header,
                         .ccr-last-update,
                         #calemdar tbody tr:first-child,
                         .bottom-border,
                         #ccr-footer-sidebar,
                         footer,
                         #scrollUp,
                         #calendar form
                         {
                            display: none !important;
                         }
                         #ccr-left-section {
                            border-right: none !important;
                         }
                         #ccr-left-section.col-md-8 {
                            width: 100%;
                         }
                         #ccr-left-section  section:nth-child(1) {
                            display: block !important;
                         }
                    </style>
                ';

                $script .= '
                    <script>
                        $(function() {
                            
                          
                          $(".fb-like").each(function() {
                                $(this).closest("tr").remove();
                          });
                          
                          $(".form-group").closest("tr").remove();
                          $(".adsbygoogle").closest("tr").remove();
                          
                          
                          
                          $("#ccr-left-section section").each(function() {
                            if (
                                $(this).html().includes("Tin bài liên quan")
                                || $(this).html().includes("Bài liên quan")
                                || $(this).html().includes("ccr-thumbnail")
                            ) {
                                $(this).remove();
                            }
                          })
                          $("#ccr-left-section table").each(function() {
                            if (
                                $(this).html().includes("Xem tử vi theo ngày sinh")
//                                || $(this).html().includes("ccr-thumbnail")
                            ) {
                                $(this).remove();
                            }
                            
                            $(this).find("tr").each(function() {
                                if (
                                    $(this).html().includes("Xem tiếp các bài")
//                                     || $(this).html().includes("<center>")
//                                     || $(this).html().includes("fb-like")
                                ) {
                                    $(this).remove();
                                }
                          });
                          })
                        })
                    </script>
                ';

                $baseURL = '<base href="https://huyenbi.net" />';

                $content = $baseURL . $style . $response . $script;
                break;
            case 5 :
                $date1 = $params[0];
                $gt = $params[2];


                $response = $this->_curl($url, true, [
                    'tuoiban' => (int)$date1,
                    'r1' => (int)$gt,
                    'xemtuoi' => ''
                ], true);


                $style = '
                    <style>
                         #ccr-right-section,
                         #ccr-header,
                         .ccr-last-update,
                         #calemdar tbody tr:first-child,
                         .bottom-border,
                         #ccr-footer-sidebar,
                         footer,
                         #scrollUp,
                         #calendar form
                         {
                            display: none !important;
                         }
                         #ccr-left-section {
                            border-right: none !important;
                         }
                         #ccr-left-section.col-md-8 {
                            width: 100%;
                         }
                         #ccr-left-section  section:nth-child(1) {
                            display: block !important;
                         }
                    </style>
                ';

                $script .= '
                    <script>
                        $(function() {
                            
                          
                          $(".fb-like").each(function() {
                                $(this).closest("tr").remove();
                          });
                          
                          $(".form-group").closest("tr").remove();
                          $(".adsbygoogle").closest("tr").remove();
                          
                          
                          
                          $("#ccr-left-section section").each(function() {
                            if (
                                $(this).html().includes("Tin bài liên quan")
                                || $(this).html().includes("Bài liên quan")
                                || $(this).html().includes("ccr-thumbnail")
                            ) {
                                $(this).remove();
                            }
                          })
                          $("#ccr-left-section table").each(function() {
                            if (
                                $(this).html().includes("Xem tử vi theo ngày sinh")
//                                || $(this).html().includes("ccr-thumbnail")
                            ) {
                                $(this).remove();
                            }
                            
                            $(this).find("tr").each(function() {
                                if (
                                    $(this).html().includes("Xem tiếp các bài")
                                     || $(this).html().includes("Xem hướng nhà theo tuổi")
//                                     || $(this).html().includes("fb-like")
                                ) {
                                    $(this).remove();
                                }
                          });
                          })
                        })
                    </script>
                ';

                $baseURL = '<base href="https://huyenbi.net" />';

                $content = $baseURL . $style . $response . $script;
                break;
            case 6 :
                $date1 = $params[0];
                $date2 = $params[1];


                $response = $this->_curl($url, true, [
                    'tuoiban' => (int)$date1,
                    'tuoikhac' => (int)$date2,
                    'xemtuoi' => ''
                ], true);


                $style = '
                    <style>
                         #ccr-right-section,
                         #ccr-header,
                         .ccr-last-update,
                         #calemdar tbody tr:first-child,
                         .bottom-border,
                         #ccr-footer-sidebar,
                         footer,
                         #scrollUp,
                         #calendar form
                         {
                            display: none !important;
                         }
                         #ccr-left-section {
                            border-right: none !important;
                         }
                         #ccr-left-section.col-md-8 {
                            width: 100%;
                         }
                         #ccr-left-section  section:nth-child(1) {
                            display: block !important;
                         }
                    </style>
                ';

                $script .= '
                    <script>
                        $(function() {
                            
                          
                          $(".fb-like").each(function() {
                                $(this).closest("tr").remove();
                          });
                          
                          $(".form-group").closest("tr").remove();
                          $(".adsbygoogle").closest("tr").remove();
                          
                          
                          
                          $("#ccr-left-section section").each(function() {
                            if (
                                $(this).html().includes("Tin bài liên quan")
                                || $(this).html().includes("Bài liên quan")
                                || $(this).html().includes("ccr-thumbnail")
                            ) {
                                $(this).remove();
                            }
                          })
                          $("#ccr-left-section table").each(function() {
                            if (
                                $(this).html().includes("Xem tử vi theo ngày sinh")
//                                || $(this).html().includes("ccr-thumbnail")
                            ) {
                                $(this).remove();
                            }
                            
                            $(this).find("tr").each(function() {
                                if (
                                    $(this).html().includes("Xem tiếp các bài")
                                     || $(this).html().includes("Xem hợp tuổi vợ chồng")
//                                     || $(this).html().includes("fb-like")
                                ) {
                                    $(this).remove();
                                }
                          });
                          })
                        })
                    </script>
                ';

                $baseURL = '<base href="https://huyenbi.net" />';

                $content = $baseURL . $style . $response . $script;
                break;
            case 7 :
                $name = $params[0];

                $response = $this->_curl($url, true, [
                    'hoten' => $name,
                    'xemboiaicap' => '',
                ], true);

                $style = '
                    <style>
                        #ccr-header,
                         .ccr-last-update,
                         #ccr-world-news table:first-child,
                         #ccr-right-section,
                         #ccr-world-news table:last-child tr:not(:first-child),
                         .fb-comments,
                         #ccr-world-news:last-child,
                         #ccr-footer-sidebar,
                         #ccr-footer,
                         #scrollUp
                         {
                            display: none !important;
                         }
                         #ccr-left-section {
                            border-right: none !important;
                         }
                         #ccr-left-section.col-md-8 {
                            width: 100%;
                         }
                    </style>
                ';

                $baseURL = '<base href="https://huyenbi.net" />';

                $content = $baseURL . $style . $response;
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

                     .trac-nghiem-thu-vien, .divBody > div:nth-child(2)
                     {
                        display: none !important;
                    }
                    </style>
                ';
                $content = $style . $response;
                break;
            case 9 :

                $date1 = $params[0];
                $gt = $params[1];


                $response = $this->_curl($url, true, [
                    'tuoiban' => (int)$date1,
                    'r1' => (int)$gt,
                    'xemtuoi' => ''
                ], true);


                $style = '
                    <style>
                         #ccr-right-section,
                         #ccr-header,
                         .ccr-last-update,
                         #calemdar tbody tr:first-child,
                         .bottom-border,
                         #ccr-footer-sidebar,
                         footer,
                         #scrollUp,
                         #calendar form
                         {
                            display: none !important;
                         }
                         #ccr-left-section {
                            border-right: none !important;
                         }
                         #ccr-left-section.col-md-8 {
                            width: 100%;
                         }
                         #ccr-left-section  section:nth-child(1) {
                            display: block !important;
                         }
                    </style>
                ';

                $script .= '
                    <script>
                        $(function() {
                            
                          
                          $(".fb-like").each(function() {
                                $(this).closest("tr").remove();
                          });
                          
                          $(".form-group").closest("tr").remove();
                          $(".adsbygoogle").closest("tr").remove();
                          
                          
                          
                          $("#ccr-left-section section").each(function() {
                            if (
                                $(this).html().includes("Tin bài liên quan")
                                || $(this).html().includes("Bài liên quan")
                                || $(this).html().includes("ccr-thumbnail")
                            ) {
                                $(this).remove();
                            }
                          })
                          $("#ccr-left-section table").each(function() {
                            if (
                                $(this).html().includes("Xem tử vi theo ngày sinh")
//                                || $(this).html().includes("ccr-thumbnail")
                            ) {
                                $(this).remove();
                            }
                            
                            $(this).find("tr").each(function() {
                                if (
                                    $(this).html().includes("Xem tiếp các bài")
                                     || $(this).html().includes("Con số may mắn hôm nay")
                                     || $(this).html().includes("createElement")
                                ) {
                                    $(this).remove();
                                }
                          });
                          })
                        })
                    </script>
                ';

                $baseURL = '<base href="https://huyenbi.net" />';

                $content = $baseURL . $style . $response . $script;
                break;
            case 10:
                $so = $params[0];
                $year = $params[1];
                $gt = $params[2];

                $response = $this->_curl($url, true, [
                    'nam' => (int)$year,
                    'r1' => (int)$gt,
                    'hoten' => (int)$so,
                    'xemboisim' => ''
                ], true);


                $style = '
                    <style>
                         #ccr-right-section,
                         #ccr-header,
                         .ccr-last-update,
                         #calemdar tbody tr:first-child,
                         .bottom-border,
                         #ccr-footer-sidebar,
                         footer,
                         #scrollUp,
                         #calendar form
                         {
                            display: none !important;
                         }
                         #ccr-left-section {
                            border-right: none !important;
                         }
                         #ccr-left-section.col-md-8 {
                            width: 100%;
                         }
                         #ccr-left-section  section:nth-child(1) {
                            display: block !important;
                         }
                    </style>
                ';

                $script .= '
                    <script>
                        $(function() {
                            
                          
                          $(".fb-like").each(function() {
                                $(this).closest("tr").remove();
                          });
                          
                          $(".form-group").closest("tr").remove();
                          $(".adsbygoogle").closest("tr").remove();
                          
                          
                          
                          $("#ccr-left-section section").each(function() {
                            if (
                                $(this).html().includes("Tin bài liên quan")
                                || $(this).html().includes("Bài liên quan")
                                || $(this).html().includes("ccr-thumbnail")
                            ) {
                                $(this).remove();
                            }
                          })
                          $("#ccr-left-section table").each(function() {
                            if (
                                $(this).html().includes("Xem tử vi theo ngày sinh")
//                                || $(this).html().includes("ccr-thumbnail")
                            ) {
                                $(this).remove();
                            }
                            var table = $(this);
                            
                            $(this).find("tr").each(function() {
                                if (
                                    $(this).html().includes("Xem tiếp các bài")
                                     || $(this).html().includes("Con số may mắn hôm nay")
                                     || $(this).html().includes("Xem tiếp các chuyên mục")
                                     || $(this).html().includes("createElement")
                                ) {
                                    $(this).remove();
                                    if ($(this).html().includes("Xem tiếp các chuyên mục")) {
                                        table.remove();
                                    }
                                }
                          });
                          })
                        })
                    </script>
                ';

                $baseURL = '<base href="https://huyenbi.net" />';

                $content = $baseURL . $style . $response . $script;
                break;
            case 11:
                $response = file_get_contents($url);

                preg_match('/name=\"form_build_id\" value="(.*)"/', $response, $match);
                $form_build_id = $match[1];

                $url = 'http://vansu.net/system/ajax';
                $sdt = $params[0];
                $year = $params[1];

                $gioitinh = $params[2];


                $response = $this->_curl($url, true, [
                    'sdt' => $sdt,
                    'nam' => $year,
                    'gioitinh' => $gioitinh,
                    'form_build_id' => $form_build_id,
                    'form_id' => 'lich_sim_phong_thuy_form',
                ]);

                $response = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true);
                $response = $response[1]['data'] ?? '';

                $style = '
                    <style>

                     .trac-nghiem-thu-vien
                     {
                        display: none !important;
                    }
                    .kqua {
                        text-align: center;
                        padding: 20px 20px 0px 20px;
                    }
                    </style>
                ';
                $content = $style . $response;
                break;

            default:
                break;
        }

        $content = preg_replace('/\<link rel=\"shortcut icon\" href=\"\/img\/icon.png\"\/\>/', '', $content);

        $this->response->withStringBody($commonStyle . $content)->withStatus(200)->send();
        die;
    }


    public $cookies = [];

    private function _curl($url, $post = false, $params = [], $useShell = false)
    {
        if ($useShell) {
            $shell = 'curl';
            $shell .= ' -k -X ' . ($post ? 'POST' : 'GET');
            foreach ($params as $key => $val) {
                $shell .= ' -F "' . $key . '=' . $val . '"';
            }

            return shell_exec($shell . ' ' . $url);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_COOKIEFILE, "");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);



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
