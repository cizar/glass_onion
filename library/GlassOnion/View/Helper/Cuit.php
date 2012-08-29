<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_Cuit
{
    /**
     * Retorna el valor de CUIT/CUIL con el formato XX-XXXXXXXX-X.
     *
     * CUIT/CUIL es la clave única de identificación tributaria/laboral del ANSES
     *
     * @param string $value
     * @return string
     */
    public function cuit($value)
    {
        $value = str_replace('-', '', $value);

        return sprintf('%s-%s-%s',
            substr($value,  0,  2),  // Tipo de identidad
            substr($value,  2, -1),  // Número de documento
            substr($value, -1,  1)   // Dígito verificador
        );
    }
}
