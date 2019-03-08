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
        "https://www.goal.com/en/transfer-news/{PAGE}",
        "https://www.goal.com/en/team/manchester-united/{PAGE}/6eqit8ye8aomdsrrq0hk3v7gh",
        "https://www.goal.com/en/team/chelsea/{PAGE}/9q0arba2kbnywth8bkxlhgmdr",
        "https://www.goal.com/en/team/arsenal/{PAGE}/4dsgumo7d4zupm2ugsvm4zm4d",
        "https://www.goal.com/en/team/liverpool/{PAGE}/c8h9bw1l82s06h77xxrelzhur",
        "https://www.goal.com/en/team/barcelona/{PAGE}/agh9ifb2mw3ivjusgedj7c3fe",
        "https://www.goal.com/en/team/real-madrid/{PAGE}/3kq9cckrnlogidldtdie2fkbl"
    ];

    public function listNews($selectID = 0, $page = 1)
    {
        $response['List_All'] = [];

        if ($page > 1) {
            $this->response->withStringBody(json_encode($response))->withStatus(200)->send();
            die;
        }

        $imgs = [
            0 => 'https://images.performgroup.com/di/library/GOAL/22/cc/justin-kluivert-roma_kltwl4dlf0c716oufpfp0n6j6.jpg?t=1314052441&amp;quality=100&amp;h=100',
            1 => 'https://images.performgroup.com/di/library/GOAL/c/9b/victor-lindelof-manchester-united-2018-19_27zeze5x5tlb13fyexbcv0gii.jpg?t=-1114199624&amp;quality=100&amp;h=100',
            2 => 'https://images.performgroup.com/di/library/GOAL/39/f3/gianluigi-buffon-psg-2018-19_1o3nwzixr61lf1llqit9l7anbo.jpg?t=1374486505&amp;quality=100&amp;h=100',
            3 => 'https://images.performgroup.com/di/library/omnisport/2/77/tonikroos-cropped_1vn9uz5kmi9oc11r9k29y2n89y.jpg?t=1360210313&amp;quality=100&amp;h=100',
            4 => 'https://images.performgroup.com/di/library/GOAL/d7/cc/tahith-chong-manchester-united-2018-19_1scvp383huos618yzdlecd9cpt.jpg?t=520633608&amp;quality=100&amp;h=100',
            5 => 'https://images.performgroup.com/di/library/GOAL/e4/3e/marco-reus-borussia-dortmund-tottenham-champions-league-2019_eebibfms39yb1fvtygffj2qka.jpg?t=1357804313&amp;quality=100&amp;h=100',
            6 => 'https://images.performgroup.com/di/library/GOAL/4e/59/harry-kane-tottenham-2018-19_ro2bvd6ri4jp1j9upj0umc9at.jpg?t=1333876977&amp;quality=100&amp;h=100',
            7 => 'https://images.performgroup.com/di/library/GOAL/9/9a/daley-blind-ajax-2018-19_1k430iaoa3gnw1ehwe4g25yarw.jpg?t=1339468849&amp;quality=100&amp;h=100',
            8 => 'https://images.performgroup.com/di/library/omnisport/b0/75/pochettino-cropped_l0j8nshx97an1djxyjwc3g1k1.jpg?t=1340729929&amp;quality=100&amp;h=100',
            9 => 'https://images.performgroup.com/di/library/omnisport/36/53/eriktenhag-cropped_1219cl925e4s118n00jf7lbxq2.jpg?t=1340542377&amp;quality=100&amp;h=100',
            10 => 'https://images.performgroup.com/di/library/GOAL/86/3d/sergio-ramos-yellow-card-real-madrid-ajax-champions-league_m6a5kk0ufour1du6x4iaf400l.jpg?t=-321733024&amp;quality=100&amp;h=100',
        ];

        $titles = [
            0 => 'Furious Mauricio Pochettino to miss Southampton and Liverpool games after receiving 2 match ban',
            1 => 'Celtic driven on by fans’ anger at Brendan Rodgers – John Kennedy',
            2 => 'Benitez May Go After Former Midfield Target This Summer',
            3 => 'Pochettino suspended: Mauricio Pochettino has been fined £10,000 and will serve a two-match touchline ban',
            4 => 'Pochettino fined and banned by FA for confrontation with Mike Dean',
            5 => '‘I think he shows promise’, ‘It’s just so strange’ – These Everton fans discuss 5ft 7in star’s future',
            6 => 'Cult hero\'s comments on 2011/12 sum up lingering problem at Newcastle',
            7 => 'Liverpool handed Premier League title race boost as Spurs boss Mauricio Pochettino handed ban',
            8 => 'Book your place for exclusive Crystal Palace Golf Day now',
            9 => 'A Gallowgate End-sized banner\': Wor Flags\' most-ambitious NUFC project revealed - and how to&#133;',
            10 => 'Laughable Peter Kenyon Millionaire club plan to buy Newcastle United crops up again in new report',
        ];

        $date = new \DateTime();
        $times = [
            $date->format('Y-m-d'). 'T'.$date->modify('-1hour')->modify('-30minute')->format('h').':'.$date->format('i').':18+00:00',
            $date->format('Y-m-d'). 'T'.$date->modify('-1hour')->modify('-30minute')->format('h').':'.$date->format('i').':18+00:00',
            $date->format('Y-m-d'). 'T'.$date->modify('-1hour')->modify('-30minute')->format('h').':'.$date->format('i').':18+00:00',
            $date->format('Y-m-d'). 'T'.$date->modify('-1hour')->modify('-30minute')->format('h').':'.$date->format('i').':18+00:00',
            $date->format('Y-m-d'). 'T'.$date->modify('-1hour')->modify('-30minute')->format('h').':'.$date->format('i').':18+00:00',
            $date->format('Y-m-d'). 'T'.$date->modify('-1hour')->modify('-30minute')->format('h').':'.$date->format('i').':18+00:00',
            $date->format('Y-m-d'). 'T'.$date->modify('-1hour')->modify('-30minute')->format('h').':'.$date->format('i').':18+00:00',
            $date->format('Y-m-d'). 'T'.$date->modify('-1hour')->modify('-30minute')->format('h').':'.$date->format('i').':18+00:00',
            $date->format('Y-m-d'). 'T'.$date->modify('-1hour')->modify('-30minute')->format('h').':'.$date->format('i').':18+00:00',
            $date->format('Y-m-d'). 'T'.$date->modify('-1hour')->modify('-30minute')->format('h').':'.$date->format('i').':18+00:00',
            $date->format('Y-m-d'). 'T'.$date->modify('-1hour')->modify('-30minute')->format('h').':'.$date->format('i').':18+00:00',
        ];
        for ($i = 0; $i <= 10; $i++) {
            $response['List_All'][] = [
                'image'      => $imgs[$i],
                'title'      => $titles[$i],
                'time'       => $times[$i],
                'new-detail' => Router::url([
                    'action' => 'newDetail',
                    'id'    => $i
                ], true)
            ];
        }
        $response['loadmore'] = Router::url([
            'action' => 'listNews',
            $selectID,
            $page + 1
        ], true);

        $this->response->withStringBody(json_encode($response))->withStatus(200)->send();
        die;

        $url = self::NEWS_URL[$selectID] ?? '';
        $url = preg_replace('/{PAGE}/', $page, $url);

        if (!$url) {
            die;
        }
        $html = shell_exec('curl ' . $url);

        $html = \Pharse::str_get_dom($html);
        $news = $html("article");

        $response['List_All'] = [];

        foreach ($news as $k => $new) {
            $date = $new('time')[0]->getAttribute('datetime');

            $img                    = $new('img')[1]->getAttribute('src');
            $img                    = preg_replace('/quality=\d+/', 'quality=100', $img);
            $img                    = preg_replace('/h=\d+/', 'h=100', $img);
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

        $response['loadmore'] = Router::url([
            'action' => 'listNews',
            $selectID,
            $page + 1
        ], true);

        $this->response->withStringBody(json_encode($response))->withStatus(200)->send();
        die;
    }

    public function newDetail()
    {
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
                 .widget-smart-banner,
                 aside,
                 .social-container
                 {
                    display: none !important;
                }
                body, .layout-master .page-container-bg {
                    background: white !important;
                }
                body { text-align: justify;} img { max-width: 100%; height:auto !important;} video {max-width: 100%; height:auto !important;}
            </style>
        ";

        $html   = preg_replace('/\<\~root\~\>/', '', $html);
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
        $id  = $this->getRequest()->getQuery('id');
        $detail = $this->render('detail' . $id, false);
        echo $custom. $style. $detail . $script;die;
        echo $id;die;



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
                 .widget-smart-banner,
                 aside,
                 .social-container
                 {
                    display: none !important;
                }
                body, .layout-master .page-container-bg {
                    background: white !important;
                }
            </style>
        ";
        $html   = $html->toString();
        $html   = preg_replace('/\<\~root\~\>/', '', $html);
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

        echo $custom . $style . $html . $script;
        die;
    }

    public function videos($page = 1)
    {
        $html = $this->_curl(self::VIDEO . '?page=' . $page, false);
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
        $detail = $this->_curl($url, false);
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

    private function _curl($url, $useShell = true)
    {
        if ($useShell) {
            return shell_exec('curl ' . $url);
        }
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

