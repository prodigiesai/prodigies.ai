<?php
/**
 * ExtMinScript class file.
 *
 * @author Tobias Giacometti
 * @link http://bitbucket.org/limi7less/minscript/
 * @copyright Copyright &copy; 2011-2013 Tobias Giacometti
 * @license http://bitbucket.org/limi7less/minscript/wiki/License
 */

/**
 * ExtMinScript is the main class in the minScript extension. It offers all the necessary properties
 * and methods for minScript to do its magic.
 *
 * ExtMinScript extends CClientScript to automatically create minScript groups from files registered
 * with CClientScript::registerScriptFile() or CClientScript::registerCssFile().
 *
 * It is highly encouraged to define a valid cache application component ID in ExtMinScript::$minScriptCacheId
 * so minScript can cache last modified timestamps and processed files.
 *
 * @property CCache $minScriptCache The cache application component instance used by minScript.
 *
 * @author Tobias Giacometti
 * @package ext.minScript.components
 * @since 1.0
 */
class ExtMinScript extends CClientScript {

	/**
	 * @var string ID of the minScript controller. Defaults to "min".
	 * @since 2.1
	 */
	public $minScriptControllerId = 'min';

	/**
	 * @var string ID of the cache application component that is used by minScript. If set to an invalid ID,
	 * minScript will automatically create a cache application component based on CFileCache. Defaults to "cache".
	 * @since 2.1
	 */
	public $minScriptCacheId = 'cache';

	/**
	 * @var boolean Whether output from minScript should be displayed in debug mode. If set to true, files
	 * won't be minified and will be populated with helpful comments. If enabled, performance will be degraded
	 * considerably. Defaults to false.
	 * @since 2.0
	 */
	public $minScriptDebug = false;

	/**
	 * @var string This property needs to be set to the same URL as the HTML base tag. The URL has to be
	 * absolute. Leave empty if no HTML base tag is used.
	 * @since 2.0
	 */
	public $minScriptBaseUrl;

	/**
	 * @var integer Defines how long (in seconds) the last modified timestamp for a set of files should be
	 * stored in cache. If files that get served by minScript change frequently, a low value in this property
	 * will ensure that visitors see changes faster. If the Yii debug mode is turned on or this property is
	 * set to false, files will be checked on every request. Defaults to false.
	 * @since 2.1
	 */
	public $minScriptLmCache = false;

	/**
	 * @var array In case of URL rewrites or aliases, this property helps minScript locate files by assigning
	 * file system paths to URLs. This property uses an array of URL to path mappings where the key is a URL
	 * and the value a file system path. The URL matching is done using Regular Expressions. Backreferences
	 * can be used in the path values. For example:
	 * <pre>
	 * array(
	 * 		'/^\/url\/to\/(file)$/' => 'path/to/$1',
	 * )
	 * </pre>
	 * Another use case of this property is the exclusion of URLs from automatic processing. If false is
	 * assigned to a URL, minScript will not process it automatically. For example:
	 * <pre>
	 * // Exclude all URLs from automatic minScript processing
	 * array(
	 * 		'/^.*$/' => false,
	 * )
	 * </pre>
	 * @since 2.1
	 */
	public $minScriptUrlMap = array();

	/**
	 * @var array This property is used to disable minification of specific files. The files will still be
	 * combined and compressed. The matching is done using Regular Expression patterns which are applied to
	 * file system paths. By default, files ending with "-min.js", ".min.js", "-min.css" and ".min.css" won't
	 * be minified.
	 * @since 2.2
	 */
	public $minScriptDisableMin = array('/[-\.]min\.(?:js|css)$/i');

	/**
	 * @var boolean When combining multiple CSS files, @import at-rules can end up after normal CSS rules,
	 * which is invalid. If this happens, a warning comment will be placed at the top of the minScript output.
	 * To resolve this, the @import at-rules can either be moved manually or this property can be set to true,
	 * which will move all @import at-rules to the top of the minScript output. Please note that moving @import
	 * at-rules could affect how other CSS rules are applied. Defaults to false.
	 * @since 2.2
	 */
	public $minScriptBubbleCssImports = false;

	protected $_minScriptCache;

	/**
	 * Initialize the minScript application component.
	 * @throws CException if the minScript controller is not defined in CWebApplication::$controllerMap.
	 */
	public function init() {
		parent::init();
		// Initialize the cache application component instance for minScript
		if (($minScriptCache = Yii::app() -> getComponent($this -> minScriptCacheId)) !== null) {
			$this -> _minScriptCache = $minScriptCache;
		} else {
			Yii::app() -> setComponents(array('minScriptCache' => array('class' => 'system.caching.CFileCache', 'cachePath' => Yii::app() -> runtimePath . '/minScript/cache/', 'cacheFileSuffix' => '')), false);
			$this -> _minScriptCache = Yii::app() -> getComponent('minScriptCache');
		}
		// Check for existence of the minScript controller inside CWebApplication::$controllerMap
		if (!isset(Yii::app() -> controllerMap[$this -> minScriptControllerId])) {
			throw new CException('The minScript controller with ID "' . $this -> minScriptControllerId . '" needs to be defined in CWebApplication::$controllerMap.');
		}
	}

	/**
	 * Returns the cache application component instance used by minScript.
	 * @return CCache The cache application component instance used by minScript.
	 * @since 2.1
	 */
	public function getMinScriptCache() {
		return $this -> _minScriptCache;
	}

	/**
	 * Get the last modified timestamp for a set of files.
	 * @param array $files File system paths for the files.
	 * @param boolean $log Whether to log messages. Defaults to false.
	 * @return integer The last modified timestamp for the set of files or false on failure.
	 * @since 2.1
	 */
	public function minScriptGetLm($files, $log = false) {
		$files = (array)$files;
		$lmId = 'minScriptLm' . serialize($files);
		if (!empty($this -> minScriptLmCache) && !YII_DEBUG && ($lmCache = $this -> _minScriptCache -> get($lmId)) !== false) {
			// Get last modified timestamp from cache
			$lm = $lmCache;
			if ($log === true) {
				Yii::log('Last modified timestamp was fetched from cache.', CLogger::LEVEL_INFO, 'ext.minScript.components.ExtMinScript');
			}
		} else {
			// Get last modified timestamp from files
			foreach ($files as $file) {
				if (($filemtimes[] = @filemtime($file)) === false && $log === true) {
					Yii::log('Can\'t access ' . $file, CLogger::LEVEL_ERROR, 'ext.minScript.components.ExtMinScript');
				}
			}
			$lm = (isset($filemtimes) && !in_array(false, $filemtimes, true)) ? max($filemtimes) : false;
			// Add last modified timestamp to cache
			if (!empty($this -> minScriptLmCache) && !YII_DEBUG && $lm !== false) {
				$this -> _minScriptCache -> set($lmId, $lm, (int)$this -> minScriptLmCache);
			}
		}
		return $lm;
	}

	/**
	 * Get the file system path from a URL.
	 * @param string $url The URL for which to get the path.
	 * @return mixed The absolute file system path with no trailing slash. Returns false if the URL points
	 * to a remote resource or is excluded from processing.
	 * @since 2.1
	 */
	protected function _minScriptGetPath($url) {
		// Check ExtMinScript::$minScriptUrlMap for matches
		foreach ($this -> minScriptUrlMap as $mapUrl => $mapPath) {
			if (($path = preg_replace($mapUrl, $mapPath, $url, -1, $mapCount)) && $mapCount > 0 && $mapPath !== false) {
				Yii::log('A URL to path mapping was found. The URL "' . $url . '" points to the file system path "' . $path . '".', CLogger::LEVEL_INFO, 'ext.minScript.components.ExtMinScript');
				return $path;
			} elseif ($mapPath === false && $mapCount > 0) {
				Yii::log('The URL "' . $url . '" is excluded from automatic processing.', CLogger::LEVEL_INFO, 'ext.minScript.components.ExtMinScript');
				return false;
			}
		}
		// Get document root
		$docRoot = rtrim(substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['SCRIPT_NAME'])), '/\\');
		// Process specified URL
		if (preg_match('/^([a-z0-9\.+-]+:)?\/\//i', $url) > 0) {
			// The URL is absolute
			$urlAbsolute = (strpos($url, '//') === 0) ? 'http:' . $url : $url;
			if (($urlSegments = @parse_url($urlAbsolute)) && isset($urlSegments['host']) && $urlSegments['host'] != @parse_url(Yii::app() -> request -> hostInfo . Yii::app() -> request -> url, PHP_URL_HOST)) {
				Yii::log('The URL "' . $url . '" is pointing to an external resource.', CLogger::LEVEL_INFO, 'ext.minScript.components.ExtMinScript');
				return false;
			}
			$urlPath = (isset($urlSegments['path'])) ? $urlSegments['path'] : '';
			$path = $docRoot . $urlPath;
			Yii::log('The URL "' . $url . '" is absolute and points to the file system path "' . $path . '".', CLogger::LEVEL_INFO, 'ext.minScript.components.ExtMinScript');
		} elseif (strpos($url, Yii::app() -> assetManager -> baseUrl) === 0) {
			// The URL points to an asset
			$assetBasePath = rtrim(Yii::app() -> assetManager -> basePath, '/\\');
			$path = $assetBasePath . (string)@parse_url(substr($url, strlen(Yii::app() -> assetManager -> baseUrl)), PHP_URL_PATH);
			Yii::log('The URL "' . $url . '" is an asset and points to the file system path "' . $path . '".', CLogger::LEVEL_INFO, 'ext.minScript.components.ExtMinScript');
		} elseif (strpos($url, '/') === 0) {
			// The URL is relative to the document root
			$path = $docRoot . (string)@parse_url($url, PHP_URL_PATH);
			Yii::log('The URL "' . $url . '" is relative to the document root and points to the file system path "' . $path . '".', CLogger::LEVEL_INFO, 'ext.minScript.components.ExtMinScript');
		} else {
			// The URL is relative to the current request
			$requestPathRaw = (($requestPathRaw = @parse_url(Yii::app() -> request -> hostInfo . Yii::app() -> request -> url, PHP_URL_PATH)) && substr($requestPathRaw, -1) == '/') ? $requestPathRaw .= 'dummy' : $requestPathRaw;
			$requestPath = rtrim(dirname($requestPathRaw), '/\\');
			if (!empty($this -> minScriptBaseUrl)) {
				$basePathRaw = (($basePathRaw = @parse_url($this -> minScriptBaseUrl, PHP_URL_PATH)) && substr($basePathRaw, -1) == '/') ? $basePathRaw .= 'dummy' : $basePathRaw;
				$basePath = rtrim(dirname($basePathRaw), '/\\');
			}
			$path = (isset($basePath)) ? $docRoot . $basePath . '/' . (string)@parse_url($url, PHP_URL_PATH) : $docRoot . $requestPath . '/' . (string)@parse_url($url, PHP_URL_PATH);
			Yii::log('The URL "' . $url . '" is relative to the current request "' . Yii::app() -> request -> url . '" and points to the file system path "' . $path . '".', CLogger::LEVEL_INFO, 'ext.minScript.components.ExtMinScript');
		}
		return rtrim($path, '/\\');
	}

	/**
	 * Create a minScript group from the supplied files.
	 * @param array $files File system paths for the files.
	 * @return string URL for the group.
	 * @throws CException if the minScript groups folder is not writable or couldn't be created.
	 * @since 2.0
	 */
	public function minScriptCreateGroup($files) {
		$files = (array)$files;        
		$filesSerialized = serialize($files);
		$groupFile = Yii::app() -> runtimePath . '/minScript/groups/' . md5(Yii::app() -> id . $filesSerialized);
		// Create group if necessary
		if (@is_file($groupFile) === false) {
			$groupsPath = dirname($groupFile);
			if (@is_dir($groupsPath) === false) {
				@mkdir($groupsPath, 0777, true);
			}
			if (@is_writable($groupsPath) !== true) {
				throw new CException('The minScript groups folder "' . $groupsPath . '" is not writable or couldn\'t be created. Please check file and folder permissions.');
			}
			@file_put_contents($groupFile, $filesSerialized, LOCK_EX);
		}
		// Get last modified timestamp
		$lm = $this -> minScriptGetLm($files, true);
		// Generate URL
		$params['g'] = basename($groupFile);
		if ($this -> minScriptDebug === true) {
			$params['debug'] = 1;
		} elseif ($lm !== false) {
			$params['lm'] = $lm;
		}
		return Yii::app() -> createUrl($this -> minScriptControllerId . '/serve', $params);
	}

	/**
	 * Get files from the specified minScript group.
	 * @param string $id ID of the group.
	 * @return array Files from the group or false if group doesn't exist.
	 * @since 2.0
	 */
	public function minScriptGetGroup($groupId) {
		return (($filesSerialized = @file_get_contents(Yii::app() -> runtimePath . '/minScript/groups/' . $groupId)) === false) ? false : unserialize($filesSerialized);
	}

	/**
	 * Process files registered with CClientScript::registerCssFile() or CClientScript::registerScriptFile().
	 * @param string $type Type of files to process.
	 * @param integer $position Position of scripts to process. Not needed for CSS files.
	 * @since 2.0
	 */
	protected function _minScriptProcessor($type, $position = '') {
		// Get file system paths for registered files and reset CClientScript::$scriptFiles or CClientScript::$cssFiles
		$files = array();
		if ($type == 'scripts') {
			// Loop through registered script files
			if (isset($this -> scriptFiles[$position])) {
				foreach ($this->scriptFiles[$position] as $scriptUrl) {
					foreach ($this -> minScriptDisableMin as $disableMinPattern) {
						if (preg_match($disableMinPattern, $scriptUrl)) {
							continue 2;
						}
					}
					$files[$position][$scriptUrl] = $this -> _minScriptGetPath($scriptUrl);
					unset($this -> scriptFiles[$position][$scriptUrl]);
				}
			}
		} elseif ($type == 'css') {
			// Loop through registered CSS files and ensure that the correct order is kept
			$cssSort = 0;
			foreach ($this->cssFiles as $cssUrl => $cssMedia) {
                //logs($cssUrl);
                
				foreach ($this -> minScriptDisableMin as $disableMinPattern) {
					if (preg_match($disableMinPattern, $cssUrl)) {
						continue 2;
					}
				}

				if (isset($prevCssMedia) && $cssMedia == $prevCssMedia) {
					$cssMediaSort = $cssMedia . 'minScriptCssSort' . $cssSort;
				} else {
					$cssMediaSort = $cssMedia . 'minScriptCssSort' . ($cssSort = $cssSort + 1);
				}
				$prevCssMedia = $cssMedia;
				$files[$cssMediaSort][$cssUrl] = $this -> _minScriptGetPath($cssUrl);
				unset($this -> cssFiles[$cssUrl]);
			}
		}
		// Loop through registered positions/medias
		foreach (array_keys($files) as $key) {
			$urls = array();
			// Get URLs for registered files
			foreach ($files[$key] as $url => $path) {
				if ($path !== false) {
					$paths[] = $path;
				} else {
					// To keep the correct order, the minScript group creation process is split up if an external/excluded URL is detected
					if (!empty($paths)) {
						$urls[] = $this -> minScriptCreateGroup($paths);
						$paths = array();
					}
					$urls[] = $url;
				}
			}
			if (!empty($paths)) {
				$urls[] = $this -> minScriptCreateGroup($paths);
				$paths = array();
			}
			// Store URLs back to CClientScript::$scriptFiles or CClientScript::$cssFiles
			foreach ($urls as $url) {
				if ($type == 'scripts') {
					$this -> scriptFiles[$key][$url] = $url;
				} elseif ($type == 'css') {
					$keySegments = explode('minScriptCssSort', $key);
					$this -> cssFiles[$url] = array_shift($keySegments);
				}
			}
		}
	}

	/**
	 * Inserts the scripts at the beginning of the body section (overrides parent method).
	 * @param string $output the output to be inserted with scripts.
	 * @since 2.0
	 */
	public function renderBodyBegin(&$output) {
		$this -> _minScriptProcessor('scripts', self::POS_BEGIN);
		$this -> _minScriptProcessor('css');
		
		$html='';
		
		foreach($this->cssFiles as $url=>$media)
			$html.=CHtml::cssFile($url,$media)."\n";
		foreach($this->css as $css)
			$html.=CHtml::css($css[0],$css[1])."\n";
		
		if(isset($this->scriptFiles[self::POS_BEGIN]))
		{
			foreach($this->scriptFiles[self::POS_BEGIN] as $scriptFileUrl=>$scriptFileValue)
			{
				if(is_array($scriptFileValue))
					$html.=CHtml::scriptFile($scriptFileUrl,$scriptFileValue)."\n";
				else
					$html.=CHtml::scriptFile($scriptFileUrl)."\n";
			}
		}
		if(isset($this->scripts[self::POS_BEGIN]))
			$html.=$this->renderScriptBatch($this->scripts[self::POS_BEGIN]);

		if($html!=='')
		{
			$count=0;
			$output=preg_replace('/(<body\b[^>]*>)/is','$1<###begin###>',$output,1,$count);
			if($count)
				$output=str_replace('<###begin###>',$html,$output);
			else
				$output=$html.$output;
		}
	}

	/**
	 * Inserts the scripts at the end of the body section (overrides parent method).
	 * @param string $output the output to be inserted with scripts.
	 * @since 2.0
	 */
	public function renderBodyEnd(&$output) {
		$this -> _minScriptProcessor('scripts', self::POS_END);
		parent::renderBodyEnd($output);
	}

	/**
	 * Inserts the scripts in the head section (overrides parent method).
	 * @param string $output the output to be inserted with scripts.
	 * @since 2.0
	 */
	public function renderHead(&$output) {
		$this -> _minScriptProcessor('scripts', self::POS_HEAD);		
		
		$html='';
		foreach($this->metaTags as $meta)
			$html.=CHtml::metaTag($meta['content'],null,null,$meta)."\n";
		foreach($this->linkTags as $link)
			$html.=CHtml::linkTag(null,null,null,null,$link)."\n";
        foreach($this->cssFiles as $url=>$media)
            $html.=CHtml::cssFile($url,$media)."\n";
		if($this->enableJavaScript)
		{
			if(isset($this->scriptFiles[self::POS_HEAD]))
			{
				foreach($this->scriptFiles[self::POS_HEAD] as $scriptFileValueUrl=>$scriptFileValue)
				{
					if(is_array($scriptFileValue))
						$html.=CHtml::scriptFile($scriptFileValueUrl,$scriptFileValue)."\n";
					else
						$html.=CHtml::scriptFile($scriptFileValueUrl)."\n";
				}
			}

			if(isset($this->scripts[self::POS_HEAD]))
				$html.=$this->renderScriptBatch($this->scripts[self::POS_HEAD]);
		}

		if($html!=='')
		{
			$count=0;
			$output=preg_replace('/(<title\b[^>]*>|<\\/head\s*>)/is','<###head###>$1',$output,1,$count);
			if($count)
				$output=str_replace('<###head###>',$html,$output);
			else
				$output=$html.$output;
		}
	}

	public function registerScript($id,$script,$position=null,array $htmlOptions=array(), $isMin = false)
	{
		if (param('useMinify', false) && $isMin && ($position == self::POS_BEGIN || $position == self::POS_END || $position == self::POS_HEAD)) {	
			$fileGenName = 'generate-'. Yii::app()->language. '-'.Yii::app()->theme->name. '-'. $id . '.js';

			$hashFile = $lengthFile = '';
			
			$hashScript = hash('crc32b', $script);
			
			$fileContent = Yii::app()->controller->assetsGenPath.DIRECTORY_SEPARATOR.$fileGenName;
			if (file_exists($fileContent)) {				
				$hashFile = hash_file('crc32b', $fileContent);
			}

			$add = '';
			if ($hashFile != $hashScript) {
				$add = '?v='. microtime();
				file_put_contents(Yii::app()->controller->assetsGenPath.DIRECTORY_SEPARATOR.$fileGenName, $script, LOCK_EX);
			}

			Yii::app()->clientScript->registerScriptFile(Yii::app()->controller->assetsGenUrl.$fileGenName.$add, CClientScript::POS_BEGIN);
		}
		else {
			if($position===null)
				$position=$this->defaultScriptPosition;
			$this->hasScripts=true;
			if(empty($htmlOptions))
				$scriptValue=$script;
			else
			{
				if($position==self::POS_LOAD || $position==self::POS_READY)
					throw new CException(Yii::t('yii','Script HTML options are not allowed for "CClientScript::POS_LOAD" and "CClientScript::POS_READY".'));
				$scriptValue=$htmlOptions;
				$scriptValue['content']=$script;
			}
			$this->scripts[$position][$id]=$scriptValue;
			if($position===self::POS_READY || $position===self::POS_LOAD)
				$this->registerCoreScript('jquery');
			$params=func_get_args();
			$this->recordCachingAction('clientScript','registerScript',$params);
			return $this;
		}
	}

}