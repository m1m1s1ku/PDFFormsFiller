# PDFFormsFiller

Fill Acrobat forms easily using pure PHP ! 💪

## Install :
```
composer require ghostfly/pdf-forms-filler:dev-master
```

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
