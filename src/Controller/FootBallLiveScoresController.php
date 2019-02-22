<?php

namespace App\Controller;

use Cake\Routing\Router;

require ROOT . "/vendor/ressio/pharse/pharse.php";

class FootBallLiveScoresController extends AppController
{
    const VIDEO    = "https://hoofoot.com";
    const NEWS_URL = [
        "https://www.goal.com/en/news/{PAGE}",
        "https://www.goal.com/en/uefa-champions-league/{PAGE}/4oogyu6o156iphvdvphwpck10",
        "https://www.goal.com/en/premier-league/{PAGE}/2kwbbcootiqqgmrzs6o5inle5",
        "https://www.goal.com/en/primera-divisi%C3%B3n/{PAGE}/34pl8szyvrbwcmfkuocjm3r6t",
        "https://www.goal.com/en/serie-a/{PAGE}/1r097lpxe0xn03ihb7wi98kao",
        "https://www.goal.com/en/bundesliga/{PAGE}/6by3h89i2eykc341oz7lv1ddd",
        "https://www.goal.com/en/ligue-1/{PAGE}/dm5ka0os1e3dxcp3vh05kmp33",
        "https://www.goal.com/en/uefa-europa-league/{PAGE}/4c1nfi2j1m731hcay25fcgndq",
        "https://www.goal.com/en/transfer-news/{PAGE}"
    ];

    public function listNews($selectID = 0, $page = 1)
    {
        $url = self::NEWS_URL[$selectID] ?? '';
        $url = preg_replace('/{PAGE}/', $page, $url);

        if (!$url) {
            die;
        }
        $html = \Pharse::file_get_dom($url);
        $news = $html("article");

        $response['List_All'] = [];

        foreach ($news as $k => $new) {
            $date = $new('time')[0]->getAttribute('datetime');

            $img    = $new('img')[1]->getAttribute('src');
            $img    = preg_replace('/quality=\d+/', 'quality=100', $img);
            $img    = preg_replace('/h=\d+/', 'h=100', $img);
            $response['List_All'][] = [
                'image'      => $img,
                'title'      => $new('.title-wrapper')[0]('h3')[0]->getPlainText(),
                'time'       => $date,
                'new-detail' => Router::url([
                    'action' => 'newDetail',
                    'url'    => "https://www.goal.com" . $new('a')[0]->getAttribute('href')
                ], true)
            ];
        }

        $response['loadmore']   = Router::url([
            'action' => 'listNews',
            $selectID,
            $page + 1
        ], true);

        $this->response->withStringBody(json_encode($response))->withStatus(200)->send();
        die;
    }

    public function newDetail()
    {
        $url  = $this->getRequest()->getQuery('url');
        $url  = urldecode($url);
        $url  = urldecode($url);
        $url  = urldecode($url);
        $url  = preg_replace('/\s/', '+', $url);
        $html = $this->_curl($url);
        $html = \Pharse::str_get_dom($html);
        foreach ($html('head')[0]('script') as $script) {
            if ($script->getPlainText() != '') {
                $script->delete();
            }
        }
        $custom = "<base href ='https://www.goal.com'>";
        $style  = "
            <style>
                .widget-header,
                 .layout-article .scroll-group .article-content aside,
                 .widget-taboola,
                 .widget-article .social-container,
                 footer,
                 pre,
                 .widget-inline-editors-picks,
                 .tags-list,
                 .actions-bar,
                 .smart-banner
                 {
                    display: none !important;
                }
                body, .layout-master .page-container-bg {
                    background: white !important;
                }
            </style>
        ";
        $html   = $html->toString();
        $script        = '
            <script>
                var aTags = document.getElementsByTagName("a"),
                    atl = aTags.length,
                    i;
            
                for (i = 0; i < atl; i++) {
                    aTags[i].href="javascript:void(0)"
                }
            </script>
        ';

        echo $custom . $style . $html . $script;
        die;
    }

    public function videos($page = 1)
    {
        $html = $this->_curl(self::VIDEO . '?page=' . $page);
        $html = \Pharse::str_get_dom($html);

        $response = ['List_All' => []];
        foreach ($html('#port') as $video) {
            $detailUrl              = $video('#gggg')[0]('a')[0]->getAttribute('href');
            $detailUrl              = self::VIDEO . preg_replace('/.\//', '/', $detailUrl);
            $response['List_All'][] = [
                'title'  => $video('#gggg')[0]('h2')[0]->getPlainText(),
                'image1' => $video('#cocog')[0]('img')[0]->getAttribute('src'),
                'image2' => $video('#cocog')[0]('img')[1]->getAttribute('src'),
                'time'   => $video('#cocog')[0]('font')[0]->getPlainText(),
                'link'   => $detailUrl
            ];
        }
        $response['loadmore'] = Router::url([
            'action' => 'videos',
            $page + 1
        ], true);
        $this->response->withStringBody(json_encode($response))->withStatus(200)->send();
        die;
    }

    public function getVideoUrl()
    {
        $url    = $this->getRequest()->getQuery('url');
        $url    = urldecode($url);
        $url    = urldecode($url);
        $url    = urldecode($url);
        $url    = preg_replace('/\s/', '+', $url);
        $detail = $this->_curl($url);
        $detail = \Pharse::str_get_dom($detail);
        $link   = 'https:' . $detail('#videoz')[0]('iframe')[0]->getAttribute('src');
        $this->response->withStringBody(json_encode(['link' => $link]))->withStatus(200)->send();
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
        $this->response->withStringBody(json_encode($listNews))->withStatus(200)->send();
        die;
    }

    public function _convertNewsHtmlToArray($html, $more = null)
    {
        $divs                 = $html(".hl_time");
        $listNews['List_All'] = [];
        foreach ($divs as $div) {
            $next = $div->getNextSibling();

            $item = [
                'title'      => $div->getPlainText(),
                'SubCatgory' => []
            ];

            while ($next != null && trim($next->getAttribute('class')) != 'hl_time') {
                if (preg_match('/^hl([^a-z])*$/', trim($next->getAttribute('class')))) {
                    $aTag = $next('.hll')[0];

                    $item['SubCatgory'][] = [
                        'title'  => $aTag->getPlainText(),
                        'url'    => Router::url([
                            'controller' => 'News',
                            'action'     => 'detail',
                            'url'        => $aTag->getAttribute('href')
                        ], true),
                        'time'   => $next('.time')[0]->getAttribute('data-time'),
                        'chanel' => $next('.src-part')[0]->getPlainText()
                    ];
                }
                $next = $next->getNextSibling();
            }
            $listNews['List_All'][] = $item;
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
        $item    = $thirdPartyContent('rss channel item')[0];
        $content = $item('description')[0]->getPlainText();
        $title   = "<h2>" . $item('title')[0]->getPlainText() . "</h2>";

        $content = str_replace('<strong><a href="https://blockads.fivefilters.org">Let\'s block ads!</a></strong> <a href="https://blockads.fivefilters.org/acceptable.html">(Why?)</a></p>', '', $content);
        $this->response->withStringBody($title . $content)->withStatus(200)->send();
        die;
    }

    private function _curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $rqheaders = getallheaders();
        $headers   = [];
        foreach ($rqheaders as $key => $val) {
            if (strpos($val, ":") != false
                || preg_match('/host|Host|Accept\-Encoding/', $key)
            ) {
                continue;
            }
            $headers[] = $key . ':' . $val;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        return curl_exec($ch);
    }
}

