<?php

class Request {

    private $_pathprefix = '';
    private $_lang;
    private $_controller;
    private $_method;
    private $_args;
    private $_requestobjects = array();
    private static $_context = null;

    public static function getContext() {
        if (self::$_context === null) {
            self::$_context = new self;
        }
        return self::$_context;
    }

    public function __construct() {
        $this->_dorequest();
    }

    public function isPost() {
        return ($_SERVER['REQUEST_METHOD'] === 'POST');
    }

    public function isAjax() {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
    }

    public function isMobile() {
        $useragent = $_SERVER['HTTP_USER_AGENT'];

        if (preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|meego.+mobile|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) or preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            return true;
        }
        return false;
    }

    // these methods are for type safety only meaning normalizing it to the correct form: for validation do javascript and html5 extension
    public function getChar($param = '') {
        if (isset($this->_requestobjects[$param])) {
            return (string) str_sanitize(strip_tags($this->_requestobjects[$param]));
        }
        return '';
    }

    public function getInt($param = '') {
        if (isset($this->_requestobjects[$param])) {
            return (int) trim($this->_requestobjects[$param]);
        }
        return 0;
    }

    public function getFloat($param = '') {
        if (isset($this->_requestobjects[$param])) {
            return (float) trim($this->_requestobjects[$param]);
        }
        return 0.0;
    }

    public function getDate($param = '', $inputformat = 'd-m-Y') {
        if (isset($this->_requestobjects[$param])) {
            return DateTime::createFromFormat($inputformat, $this->_requestobjects[$param])->format('Y-m-d');
        }
        return 0;
    }

    public function getDateTime($param = '', $inputformat = 'd-m-Y H:i:s') {
        if (isset($this->_requestobjects[$param])) {
            return DateTime::createFromFormat($inputformat, $this->_requestobjects[$param])->format('Y-m-d H:i:s');
        }
        return 0;
    }

    public function getString($param = '') {
        if (isset($this->_requestobjects[$param])) {
            return (string) trim(strip_tags($this->_requestobjects[$param]));
        }
        return '';
    }

    public function getHtml($param = '') {
        if (isset($this->_requestobjects[$param])) {
            return (string) htmlentities(trim(real_strip_tags($this->_requestobjects[$param])), ENT_XML1 | ENT_XHTML | ENT_HTML5, 'UTF-8');
        }
        return '';
    }

    /*
      html5 allows multiple file uploads via fieldname[] and multiple attribute

      here is example how to use it in controller

      $upfile = $this->request->getFiles['fieldname'];
      $allowedExtensions = array("txt","csv","htm","doc","xls","rtf","ppt",
      "pdf","swf","flv","avi","wmv","mov","jpg","jpeg","gif","png");

      foreach ($upfile AS $file) {
      if ($file['tmp_name'] > '') {
      if (!in_array(end(explode(".", strtolower($file['name']))), $allowedExtensions)) {
      move_uploaded_file($upfile['tmp_name'], UPLOAD_DIR .'/'. $upfile['name']);;
      }
      }
      }
     */

    public function getFiles($param = '') {
        $files = array();
        $fdata = isset($_FILES[$param]) ? $_FILES[$param] : array();
        if (@is_array($fdata['name'])) {
            for ($i = 0; $i < count($fdata['name']); ++$i) {
                $files[] = array(
                    'name' => $fdata['name'][$i],
                    'type' => $fdata['type'][$i],
                    'tmp_name' => $fdata['tmp_name'][$i],
                    'error' => $fdata['error'][$i],
                    'size' => $fdata['size'][$i]
                );
            }
        } else {
            $files[] = $fdata;
        }
        return $files;
    }

    public function getRequestIP() {
        return isset($_SERVER['HTTP_VIA']) ? $_SERVER['HTTP_VIA'] : $_SERVER['REMOTE_ADDR'];
    }

    public function getHeaders() {
        $headers = array();
        foreach ($_SERVER as $param => $value) {
            if (strpos($param, 'HTTP_') === 0) {
                $headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($param, 5)))))] = $value;
            }
        }
        return $headers;
    }

    public function isValidToken() {
        return false;
    }

    public function getPathPrefix() {
        return $this->_pathprefix;
    }

    public function getLang() {
        return $this->_lang;
    }

    public function getController() {
        return ucfirst(strtolower($this->_controller));
    }

    public function getMethod() {
        return strtolower($this->_method);
    }

    public function getArgs() {
        return $this->_args;
    }

    public function setPathPrefix($pathprefix = '') {
        $this->_pathprefix = $pathprefix;
    }

    public function setLang($lang = 'en') {
        $this->_lang = $lang;
    }

    public function setController($controllername = 'Home') {
        $this->_controller = ucfirst(strtolower($controllername));
    }

    public function setMethod($methodname = 'index') {
        $this->_method = strtolower($methodname);
    }

    public function setArgs(array $args = array()) {
        $this->_args = $args;
    }

    private function _dorequest() {
        if (MIX_GET_POST) {
            $this->_requestobjects = array_merge($_GET, $_POST);
        } else {
            $this->_requestobjects = ($_SERVER['REQUEST_METHOD'] === 'POST') ? $_POST : $_GET;
        }
        unset($_GET);
        unset($_POST);
        unset($_REQUEST);
    }

}
