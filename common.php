<?php

class Shorten
{
    protected static $digits;
    protected static $array;
    protected static $db;

    public static function configure($digits, $database)
    {
        self::$digits = $digits['digits'];
        self::$array  = $digits['array'];
        self::$db     = $database;
    }

    public static function route($id)
    {
        if (!preg_match("/^[" . preg_quote(self::$digits) . "]+$/", $id)) {
            return NULL;
        }
        return self::$db->id(self::id2int($id));
    }

    public static function create($url)
    {
        $id = self::$db->insert($url);

        return self::int2id($id);
    }

    protected static function id2int($id) 
    {
        return self::base_convert($id, self::$digits);
    }

    protected static function int2id($id) 
    {
        return self::base_convert($id, "0123456789", self::$digits);
    }

    protected static function base_convert($numstring, $baseFrom = "0123456789", $baseTo = "0123456789") 
    { 
        $numstring = (string) $numstring; 
        $baseFromLen = strlen($baseFrom); 
        $baseToLen = strlen($baseTo); 
        if ($baseFrom == "0123456789") {
            $decVal = (int) $numstring; 
        } else { 
            $decVal = 0; 
            for ($len = (strlen($numstring) - 1); $len >= 0; $len--)  { 
                $char = substr($numstring, 0, 1); 
                $pos = strpos($baseFrom, $char); 
                if ($pos !== FALSE) 
                { 
                    $decVal += $pos * ($len > 0 ? pow($baseFromLen, $len) : 1); 
                } 
                $numstring = substr($numstring, 1); 
            } 
        } 
        if ($baseTo == "0123456789")  { 
            $numstring = (string) $decVal; 
        } else { 
            $numstring = FALSE; 
            $nslen = 0; 
            $pos = 1; 
            while ($decVal > 0)  { 
                $valPerChar = pow($baseToLen, $pos); 
                $curChar = floor($decVal / $valPerChar); 
                if ($curChar >= $baseToLen)  { 
                    $pos++; 
                } else { 
                    $decVal -= ($curChar * $valPerChar); 
                    if ($numstring === FALSE) { 
                        $numstring = str_repeat($baseTo{1}, $pos); 
                        $nslen = $pos; 
                    } 
                    $numstring = substr($numstring, 0, ($nslen - $pos)) 
                        . $baseTo[(int)$curChar]
                        . substr($numstring, (($nslen - $pos) + 1)); 
                    $pos--; 
                } 
            } 
            if ($numstring === FALSE) $numstring = $baseTo{1}; 
        } 
        return $numstring; 
    }
} 

