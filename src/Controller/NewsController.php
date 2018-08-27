<?php

namespace App\Controller;

use Cake\Routing\Router;

require ROOT . "/vendor/ressio/pharse/pharse.php";

class NewsController extends AppController
{
    const BASE_URL          = "http://www.newsnow.co.uk";
    const WEBSITE_CONVERT   = "http://ftr.fivefilters.org/makefulltextfeed.php?url=";
    const COMMON            = self::BASE_URL . "/h/Sport/Football";
    const PREMIER_LEAGUE    = self::BASE_URL . "/h/Sport/Football/Premier+League";
    const LALIGA            = self::BASE_URL . "/h/Sport/Football/La+Liga";
    const SERIE_A           = self::BASE_URL . "/h/Sport/Football/Serie+A";
    const BUNDESLIGA        = self::BASE_URL . "/h/Sport/Football/Bundesliga";
    const LIGUE_1           = self::BASE_URL . "/h/Sport/Football/Ligue+1";
    const CHAMPIONS_LEAGUE  = self::BASE_URL . "/h/Sport/Football/Europe/UEFA+Champions+League";
    const MANCHESTER_UNITED = self::BASE_URL . "/h/Sport/Football/Premier+League/Manchester+United";
    const CHELSEA           = self::BASE_URL . "/h/Sport/Football/Premier+League/Chelsea";
    const ARSEAL            = self::BASE_URL . "/h/Sport/Football/Premier+League/Arsenal";
    const LIVERPOOL         = self::BASE_URL . "/h/Sport/Football/Premier+League/Liverpool";
    const BARCELONA         = self::BASE_URL . "/h/Sport/Football/La+Liga/Barcelona";
    const REAL_MADRID       = self::BASE_URL . "/h/Sport/Football/La+Liga/Real+Madrid";
    const BAYERN_MUNICH     = self::BASE_URL . "/h/Sport/Football/Bundesliga/Bayern+Munich";
    const JUVENTUS          = self::BASE_URL . "/h/Sport/Football/Serie+A/Juventus";
    const AC_MILAN          = self::BASE_URL . "/h/Sport/Football/Serie+A/AC+Milan";

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
        $html     = \Pharse::file_get_dom(self::COMMON);
        $newsfeed = $html("#content .newsmain_wrap.central_ln_wrap")[0](".newsfeed")[0];

        $f = fopen("a.txt", "w");
        fwrite($f, $newsfeed->toString());
        fclose($f);
        $listNews = [];

        $listNews = $this->_convertNewsHtmlToArray($newsfeed);


        print_r(($listNews));
        die;
//        foreach ($trs as $tr) {
////            echo $tr->toString() . "\n============================\n\n\n";
//        }
        $this->response->withStringBody($trs->toString())->withStatus(200)->send();
        die;
    }

    public function loadMore()
    {
        $more     = $this->getRequest()->getQuery('more');
        $data     = json_decode(file_get_contents(self::BASE_URL . '/ajax/more?more=' . $more), true);
        $more     = Router::url([
            'controller' => 'News',
            'action'     => 'loadMore',
            'more'       => urlencode($data['content']['more'])
        ], true);
        $stream   = join("", $data['stream']);
        $html     = $this->_deobfuscate($stream);
        $html     = \Pharse::str_get_dom($html);
        $listNews = $this->_convertNewsHtmlToArray($html, $more);
        print_r($listNews);
        die;
    }

    public function _convertNewsHtmlToArray($html, $more = null)
    {
        $divs = $html(".hl_time");
        foreach ($divs as $div) {
            $next                           = $div->getNextSibling();
            $listNews[$div->getPlainText()] = [];

            while ($next != null && trim($next->getAttribute('class')) != 'hl_time') {
                if (trim($next->getAttribute('class')) == 'hl') {
                    $aTag                             = $next('.hll')[0];
                    $listNews[$div->getPlainText()][] = [
                        'title'  => $aTag->getPlainText(),
                        'url'    => Router::url([
                            'controller' => 'News',
                            'action'     => 'detail',
                            'url'        => $aTag->getAttribute('href')
                        ], true),
                        'time'   => $next('.time')[0]->getPlainText(),
                        'chanel' => $next('.src-part')[0]->getPlainText()
                    ];
                }
                $next = $next->getNextSibling();
            }
        }
        $listNews['loadmore'] = $more ? $more : Router::url([
            'controller' => 'News',
            'action'     => 'loadMore',
            'more'       => urlencode($html->getAttribute('data-more'))
        ], true);
        return $listNews;
    }

    public function detail()
    {
        $url            = urldecode($this->getRequest()->getQuery('url'));
        $content        = file_get_contents($url);
        $destinationUrl = \Pharse::str_get_dom($content)('#retrieval-msg strong a')[0]->getAttribute('href');

        $thirdPartyContent = \Pharse::file_get_dom(self::WEBSITE_CONVERT . $destinationUrl);
        $content = $thirdPartyContent('rss channel item')[0]('description')[0]->getPlainText();
        $this->response->withStringBody($content)->withStatus(200)->send();
        die;
    }
}