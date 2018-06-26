<?php

if (!function_exists('clog_messages')) {

    /** Cloudbric log_message
     *
     * Codeigniter log_messsage base log function
     *
     * @param $level
     * @param $message
     * @param array $params
     */
    function clog_message($level, $message, $params = array())
    {
        static $_log;

        if ($_log === NULL) {
            // references cannot be directly assigned to static variables, so we use an array
            $_log[0] =& load_class('Log', 'core');
        }

        // custom1: message formatting
        //
        $format_message = vsprintf($message, $params);

        // custom2: make postfix
        //  - filename, function, line
        $backtrace_array = debug_backtrace();
        $filename_with_path = $backtrace_array[0]["file"];
        $filename_start_position = strrpos($filename_with_path, '/');
        $filename_length = strlen($filename_with_path)-$filename_start_position;

        $postfix = sprintf(" [%s:%s:%d]",
            substr($filename_with_path, $filename_start_position + 1, $filename_length),
            $backtrace_array[1]["function"],
            $backtrace_array[0]["line"]);

        $format_message .= $postfix;

        $_log[0]->write_log($level, $format_message);
    }
}