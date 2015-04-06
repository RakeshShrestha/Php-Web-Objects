<?php

class Form {

    private static $_context = null;

    public static function getContext() {
        if (self::$_context === null) {
            self::$_context = new self;
        }
        return self::$_context;
    }

    public static function open(array $attributes) {
        $html = '<form';
        if (!empty($attributes)) {
            foreach ($attributes as $attribute => $value) {
                if (in_array($attribute, array('action', 'method', 'id', 'class', 'enctype')) and !empty($value)) {
                    if ($attribute === 'method' AND ($value !== 'post' OR $value !== 'get')) {
                        $value = 'post';
                    }
                    $html .= ' ' . $attribute . '="' . $value . '"';
                }
            }
        }
        return $html . '>';
    }

    public static function input(array $attributes) {
        $html = '<input';
        if (!empty($attributes)) {
            foreach ($attributes as $attribute => $value) {
                if (in_array($attribute, array('type', 'id', 'class', 'name', 'value')) and !empty($value)) {
                    $html .= ' ' . $attribute . '="' . $value . '"';
                }
            }
        }
        return $html . '>';
    }

    public static function textarea(array $attributes) {
        $html = '<textarea';
        $content = '';
        if (!empty($attributes)) {
            foreach ($attributes as $attribute => $value) {
                if (in_array($attribute, array('rows', 'cols', 'id', 'class', 'name', 'value')) and !empty($value)) {
                    if ($attribute === 'value') {
                        $content = $value;
                        continue;
                    }
                    $html .= ' ' . $attribute . '="' . $value . '"';
                }
            }
        }
        return $html . '>' . $content . '</textarea>';
    }

    public static function close() {
        return '</form>';
    }

}
