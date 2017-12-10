<?php
require __DIR__ . '/vendor/autoload.php';

use FormFiller\PDF\Converter\Converter;
use FormFiller\PDF\Field;
use FormFiller\PDF\PDFGenerator;

opcache_reset();

$string = "4 widget annotations found on page 1.
----------------------------------------------

date: 
     llx: 109.299
     lly: 741.093
     urx: 259.299
     ury: 763.093
   width: 150
  height: 22


name: 
     llx: 105.232
     lly: 694.832
     urx: 255.232
     ury: 716.832
   width: 150
  height: 22


address: 
     llx: 130.888
     lly: 652.708
     urx: 280.888
     ury: 674.708
   width: 150
  height: 22


name_2: 
     llx: 139.53
     lly: 465.629
     urx: 289.53
     ury: 487.629
   width: 150
  height: 22
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
  'date'              => "25/12/2017",
  'name'              => "Troll",
  'address'           => "123 Route du Champomy",
  'name_2'            => "Troll des Cavernes"
];

$original = getcwd() . "/FormAcrobat6.pdf";
$dest = getcwd() . "/FormFilled.pdf";

$pdfGenerator = new PDFGenerator($fieldEntities, $data, 'P', 'pt', 'A4');
$pdfGenerator->start($original, $dest);

echo "done";

