<?php

namespace Quintlab\GmoLabel;

require_once(__DIR__ . '/../../vendor/autoload.php');

$values = CSV::parse_file(__DIR__ . '/../../data/test.csv');
$l = new Labeller(Definitions::get_definitions()[0], $values, false);
$l->save_labels(__DIR__ . '/../../-test.pdf');

