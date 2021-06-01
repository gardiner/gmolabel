<?php

namespace Quintlab\GmoLabel;


class CSV {
    public static function parse_file($filename) {
        $csv = file($filename);
        $keys = static::parse_line(array_shift($csv));
        $result = array();
        foreach($csv as $line) {
            $values = static::parse_line($line);
            $result[] = array_combine($keys, $values);
        }
        return $result;
    }

    protected static function parse_line($line) {
        $sep = ';';
        return str_getcsv($line, $sep);
    }
}
