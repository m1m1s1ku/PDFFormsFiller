# PDFFormsFiller

[![Latest Stable Version](https://poser.pugx.org/ghostfly/pdf-forms-filler/v/stable)](https://packagist.org/packages/ghostfly/pdf-forms-filler)
[![Total Downloads](https://poser.pugx.org/ghostfly/pdf-forms-filler/downloads)](https://packagist.org/packages/ghostfly/pdf-forms-filler)
[![Latest Unstable Version](https://poser.pugx.org/ghostfly/pdf-forms-filler/v/unstable)](https://packagist.org/packages/ghostfly/pdf-forms-filler)
[![License](https://poser.pugx.org/ghostfly/pdf-forms-filler/license)](https://packagist.org/packages/ghostfly/pdf-forms-filler)
[![composer.lock](https://poser.pugx.org/ghostfly/pdf-forms-filler/composerlock)](https://packagist.org/packages/ghostfly/pdf-forms-filler)

Fill Acrobat forms easily using pure PHP ! 💪

## Install :
```
composer require ghostfly/pdf-forms-filler
```

## Run example :
- clone repository
- go to examples folder
- composer install
- run index.php

## Usage :
You need to do a PDF Form with Acrobat, and the string to convert is given by this page :

[Find Form Field coordinates](https://www.setasign.com/products/setapdf-core/demos/find-form-field-coordinates/)

Use Converter who gives you a JSON Array containing fields with locations / page, in a form usable by the Generator

```
$converter = new Converter($string);
$converter->getPagesWithFieldsCount();
$json = $converter->formatFieldsAsJson($pages);

echo json;
```

Use PDF Generator with one array containing every field with id -> value
And one array containing every field with id -> llx, lly, urx, ury, page

```
$pdfGenerator = new PDFGenerator($coords, $data, 'P', 'pt', 'A4');
$pdfGenerator->start($original, $dest);
```

Done. ;)
