<?php
require_once APP_DIR . 'config/config.php';

// begin core functions
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

function cache()
{
    Cache::getContext(CACHE_TYPE);
}

function setCurrentUser(array $userdata = array())
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

function bool_array_search($string = '', array $aval = array())
{
    foreach ($aval as $val) {
        if (strstr($val, "'" . $string . "'")) {
            return true;
        }
    }
    return false;
}

function cleanHtml($html = null)
{
    $allowed_tags = array(
        'a',
        'em',
        'strong',
        'cite',
        'code',
        'ul',
        'ol',
        'li',
        'dl',
        'dt',
        'dd',
        'table',
        'tr',
        'td',
        'tbody',
        'thead',
        'th',
        'br',
        'b',
        'i',
        'p'
    );
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
    $bytes = random_bytes(16);
    assert(mb_strlen($data) == 16);

    $hex = bin2hex($bytes);

    return substr($hex, 0, 12);
}

function genGUID()
{
    $data = random_bytes(16);
    assert(mb_strlen($data) == 16);

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

function base64_url_encode($text)
{
    return mb_str_replace([
        '+',
        '/',
        '='
    ], [
        '-',
        '_',
        ''
    ], base64_encode($text));
}

function base64_url_decode($text)
{
    $data = mb_str_replace(array(
        '-',
        '_'
    ), array(
        '+',
        '/'
    ), $text);
    $mod4 = mb_strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    return base64_decode($data);
}

function url_encode($string = null)
{
    return urlencode($string);
}

function url_decode($string = null)
{
    return urldecode($string);
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
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet'
    );

    $temporary = explode(".", $filename);
    $ext = mb_strtolower(end($temporary));

    if (array_key_exists($ext, $mime_types)) {
        return $mime_types[$ext];
    } else {
        return 'application/octet-stream';
    }
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
        $emsg = "";
        $emsg .= "<div class='error' style='text-align:left'>";
        $emsg .= "<b>Custom error:</b> [$errno] $errstr<br />";
        $emsg .= "Error on line $errline in $errfile<br />";
        $emsg .= "Ending Script";
        $emsg .= "</div>";

        throw new Exception($emsg);
    }

    set_error_handler("customError");
}

// begin core classes
unset($_REQUEST);
unset($_GET);

spl_autoload_extensions('.php');
spl_autoload_register(array(
    'Loader',
    'load'
));

final class Loader
{

    public static function load($classname)
    {
        $a = $classname[0];

        if ($a >= 'A' && $a <= 'Z') {
            require_once LIBS_DIR . mb_str_replace(array(
                '\\',
                '_'
            ), '/', $classname) . '.php';
        } else {
            require_once MODS_DIR . mb_strtolower($classname) . '.php';
        }
    }
}

class ApiException extends Exception
{
}

final class DB
{

    private static $_context = null;

    public static function getContext()
    {
        if (self::$_context) {
            return self::$_context;
        }

        list ($dbtype, $host, $user, $pass, $dbname) = unserialize(DB_CON);

        $dsn = $dbtype . ':host=' . $host . ';dbname=' . $dbname;

        try {
            self::$_context = new PDO($dsn, $user, $pass);
            self::$_context->exec('SET NAMES utf8');
            self::$_context->setAttribute(PDO::ATTR_PERSISTENT, true);
            self::$_context->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            self::$_context->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$_context->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        } catch (PDOException $ex) {
            throw $ex;
        }

        return self::$_context;
    }
}

final class Cache
{

    private static $_context = null;

    public static function getContext($cachetype)
    {
        if (self::$_context === null) {
            $classname = 'Cache_' . $cachetype;
            self::$_context = new $classname();
        }

        return self::$_context;
    }
}

final class Request
{

    private $_pathprefix = null;

    private $_controller = null;

    private $_method = null;

    private static $_context = null;

    public static function getContext()
    {
        if (self::$_context === null) {
            self::$_context = new self();
        }

        return self::$_context;
    }

    public function isAjax()
    {
        if (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && mb_strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }
        return false;
    }

    public function isPost()
    {
        return ($_SERVER['REQUEST_METHOD'] === 'POST');
    }

    public function getPathPrefix()
    {
        return $this->_pathprefix;
    }

    public function getController()
    {
        return mb_strtolower($this->_controller);
    }

    public function getMethod()
    {
        return mb_strtolower($this->_method);
    }

    public function getHeaders()
    {
        $headers = array();
        foreach ($_SERVER as $param => $value) {
            if (mb_strpos($param, 'HTTP_') === 0) {
                $headers[mb_str_replace(' ', '-', mb_ucwords(mb_str_replace('_', ' ', mb_strtolower(mb_substr($param, 5)))))] = $value;
            }
        }
        return $headers;
    }

    public function getToken()
    {
        return isset($_SERVER['HTTP_X_TOKEN']) ? $_SERVER['HTTP_X_TOKEN'] : (isset($_COOKIE['AdminToken']) ? $_COOKIE['AdminToken'] : '');
    }

    public function setPathPrefix($pathprefix = null)
    {
        $this->_pathprefix = $pathprefix;
    }

    public function setController($controllername = null)
    {
        $this->_controller = mb_strtolower($controllername);
    }

    public function setMethod($methodname = null)
    {
        $this->_method = mb_strtolower($methodname);
    }
}

final class Response
{

    private static $_context = null;

    private $_statusCode = array(
        200 => "200 OK",
        301 => "301 Moved Permanently",
        302 => "302 Found",
        303 => "303 See Other",
        307 => "307 Temporary Redirect",
        400 => "400 Bad Request",
        403 => "403 Forbidden",
        404 => "404 Not Found",
        405 => "405 Method Not Allowed",
        500 => "500 Internal Server Error",
        503 => "503 Service Unavailable"
    );

    private $_page_messages = array(
        405 => "Try HTTP GET.",
        500 => "Something went horribly wrong."
    );

    public static function getContext()
    {
        if (self::$_context === null) {
            self::$_context = new self();
        }

        return self::$_context;
    }

    public function setStatus($status)
    {
        header("HTTP/1.0 " . $this->_get_status_message($status));
    }

    public function redirect($path = null, $alertmsg = null)
    {
        if ($alertmsg) {
            $this->addSplashMsg($alertmsg);
        }

        $redir = getUrl($path);

        header("Location: $redir");
        exit();
    }

    public function display(array &$data = array(), $viewname = null)
    {
        View::display($data, $viewname);
    }

    public function assign(array &$data = array(), $viewname = null)
    {
        return View::assign($data, $viewname);
    }

    public static function addSplashMsg($msg = null)
    {
        Session::getContext(SESS_TYPE)->set('splashmessage', $msg);
    }

    public static function getSplashMsg()
    {
        $sess = Session::getContext(SESS_TYPE);
        $msg = $sess->get('splashmessage');
        $sess->set('splashmessage', null);
        return $msg;
    }

    private function _get_status_message($code)
    {
        if (! isset($this->_statusCode[$code])) {
            return $this->_statusCode[500];
        }
        return $this->_statusCode[$code];
    }
}

final class View
{

    public static function assign(array &$vars = array(), $viewname = null)
    {
        $req = req();
        if (is_array($vars)) {
            extract($vars);
        }
        ob_start();
        if ($viewname == null) {
            $viewname = mb_strtolower($req->getController() . '/' . $req->getMethod());
        }
        include VIEW_DIR . mb_strtolower($viewname) . '.php';
        return ob_get_clean();
    }

    public static function display(array &$vars = array(), $viewname = null)
    {
        $req = req();
        if ($viewname == null) {
            $viewname = mb_strtolower($req->getController() . '/' . $req->getMethod());
        }
        if (! isset($vars['layout'])) {
            $playout = 'layouts/' . $req->getPathPrefix() . 'layout';
            $vars['mainregion'] = self::assign($vars, $viewname);
        } else {
            if ($vars['layout']) {
                $playout = $vars['layout'];
            } else {
                $playout = $viewname;
            }
        }
        if (is_array($vars)) {
            extract($vars);
        }
        include VIEW_DIR . mb_strtolower($playout) . '.php';
    }
}

// begin controller class
abstract class cController
{

    public $req = null;

    public $res = null;

    public function __construct()
    {
        $this->req = req();
        $this->res = res();
    }
}

abstract class cAuthController
{

    public $req = null;

    public $res = null;

    public $user = null;

    public function __construct()
    {
        $this->req = req();
        $this->res = res();

        $jwt = $this->req->getToken();

        if (! $jwt) {
            throw new ApiException('Invalid Access.');
        }

        // split the token
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        // build a signature based on the header and payload using the secret
        $base64UrlHeader = base64_url_encode($header);
        $base64UrlPayload = base64_url_encode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, SECRET_KEY, true);
        $base64UrlSignature = base64_url_encode($signature);

        // check the expiration time - note this will cause an error if there is no 'exp' claim in the token
        $expiration = json_decode($payload)->exp;
        $tokenExpired = (time() > $expiration);

        if ($tokenExpired) {
            throw new ApiException('Token has expired.');
        }

        // verify it matches the signature provided in the token
        $signatureValid = ($base64UrlSignature === $signatureProvided);

        if ($signatureValid) {
            $this->user = json_decode($payload);
        } else {
            throw new ApiException('Signature is not vald.');
        }
    }
}

abstract class cAdminController
{

    public $req = null;

    public $res = null;

    public function __construct()
    {
        $this->req = req();
        $this->res = res();

        $pathprefix = $this->req->getPathPrefix();

        $cusertype = getCurrentUserType();

        if ($pathprefix == 'dashboard' && $cusertype != 'user') {
            $this->res->redirect('login', 'Invalid Access');
        }
        if ($pathprefix == 'manage' && $cusertype != 'superadmin') {
            $this->res->redirect('login', 'Invalid Access');
        }
    }
}

// begin model class
class model
{

    protected $db = null;

    private $_rs = array();

    private $_pk;

    private $_table;

    public function __construct($table = null, $pk = 'id')
    {
        $this->_pk = $pk;
        $this->_table = $table;
        $this->db = db();
    }

    public function __set($key, $val)
    {
        $this->_rs[$key] = $val;
    }

    public function __get($key)
    {
        return isset($this->_rs[$key]) ? $this->_rs[$key] : '';
    }

    public function select($selectwhat = '*', $wherewhat = null, $bindings = null)
    {
        if (is_scalar($bindings)) {
            $bindings = mb_trim($bindings) ? array(
                $bindings
            ) : array();
        }
        $sql = 'SELECT ' . $selectwhat . ' FROM ' . $this->_table;
        if ($wherewhat) {
            $sql .= ' WHERE ' . $wherewhat;
        }

        $stmt = $this->db->prepare($sql);

        $i = 0;
        if ($wherewhat) {
            foreach ($bindings as $v) {
                $stmt->bindValue(++ $i, $v);
            }
        }

        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $this->_rs = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return $stmt->fetchAll();
        }
    }

    public function insert()
    {
        $_pk = $this->_pk;

        $s1 = $s2 = '';

        foreach ($this->_rs as $k => $v) {
            if ($k != $_pk || $v) {
                $s1 .= ',' . $k;
                $s2 .= ',?';
            }
        }

        $sql = 'INSERT INTO ' . $this->_table . ' (' . mb_substr($s1, 1) . ') VALUES (' . mb_substr($s2, 1) . ')';

        $stmt = $this->db->prepare($sql);

        $i = 0;

        foreach ($this->_rs as $k => $v) {
            if ($k != $_pk || $v) {
                $stmt->bindValue(++ $i, is_scalar($v) ? $v : serialize($v));
            }
        }

        $stmt->execute();

        return $this->db->lastInsertId();
    }

    public function update()
    {
        $s = '';

        foreach ($this->_rs as $k => $v) {
            $s .= ',' . $k . '=?';
        }

        $s = substr($s, 1);

        $sql = 'UPDATE ' . $this->_table . ' SET ' . $s . ' WHERE ' . $this->_pk . '=?';

        $stmt = $this->db->prepare($sql);

        $i = 0;

        foreach ($this->_rs as $k => $v) {
            $stmt->bindValue(++ $i, is_scalar($v) ? $v : serialize($v));
        }

        $stmt->bindValue(++ $i, $this->_rs[$this->_pk]);

        return $stmt->execute();
    }

    public function delete()
    {
        $sql = 'DELETE FROM ' . $this->_table . ' WHERE ' . $this->_pk . '=?';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $this->_rs[$this->_pk]);

        return $stmt->execute();
    }

    public function exist($checkdb = false)
    {
        if ((int) $this->{$this->_pk} >= 1) {
            return 1;
        }

        if ($checkdb) {
            $sql = 'SELECT 1 FROM ' . $this->_table . ' WHERE ' . $this->_pk . "='" . $this->{$this->_pk} . "'";
            return $this->db->query($sql)->rowCount();
        }

        return 0;
    }

    public function get()
    {
        return $this->_rs;
    }

    public function assign(array &$arr = array(), $chechfield = false)
    {
        foreach ($arr as $key => $val) {
            if ($chechfield) {
                if (isset($this->$key)) {
                    $this->$key = cleanHtml($val);
                }
            } else {
                $this->$key = cleanHtml($val);
            }
        }
    }
}

// application bootstrap class
final class Application
{

    public static function run(Request &$request, Response &$response)
    {
        $uriparts = explode('/', mb_str_replace(SITE_URI . PATH_URI, '', SITE_URI . $_SERVER['REQUEST_URI']));
        $uriparts = array_filter($uriparts);

        $controller = ($c = array_shift($uriparts)) ? $c : MAIN_CONTROLLER;
        $pathprefix = '';

        if (in_array($controller, unserialize(PATH_PREFIX))) {
            $pathprefix = mb_strtolower($controller) . '_';
            $controller = ($c = array_shift($uriparts)) ? $c : MAIN_CONTROLLER;
        }

        $controllerfile = CONT_DIR . mb_strtolower($controller) . '.php';
        if (! preg_match('#^[A-Za-z0-9_-]+$#', $controller) || ! is_readable($controllerfile)) {
            $controller = MAIN_CONTROLLER;
            $controllerfile = CONT_DIR . MAIN_CONTROLLER . '.php';
        }

        $cont = 'c' . $controller;
        $method = ($c = array_shift($uriparts)) ? $pathprefix . mb_str_replace(unserialize(PATH_PREFIX), '', $c) : $pathprefix . MAIN_METHOD;
        $args = (isset($uriparts[0])) ? $uriparts : array();

        require_once $controllerfile;

        if (! method_exists($cont, $method)) {
            $method = MAIN_METHOD;
        }

        $request->setPathPrefix(mb_substr($pathprefix, 0, - 1));
        $request->setController($controller);
        $request->setMethod($method);

        $cont = new $cont();

        call_user_func_array(array(
            $cont,
            $method
        ), $args);
    }
}
