<?php

$hostname_visitors = "localhost";
$database_visitors = "datatu6_solar";
$username_visitors = "datatu6_solaruser";
$password_visitors = "S2LKDxB7yfDDY8w";
$dbh =null;

/* **
    $visitors = mysql_connect($hostname_visitors, $username_visitors,
        $password_visitors) or rigger_error(mysql_error(),E_USER_ERROR);
*/

try {
    $dbh = new PDO("mysql:host=$hostname_visitors; dbname=$database_visitors", $username_visitors, $password_visitors);
}
catch(PDOException $e)
{
   echo $e->getMessage();
}

function getBrowserType () {
  $u_agent = $_SERVER['HTTP_USER_AGENT'];
  $bname = 'Unknown';
  $platform = 'Unknown';
  $version= "";

  // First get the platform?
  if (preg_match('/linux/i', $u_agent)) {
    $platform = 'linux';
  } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
    $platform = 'mac';
  } elseif (preg_match('/windows|win32/i', $u_agent)) {
    $platform = 'windows';
  }

  // Next get the name of the useragent yes seperately and for good reason
  if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
    $bname = 'Internet Explorer';
    $ub = "MSIE";
  } elseif(preg_match('/Firefox/i',$u_agent)) {
    $bname = 'Mozilla Firefox';
    $ub = "Firefox";
  } elseif(preg_match('/OPR/i',$u_agent)) {
    $bname = 'Opera';
    $ub = "Opera";
  } elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)) {
    $bname = 'Google Chrome';
    $ub = "Chrome";
  } elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)) {
    $bname = 'Apple Safari';
    $ub = "Safari";
  } elseif(preg_match('/Netscape/i',$u_agent)) {
    $bname = 'Netscape';
    $ub = "Netscape";
  } elseif(preg_match('/Edge/i',$u_agent)) {
    $bname = 'Edge';
    $ub = "Edge";
  } elseif(preg_match('/Trident/i',$u_agent)) {
    $bname = 'Internet Explorer';
    $ub = "MSIE";
  }

  // Finally get the correct version number
  $known = array('Version', $ub, 'other');
  $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
  if (!preg_match_all($pattern, $u_agent, $matches)) {
    // We have no matching number just continue
  }

  // See how many we have
  $i = count($matches['browser']);
  if ($i != 1) {
    // Ok. We will have two since we are not using 'other' argument yet
    // See if version is before or after the name
    if (strripos($u_agent,"Version") < strripos($u_agent,$ub)) {
        $version= $matches['version'][0];
    } else {
        $version= $matches['version'][1];
    }
  } else {
    $version= $matches['version'][0];
  }

  // Check if we have a number
  if ($version==null || $version=="") { $version="?"; }

  $ua = array(
    'userAgent' => $u_agent,
    'name'      => $bname,
    'version'   => $version,
    'platform'  => $platform,
    'pattern'    => $pattern
  );

  $browser = $ua['name'] . " " . $ua['version'] . " " .$ua['platform'];
  return $browser;

}

function selfURL() {
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);

    return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
}

function strleft($s1, $s2) {
    return substr($s1, 0, strpos($s1, $s2));
}

function paginate($start,$limit,$total,$filePath,$otherParams) {
    global $lang;

    $allPages = ceil($total/$limit);
    $currentPage = floor($start/$limit) + 1;

    $pagination = "";
    if ($allPages>10) {
        $maxPages = ($allPages>9) ? 9 : $allPages;

        if ($allPages>9) {
            if ($currentPage>=1&&$currentPage<=$allPages) {
                $pagination .= ($currentPage>4) ? " ... " : " ";

                $minPages = ($currentPage>4) ? $currentPage : 5;
                $maxPages = ($currentPage<$allPages-4) ? $currentPage : $allPages - 4;

                for($i=$minPages-4; $i<$maxPages+5; $i++) {
                    $pagination .= ($i == $currentPage) ? "<a href=\"#\"
                    class=\"current\">".$i."</a> " : "<a href=\"".$filePath."?
                    start=".(($i-1)*$limit).$otherParams."\">".$i."</a> ";
                }
                $pagination .= ($currentPage<$allPages-4) ? " ... " : " ";
            } else {
                $pagination .= " ... ";
            }
        }
    } else {
        for($i=1; $i<$allPages+1; $i++) {
        $pagination .= ($i==$currentPage) ? "<a href=\"#\" class=\"current\">".$i."</a> "
        : "<a href=\"".$filePath."?start=".(($i-1)*$limit).$otherParams."\">".$i."</a> ";
        }
    }

    if ($currentPage>1) $pagination = "<a href=\"".$filePath."?
    start=0".$otherParams."\">FIRST</a> <a href=\"".$filePath."?
    start=".(($currentPage-2)*$limit).$otherParams."\"><</a> ".$pagination;
    if ($currentPage<$allPages) $pagination .= "<a href=\"".$filePath."?
    start=".($currentPage*$limit).$otherParams."\">></a> <a href=\"".$filePath."?
    start=".(($allPages-1)*$limit).$otherParams."\">LAST</a>";

    echo '<style>
            .center {
            text-align: center;
            }

            .pagination {
            display: inline-block;
            }

            .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
            margin: 0 4px;
            }

            .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
            }

            .pagination a:hover:not(.active) {background-color: #ddd;}
        </style>';

	echo '
	<div class="center">
	<div class="pagination">' . $pagination . '</div></div>';
}
