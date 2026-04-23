<?php
class A25_ListenerManager extends A25_StrictObject
{
    protected static $instance;

    /**
     * protected for testing
     */
    protected $listeners = array();

    /**
     * Generally, this class shouldn't be instantiated directly, but should use
     * the static function startUp() instead.  Basically, this should be treated
     * as a singleton.
     */
    public function __construct()
    {
    }

    /**
     * Don't use this directly. Instead, use dependency injection to pass the
     * listeners array into an object. This makes it easier to test.
     *
     * This method can be used at the top level of the object graph, for the
     * default dependency injection.
     */
    public static function all()
    {
        return self::$instance->listeners;
    }
    /**
     * Scan plugin directory and add all listeners
     */
    public static function startUp()
    {
        if (self::$instance) {
            return;
        }

        self::$instance = new A25_ListenerManager();
        self::$instance->scanPluginDir();
    }
    public function scanPluginDir()
    {
        $filenames = $this->scanDir(ServerConfig::webRoot
                . '/custom/A25/Plugin');

        $classNames = $this->figureOutClassNames($filenames);

        foreach ($classNames as $className) {
            $this->listeners[] = new $className();
        }
    }
    /**
     * This function exists purely so we can mock it.
     */
    protected function scanDir($path)
    {
        if (!file_exists(ServerConfig::webRoot . '/custom/A25/Plugin')) {
            return array();
        }

        return scandir(ServerConfig::webRoot . '/custom/A25/Plugin');
    }
    protected function figureOutClassNames(array $filenames)
    {
        $listeners = array();

        foreach ($filenames as $filename) {
            if (preg_match('/\.php$/', $filename)) {
                $listener = preg_replace('/^(.+)\.php$/', 'A25_Plugin_$1', $filename);
                if ($listener) {
                    $listeners[] = $listener;
                }
            }
        }

        return $listeners;
    }

  /**
   * @deprecated - Use dependency injection to pass in $listeners to the object
   * under test, instead.
   *
   * This function isn't usually used with production code, only test code.
   *
   * @param array $listeners
   */
    public function startUpWithListeners($listeners)
    {
        self::$instance = new A25_ListenerManager();
        self::$instance->listeners = $listeners;
    }

  /**
   * @deprecated - Use dependency injection to pass in $listeners to the object
   * under test, instead.
   *
   * This function isn't usually used with production code, only test code.
   */
    public function destroy()
    {
        self::$instance = null;
    }
}

// Load plugins
A25_ListenerManager::startUp();
