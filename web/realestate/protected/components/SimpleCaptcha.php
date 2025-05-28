<?php
//session_start();


// The class (and all requests) are handled at the end of the script
class SimpleCaptcha {

  // ------------------------------------------------- //
  // -------------------- EDIT THESE ----------------- //
  // ------------------------------------------------- //
  // 
  // Note that if they are relative paths, they must be 
  // relative to THIS script!
  private $images = array(
    // NAME          =>  IMAGE_FILE
      'house'        => 'common/images/captchaImages/01.png',
      'key'          => 'common/images/captchaImages/02.png',
      'flag'         => 'common/images/captchaImages/03.png',
      'clock'        => 'common/images/captchaImages/04.png',
      'bug'          => 'common/images/captchaImages/05.png',
      'pen'          => 'common/images/captchaImages/06.png',
      'light bulb'   => 'common/images/captchaImages/07.png',
      'musical note' => 'common/images/captchaImages/08.png',
      'heart'        => 'common/images/captchaImages/09.png',
      'world'        => 'common/images/captchaImages/10.png'
  );

  // You should change this for security reasons, 10-20 characters is good
  private $salt = "o^G-j7%8W3xP!2_";

  // ------------------------------------------------- //
  // ------------------- STOP EDITING ---------------- //
  // ------------------------------------------------- //
  // (All other options should be edited in the plugin JavaScript.)



  const MIN_IMAGES = 3;
  const DEFAULT_NUM_IMAGES = 5;
  private $sessionData = null;
  private $answer = null;

  public function __construct() {
    $this->getDataFromSession();

    if (!$this->sessionData) {
      $this->resetSessionData();
    }
  }

  public function resetSessionData() {
    $this->sessionData = array(
      'time'   => time(),
      'images' => array(),
      'salt'   => null
    );
    $this->sessionData['salt'] = $this->salt . $this->sessionData['time'];
  }

  public function getImageByHash($hash) {
    if (isset($this->sessionData['images'][$hash])) {
      $fn = $this->sessionData['images'][$hash];

      if (file_exists($fn)) {
        $mime = null;
        if (function_exists("finfo_open")) {
          // PHP 5.3
          $finfo = finfo_open(FILEINFO_MIME_TYPE);
          $mime = finfo_file($finfo, $fn);

        } else if (function_exists("mime_content_type")) {
          // PHP 5.2
          $mime = mime_content_type($fn);
        }

        if (!$mime) { $mime = "image/png"; }

        header("Content-Type: {$mime}");
        readfile($fn);
        exit;

      } else {
        throw new InvalidArgumentException("That captcha image file does not exist ".$fn, 404);
      }

    } else {
      throw new InvalidArgumentException("No captcha image exists with that hash: ".json_encode($this->sessionData->images), 404);
    }
  }

  public function getAllImageData() {
    $iData = array(
      'text'   => '',
      'images' => array()
    );

    if (!isset($this->images) || !is_array($this->images) || sizeof($this->images) < SimpleCaptcha::MIN_IMAGES) {
      throw new InvalidArgumentException("There aren\'t enough images on the server!", 400);
    }

    $numImages = SimpleCaptcha::DEFAULT_NUM_IMAGES;
    if (isset($_REQUEST['numImages']) && 
        intval($_REQUEST['numImages']) && 
        intval($_REQUEST['numImages']) > SimpleCaptcha::MIN_IMAGES) {
      $numImages = intval($_REQUEST['numImages']);
    }
    $totalSize = sizeof($this->images);
    $numImages = min(array($totalSize, $numImages));

    $keys = array_keys($this->images);
    $used = array();
    
    mt_srand(((float) microtime() * 587) / 33); // add some randomness

    for ($i=0; $i<$numImages; ++$i) {
      $r = rand(0, $totalSize-1);
      while (array_search($keys[$r], $used) !== false) {
        $r = rand(0, $totalSize-1);
      }
      array_push($used, $keys[$r]);
    }
    
    $iData['text'] = $used[rand(0, $numImages-1)];
    $this->answer = sha1($iData['text'] . $this->sessionData['salt']);

    shuffle($used);

    for ($i=0; $i<sizeof($used); ++$i) {
      $hash = sha1($used[$i] . $this->sessionData['salt']);
      array_push($iData['images'], $hash);
      $this->sessionData['images'][$hash] = $this->images[$used[$i]];
    }

    return $iData;
  }


  public function writeSessionData() {
      Yii::app()->session['simpleCaptchaAnswer'] = $this->answer;
      Yii::app()->session['simpleCaptchaData'] = json_encode($this->sessionData);
    // And for backward compatibility...
      Yii::app()->session['simpleCaptchaTimestamp'] = $this->sessionData['time'];
  }

  public function getDataFromSession() {
    if (isset(Yii::app()->session['simpleCaptchaData'])) {
      $this->sessionData = json_decode(Yii::app()->session['simpleCaptchaData'], true);
    }
    if (isset(Yii::app()->session['simpleCaptchaAnswer'])) {
      $this->answer = Yii::app()->session['simpleCaptchaAnswer'];
    }
  }


  // Static helper methods

  public static function getProtocol() {
    $protocol = "HTTP/1.1";
    if(isset($_SERVER['SERVER_PROTOCOL'])) {
      $protocol = $_SERVER['SERVER_PROTOCOL'];
    }
    return $protocol;
  }

}
