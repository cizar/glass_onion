<?php

/**
 * @category   GlassOnion
 * @package    GlassOnion_Csv
 */
class GlassOnion_Csv
{
    /**
     * The CSV reader factory
     *
     * @return GlassOnion_Csv_Reader
     */
    public static function load()
    {
        $args = func_get_args();
        require_once 'GlassOnion/Csv/Reader.php';
        $class = new ReflectionClass('GlassOnion_Csv_Reader');
        return $class->newInstanceArgs($args);
    }
}
