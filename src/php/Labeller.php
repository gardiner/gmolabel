<?php

namespace Quintlab\GmoLabel;

use \TCPDF;


class Labeller {
    protected $values;
    protected $definition;
    protected $debug;

    public function __construct(array $definition, array $values=null, bool $debug=false) {
        $this->definition = $definition;
        $this->values = $values ?? array();
        $this->debug = $debug;
    }

    public function save_labels($filename) {
        $pdf = $this->create_labels();
        $pdf->Output($filename, 'F');
    }

    public function send_labels_to_browser($filename) {
        $pdf = $this->create_labels();
        $pdf->Output(basename($filename), 'I');
    }

    protected function create_labels() {
        $def = $this->definition;
        $margins = $def['margins'];
        $fontsize = $def['fontsize'] ?? 9;

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator('quintlab/gmolabels');

        // set margins
        $pdf->SetMargins($margins['left'], $margins['top'], $margins['right']);
        $pdf->SetAutoPageBreak(true, $margins['bottom']);
        $pdf->SetFont('helvetica', '', $fontsize);
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

                $size = $def['label_height'] - (3 * $def['label_padding']) - $this->point_to_mm($fontsize);
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

        return $pdf;
    }

    protected function point_to_mm($point) {
        $in = $point / 72.0;
        $mm = $in * 25.4;
        return $mm;
    }
}

