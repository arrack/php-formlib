<?php
/**
 * FormLib
 * 產生Form
 *
 * @author Arrack
 */
class FormLib
{
    public static $default_class = '';
    private static $reserved_keywords = ['id', 'name', 'type', 'value'];

    public static function __callStatic($name, $arguments)
    {
        return self::input($name, $arguments[0], $arguments[1], $arguments[2]);
    }

    /**
     * input
     *
     * @static
     * @param string $type input type
     * @param string $name input name
     * @param string $value input value
     * @param array $attributes attributes
     * @return string html
     */
    public static function input($type, $name, $value, $attributes = [])
    {
        $html = sprintf(
            '<input type="%s" id="%s" name="%s" value="%s"%s>',
            $type,
            $name,
            $name,
            htmlspecialchars($value),
            self::attributesToHtml($attributes)
        );

        return $html;
    }

    /**
     * button
     *
     * @static
     * @param string $value input value
     * @param array $attributes attributes
     * @return string html
     */
    public static function button($value, $attributes = [])
    {
        $html = sprintf(
            '<button type="button"%s>%s</button>',
            self::attributesToHtml($attributes),
            htmlspecialchars($value)
        );
        return $html;
    }

    /**
     * submit
     * get submit
     *
     * @static
     * @param string $value input value
     * @param array $attributes attributes
     * @return string html
     */
    public static function submit($value, $attributes = [])
    {
        $html = sprintf(
            '<button type="submit"%s>%s</button>',
            self::attributesToHtml($attributes),
            $value
        );
        return $html;
    }

    /**
     * text
     * get input textbox
     *
     * @static
     * @param string $name input name
     * @param string $value input value
     * @param array $attributes attributes
     * @return string html
     */
    public static function text($name, $value, $attributes = [])
    {
        if (!isset($attributes['class']) and '' != self::$default_class) {
            $attributes['class'] = self::$default_class;
        }
        return self::input('text', $name, $value, $attributes);
    }

    /**
     * checkbox
     * get input checkbox
     *
     * @static
     * @param string $name input name
     * @param string $value input value
     * @param array $attributes attributes
     * @return string html
     */
    public static function checkbox($name, $value, $attributes = [])
    {
        if ($value == $attributes['value']) {
            $attributes['checked'] = true;
        }
        return self::input('checkbox', $name, $attributes['value'], $attributes);
    }

    /**
     * radio
     * get input radio
     *
     * @static
     * @param string $name input name
     * @param string $value input value
     * @param array $attributes attributes
     * @return string html
     */
    public static function radio($name, $value, $attributes = [])
    {
        if ($value == $attributes['value']) {
            $attributes['checked'] = true;
        }
        return self::input('radio', $name, $attributes['value'], $attributes);
    }

    /**
     * select
     * get input select
     *
     * @static
     * @param string $name input name
     * @param string $value input value
     * @param array $options select options
     * @param array $attributes attributes
     * @return string html
     */
    public static function select($name, $value, $options, $attributes = [])
    {
        if (!isset($attributes['class']) and '' != self::$default_class) {
            $attributes['class'] = self::$default_class;
        }

        $id = $name;
        if ($attributes['multiple']) {
            $name .= '[]';
        }

        $value_array = is_array($value) ? $value : [];

        $html = sprintf(
            '<select id="%s" name="%s"%s>',
            $id,
            $name,
            self::attributesToHtml($attributes)
        );

        foreach ($options as $option_value => $option_name) {
            if (is_array($option_name)) {
                $html .= '<optgroup label="' . $option_value . '">';
                foreach ($option_name as $suboption_value => $suboption_name) {
                    $html .= sprintf(
                        '<option value="%s"%s>%s</option>',
                        htmlspecialchars($suboption_value),
                        ($suboption_value == $value || in_array($suboption_value, $value_array)) ? ' selected="selected"' : '',
                        htmlspecialchars($suboption_name)
                    );
                }
                $html .= '</optgroup>';
            } else {
                $html .= sprintf(
                    '<option value="%s"%s>%s</option>',
                    htmlspecialchars($option_value),
                    ($option_value == $value || in_array($option_value, $value_array)) ? ' selected="selected"' : '',
                    htmlspecialchars($option_name)
                );
            }
        }

        $html .= '</select>';
        return $html;
    }

    /**
     * textarea
     * get textarea
     *
     * @static
     * @param string $name input name
     * @param string $value input value
     * @param array $attributes attributes
     * @return string html
     */
    public static function textarea($name, $value, $attributes = [])
    {
        if (!isset($attributes['class']) and '' != self::$default_class) {
            $attributes['class'] = self::$default_class;
        }

        $html = sprintf(
            '<textarea id="%s" name="%s" %s>%s</textarea>',
            $name,
            $name,
            self::attributesToHtml($attributes),
            htmlspecialchars($value)
        );
        return $html;
    }

    /**
     * attributesToHtml
     * attributes to html
     *
     * @static
     * @param array $attributes attributes
     * @return string html
     */
    public static function attributesToHtml($attributes)
    {
        $html = '';
        if (is_array($attributes)) {
            foreach ($attributes as $attribute => $attribute_value) {
                if (in_array($attribute, self::$reserved_keywords)) {
                    continue;
                }
                if (true === $attribute_value) {
                    $attribute_value = $attribute;
                }
                $html .= sprintf(' %s="%s"', $attribute, htmlspecialchars($attribute_value));
            }
        }
        return $html;
    }

    /**
     * optionsFromNumber
     *
     * @static
     * @param int $start attributes
     * @param int $end attributes
     * @param int $step attributes
     * @param boolean $fillzero auto file zero
     * @return array select options
     */
    public static function optionsFromNumber($start, $end, $step = 1, $fillzero = true)
    {
        $options = [];

        for ($i = $start; $i <= $end; $i += $step) {
            $show = $i;
            if ($fillzero) {
                $show = str_pad($show, strlen($end), "0", STR_PAD_LEFT);
            }
            $options[$i] = $show;
        }

        return $options;
    }
}
