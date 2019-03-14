<?php

namespace App\Controller;

use Cake\Routing\Router;

require_once ROOT . "/vendor/ressio/pharse/pharse.php";

class NewsController extends AppController
{
    const BASE_URL = "http://www.newsnow.co.uk";
    const WEBSITE_CONVERT = "http://ftr.fivefilters.org/makefulltextfeed.php?url=";
    const COMMON = self::BASE_URL . "/h/Sport/Football";
    const PREMIER_LEAGUE = self::BASE_URL . "/h/Sport/Football/Premier+League";
    const LALIGA = self::BASE_URL . "/h/Sport/Football/La+Liga";
    const SERIE_A = self::BASE_URL . "/h/Sport/Football/Serie+A";
    const BUNDESLIGA = self::BASE_URL . "/h/Sport/Football/Bundesliga";
    const LIGUE_1 = self::BASE_URL . "/h/Sport/Football/Ligue+1";
    const CHAMPIONS_LEAGUE = self::BASE_URL . "/h/Sport/Football/Europe/UEFA+Champions+League";
    const MANCHESTER_UNITED = self::BASE_URL . "/h/Sport/Football/Premier+League/Manchester+United";
    const CHELSEA = self::BASE_URL . "/h/Sport/Football/Premier+League/Chelsea";
    const ARSEAL = self::BASE_URL . "/h/Sport/Football/Premier+League/Arsenal";
    const LIVERPOOL = self::BASE_URL . "/h/Sport/Football/Premier+League/Liverpool";
    const BARCELONA = self::BASE_URL . "/h/Sport/Football/La+Liga/Barcelona";
    const REAL_MADRID = self::BASE_URL . "/h/Sport/Football/La+Liga/Real+Madrid";
    const BAYERN_MUNICH = self::BASE_URL . "/h/Sport/Football/Bundesliga/Bayern+Munich";
    const JUVENTUS = self::BASE_URL . "/h/Sport/Football/Serie+A/Juventus";
    const AC_MILAN = self::BASE_URL . "/h/Sport/Football/Serie+A/AC+Milan";

    function _deobfuscate($d)
    {
        $a = $d;
        $a = preg_replace('/`$/', '', $a);
        $a = preg_replace('/^o1`/', '', $a);
        $a = explode('-', $a);
        $r = array();
        for ($i = 0;
             $i < count($a); $i++) {
            $b = explode('_', $a[$i]);
            $c = array_shift($b);
            if ($c == 'a') {
                $c = 'i';
            } else if ($c == 'e') {
                $c = 'a';
            } else if ($c == 'i') {
                $c = 'e';
            } else if ($c == 'j') {
                $c = 'u';
            } else if ($c == 'd') {
                $c = 'j';
            } else if ($c == 'u') {
                $c = 'd';
            } else if ($c == 'w') {
                $c = 'y';
            } else if ($c == 'h') {
                $c = 'w';
            } else if ($c == 'y') {
                $c = 'h';
            } else if ($c == 'l') {
                $c = 'r';
            } else if ($c == ':') {
                $c = 'l';
            } else if ($c == 'r') {
                $c = ':';
            }
            $b = join($c, array_reverse($b));
            array_push($r, $b);
        }
        $r = join('', array_reverse($r));
        $r = preg_replace('/`u/', '_', $r);
        $r = preg_replace('/`d/', '-', $r);
        $r = preg_replace('/`x/', '`', $r);
        return $r;
    }


    public function listNews()
    {
        $url = $this->getRequest()->getQuery('url');
        $url = urldecode($url);
        $url = urldecode($url);
        $url = urldecode($url);
        $url = preg_replace('/\s/', '+', $url);
        if (!$url) {
            return false;
        }
        $html = \Pharse::file_get_dom($url);
        $newsfeed = $html("#content .newsmain_wrap.central_ln_wrap");
        $newsfeed = $newsfeed[0](".newsfeed")[0];

        $listNews = $this->_convertNewsHtmlToArray($newsfeed);
        $this->response->withStringBody(json_encode($listNews))->withStatus(200)->send();
        die;
    }


    public function iosNews()
    {
        $url = $this->getRequest()->getQuery('url');
        $url = urldecode($url);
        $url = urldecode($url);
        $url = urldecode($url);
        $url = preg_replace('/\s/', '+', $url);
        if (!$url) {
            return false;
        }
        $html = \Pharse::file_get_dom($url);
        $newsfeed = $html("#content .newsmain_wrap.central_ln_wrap");
        $newsfeed = $newsfeed[0](".newsfeed")[0];

        $listNews = $this->_convertIosNewsHtmlToArray($newsfeed);
        $this->response->withStringBody(json_encode($listNews))->withStatus(200)->send();
        die;
    }

    public function loadMore()
    {
        $more = $this->getRequest()->getQuery('more');
        $data = json_decode(file_get_contents(self::BASE_URL . '/ajax/more?more=' . $more), true);
        $more = Router::url([
            'controller' => 'News',
            'action' => 'loadMore',
            'more' => urlencode($data['content']['more'])
        ], true);
        $stream = join("", $data['stream']);
        $html = $this->_deobfuscate($stream);
        $html = \Pharse::str_get_dom($html);
        $listNews = $this->_convertNewsHtmlToArray($html, $more);
        $this->response->withStringBody(json_encode($listNews))->withStatus(200)->send();
        die;
    }

    public function iosLoadMore()
    {
        $more = $this->getRequest()->getQuery('more');
        $data = json_decode(file_get_contents(self::BASE_URL . '/ajax/more?more=' . $more), true);
        $more = Router::url([
            'controller' => 'News',
            'action' => 'iosLoadMore',
            'more' => urlencode($data['content']['more'])
        ], true);
        $stream = join("", $data['stream']);
        $html = $this->_deobfuscate($stream);
        $html = \Pharse::str_get_dom($html);
        $listNews = $this->_convertIosNewsHtmlToArray($html, $more);
        $this->response->withStringBody(json_encode($listNews))->withStatus(200)->send();
        die;
    }

    public function _convertNewsHtmlToArray($html, $more = null)
    {
        $divs = $html(".hl_time");
        $listNews['List_All'] = [];
        foreach ($divs as $div) {
            $next = $div->getNextSibling();

            $item = [
                'title' => $div->getPlainText(),
                'SubCatgory' => []
            ];

            while ($next != null && trim($next->getAttribute('class')) != 'hl_time') {
                if (preg_match('/^hl([^a-z])*$/', trim($next->getAttribute('class')))) {
                    $aTag = $next('.hll')[0];

                    $item['SubCatgory'][] = [
                        'title' => $aTag->getPlainText(),
                        'url' => Router::url([
                            'controller' => 'News',
                            'action' => 'detail',
                            'url' => $aTag->getAttribute('href')
                        ], true),
                        'time' => $next('.time')[0]->getAttribute('data-time'),
                        'chanel' => $next('.src-part')[0]->getPlainText()
                    ];
                }
                $next = $next->getNextSibling();
            }
            $listNews['List_All'][] = $item;
        }
        $listNews['loadmore'] = $more ? $more : Router::url([
            'controller' => 'News',
            'action' => 'loadMore',
            'more' => urlencode($html->getAttribute('data-more'))
        ], true);
        return $listNews;
    }
    public function _convertIosNewsHtmlToArray($html, $more = null)
    {
        $divs = $html(".hl_time");
        $listNews['List_All'] = [];
        foreach ($divs as $div) {
            $next = $div->getNextSibling();

            $item = [];

            while ($next != null && trim($next->getAttribute('class')) != 'hl_time') {
                if (preg_match('/^hl([^a-z])*$/', trim($next->getAttribute('class')))) {
                    $aTag = $next('.hll')[0];

                    $item[] = [
                        'title' => $aTag->getPlainText(),
                        'new-detail' => Router::url([
                            'controller' => 'News',
                            'action' => 'iosDetail',
                            'url' => base64_encode($aTag->getAttribute('href'))
                        ], true),
                    ];
                }
                $next = $next->getNextSibling();
            }
        }
        $listNews['List_All'][] = $item;
        $listNews['loadmore'] = $more ? $more : Router::url([
            'controller' => 'News',
            'action' => 'iosLoadMore',
            'more' => urlencode($html->getAttribute('data-more'))
        ], true);
        return $listNews;
    }

    public function detail()
    {

        $url = urldecode($this->getRequest()->getQuery('url'));
        $content = file_get_contents($url);
        $destinationUrl = \Pharse::str_get_dom($content);
        $destinationUrl = $destinationUrl('#retrieval-msg strong a');
        $destinationUrl = $destinationUrl[0]->getAttribute('href');

        $thirdPartyContent = \Pharse::file_get_dom(self::WEBSITE_CONVERT . $destinationUrl);

        if (strpos($thirdPartyContent->getPlainText(), 'URL blocked - Why') !== false
            || strlen($thirdPartyContent->getPlainText()) <= 20
        ) {
            $this->response->withStringBody(file_get_contents($destinationUrl))->withStatus(200)->send();
            die;
        }
        $item = $thirdPartyContent('rss channel item')[0];
        $content = $item('description')[0]->getPlainText();
        $title = "<h2>" . $item('title')[0]->getPlainText() . "</h2>";

        $content = str_replace('<strong><a href="https://blockads.fivefilters.org">Let\'s block ads!</a></strong> <a href="https://blockads.fivefilters.org/acceptable.html">(Why?)</a></p>', '', $content);
        $this->response->withStringBody($title . $content)->withStatus(200)->send();
        die;
    }

    public function iosDetail()
    {
        $url = base64_decode($this->getRequest()->getQuery('url'));
        $content = file_get_contents($url);
        $destinationUrl = \Pharse::str_get_dom($content);
        $destinationUrl = $destinationUrl('#retrieval-msg strong a');
        $destinationUrl = $destinationUrl[0]->getAttribute('href');

        $thirdPartyContent = \Pharse::file_get_dom(self::WEBSITE_CONVERT . $destinationUrl);

        if (strpos($thirdPartyContent->getPlainText(), 'URL blocked - Why') !== false
            || strlen($thirdPartyContent->getPlainText()) <= 20
        ) {
            $this->response->withStringBody(file_get_contents($destinationUrl))->withStatus(200)->send();
            die;
        }
        $item = $thirdPartyContent('rss channel item')[0];
        $content = $item('description')[0]->getPlainText();
        $title = "<h2>" . $item('title')[0]->getPlainText() . "</h2>";

        $content = str_replace('<strong><a href="https://blockads.fivefilters.org">Let\'s block ads!</a></strong> <a href="https://blockads.fivefilters.org/acceptable.html">(Why?)</a></p>', '', $content);
        $this->response->withStringBody($title . $content)->withStatus(200)->send();
        die;
    }
}

