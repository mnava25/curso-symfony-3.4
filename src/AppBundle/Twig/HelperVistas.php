<?php

namespace AppBundle\Twig;
    

class HelperVistas extends \Twig_Extension{
    
    public function getFunctions(){
        return array(
            "generateTable" => new \Twig_Function_Method($this,"generateTable")
        );
    }
    
    public function generateTable($result_set){
        $table ="<table class='table' border=1>";
        for ($i = 0; $i < count($result_set); $i++) {
            $table .= "<tr>"; 
            for ($f = 0; $f < count($result_set[$i]); $f++) {
                $resultSet_values = array_values($result_set[$i]);
                $table .= "<td>".$resultSet_values[$f]."</td>";
            }
            $table .= "</tr>";
        }
        $table .= "</table>";
        return $table;
    }
    
    public function getName() {
        return "app_bundle";
    }
}