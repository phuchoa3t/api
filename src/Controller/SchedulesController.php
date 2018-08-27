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
    const FIXTURES               = self::ESPN_URL . "/soccer/team/fixtures/_/id/111/juventus";
    const RESULT                 = self::GLOBAL_ESPN_URL . "/football/team/results/_/id/111/juventus";


    public function ()
    {

    }
}