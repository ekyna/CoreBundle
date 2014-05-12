<?php

namespace Ekyna\Bundle\CoreBundle\Form\Util;

/**
 * Datetime.
 *
 * @author Stephane Collot
 * @see https://github.com/stephanecollot/DatetimepickerBundle/blob/master/Form/Type/DatetimeType.php
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Datetime
{
    /**
     * Convert the Bootstrap Datetimepicker date format to PHP date format
     */
    public static function convertMalotToIntlFormater($formatter)
    {
        $malotFormater  =  array("yyyy", "ss", "ii", "hh", "HH", "dd", "mm", "MM",   "yy");
        $intlFormater   =  array("yyyy", "ss", "mm", "HH", "hh", "dd", "MM", "MMMM", "yy");
        $return = str_replace($malotFormater, $intlFormater, $formatter);

        $malotFormater  =  array("p", "P", "s", "i", "h", "H", "d", "m", "M");
        $intlFormater   =  array("a", "a", "s", "m", "H", "h", "d", "M", "MMM");
        $return = str_replace($malotFormater, $intlFormater, $return);

        $patterns = preg_split('([\\\/.:_;,\s-\ ]{1})', $formatter);
        $exits = array();

        foreach ($patterns as $index => $val) {
            switch ($val) {
            	case 'yyyy':
            	    $exits[$val] = 'yyyy';
            	    break;
            	case 'ss':
            	    $exits[$val] = 'ss';
            	    break;
            	case 'ii':
            	    $exits[$val] = 'mm';
            	    break;
            	case 'hh':
            	    $exits[$val] = 'HH';
            	    break;
            	case 'HH':
            	    $exits[$val] = 'hh';
            	    break;
            	case 'dd':
            	    $exits[$val] = 'dd';
            	    break;
            	case 'mm':
            	    $exits[$val] = 'MM';
            	    break;
            	case 'MM':
            	    $exits[$val] = 'MMMM';
            	    break;
            	case 'p':
            	case 'P':
            	    $exits[$val] = 'a';
            	    break;
            	case 's':
            	    $exits[$val] = 's';
            	    break;
            	case 'i':
            	    $exits[$val] = 'm';
            	    break;
            	case 'h':
            	    $exits[$val] = 'H';
            	    break;
            	case 'H':
            	    $exits[$val] = 'h';
            	    break;
            	case 'd':
            	    $exits[$val] = 'd';
            	    break;
            	case 'm':
            	    $exits[$val] = 'M';
            	    break;
            	case 'M':
            	    $exits[$val] = 'MMM';
            	    break;
            }
        }

        return str_replace(array_keys($exits), array_values($exits), $formatter);
    }
}
