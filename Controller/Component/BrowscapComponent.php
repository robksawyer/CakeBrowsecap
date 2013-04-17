<?php

require(dirname(dirname(dirname(__FILE__))).'/Vendor/phpbrowscap/src/phpbrowscap/Browscap.php');

// The Browscap class is in the phpbrowscap namespace, so import it
use phpbrowscap\Browscap;

App::uses('Component', 'Controller');
class BrowscapComponent extends Component{

	/**
    * Allow direct access to the browscap API
    * @link http://code.google.com/p/phpbrowscap/wiki/QuickStart
    * @access public
    */
	public $Browscap = null;
	public $cacheDir = null;
	
	/**
	 * Initialize
	 */
	public function initialize(Controller $controller) {
		$this->cacheDir = $this->createCacheDir(CACHE.'browsecap');
		$this->Browscap = new Browscap($this->cacheDir);
    }

    protected function createCacheDir($cacheDir = null) {
    	if(empty($cacheDir)){
    		$cacheDir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'browscap_testing';
    	}

        if (!is_dir($cacheDir)) {
            if (false === @mkdir($cacheDir, 0777, true)) {
                throw new \RuntimeException(sprintf('Unable to create the "%s" directory', $cacheDir));
            }
        }

        $this->cacheDir = $cacheDir;
        return $this->cacheDir;
    }

    /**
     * 
     */
	public function startup() { }

	/**
	 * Get the user's browser info
	 */
	public function get($agent = null, $asArray = true) {
		if(empty($agent)) $agent = env('HTTP_USER_AGENT');
		$browser = $this->Browscap->getBrowser($agent, $asArray);
		return (empty($path))
			? $browser
			: Set::extract($browser, $path);
	}

	/**
	 * Test to see if it's working
	 */
	public function test(){
		// Create a new Browscap object (loads or creates the cache)
		//$bc = new Browscap($this->cacheDir);
		// Get information about the current browser's user agent
		$current_browser = $this->get();
		// Output the result
		echo '<pre>'; // some formatting issues ;)
		print_r($current_browser);
		echo '</pre>';
	}

	protected function removeCacheDir(){
        if (isset($this->cacheDir) && is_dir($this->cacheDir)) {
            if (false === @rmdir($this->cacheDir)) {
                throw new \RuntimeException(sprintf('Unable to remove the "%s" directory', $this->cacheDir));
            }
            $this->cacheDir = null;
        }
    }

    public function tearDown() {
        $this->removeCacheDir();
    }

}

?>