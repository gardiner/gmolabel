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
                'title' => 'Avery Zweckform 18x10mm (transparent foil)',
                'layout' => 'columns',

                'label_height' => 9.7,
                'label_width' => 17.45,
                'label_padding' => 0.5,
                'labels_per_row' => 10,
                'labels_per_page' => 270,
                'gap_left' => 2.9,
                'gap_top' => 0.3,
                'fontsize' => 6,

                'margins' => array(
                    'top' => 13,
                    'left' => 4.5,
                    'right' => 4.5,    //obsolete
                    'bottom' => 13,  //obsolete
                ),
            ),
            array(
                'title' => 'Avery Zweckform 20x20mm (transparent foil)',
                'layout' => 'rows',

                'label_height' => 20,
                'label_width' => 20,
                'label_padding' => 1,
                'labels_per_row' => 8,
                'labels_per_page' => 96,
                'gap_left' => 4,
                'gap_top' => 4,
                'fontsize' => 7,

                'margins' => array(
                    'top' => 6,
                    'left' => 12,
                    'right' => 12,    //obsolete
                    'bottom' => 6,  //obsolete
                ),
            ),
        );
    }
}
