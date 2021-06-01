<?php

namespace Quintlab\GmoLabel;

require_once(__DIR__ . '/../../vendor/autoload.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //form submission

    $definition = Definitions::get_definition($_POST['definition'] ?? null);
    $values = null;
    if (isset($_FILES["csvfile"]["error"]) && $_FILES["csvfile"]["error"] == UPLOAD_ERR_OK) {
        $tmp = $_FILES["csvfile"]["tmp_name"];
        /*
        $destination = __DIR__ . '/../../data/' . time() . '_' . uniqid('', true);
        move_uploaded_file($tmp, $destination);
        */
        $values = CSV::parse_file($tmp);
    }

    if (!$definition) {
        throw new \Exception('Invalid submission - missing definition');
    } elseif (!$values) {
        throw new \Exception('Invalid submission - missing file');
    }

    $l = new Labeller($definition, $values, false);
    $l->send_labels_to_browser('gmolabel.pdf');

} else {
    //show form

    header('content-type: text/html');
    $definitions = Definitions::get_definitions();
    include(__DIR__ . '/../../templates/form.php');

}
