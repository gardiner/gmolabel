<?php

namespace Quintlab\GmoLabels;

use \TCPDF;

require_once(__DIR__ . '/../../vendor/autoload.php');

class Labeller {
    protected $values;
    protected $definition;
    protected $debug;
    protected $fontsize = 9.5;

    public function __construct(array $definition, array $values=null, bool $debug=false) {
        $this->definition = $definition;
        $this->values = $values ?? array();
        $this->debug = $debug;
    }

    public function saveLabels($filename) {
        $def = $this->definition;
        $margins = $def['margins'];

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator('quintlab/gmolabels');

        // set margins
        $pdf->SetMargins($margins['left'], $margins['top'], $margins['right']);
        $pdf->SetAutoPageBreak(true, $margins['bottom']);
        $pdf->SetFont('helvetica', '', $this->fontsize);
        $pdf->SetLineStyle(array('width' => 0.005));
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->AddPage();

        // set style for barcode
        $codetype = 'QRCODE,L';
        $codestyle = array(
            'border' => false,
            'padding' => 0,
            'fgcolor' => array(0,0,0),
            'bgcolor' => false,
        );

        $counter = 0;
        $cx = 0;
        $cy = 0;
        foreach($this->values as $value) {
            $content = implode(';', $value);

            $x = $margins['left'] + $cx * ($def['label_width'] + $def['gap_left']);
            $y = $margins['top'] +  $cy * ($def['label_height'] + $def['gap_top']);

            if ($this->debug) {
                $pdf->RoundedRect($x, $y, $def['label_width'], $def['label_height'], 1.5);
            }

            if ($def['layout'] == 'columns') {

                $size = $def['label_height'] - (2 * $def['label_padding']);
                $pdf->write2DBarcode($content, $codetype, $x + $def['label_padding'], $y + $def['label_padding'], $size, $size, $codestyle, 'T');
                $pdf->Text($x + $size + $def['label_padding'], $y, $value['T_number']);

            } elseif ($def['layout'] == 'rows') {

                $size = $def['label_height'] - (3 * $def['label_padding']) - $this->point_to_mm($this->fontsize);
                $pdf->write2DBarcode($content, $codetype, $x + $def['label_padding'], $y + $def['label_padding'], $size, $size, $codestyle, 'T');
                $pdf->Text($x + $def['label_padding'], $y + $size + $def['label_padding'], $value['T_number']);

            }

            $counter ++;
            $cx ++;
            if ($cx >= $this->definition['labels_per_row']) {
                if (($cx * $cy) > $this->definition['labels_per_page']) {
                    $pdf->AddPage();
                    $cx = 0;
                    $cy = 0;
                } else {
                    $cx = 0;
                    $cy ++;
                }
            }
        }

        //Close and output PDF document
        $pdf->Output($filename, 'F');
    }

    protected function point_to_mm($point) {
        $in = $point / 72.0;
        $mm = $in * 25.4;
        return $mm;
    }
}

$definition_a = array(
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
);

$definition_b = array(
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
);

$values = array_fill(0, 10, array(
    'T_number' => 'T_1234',
    'GMO_name' => 'SOME_1.23456.78910',
    'recipient_species' => 'Arabidopsis thaliana',
    'gene' => 'SOME_GENE',
    'donor_species' => 'Arabidopsis thaliana',
    'person' => 'ABCD',
));

$l = new Labeller($definition_b, $values, false);
$l->saveLabels(__DIR__ . '/../../-test.pdf');
