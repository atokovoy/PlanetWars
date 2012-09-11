<?php
namespace Util;
/**
 * @copyright 2012
 * @author Anton Tokovoy <anton.tokovoy@modera.net>
 */
class NiceJsonConverter
{
    static public function convert(array $data, $indent = 0)
    {
        $outIndent = str_repeat(' ', $indent * 4);
        $innerIndent = str_repeat(' ', ($indent + 1) * 4);
        $result = $outIndent . '[';
        $newLine = false;
        $body = array();
        foreach ($data as $key => $element) {
            if (is_string($key)) {
                $key = "$key: ";
            } else {
                $key = '';
            }
            if (is_array($element)) {
                $newLine = true;
                $body[] = $key . self::convert($element, $indent + 1);
                continue;
            }
            $body[] = $key . "'$element'";
        }
        if ($newLine) {
            $result.= "\n" . $innerIndent;
            $glue = ",\n" . $innerIndent;
        } else {
            $glue = ", ";
        }
        $result .= implode($glue, $body);
        if ($newLine) {
            $result.= "\n" . $outIndent;
        }
        $result .= ']';

        return $result;
    }
}
