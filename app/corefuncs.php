<?phpfunction getUrl($path = null) {    if (PATH_URI != '/') {        return SITE_URI . PATH_URI . '/' . $path;    } else {        return SITE_URI . '/' . $path;    }}function clean($string = null) {    return strip_tags(mb_trim($string));}function cleanHtml($html = null) {    $allowed_tags = array('a', 'em', 'strong', 'cite', 'code', 'ul', 'ol', 'li', 'dl', 'dt', 'dd', 'table', 'tr', 'td', 'tbody', 'thead', 'br', 'b', 'i');    $rhtml = preg_replace_callback('/<\/?([^>\s]+)[^>]*>/i', function ($matches) use (&$allowed_tags) {        return in_array(mb_strtolower($matches[1]), $allowed_tags) ? $matches[0] : '';    }, $html);    return mb_trim($rhtml);}function req() {    return Request::getContext();}function res() {    return Response::getContext();}function getRequestIP() {    return $_SERVER['REMOTE_ADDR'];}function genUID() {    return mb_substr(md5(uniqid(rand())), 0, 12);}function genGUID() {    $data = openssl_random_pseudo_bytes(16);    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));}function createDir($path, $mode = 0777, $rec = true) {    $oldumask = umask(0);    mkdir($path, $mode, $rec);    umask($oldumask);}function my_mime_content_type($filename) {    $mime_types = array(        'txt' => 'text/plain',        'htm' => 'text/html',        'html' => 'text/html',        'php' => 'text/html',        'css' => 'text/css',        'js' => 'application/javascript',        'json' => 'application/json',        'xml' => 'application/xml',        'swf' => 'application/x-shockwave-flash',        'flv' => 'video/x-flv',        // images        'png' => 'image/png',        'jpe' => 'image/jpeg',        'jpeg' => 'image/jpeg',        'jpg' => 'image/jpeg',        'gif' => 'image/gif',        'bmp' => 'image/bmp',        'ico' => 'image/vnd.microsoft.icon',        'tiff' => 'image/tiff',        'tif' => 'image/tiff',        'svg' => 'image/svg+xml',        'svgz' => 'image/svg+xml',        // archives        'zip' => 'application/zip',        'rar' => 'application/x-rar-compressed',        'exe' => 'application/x-msdownload',        'msi' => 'application/x-msdownload',        'cab' => 'application/vnd.ms-cab-compressed',        // audio/video        'mp3' => 'audio/mpeg',        'qt' => 'video/quicktime',        'mov' => 'video/quicktime',        // adobe        'pdf' => 'application/pdf',        'psd' => 'image/vnd.adobe.photoshop',        'ai' => 'application/postscript',        'eps' => 'application/postscript',        'ps' => 'application/postscript',        // ms office        'doc' => 'application/msword',        'rtf' => 'application/rtf',        'xls' => 'application/vnd.ms-excel',        'ppt' => 'application/vnd.ms-powerpoint',        // open office        'odt' => 'application/vnd.oasis.opendocument.text',        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',    );    $ext = strtolower(array_pop(explode('.', $filename)));    if (array_key_exists($ext, $mime_types)) {        return $mime_types[$ext];    } else {        return 'application/octet-stream';    }}function writeLog($type = 'mylog', $msg = null) {    $file = APP_DIR . 'logs/' . $type . '.txt';    $datetime = @date('Y-m-d H:i:s');    $logmsg = '###' . $datetime . '### ' . $msg . "\r\n";    @file_put_contents($file, $logmsg, FILE_APPEND | LOCK_EX);}if (DEBUG) {    function customError($errno, $errstr, $errfile, $errline) {        echo "<div class='error' style='text-align:left'>";        echo "<b>Custom error:</b> [$errno] $errstr<br />";        echo "Error on line $errline in $errfile<br />";        echo "Ending Script";        echo "</div>";    }    set_error_handler("customError");}