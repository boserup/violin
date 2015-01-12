<?php

namespace Violin\Rules;

class ActiveUrl
{
    /**
     * Run the validation
     *
     * @param  string $name
     * @param  mixed $value
     * @return bool
     */
    public function run($name, $value)
    {
        $url = str_replace(array('http://', 'https://', 'ftp://'), '', strtolower($value));
        
        return !checkdnsrr($url);
    }
}
