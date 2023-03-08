<?php
    /**
     * A simple class that makes php cli scripting really easy. 
	 * https://github.com/cha55son/php-cli-helper
     *
     * @author Chason Choate <cha55son@gmail.com>
     */
    
    define("CLI_COLOR_START", "\033[");
    define("CLI_COLOR_END"  , "\033[0m");

    // Forground colors
    define("CLI_BLACK"      , CLI_COLOR_START."0;30m"); 
    define("CLI_BLUE"       , CLI_COLOR_START."1;34m");
    define("CLI_BROWN"      , CLI_COLOR_START."0;33m");
    define("CLI_CYAN"       , CLI_COLOR_START."1;36m");
    define("CLI_GRAY"       , CLI_COLOR_START."0;37m");
    define("CLI_GREEN"      , CLI_COLOR_START."1;32m");
    define("CLI_PURPLE"     , CLI_COLOR_START."1;35m");
    define("CLI_RED"        , CLI_COLOR_START."1;31m");
    define("CLI_WHITE"      , CLI_COLOR_START."1;37m");
    define("CLI_YELLOW"     , CLI_COLOR_START."1;33m");

    define("CLI_DARK_BLUE"  , CLI_COLOR_START."0;34m");
    define("CLI_DARK_CYAN"  , CLI_COLOR_START."0;36m");
    define("CLI_DARK_GRAY"  , CLI_COLOR_START."1;30m");
    define("CLI_DARK_GREEN" , CLI_COLOR_START."0;32m");
    define("CLI_DARK_PURPLE", CLI_COLOR_START."0;35m");
    define("CLI_DARK_RED"   , CLI_COLOR_START."0;31m");

    define("CLI_UND_BLACK"  , CLI_COLOR_START."4;30m");
    define("CLI_UND_BLUE"   , CLI_COLOR_START."4;34m");
    define("CLI_UND_CYAN"   , CLI_COLOR_START."4;36m");
    define("CLI_UND_GREEN"  , CLI_COLOR_START."4;32m");
    define("CLI_UND_PURPLE" , CLI_COLOR_START."4;35m");
    define("CLI_UND_RED"    , CLI_COLOR_START."4;31m");
    define("CLI_UND_WHITE"  , CLI_COLOR_START."4;37m");
    define("CLI_UND_YELLOW" , CLI_COLOR_START."4;33m");

    // Background colors
    define("CLI_BG_BLACK"   , CLI_COLOR_START."40m");
    define("CLI_BG_BLUE"    , CLI_COLOR_START."44m");
    define("CLI_BG_CYAN"    , CLI_COLOR_START."46m");
    define("CLI_BG_GRAY"    , CLI_COLOR_START."47m");
    define("CLI_BG_GREEN"   , CLI_COLOR_START."42m");
    define("CLI_BG_MAGENTA" , CLI_COLOR_START."45m");
    define("CLI_BG_RED"     , CLI_COLOR_START."41m");
    define("CLI_BG_WHITE"   , CLI_COLOR_START."107m");
    define("CLI_BG_YELLOW"  , CLI_COLOR_START."43m");

    class CLI {
        public static $outputPrefix = '';
        // Variables to hold the output and status
        // of the last ran command.
        public static $lastCmdStatus = false;
        public static $lastCmdOutput = false;
        // Scren dimensions
        public static $screen = array();
        // Log file
        private static $logFile = '';

        /*
         * Sets the log file. Log all outputs (excluding outLine) to the given file.
         *
         * @param string $filePath A valid file path, if the file does not exist it will be created.
         *
         * @return none.
         */
        public static function setLogFile($filePath) {
           if (!file_exists($filePath)) 
               touch($filePath);
           self::$logFile = $filePath;
           // Add start block for each session
           self::log(PHP_EOL.'+ '.@date("F j, Y, g:i a").' ----------------------'.PHP_EOL);
        }

        /**
         * Builds a simple progress bar.
         * 
         * @param float $percent A fraction between 0 and 1.
         * @param int   $width   The full size of the progress bar including the sides.
         *
         * @return string The progress bar.
         */
        public static function progress($percent = 0, $width = 40) {
            if ($percent > 1 || $percent < 0) 
                $percent = 0;
            $str = self::color('[', CLI_WHITE);
            $complete = ceil(floatval($percent) * ($width - 2));
            for ($i = 0; $i < $width - 2; $i++)
                if ($i < $complete)
                    $str .= self::color('=', CLI_GREEN);
                else if ($i == $complete)
                    $str .= CLI::color('>', CLI_YELLOW);
                else
                    $str .= '-';
            return $str.self::color(']', CLI_WHITE);
        }

        /**
         * Retrieves and caches the user's screen size.
         */
        public static function getScreenSize() {
            self::$screen['width'] = exec('tput cols');
            self::$screen['height'] = exec('tput lines');
            return self::$screen;
        }

        /*
         * Outputs data to the cmdline. The output will have a prefix to 
         * differentiate from the prompt/etc.
         * 
         * @param string  $msg      The data you would like to post to the screen. 
         *                          If $msg starts with a single '-' then the prefix 
         *                          will be excluded.
         * @param boolean $newLine  Do you want a newline at the end or not?
         * @param boolean $exit     Do you want to exit after printing the message or not?
         *
         * @return none.
         */
        public static function out($msg = '', $newLine = true, $exit = false) { 
            $output = (
                preg_match('/^-[^-]+/', $msg) ? 
                    ltrim($msg, '-') : 
                    self::$outputPrefix.$msg
                ).($newLine ? PHP_EOL : ''); 
            echo $output;
            self::log($output);
            if ($exit) exit;
        }

        /**
         * Same as output except this function resets the cursor to the start of the line.
         * 
         * @param string $msg The data you would like to post to the screen. If $msg starts with
         *                    a single '-' then the prefix will be excluded.
         *
         * @return none.
         */
        public static function outLine($msg = '') {
            // Check for resizing
            self::getScreenSize();
            for ($cnt = 0; $cnt < self::$screen['width']; $cnt++)
                echo ' ';
            echo "\r";
            self::out($msg." \r", false);
        }

        /**
         * Simply takes input from the user.
         *
         * @return string Whatever the user enters and then presses enter.
         */
        public static function in($msg = '') {
            if (!empty($msg))
                self::out($msg . ' : ', false);
            $input = trim(fgets(STDIN));
            self::log($input.PHP_EOL);
            return $input;
        }

        /**
         * An extension of the input function, loopInput will keep looping until
         * the user either enters a proper supplied input, if options is set, or 'q' to quit.
         *
         * @param string    $msg        The prompt to show the user for input.
         * @param array     $options    An array of strings representing valid input. 
         *                              If empty, any input is valid.
         *
         * @return string A valid input option.
         */
        public static function loopIn($msg = '', $options = array()) {
            $prompt = '';
            if (empty($options)) { // No options provided
            $prompt = "$msg"; 
            } else { // options+ provided
                $optionStr = '';
                foreach ($options as $index => $opt) 
                    $optionStr .= $opt . (((count($options) - 1) == $index) ? '' : ',');
                $prompt = (empty($msg) ? '' : "$msg ") . self::color("[{$optionStr},q] ", CLI_WHITE);
            }
            $prompt .= ': ';
            // Loop until the user gives a valid answer or quits
            while (true) {
                self::out($prompt, false);
                $input = self::in();
                if (empty($options)) { // If options are empty any answer is valid.
                    if (!empty($input)) return $input;
                } else { // Must be a valid answer
                    foreach ($options as $opt) {
                        if ($input == $opt)     return $opt;
                        else if ($input == 'q') exit;
                    }
                }
            }
        }

        /**
         * Run a shell command. Stderr is redirected to stdout. The output and status 
         * are stored in the class' static variables.
         *
         * @param string $cmd Any shell command. ex. 'cp -r ./here ~/there'
         *
         * @return string The last line from the cmd.
         */
        public static function run($cmd) {
            self::$lastCmdOutput = false;
            self::$lastCmdStatus = false;
            return exec("$cmd 2>&1", self::$lastCmdOutput, self::$lastCmdStatus);
        }

        /**
         * Outputs a message with a success tag.
         *
         * @param string    $msg        A string describing why something was successful.
         * @param boolean   $newLine    Adds a newline to the end of the line.
         * @param boolean   $exit       Exit the program after output.
         *
         * @return none.
         */
        public static function success($msg, $newLine = true, $exit = false) { 
            self::out(self::color("[SUCCESS]", CLI_GREEN)." $msg", $newLine, $exit);
        }

        /**
         * Outputs a message with an error tag.
         *
         * @param string    $msg        A string describing why an error occurred.
         * @param boolean   $newLine    Adds a newline to the end of the line.
         * @param boolean   $exit       Exit the program after output.
         *
         * @return none.
         */
        public static function error($msg, $newLine = true, $exit = false) { 
            self::out(self::color("[ERROR]", CLI_RED)." $msg", $newLine, $exit);
        }

        /**
         * Outputs a message with an info tag.
         *
         * @param string    $msg        A string detailing that something occurred.
         * @param boolean   $newLine    Adds a newline to the end of the line.
         * @param boolean   $exit       Exit the program after output.
         *
         * @return none.
         */
        public static function info($msg, $newLine = true, $exit = false) { 
            self::out(self::color("[INFO]", CLI_DARK_GRAY)." $msg", $newLine, $exit);
        }

        /**
         * Outputs a message with a warning tag.
         *
         * @param string    $msg        A string detailing that something occurred and 
         *                              may have been dangerous.
         * @param boolean   $newLine    Adds a newline to the end of the line.
         * @param boolean   $exit       Exit the program after output.
         *
         * @return none.
         */
        public static function warning($msg, $newLine = true, $exit = false) { 
            self::out(self::color("[WARNING]", CLI_YELLOW)." $msg", $newLine, $exit);
        }
    
        /**
         * Outputs a message with a custom tag.
         *
         * @param string    $type       Any type of identifier such as ALERT,TESTING,etc.
         * @param constant  $color      Any color constant from resources/colors.php. 
         * @param string    $msg        A string detailing that something occurred.
         * @param boolean   $newLine    Adds a newline to the end of the line.
         * @param boolean   $exit       Exit the program after output.
         *
         * @return none.
         */
        public static function custom($type = 'CUSTOM', $color = CLI_YELLOW, $msg = '', $newLine = true, $exit = false) {
            self::out(self::color("[$type]", $color)." $msg", $newLine, $exit);
        }

        /**
         * Outputs an error message and then exits.
         * Uses the error function above.
         *
         * @return none.
         */
        public function fail($msg = '', $newLine = true) {
            self::error($msg, $newLine, true);
        }


        /**
         * Colors a string.
         *
         * @param string    $msg    The string to be colored.
         * @param constant  $fg     A foreground color from the color constants.
         * @param constant  $bg     A background color from the color constants.
         *
         * @return colored string.
         */
        public static function color($msg, $fg = CLI_WHITE, $bg = '') {
           return $fg.$bg.$msg.CLI_COLOR_END; 
        }

        private static function log($data) {
            if (file_exists(self::$logFile) && is_writable(self::$logFile))
                file_put_contents(self::$logFile, $data, FILE_APPEND);
		}
		

		public static function deleteDirectoryAndFiles(string $dir) {
			$files = array_diff(scandir($dir), array('.','..'));
			foreach ($files as $file) {
			(is_dir("$dir/$file")) ? self::deleteDirectoryAndFiles("$dir/$file") : unlink("$dir/$file");
			}
			return rmdir($dir);
		}
    }
?>
