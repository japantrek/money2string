<?php

namespace nvbooster\Money2String\TwigExtension;

use nvbooster\Money2String\Money2StringConverter;

/**
 * Money2StringTwigExtension
 */
class Money2StringTwigExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     *
     * @see \Twig_Extension::getFilters()
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('money2string_convert', [$this, 'num2str']),
            new \Twig_SimpleFilter('money2string_pluralize', [$this, 'morph']),
            new \Twig_SimpleFilter('money2string_ucfirst', [$this, 'ucfirst']),
        ];
    }

    /**
     * Возвращает сумму прописью
     * @param integer $num
     *
     * @return string
     */
    public function num2str($num)
    {
        return Money2StringConverter::convert($num);
    }

    /**
     * Склоняем словоформу
     * @param integer $n
     * @param string  $f1
     * @param string  $f2
     * @param string  $f5
     *
     * @return string
     */
    public function morph($n, $f1, $f2, $f5)
    {
        return Money2StringConverter::morph($n, $f1, $f2, $f5);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function ucfirst($string)
    {
        $charset = 'UTF-8';

        return mb_strtoupper(mb_substr($string, 0, 1, $charset), $charset).
            mb_substr($string, 1, mb_strlen($string, $charset), $charset);
    }

    /**
     * {@inheritDoc}
     *
     * @see \Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'nvbooster_money2string_twigextension';
    }
}
