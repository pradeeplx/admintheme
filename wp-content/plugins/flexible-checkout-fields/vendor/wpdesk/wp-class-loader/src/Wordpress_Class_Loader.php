<?php

/**
 * Guards class versioning. You have to register class and after a wile ie. in "plugins _registered" hook
 * you can instantiate this class with confidence that is would be the newest.
 */
class WPDesk_Wordpress_Class_Loader
{
    const PATH_KEY = 'path';
    const CLASS_NAME_KEY = 'class_name';
    const PLUGIN_NAME_KEY = 'plugin_name';
    const INTERFACE_NAME_KEY = 'interface_name';
    const PLUGIN_RELEASE_TIMESTAMP_KEY = 'plugin_release_timestamp';

    /** @var string */
    private $plugin_name;

    /** @var int */
    private $plugin_release_timestamp;

    /**
     * @param string $plugin_name Some unique plugin name
     * @param string $plugin_release_timestamp Timestamp in string format that strtotime can understand
     */
    public function __construct($plugin_name, $plugin_release_timestamp)
    {
        if (empty($plugin_name) || empty($plugin_release_timestamp)) {
            throw new RuntimeException('Empty string in constructor');
        }

        $this->plugin_name              = $plugin_name;
        $this->plugin_release_timestamp = strtotime($plugin_release_timestamp);

        if (empty($this->plugin_release_timestamp)) {
            throw new RuntimeException('Release timestamp is invalid: ' . $plugin_release_timestamp);
        }
    }

    /**
     * Registers class for further use. Use before hook call.
     *
     * @param string $path Path of file with this class
     * @param string $class_name Name of the class in file
     * @param string $interface_name Interface name of the class or some other alias. Can be also just class name.
     */
    public function register_class($path, $class_name, $interface_name)
    {
        if (empty($path) || empty($class_name) || empty($interface_name)) {
            throw new RuntimeException('Invalid register_class. Empty data');
        }

        $registry = $this->get_registry();
        if ( ! is_array($registry)) {
            $registry = array();
        }
        if ( ! isset($registry[$interface_name])) {
            $registry[$interface_name] = array();
        }

        $registry[$interface_name][$this->plugin_name] = array(
            self::PATH_KEY                     => $path,
            self::CLASS_NAME_KEY               => $class_name,
            self::INTERFACE_NAME_KEY           => $interface_name,
            self::PLUGIN_NAME_KEY              => $this->plugin_name,
            self::PLUGIN_RELEASE_TIMESTAMP_KEY => $this->plugin_release_timestamp
        );
        $this->save_registry($registry);
    }

    /**
     * Returns remembered registry.
     *
     * @return array
     */
    private function get_registry()
    {
        global $wpdesk_wordpress_class_loader_registry;

        if ( ! is_array($wpdesk_wordpress_class_loader_registry)) {
            return array();
        }

        return apply_filters('class_loader_registry', $wpdesk_wordpress_class_loader_registry);
    }

    /**
     * Destroys any info about given registered class
     *
     * @param string $interface_name Interface name of the class or some other alias. Can be also just class name.
     * @param string $class_name Name of the class in file
     * @param string $path Path of file with this class
     */
    private function unregister_class($interface_name, $class_name, $path)
    {
        $registry = $this->get_registry();
        foreach ($registry[$interface_name] as $index => $value) {
            if ($value[self::PATH_KEY] === $path && $value[self::CLASS_NAME_KEY] === $class_name) {
                unset($registry[$interface_name][$index]);
            }
        }

        $this->save_registry($registry);
    }

    /**
     * Saves registry in memory
     *
     * @param array $registry
     */
    private function save_registry($registry)
    {
        global $wpdesk_wordpress_class_loader_registry;

        $wpdesk_wordpress_class_loader_registry = $registry;
    }

    /**
     * Search for best available class and returns class loader record for it.
     *
     * @param string $interface_name Interface name of the class or some other alias. Can be also just class name.
     *
     * @return array
     */
    private function get_best_registry_record($interface_name)
    {
        $registry = $this->get_registry();

        if (!isset($registry[$interface_name])) {
            throw new RuntimeException($interface_name . ' interface is not registered');
        }
        $registered_classes = $registry[$interface_name];
        if (is_array($registered_classes)) {
            usort($registered_classes, apply_filters('wpdesk_class_loader_sort', 'wpdesk_class_loader_sort_desc'));

            return reset($registered_classes);
        }

        throw new RuntimeException('Could not find record ' . $interface_name);
    }

    /**
     * Checks if registry record is valid. Plugin that registered exists and is working.
     *
     * @param array $registry_record
     *
     * @return bool
     */
    private function is_record_invalid($registry_record)
    {
        // TODO: HOTFIX!
//        if ( ! function_exists('is_plugin_active')) {
//            require_once ABSPATH . 'wp-admin/includes/plugin.php';
//        }

        return ! file_exists($registry_record[self::PATH_KEY]);
    }

    /**
     * Returns final class name for given alias/interface.
     * This method also loads this class if was not loaded before.
     *
     * @param string $interface_name Interface name of the class or some other alias. Can be also just class name.
     *
     * @throws RuntimeException
     *
     * @return array
     */
    public function get_class_name($interface_name)
    {
        $registry_record = $this->get_best_registry_record($interface_name);

        if (is_array($registry_record) && ! empty($registry_record)) {
            if ($this->is_record_invalid($registry_record)) {
                $this->unregister_class(
                    $registry_record[self::INTERFACE_NAME_KEY],
                    $registry_record[self::CLASS_NAME_KEY],
                    $registry_record[self::PATH_KEY]
                );

                return $this->get_class_name($interface_name);
            }

            if ( ! class_exists($registry_record[self::CLASS_NAME_KEY])) {
                /** @noinspection PhpIncludeInspection */
                require_once $registry_record[self::PATH_KEY];
            }

            return $registry_record[self::CLASS_NAME_KEY];
        }

        throw new RuntimeException('Could not find interface ' . $interface_name);
    }

    /**
     * Creates instance of the given class.
     *
     * @param string $interface_name Interface name of the class or some other alias. Can be also just class name.
     * @param array $instance_parameters Constructor parameters for a given class
     *
     * @return object
     * @throws ReflectionException
     */
    public function create_instance($interface_name, $instance_parameters = array())
    {
        $class_name = $this->get_class_name($interface_name);

        $reflection = new \ReflectionClass($class_name);

        return $reflection->newInstanceArgs($instance_parameters);
    }
}

if ( ! function_exists('wpdesk_class_loader_sort_desc')) {
    /**
     * Sort helper function. Sorts desc by given date so newest are first.
     *
     * @param array $a Class loader record.
     * @param array $b Class loader record.
     *
     * @return int
     */
    function wpdesk_class_loader_sort_desc($a, $b)
    {
        $a_key = $a[WPDesk_Wordpress_Class_Loader::PLUGIN_RELEASE_TIMESTAMP_KEY];
        $b_key = $b[WPDesk_Wordpress_Class_Loader::PLUGIN_RELEASE_TIMESTAMP_KEY];

        if ($a_key === $b_key) {
            return 0;
        }

        return ($a_key > $b_key) ? -1 : 1;
    }
}