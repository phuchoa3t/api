<?php

namespace App\Controller;

use Cake\Routing\Router;

require ROOT . "/vendor/ressio/pharse/pharse.php";

class SchedulesController extends AppController
{
    const GLOBAL_ESPN_URL    = "http://global.espn.com";
    const ESPN_URL           = "http://espn.com";
    const COMMON             = self::GLOBAL_ESPN_URL . "/football/fixtures";
    const CHAMPIONS_LEAGUE   = self::GLOBAL_ESPN_URL . "/soccer/fixtures/_/league/uefa.champions";
    const UEFA_EUROPA_LEAGUE = self::GLOBAL_ESPN_URL . "/soccer/fixtures/_/league/uefa.europa";
    const PREMIER_LEAGUE     = self::GLOBAL_ESPN_URL . "/football/fixtures/_/league/eng.1";
    const LALIGA             = self::GLOBAL_ESPN_URL . "/football/fixtures/_/league/esp.1";
    const SERIE_A            = self::GLOBAL_ESPN_URL . "/soccer/fixtures/_/league/ita.1";
    const BUNDESLIGA         = self::GLOBAL_ESPN_URL . "/football/fixtures/_/league/ger.1";
    const LIGUE_1            = self::GLOBAL_ESPN_URL . "/football/fixtures/_/league/fra.1";
    const BARCELONA_FIXTURES = self::GLOBAL_ESPN_URL . "/football/team/fixtures/_/id/83/barcelona(Có lựa chọn theo giải đấu)";
    const BARCELONA_RESULT   = self::GLOBAL_ESPN_URL . "/soccer/team/results/_/id/83/barcelona(Có lựa chọn theo mùa bóng)";

    const REAL_MADRID_FIXTURES = self::GLOBAL_ESPN_URL . "/football/team/fixtures/_/id/86/real-madrid";
    const REAL_MADRID_RESULT   = self::ESPN_URL . "/soccer/team/results/_/id/86/real%20madrid";

    const MANCHESTER_UNITED_FIXTURES = self::GLOBAL_ESPN_URL . "/football/team/fixtures/_/id/360/manchester-united";
    const MANCHESTER_UNITED_RESULT   = self::GLOBAL_ESPN_URL . "/soccer/team/results/_/id/360/manchester%20united";

    const CHELSEA_FIXTURES = self::GLOBAL_ESPN_URL . "/football/team/fixtures/_/id/363/chelsea";
    const CHELSEA_RESULT   = self::GLOBAL_ESPN_URL . "/soccer/team/results/_/id/363/chelsea";

    const ARSEAL_FIXTURES = self::GLOBAL_ESPN_URL . "/football/team/fixtures/_/id/359/arsenal";
    const ARSEAL_RESULT   = self::GLOBAL_ESPN_URL . "/soccer/team/results/_/id/359/arsenal";

    const LIVERPOOL_FIXTURES     = self::GLOBAL_ESPN_URL . "/football/team/fixtures/_/id/364/liverpool";
    const LIVERPOOL_RESULT       = self::GLOBAL_ESPN_URL . "/soccer/team/results/_/id/364/liverpool";
    const BAYERN_MUNICH_FIXTURES = self::GLOBAL_ESPN_URL . "/football/team/fixtures/_/id/132/bayern-munich";
    const BAYERN_MUNICH_RESULT   = self::GLOBAL_ESPN_URL . "/soccer/team/results/_/id/132/bayern%20munich";
    const AC_MILAN_FIXTURES      = self::GLOBAL_ESPN_URL . "/football/team/fixtures/_/id/103/ac-milan";
    const AC_MILAN_RESULT        = self::ESPN_URL . "/soccer/team/results/_/id/103/ac%20milan";
    const RESULT                 = self::GLOBAL_ESPN_URL . "/football/team/results/_/id/111/juventus";
    const SUMMARY                = self::GLOBAL_ESPN_URL . "/football/match?gameId=";
    const REPORT                 = self::GLOBAL_ESPN_URL . "/football/report?gameId=";
    const COMMENTARY             = self::GLOBAL_ESPN_URL . "/football/commentary?gameId=";
    const MATCHSTATS             = self::GLOBAL_ESPN_URL . "/football/matchstats?gameId=";
    const LINEUPS                = self::GLOBAL_ESPN_URL . "/football/lineups?gameId=";
    const VIDEO                  = self::GLOBAL_ESPN_URL . "/football/video?gameId=";

    const COMMON_STYLE = '
        <style>
            #gamepackage-odds, #gamepackage-conversation, #gamepackage-game-information, #gamepackage-mobilead {
                display: none!important;
            }
        </style>
    ';

    const FIXTURES      = [
        "http://global.espn.com/football/fixtures",
        "/league/uefa.champions",
        "/league/eng.1",
        "/league/esp.1",
        "/league/ger.1",
        "/league/ita.1",
        "/league/fra.1",
        "/league/uefa.europa"
    ];
    const FIXTURES_TEAM = [
        "http://global.espn.com/football/team/fixtures/_/id/83/barcelona",
        "http://global.espn.com/soccer/team/results/_/id/83/barcelona",
        "http://global.espn.com/football/team/fixtures/_/id/86/real-madrid",
        "http://www.espn.com/soccer/team/results/_/id/86/real%20madrid",
        "http://global.espn.com/football/team/fixtures/_/id/360/manchester-united",
        "http://global.espn.com/soccer/team/results/_/id/360/manchester%20united",
        "http://global.espn.com/football/team/fixtures/_/id/363/chelsea",
        "http://global.espn.com/soccer/team/results/_/id/363/chelsea",
        "http://global.espn.com/football/team/fixtures/_/id/359/arsenal",
        "http://global.espn.com/soccer/team/results/_/id/359/arsenal",
        "http://global.espn.com/football/team/fixtures/_/id/364/liverpool",
        "http://global.espn.com/soccer/team/results/_/id/364/liverpool"
    ];

    const CHARTS = [
        "http://global.espn.com/football/table/_/league/uefa.champions",
        "http://global.espn.com/football/table/_/league/eng.1",
        "http://global.espn.com/football/table/_/league/esp.1",
        "http://global.espn.com/football/table/_/league/ger.1",
        "http://global.espn.com/football/table/_/league/ita.1",
        "http://global.espn.com/football/table/_/league/fra.1",
        "http://global.espn.com/soccer/table/_/league/uefa.europa",
    ];

    public function fixturesTeam($selectId = 0, $league = '', $season = '')
    {
        $url = self::FIXTURES_TEAM[$selectId];
        if ($league || $season) {
            $url = substr($url, 0, strrpos($url, '/'));
            if ($league) {
                $url .= '/league/' . $league;
            }
            if ($season) {
                $url .= '/season/' . $season;
            }
        }

        $html = \Pharse::file_get_dom($url);
        $fixtures = $this->_convertFixturesHtmlToArray($html);
        $this->response->withStringBody(json_encode($fixtures))->withStatus(200)->send();
        die;
    }

    public function fixtures($os = '', $selectId = 0, $date = '')
    {
        if ($os == 'ios') {
            if ($selectId == 0) {
                $url = self::FIXTURES[$selectId];
                if ($date) {
                    $url .= '/_/date/' . $date;
                }
            } else {
                $url = self::FIXTURES[0];
                if ($date) {
                    $url .= '/_/date/' . $date;
                } else {
                    $url .= '/_';
                }
                $url .= self::FIXTURES[$selectId];
            }
        } else {
            $url = $this->getRequest()->getQuery('url');
        }
        if (!$url) {
            return false;
        }
        $html = \Pharse::file_get_dom($url);
//
//        $f = fopen("a.txt", "w");
//        fwrite($f, $newsfeed->toString());
//        fclose($f);
        $fixtures = $this->_convertFixturesHtmlToArray($html);
        $this->response->withStringBody(json_encode($fixtures))->withStatus(200)->send();
        die;
    }

    public function _convertFixturesHtmlToArray($html, $more = null)
    {
        if (!isset($html("#sched-container")[0])) {
            // for special team
            $htmlString = $html->getPlainText();
            $boxes      = $html('.Table__fixtures');
            if (count($boxes) <= 0) {
                $boxes = $html('.Table__results');
            }
            $fixtures['List_All'] = [];
            foreach ($boxes as $box) {
                $caption = $box('.Table2__Title')[0]->getPlainText();

                $item = [
                    'title'   => $caption,
                    'matches' => []
                ];

                $trs = $box('table')[0]('table')[0]('table')[0]('tbody')[0]('tr');

                foreach ($trs as $tr) {
                    $matchInfo = [
                        'team1' => [
                            'name' => $tr('td')[1]('.Table2__Team')[0]->getPlainText(),
                            'logo' => $tr('td')[2]('source')[0]->getAttribute('srcSet')
                        ],
                        'team2' => [
                            'name' => $tr('td')[3]('.Table2__Team')[0]->getPlainText(),
                            'logo' => $tr('td')[2]('source')[1]->getAttribute('srcSet')
                        ],
                    ];

                    $matchStatus = '';

                    $score = '';
                    $td4   = $tr('td')[4]('a')[0]->getPlainText();
                    if (strpos(strtolower($td4), 'am') !== false ||
                        strpos(strtolower($td4), 'pm') !== false) {

                    } else {
                        $matchStatus = $td4;
                    }

                    $score            = $tr('td')[2]('.score')[0]('a')[1]->getPlainText();
                    $summaryOriginUrl = $tr('td')[2]('.score')[0]('a')[1]->getAttribute('href');

                    preg_match('/\d+/', $summaryOriginUrl, $matches);
                    $matchInfo['gameId'] = isset($matches[0]) ? $matches[0] : '';

                    $matchInfo['score']        = strlen($score) > 2 ? $score : '';
                    $matchInfo['match_status'] = $matchStatus;
                    preg_match('/\{\"id\"\:\"' . $matchInfo['gameId'] . '\",\"date\"\:\"(.*)\"/', $htmlString, $time);
                    $time                     = isset($time[1]) ? $time[1] : '';
                    $timeExplode              = explode('"', $time);
                    $matchInfo['time']        = isset($timeExplode[0]) ? $timeExplode[0] : '';
                    $matchInfo['date']        = $tr('.matchTeams')[0]->getPlainText();
                    $matchInfo['competition'] = $tr('td')[5]('span')[0]->getPlainText();

                    $item['matches'][] = $matchInfo;
                }
                $fixtures['List_All'][] = $item;
            }
            return $fixtures;
        }
        $captions = $html("#sched-container")[0]('.table-caption');

        $fixtures['List_All'] = [];
        foreach ($captions as $caption) {
            $next = $caption->getNextSibling();
            $item = [
                'title'   => $caption->getPlainText(),
                'matches' => []
            ];

            while ($next != null && trim($next->getAttribute('class')) != 'table-caption') {
                $matches = $next('tr');

                foreach ($matches as $match) {
                    if (count($match('td')) < 3) {
                        continue;
                    }
                    $matchInfo = [
                        'team1' => [
                            'name' => $match('.team-name')[0]('span')[0]->getPlainText(),
                            'logo' => $match('.team-logo')[0]('img')[0]->getAttribute('src')
                        ],
                        'team2' => [
                            'name' => $match('.team-name')[1]('span')[0]->getPlainText(),
                            'logo' => $match('.team-logo')[1]('img')[0]->getAttribute('src')
                        ]
                    ];

                    $record      = $match('.record')[0]('a')[0]->getPlainText();
                    $td3         = $match('td')[2]->getAttribute('data-date');
                    $matchStatus = '';
                    $time        = '';
                    $score       = '';
                    if (!strpos($record, "-") === false) {
                        $score = $record;
                    } else {
                        $matchStatus = $record;
                    }
                    if ($td3 || $match('td')[2]('a')[0]->getPlainText() == 'TBD') {
                        $time = $td3 ? $td3 : $match('td')[2]('a')[0]->getPlainText();
                    } else {
                        $matchStatus = $match('td')[2]('a')[0]->getPlainText();
                    }
                    $matchInfo['score']        = strlen($score) > 2 ? $score : '';
                    $matchInfo['match_status'] = $matchStatus;
                    $matchInfo['time']         = $time;

                    $summaryOriginUrl = $match('.record')[0]('a')[0]->getAttribute('href');

                    preg_match('/\d+/', $summaryOriginUrl, $matches);
                    $matchInfo['gameId'] = isset($matches[0]) ? $matches[0] : '';


                    $item['matches'][] = $matchInfo;
                }

                $next = $next->getNextSibling();
            }
            $fixtures['List_All'][] = $item;
        }
        return $fixtures;
    }

    public function chart($os = '', $selectId = 0, $year = '')
    {
        if ($os == 'ios') {
            $url = self::CHARTS[$selectId] ?? "";
            if ($url && $year) {
                $url .= '/season/' . $year;
            }
        } else {
            $url = $this->getRequest()->getQuery('url');
        }
        if (!$url) {
            return false;
        }
        $html = \Pharse::file_get_dom($url);

        $table  = $html('.page-container')[0]('table')[0];
        $charts = [];
        $i      = 1;

        $left  = $table('.v-top')[0];
        $right = $table('.v-top')[1];

        $teams  = $left('.team-link');
        $points = $right('.Table2__tbody tr');

        foreach ($points as $k => $point) {
            if (preg_match('/header/',
                $point->getAttribute('class'))) {
                unset($points[$k]);
            }
        }
        $points = array_values($points);
        foreach ($teams as $k => $team) {
            $img      = htmlspecialchars_decode($team('img')[0]->getAttribute('src'));
            $img      = preg_replace('/h=\d+/', 'h=50', $img);
            $img      = preg_replace('/w=\d+/', 'w=50', $img);
            $charts[] = [
                'stt'  => $i++,
                'logo' => $img,
                'name' => $team('.hide-mobile')[0]->getPlainText(),
                'gp'   => $points[$k]('td')[0]->getPlainText(),
                'w'    => $points[$k]('td')[1]->getPlainText(),
                'd'    => $points[$k]('td')[2]->getPlainText(),
                'l'    => $points[$k]('td')[3]->getPlainText(),
                'f'    => $points[$k]('td')[4]->getPlainText(),
                'a'    => $points[$k]('td')[5]->getPlainText(),
                'gd'   => $points[$k]('td')[6]->getPlainText(),
                'p'    => $points[$k]('td')[7]->getPlainText(),
            ];
        }

        $this->response->withStringBody(json_encode($charts))->withStatus(200)->send();
        die;
    }

    public function privacy()
    {
        $this->layout = false;
    }

    public function policy()
    {
        $this->layout = false;
    }

    public function policydn()
    {
        $this->layout = false;
    }

    public function summary($gameId)
    {
        $server_output = $this->_curl(self::SUMMARY . $gameId);
        $html          = \Pharse::str_get_dom($server_output, true);
        $style         = '
            <style>
                #gamepackage-column-wrap .col-one, #header-wrapper , #custom-nav, 
                .ad-banner-wrapper, #gamepackage-outbrain, #gamepackage-conversation,
                .sub-module.betting-odds{
                    display: none !important;
                }
                #gamepackage-column-wrap .col-three, #gamepackage-game-information {
                    display: none !important;
                }
                
                #pane-main, #main-container {
                    padding-top: 0px!important;
                }
                .slick-slide {
                height: unset!important;
                }
            </style>
        ';
        $script        = '
            <script>
                $(function(){
                    $("a").click(function() { 
                        if ($(this).attr("href") && $(this).attr("href").indexOf("' . BASEURL . '") != -1
                            || $(this).hasClass("button-filter-alt")
                        ) {
                            return true;        
                        }
                        
                        return false; 
                    });
                })
            </script>
        ';


        $html = preg_replace('/href=\"\//', 'href="' . BASEURL . '/', $html);


        $html = preg_replace('/a.espncdn.com\/i\/teamlogos/', 'a.espncdn.com/combiner/i?img=/i/teamlogos', $html);

        $this->response->withStringBody(self::COMMON_STYLE . $style . $html . $script)->withStatus(200)->send();
        die;
    }

    public function categories($gameId)
    {
        $html = \Pharse::file_get_dom(self::SUMMARY . $gameId, true, true);

        $cates = $html('#global-nav-tertiary .link-text');

        $arrCates = [];
        foreach ($cates as $cate) {
            $arrCates[] = $cate->getPlainText();
        }

        $this->response->withStringBody(json_encode($arrCates))->withStatus(200)->send();
        die;
    }

    public function report($gameId = null)
    {
        if (!$gameId) {
            $gameId = $this->getRequest()->getQuery('gameId');
        }
        $server_output = $this->_curl(self::REPORT . $gameId);

        $html = \Pharse::str_get_dom($server_output);


        $style = '
            <style>
                #article-feed .article .article-body aside.inline-photo {
                    height: auto!important
                }
                #pane-main, #main-container {
                    padding-top: 0px!important;
                }
                #article-feed .article .article-social, .article-meta .authors>li .author, .ad-300, 
                .article-footer, .col-c, .ad-banner-wrapper, #custom-nav,#header-wrapper{
                    display: none!important;
                }
            </style>
        ';

        $this->response->withStringBody(self::COMMON_STYLE . $style . $html)->withStatus(200)->send();
        die;
    }

    public function commentary($gameId = null)
    {
        if (!$gameId) {
            $gameId = $this->getRequest()->getQuery('gameId');
        }
        $server_output = $this->_curl(self::COMMENTARY . $gameId);
        $html          = \Pharse::str_get_dom($server_output);
        $html('#header-wrapper')[0]->delete();
        $html('#custom-nav')[0]->delete();
        $html('.ad-banner-wrapper')[0]->delete();
        $html('#gamepackage-outbrain')[0]->delete();
        $style = '
            <style>
                #gamepackage-column-wrap .col-one {
                    display: none !important;
                }
                #gamepackage-column-wrap .col-three {
                    display: none !important;
                }
                
                #pane-main, #main-container {
                    padding-top: 0px!important;
                }
            </style>
        ';

        $this->response->withStringBody(self::COMMON_STYLE . $style . $html)->withStatus(200)->send();
        die;
    }

    public function matchstats($gameId = null)
    {
        if (!$gameId) {
            $gameId = $this->getRequest()->getQuery('gameId');
        }
        $html = \Pharse::file_get_dom(self::MATCHSTATS . $gameId);
        $html('#header-wrapper')[0]->delete();
        $html('#custom-nav')[0]->delete();
        $html('.ad-banner-wrapper')[0]->delete();

        $html('.col-c')[0]->delete();
        $html('#gamepackage-outbrain')[0]->delete();
        $style = '
            <style>
                #gamepackage-column-wrap .col-one {
                    display: none !important;
                }
                #gamepackage-column-wrap .col-three {
                    display: none !important;
                }
                
                #pane-main, #main-container {
                    padding-top: 0px!important;
                }
            </style>
        ';

        $script = '
            <script>
                $(function(){
                    $("a").click(function() { 
                        if ($(this).attr("href").indexOf("' . BASEURL . '") != -1) {
                            return true;        
                        }
                        
                        return false; 
                    });
                })
            </script>
        ';

        $this->response->withStringBody(self::COMMON_STYLE . $style . $html . $script)->withStatus(200)->send();
        die;
    }

    public function lineups($gameId)
    {
        $html  = \Pharse::file_get_dom(self::LINEUPS . $gameId);
        $style = '
            <style>
                #gamepackage-column-wrap .col-one, #header-wrapper, #custom-nav, .ad-banner-wrapper, .col-c,
                 #gamepackage-outbrain {
                    display: none !important;
                }
                #gamepackage-column-wrap .col-three {
                    display: none !important;
                }
                
                #pane-main, #main-container {
                    padding-top: 0px!important;
                }
            </style>
        ';

        $script = '
            <script>
                $(function(){
                    $("a").click(function() { 
                        if ($(this).attr("href") && $(this).attr("href").indexOf("' . BASEURL . '") != -1
                            || $(this).hasClass("button-filter-alt")
                        ) {
                            return true;        
                        }
                        
                        return false; 
                    });
                })
            </script>
        ';
        $this->response->withStringBody(self::COMMON_STYLE . $style . $html . $script)->withStatus(200)->send();
        die;
    }

    public function test()
    {

    }

    public function video($gameId)
    {

        $server_output = $this->_curl(self::VIDEO . $gameId);


        $html = \Pharse::str_get_dom($server_output);
        $html('#header-wrapper')[0]->delete();
        $html('#custom-nav')[0]->delete();
        $html('.ad-banner-wrapper')[0]->delete();

        $html('.col-c')[0]->delete();


        $style = '
           <style>
           .gamepackage #global-viewport #pane-main.details-header, .gamepackage #global-viewport #pane-main.details-footer {
                padding-top: 0px!important;
           }
           </style>
        ';

        $this->response->withStringBody(self::COMMON_STYLE . $style . $html)->withStatus(200)->send();
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