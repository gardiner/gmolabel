<?php

namespace Quintlab\GmoLabel;


class Definitions {
    public static function get_definition($title) {
        foreach (static::get_definitions() as $definition) {
            if ($definition['title'] == $title) {
                return $definition;
            }
        }
    }

    public static function get_definitions() {
        return array(
            array(
                'title' => 'Avery Zweckform 25x10mm',
                'layout' => 'columns',

                'label_height' => 10,
                'label_width' => 25.4,
                'label_padding' => 0.5,
                'labels_per_row' => 7,
                'labels_per_page' => 189,
                'gap_left' => 2.5,
                'gap_top' => 0,

                'margins' => array(
                    'top' => 12.8,
                    'left' => 8,
                    'right' => 8,    //obsolete
                    'bottom' => 10,  //obsolete
                ),
            ),
            array(
                'title' => 'Avery Zweckform 20x20mm',
                'layout' => 'rows',

                'label_height' => 20,
                'label_width' => 20,
                'label_padding' => 1,
                'labels_per_row' => 8,
                'labels_per_page' => 96,
                'gap_left' => 4,
                'gap_top' => 4,

                'margins' => array(
                    'top' => 12.8,
                    'left' => 8,
                    'right' => 8,    //obsolete
                    'bottom' => 10,  //obsolete
                ),
            ),
        );
    }
}
