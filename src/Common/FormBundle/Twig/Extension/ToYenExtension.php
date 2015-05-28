<?php

namespace Common\FormBundle\Twig\Extension;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ToYenExtension
 *
 * @author tnakaarai
 */
class ToYenExtension extends \Twig_Extension {
    
    public function getFilters()
    {
        return array(
            'toYen' => new \Twig_Filter_Method($this, 'toYen'),
        );
    }

    public function toYen($str)
    {
        $oku = floor($str / 100000000);
        $man = floor(($str % 100000000) / 10000);
        $nokori = ($str % 100000000) % 10000;

        $result = '';

        if ($oku) $result = $oku . '億';
        if ($man) $result .= $man . '万';
        if ($nokori) $result .= $nokori;

        return $result . '円';
        
    }

    public function getName()
    {
        return 'twig.extension.toYen';
    }
}

?>
