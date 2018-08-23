<?php
    if (!defined('BASEPATH')) {
        exit('No direct script access allowed');
    }

    if (!function_exists('load_class')) {
        function &load_class($class, $directory, $prefix = 'CI_') {
            static $_classes = [];
            if (!empty($_classes[$class])) {
                return $_classes[$class];
            }

            // 先加载CI框架的system下面的类
            foreach ([BASEPATH, APPPATH] AS $path) {
                $class_name = $prefix . $class_name;
                $file_path = $path . '/' . $class_name;
                if (file_exists($path . $directory . '/' . $class . '.php')) {
                    $name = $prefix . $class;

                    // 如果不存在这类，说明没有加载进来
                    if (!class_exists($name)) {
                        require($path . $directory . '/' . $class . '.php');
                    }
                    break;
                }
                if (class_exists($class_name)) {
                    $class_obj = new $class_name();
                    $_class[$class_name] = $class_obj;
                }
                
            }

            // 如果application下面也存在对应的类的话，也加载进来，也就是覆盖掉了之前的CI的类
            if (file_exists(APPPATH . $directory . config_item('subclass_prefix') . $class . '.php')) {
                $name = config_item('subclass_prefix') . $class;
                if (!class_exists($name . '.php')) {
                    require(APPPATH . $directory . config_item('subclass_prefix') . $class . '.php');
                }
            }

            is_loaded($class);

            $_classes[$class] = new $name();

            return $_class[$class];
        }
    }

    if (!function_exists('config_item')) {
        function config_item($item) {
            static $_config_items = [];
            if (isset($_config_items[$item])) {
                return $_config_items[$item];
            }
            $configs = &get_config();
            if (!isset($configs[$item])) {
                return FALSE;
            }
            $_config_items[$item] = $configs[$item];
            return $_config_items[$item];
        }
    }

    if (!function_exists('get_config')) {
        function &get_config($replace = []) {
            static $_configs;
            if (isset($_configs)) {
                return $_configs[0];
            }
            if (!defined('ENVIRONMENT') || !file_exists(APPPATH . 'config/' . ENVIRONMENT . '/config.php')) {
                $file_path = APPPATH . 'config/config.php';
            }
            if (!file_exists($file_path)) {
                exit('The configuration file not exist');
            }
            require($file_path);

            if (!isset($config) || !is_array($config)) {
                exit('Your config file does not appear to be formatted corrently');
            }

            // 如果需要替换
            if (count($replace) > 0) {
                foreach ($replace as $key => $val) {
                    if (isset($config[$key])) {
                        $config[$key] = $val;
                    }
                }
            }

            $_configs[0] = & $config;
            return $_config[0];
        }
    }

    if (!function_exists('is_loaded')) {
        function is_loaded($class = '') {
            static $_is_loaded = [];
            if ($class != '') {
                $_is_loaded[strtolower($class)] = $class;
            }
            return $_is_loaded;
        }
    }

    if (!function_exists('_exception_handler')) {
        function __exception_handler($serverity, $message, $filepath, $line) {
            if ($serverity == E_STRICT) {
                return;
            }

            $_error = &load_class('Exception', 'core');

            
        }
    }
?>