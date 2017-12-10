<?php
require __DIR__ . '/vendor/autoload.php';

use FormFiller\PDF\Converter\Converter;
use FormFiller\PDF\Field;
use FormFiller\PDF\PDFGenerator;

opcache_reset();

$string = "3 widget annotations found on page 1.
----------------------------------------------

cat_name: 
     llx: 278.585
     lly: 363.377
     urx: 428.585
     ury: 386.902
   width: 150
  height: 23.525


reward: 
     llx: 366.262
     lly: 297.106
     urx: 555.914
     ury: 314.53
   width: 189.652
  height: 17.424


phone: 
     llx: 365.753
     lly: 250.59
     urx: 555.406
     ury: 268.523
   width: 189.653
  height: 17.933
";


$converter = new Converter($string);
$converter->loadPagesWithFieldsCount();
$coords = $converter->formatFieldsAsJSON();

$fields = json_decode($coords, true);

$fieldEntities = [];

foreach($fields as $field) {
    $fieldEntities[] = Field::fieldFromArray($field);
}

$data = [
  'cat_name'              => "Mickey",
  'reward'              => "2 beers",
  'phone'           => "+3361265656565"
];

$original = getcwd() . "/FormAcrobat6.pdf";
$dest = getcwd() . "/FormFilled.pdf";

$pdfGenerator = new PDFGenerator($fieldEntities, $data, 'P', 'pt', 'A4');
$pdfGenerator->start($original, $dest);

echo "done";

