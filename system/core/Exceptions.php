<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class CI_Exceptions {
        private $action;
        private $ob_level;

        public function __construct() {
            $this->ob_level = ob_get_level();
        }

        public function show_error($heading, $message, $template = 'error_general', $status_code) {
            set_status_code($status_code);
            $message = '<p>' . implode('</p><p>', !is_array($message) ? [$message] : $message) . '</p>';
            if (ob_get_level() > $this->ob_level + 1) {
                ob_end_flush();
            }
            ob_start();
            include(APPPATH . 'errors/' . $template . '.php');
            $buffer = ob_get_contents();
            ob_end_clean();
            return $buffer;
        }

        public function show_404($page = '', $log_error = TRUE) {
            $heading = '404 Page Not Found';
            $message = 'The page you request is not found';
            echo $this->show_error($heading, $message, 'error_404', 404);
            exit;
        }
    }
?>