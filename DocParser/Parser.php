<?php

namespace DocParser;

class Parser
{

    /**
    * The string that we want to parse
    */
    private $string;
    /**
    * Storge for all the PHPDoc parameters list
    * each list contain a sort of paramters
    */
    private $paramsList = array();

    private $curParams = null;

    private $lead = "@";

    private $idx = 0;

    /**
    * Parse each line
    *
    * Takes an array containing all the lines in the string and stores
    * the parsed information in the object properties
    *
    * @param array $lines An array of strings to be parsed
    */
    private function parseLines($lines)
    {
        $result = [];
        foreach ($lines as $line) {
            $data = $this->parseLine($line);
            if ($data) {
                $result[] = $data; //Parse the line
            }
        }

        return $result;
    }
    /**
    * Parse the line
    *
    * Takes a string and parses it as a PHPDoc comment
    *
    * @param string $line The line to be parsed
    * @return mixed False if the line contains no parameters or paramaters
    * that aren't valid otherwise, the line that was passed in.
    */
    private function parseLine($line)
    {
        // Trim the whitespace from the line
        $line = trim($line);

        if (empty($line)) {
            return false;
        } // Empty line

        // if has lead option
        if (strpos($line, $this->lead) === 0) {
            $param = substr($line, strlen($this->lead), strpos($line, ' ') - strlen($this->lead)); // Get the parameter name
            $value = substr($line, strlen($param) + strlen($this->lead) + 1); // Get the value

            return ["annotation" => $param, "doc" => $value];
        }

        return false;
    }

    /**
    * Setup the initial object
    *
    * @param string $string The string we want to parse
    */
    public function __construct($string)
    {
        $this->string = $string;
    }
    /**
    * Parse the string
    */
    public function parse()
    {
        //Get the comment
        if (preg_match('#^/\*\*(.*)\*/#s', $this->string, $comment) === false) {
            die("Error");
        }

        $comment = trim($comment[1]);

        // Get all the lines and strip the * from the first character
        if (preg_match_all('#^\s*\*(.*)#m', $comment, $lines) === false) {
            die('Error');
        }

        return $this->parseLines($lines[1]);
    }
}
