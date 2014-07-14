<?php
/**
 * Generic helper methods
 */
namespace SimplePostPreview\Helpers;

class Utils
{
    /**
     * Convert string from camelCase to underscore format.
     *
     * @param string $string
     * @return string $string
     */
    public static function fromCamelCase($string)
    {
        $string[0] = strtolower($string[0]);
        $function = create_function('$c', 'return "_" . strtolower($c[1]);');

        return preg_replace_callback('/([A-Z])/', $function, $string);
    }

    /**
     * Convert string to from underscore format to camelCase.
     *
     * @param string $string
     * @param bool $capitaliseFirstChar
     * @return string $string
     */
    public static function toCamelCase($string, $capitaliseFirstChar = false)
    {
        if ($capitaliseFirstChar) {
            $string[0] = strtoupper($string[0]);
        }

        $function = create_function('$c', 'return strtoupper($c[1]);');

        return preg_replace_callback('/_([a-z])/', $function, $string);
    }

    /**
     * Normalize string.
     *
     * @param string $string
     */
    public static function normalizeString($string)
    {
        $string = self::removeSpecialChars($string);
        $string = str_replace(' ', '_', $string);
        $string = preg_replace('/[^A-Za-z0-9\-_]/', '', $string);
        $string = strtolower($string);

        return $string;
    }

    /**
     * Remove special chars.
     *
     * @param string $string
     * @return string $string
     */
    public static function removeSpecialChars($string)
    {
        $table = array(
            'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj', 'Ž'=>'Z', 'ž'=>'z', 'C'=>'C', 'c'=>'c', 'C'=>'C', 'c'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E',
            'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O',
            'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U',
            'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a',
            'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o',
            'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'R'=>'R', 'r'=>'r',
        );

        return strtr($string, $table);
    }
}
