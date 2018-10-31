<?php


namespace App\Controller;

use Cake\Routing\Router;

require ROOT . "/vendor/ressio/pharse/pharse.php";

class GarenaController extends AppController
{
    const BASE_URL           = 'https://lienquan.garena.vn';
    const NEWS_BASE_URL      = 'http://gamek.vn';
    const TUONG_URL          = 'https://lienquan.garena.vn/tuong';
    const CHI_TIET_TUONG_URL = 'https://lienquan.garena.vn/tuong-chi-tiet/';
    const NGOC_URL           = 'https://lienquan.garena.vn/bo-tro';
    const DOC_CHIEU_URL      = 'https://lienquan.garena.vn/doc-chieu';
    const TRANG_BI_URL       = 'https://lienquan.garena.vn/trang-bi';
    const TIN_TUC_URL        = 'http://gamek.vn/lien-quan-mobile/page-1.htm';
    const CAM_NANG_URL       = 'http://lienquan.net/cam-nang?page=1';

    public function tuong($url = null)
    {
        $url          = $this->getRequest()->getQuery('url', self::TUONG_URL);
        $typeSearchId = $this->getRequest()->getQuery('type_id');
        $nameSearch   = strtolower($this->_vn_to_str($this->getRequest()->getQuery('name')));

        $html   = \Pharse::file_get_dom($url);
        $tuongs = $html('.list-champion');
        $result = [
            'List_All' => []
        ];
        foreach ($tuongs as $tuong) {
            preg_match('/\d+/', $tuong('a')[0]->getAttribute('href'), $matches);
            $typeId = $tuong('.tags')[0]->getAttribute('type');
            $id     = isset($matches[0]) ? $matches[0] : '';
            $name   = $tuong('.name')[0]->getPlainText();

            if ((empty($typeSearchId) || $typeSearchId == 'all' || $typeId == $typeSearchId)
                && (empty($nameSearch) || preg_match('/' . $nameSearch . '/', strtolower($this->_vn_to_str($name))))
            ) {
                $result['List_All'][] = [
                    'id'   => $id,
                    'name' => $name,
                    'img'  => self::BASE_URL . $tuong('img')[0]->getAttribute('src')
                ];
            }
        }
        $this->response->withStringBody(json_encode($result))->withStatus(200)->send();
        die;
    }

    public function chiTietTuong($url = null)
    {
        $url    = $this->getRequest()->getQuery('url', self::CHI_TIET_TUONG_URL);
        $html   = \Pharse::file_get_dom($url);
        $result = [
            'ChiTietTuong' => []
        ];

        $this->response->withStringBody(json_encode($result))->withStatus(200)->send();
        die;
    }

    public function ngoc()
    {
        $url = $this->getRequest()->getQuery('url', self::NGOC_URL);

        $color = $this->getRequest()->getQuery('color');
        $tier  = strtolower($this->_vn_to_str($this->getRequest()->getQuery('tier')));

        $html  = \Pharse::file_get_dom($url);
        $ngocs = $html('.list-rune');

        $result = [
            'List_Ngoc' => []
        ];
        foreach ($ngocs as $ngoc) {
            $name = $ngoc('.name')[0]->getPlainText();
            $tags = $ngoc('.tags')[0]->getPlainText();

            if ((empty($color) || $color == 'all' || preg_match('/' . $color . '/', $tags))
                && (empty($tier) || $tier == 0 || preg_match('/cap\-' . $tier . '/', $tags))
            ) {
                $result['List_Ngoc'][] = [
                    'name' => $name,
                    'img'  => self::BASE_URL . $ngoc('img')[0]->getAttribute('src'),
                    'note' => $ngoc('.text')[0]->getPlainText()
                ];
            }
        }
        $this->response->withStringBody(json_encode($result))->withStatus(200)->send();
        die;
    }

    public function trangBi()
    {
        $url = $this->getRequest()->getQuery('url', self::TRANG_BI_URL);

        $nameSearch = strtolower($this->_vn_to_str($this->getRequest()->getQuery('name')));
        $type       = $this->getRequest()->getQuery('type');
        $property   = $this->getRequest()->getQuery('property');

        $html     = \Pharse::file_get_dom($url);
        $trangBis = $html('.group-items');

        $result = [
            'List_TrangBi' => []
        ];
        foreach ($trangBis as $trangBi) {
            $name = $trangBi('.name')[0]->getPlainText();
            $tags = $trangBi('.tags')[0]->getPlainText();
            $note = $html($trangBi('a')[0]->getAttribute('data-target'))[0]('.modal-dialog')[0]->html();
            if (
                (empty($nameSearch) || preg_match('/' . $nameSearch . '/', strtolower($this->_vn_to_str($name))))
                && (empty($type) || $type == 0 || preg_match('/cap\-' . $type . '/', $tags))
                && (empty($property) || $property == 0 || preg_match('/' . $property . '/', $tags))
            ) {
                $result['List_TrangBi'][] = [
                    'name' => $name,
                    'img'  => $trangBi('img')[0]->getAttribute('src'),
                    'note' => $note
                ];
            }
        }
        $this->response->withStringBody(json_encode($result))->withStatus(200)->send();
        die;
    }

    public function docChieu()
    {
        $url = $this->getRequest()->getQuery('url', self::DOC_CHIEU_URL);


        $html      = \Pharse::file_get_dom($url);
        $docChieus = $html('.tabs-content');

        $result = [
            'List_Doc_Chieu' => []
        ];

        $imgs     = [];
        $imgsHtml = $html('.tabs-all')[0]('li');

        foreach ($imgsHtml as $imgHtml) {
            $imgs[$imgHtml('a')[0]->getAttribute('href')] = $imgHtml('img')[0]->getAttribute('src');
        }

        foreach ($docChieus as $docChieu) {
            $result['List_Doc_Chieu'][] = [
                'name'  => $docChieu('.title')[0]->getPlainText(),
                'img'   => $imgs['#' . $docChieu->getAttribute('id')],
                'note'  => $docChieu('.txtcript')[0]->getPlainText(),
                'video' => $docChieu('.playvideo')[0]->getAttribute('data-video')
            ];
        }
        $this->response->withStringBody(json_encode($result))->withStatus(200)->send();
        die;
    }

    public function getFilterNgoc($html = null, $json = true)
    {
        $url = $this->getRequest()->getQuery('url', self::NGOC_URL);
        if (!$html) {
            $html = \Pharse::file_get_dom($url);
        }

        $result      = [
            'color' => [],
            'tier'  => []
        ];
        $itemFilters = $html('.item-filter');
        foreach ($itemFilters as $itemFilter) {
            $result['color'][] = [
                $itemFilter('input')[0]->getAttribute('value') => trim($itemFilter('label')[0]->getPlainText())
            ];
        }

        $optionFilters = $html('#select-tier')[0]('option');
        foreach ($optionFilters as $optionFilter) {
            $result['tier'][] = [
                $optionFilter->getAttribute('value') => trim($optionFilter->getPlainText())
            ];
        }

        if ($json) {
            $this->response->withStringBody(json_encode($result))->withStatus(200)->send();
            die;
        } else {
            return $result;
        }
    }

    public function getFilterTrangBi($html = null, $json = true)
    {
        $url = $this->getRequest()->getQuery('url', self::TRANG_BI_URL);
        if (!$html) {
            $html = \Pharse::file_get_dom($url);
        }

        $result      = [
            'type'       => [],
            'properties' => []
        ];
        $optionsType = $html('#select-type')[0]('option');
        foreach ($optionsType as $optionType) {
            $result['type'][] = [
                $optionType->getAttribute('value') => trim($optionType->getPlainText())
            ];
        }
        $optionsProperties = $html('#select-properties')[0]('option');
        foreach ($optionsProperties as $optionProperties) {
            $result['properties'][] = [
                $optionProperties->getAttribute('value') => trim($optionProperties->getPlainText())
            ];
        }

        if ($json) {
            $this->response->withStringBody(json_encode($result))->withStatus(200)->send();
            die;
        } else {
            return $result;
        }
    }

    public function getTypesTuong($html = null, $json = true)
    {
        $url = $this->getRequest()->getQuery('url', self::TUONG_URL);
        if (!$html) {
            $html = \Pharse::file_get_dom($url);
        }
        $itemFilters = $html('.item-filter');
        $typesTuong  = [
            'Type_Tuong' => []
        ];
        foreach ($itemFilters as $itemFilter) {
            $typesTuong['Type_Tuong'][] = [
                $itemFilter('input')[0]->getAttribute('value') => trim($itemFilter('label')[0]->getPlainText())
            ];
        }

        if ($json) {
            $this->response->withStringBody(json_encode($typesTuong))->withStatus(200)->send();
            die;
        } else {
            return $typesTuong;
        }
    }

    private function _vn_to_str($str)
    {

        $unicode = array(

            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

            'd' => 'đ',

            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

            'i' => 'í|ì|ỉ|ĩ|ị',

            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'D' => 'Đ',

            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',

            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

        );

        foreach ($unicode as $nonUnicode => $uni) {

            $str = preg_replace("/($uni)/i", $nonUnicode, $str);

        }
        $str = str_replace(' ', '_', $str);

        return $str;

    }

    public function tintuc()
    {
        $url = $this->getRequest()->getQuery('url', self::TIN_TUC_URL);

        $html = \Pharse::file_get_dom($url);
        $news = [
            'TinTuc' => []
        ];

        if (count($html('.newsgame')) <= 0) {
            goto endpoint;
        }

        $htmlNews = $html('.newsgame')[0]('ul li');
        foreach ($htmlNews as $htmlNew) {
            $news['TinTuc'][] = [
                'title'       => $htmlNew('.ltitle')[0]('a')[0]->getPlainText(),
                'img'         => $htmlNew('img')[0]->getAttribute('src'),
                'date'        => $htmlNew('.right ')[0]('p')[1]->getPlainText(),
                'description' => $htmlNew('.right ')[0]('p')[2]->getPlainText(),
                'detail'      => BASEURL . '/chi-tiet-tin-tuc?url=' . self::NEWS_BASE_URL . $htmlNew('a')[0]->getAttribute('href')
            ];
        }
        endpoint:
        $this->response->withStringBody(json_encode($news))->withStatus(200)->send();
        die;
    }

    public function camnang()
    {
        $url = $this->getRequest()->getQuery('url', self::CAM_NANG_URL);

        $html = \Pharse::file_get_dom($url);
        $news = [
            'CamNang' => []
        ];

        if (count($html('.videos_box')) <= 0) {
            goto endpoint;
        }

        $htmlNews = $html('.videos_box');
        foreach ($htmlNews as $htmlNew) {
            $news['CamNang'][] = [
                'title'  => $htmlNew('.text1')[0]->getPlainText(),
                'img'    => $htmlNew('img')[0]->getAttribute('src'),
                'detail' => BASEURL . '/chi-tiet-cam-nang?url=' . $htmlNew('a')[0]->getAttribute('href')
            ];
        }
        endpoint:
        $this->response->withStringBody(json_encode($news))->withStatus(200)->send();
        die;
    }

    public function newsDetail()
    {
        $url = $this->getRequest()->getQuery('url');

        if ($url) {
            $html    = \Pharse::file_get_dom($url);
            $h1      = $html('.topdetail')[0]('h1')[0]->html();
            $h2      = $html('.rightdetail')[0]('h2')[0]->html();
            $content = $html('.rightdetail_content')[0]->html();
            $this->response->withStringBody($h1 . "<br/>" . $h2 . "<br/>" . $content)->withStatus(200)->send();
        }
        die;
    }

    public function camnangDetail()
    {
        $url = $this->getRequest()->getQuery('url');

        if ($url) {
            $html    = \Pharse::file_get_dom($url);
            $content = $html('.news-content')[0]->html();
            $this->response->withStringBody($content)->withStatus(200)->send();
        }
        die;
    }
}