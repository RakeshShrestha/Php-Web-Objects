<?php

/**
 # Copyright Rakesh Shrestha (rakesh.shrestha@gmail.com)
 # All rights reserved.
 #
 # Redistribution and use in source and binary forms, with or without
 # modification, are permitted provided that the following conditions are
 # met:
 #
 # Redistributions must retain the above copyright notice.
 */
function req()
{
    return Request::getContext();
}

function res()
{
    return Response::getContext();
}

function db()
{
    return DB::getContext();
}

function setCurrentUser(array &$userdata = array())
{
    Session::getContext(SESS_TYPE)->set('authUser', $userdata);
}

function getCurrentUser()
{
    return Session::getContext(SESS_TYPE)->get('authUser');
}

function getCurrentUserID()
{
    $authUser = getCurrentUser();
    return isset($authUser['id']) ? $authUser['id'] : '';
}

function getCurrentUserType()
{
    $authUser = getCurrentUser();
    return isset($authUser['perms']) ? $authUser['perms'] : '';
}

function getUrl($path = null)
{
    if (PATH_URI != '/') {
        return SITE_URI . PATH_URI . '/' . $path;
    } else {
        return SITE_URI . '/' . $path;
    }
}

function clean($string = null)
{
    return strip_tags(mb_trim($string));
}

function cleanHtml($html = null)
{
    $allowed_tags = array();
    $rhtml = preg_replace_callback('/<\/?([^>\s]+)[^>]*>/i', function ($matches) use (&$allowed_tags) {
        return in_array(mb_strtolower($matches[1]), $allowed_tags) ? $matches[0] : '';
    }, $html);
    return $rhtml;
}

function getRequestIP()
{
    return $_SERVER['REMOTE_ADDR'];
}

function genUID()
{
    return mb_substr(md5(uniqid(rand())), 0, 12);
}

function genGUID()
{
    $data = openssl_random_pseudo_bytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function createDir($path, $mode = 0777, $rec = true)
{
    $oldumask = umask(0);
    mkdir($path, $mode, $rec);
    umask($oldumask);
}

function writeLog($type = 'mylog', $msg = null)
{
    $file = APP_DIR . 'logs/' . $type . '.txt';
    $datetime = @date('Y-m-d H:i:s');
    $logmsg = '###' . $datetime . '### ' . $msg . "\r\n";
    @file_put_contents($file, $logmsg, FILE_APPEND | LOCK_EX);
}

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_regex_encoding('UTF-8');

function mb_ucwords($str = null)
{
    return mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
}

function mb_str_replace($needle, $replacement, $haystack)
{
    return str_replace($needle, $replacement, $haystack);
}

function mb_trim($string)
{
    $string = preg_replace("/(^\s+)|(\s+$)/us", "", $string);

    return $string;
}

function url_encode($string = null)
{
    return urlencode(utf8_encode($string));
}

function url_decode($string = null)
{
    return utf8_decode(urldecode($string));
}

function my_mime_content_type($filename)
{
    $mime_types = array(
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',
        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',
        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',
        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',
        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet'
    );

    $ext = strtolower(array_pop(explode('.', $filename)));
    if (array_key_exists($ext, $mime_types)) {
        return $mime_types[$ext];
    } else {
        return 'application/octet-stream';
    }
}

function getCountryList($cval = null)
{
    $country = array(
        'AF' => 'Afghanistan',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'BQ' => 'British Antarctic Territory',
        'IO' => 'British Indian Ocean Territory',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CT' => 'Canton and Enderbury Islands',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos [Keeling] Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo - Brazzaville',
        'CD' => 'Congo - Kinshasa',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'CI' => 'Côte d’Ivoire',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'NQ' => 'Dronning Maud Land',
        'DD' => 'East Germany',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'FQ' => 'French Antarctic Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard and McDonald Islands',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong SAR China',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JT' => 'Johnston Island',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Laos',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macau SAR China',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'FX' => 'Metropolitan France',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MI' => 'Midway Islands',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar [Burma]',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NT' => 'Neutral Zone',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'KP' => 'North Korea',
        'VD' => 'North Vietnam',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PC' => 'Pacific Islands Trust Territory',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territories',
        'PA' => 'Panama',
        'PZ' => 'Panama Canal Zone',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'YD' => 'Yemen',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn Islands',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RO' => 'Romania',
        'RU' => 'Russia',
        'RW' => 'Rwanda',
        'RE' => 'Réunion',
        'BL' => 'Saint Barthélemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'CS' => 'Serbia and Montenegro',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia',
        'KR' => 'South Korea',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard and Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syria',
        'ST' => 'São Tomé and Príncipe',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UM' => 'U.S. Minor Outlying Islands',
        'PU' => 'U.S. Pacific Islands',
        'VI' => 'U.S. Virgin Islands',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'SU' => 'USSR',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VA' => 'Vatican City',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WK' => 'Wake Island',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
        'AX' => 'Åland Islands'
    );

    if ($cval) {
        return isset($country[$cval]) ? $country[$cval] : '';
    } else {
        return $country;
    }
}

function paginate($current_page, $total_records, $total_pages, $page_url)
{
    $item_per_page = PAGINATE_LIMIT;
    $page_url = getUrl($page_url);
    $pagination = '';
    if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages) { // verify total pages and current page number
        $pagination .= '<ul class="pagination">';

        $right_links = $current_page + 3;
        $previous = $current_page - 3; // previous link
        $next = $current_page + 1; // next link
        $first_link = true; // boolean var to decide our first link

        if ($current_page > 1) {
            $previous_link = $current_page - 1;
            $pagination .= '<li class="first"><a href="' . $page_url . '/1" title="First">&laquo;</a></li>'; // first link
            $pagination .= '<li><a href="' . $page_url . '/' . $previous_link . '" title="Previous">&lt;</a></li>'; // previous link
            for ($i = ($current_page - 2); $i < $current_page; $i ++) { // Create left-hand side links
                if ($i > 0) {
                    $pagination .= '<li><a href="' . $page_url . '/' . $i . '">' . $i . '</a></li>';
                }
            }
            $first_link = false; // set first link to false
        }

        if ($first_link) { // if current active page is first link
            $pagination .= '<li class="active"><a>' . $current_page . '</a></li>';
        } elseif ($current_page == $total_pages) { // if it's the last active link
            $pagination .= '<li class="active"><a>' . $current_page . '</a></li>';
        } else { // regular current link
            $pagination .= '<li class="active"><a>' . $current_page . '</a></li>';
        }

        for ($i = $current_page + 1; $i < $right_links; $i ++) { // create right-hand side links
            if ($i <= $total_pages) {
                $pagination .= '<li><a href="' . $page_url . '/' . $i . '">' . $i . '</a></li>';
            }
        }
        if ($current_page < $total_pages) {
            $next_link = $current_page + 1;
            $pagination .= '<li><a href="' . $page_url . '/' . $next_link . '" >&gt;</a></li>'; // next link
            $pagination .= '<li class="last"><a href="' . $page_url . '/' . $total_pages . '" title="Last">&raquo;</a></li>'; // last link
        }

        $pagination .= '</ul>';
    }
    return $pagination; // return pagination links
}

function array_map_recursive($arr, $fn)
{
    $rarr = array();
    foreach ($arr as $k => $v) {
        $rarr[$k] = is_array($v) ? array_map_recursive($v, $fn) : $fn($v);
    }
    return $rarr;
}

if (DEBUG) {

    function customError($errno, $errstr, $errfile, $errline)
    {
        echo "<div class='error' style='text-align:left'>";
        echo "<b>Custom error:</b> [$errno] $errstr<br />";
        echo "Error on line $errline in $errfile<br />";
        echo "Ending Script";
        echo "</div>";
    }

    set_error_handler("customError");
}
