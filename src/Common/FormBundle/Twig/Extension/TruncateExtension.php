<?php

namespace Common\FormBundle\Twig\Extension;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TruncateExtension
 *
 * @author tnakaarai
 */
class TruncateExtension extends \Twig_Extension {
    
    public function getFilters()
    {
        return array(
            'truncate' => new \Twig_Filter_Method($this, 'truncateFilter'),
        );
    }

    public function truncateFilter($str,$size = 40)
    {
        if (mb_strlen($str, "UTF-8") > $size){
            $str = mb_substr($str,0,$size,"utf-8") . "...";
        }
        
        return $str;
    }

    public function getName()
    {
        return 'twig.extension.truncate';
    }
}

?>
