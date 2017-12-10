<?php

namespace FormFiller\PDF;

use setasign\Fpdi\Fpdi;
use FPDF;

/**
 * PDFGenerator
 *
 * Fill a PDF based on fields position, given by SetaSign PDF demo and data
 */
class PDFGenerator {

    /**
     * @var FPDF $fpdf instance
     */
    private $fpdf;

    /**
     * @var Field[] $fields
     */
    private $fields;

    /**
     * @var array $data : JSON object containing inputs, dropdowns and addresses to fill example in "assets/sample.json"
     */
    private $data;

    /**
     * @var string $orientation
     */
    private $orientation;

    /**
     * @var string $unit
     */
    private $unit;

    /**
     * @var string $size
     */
    private $size;

    /**
     * Constructor
     *
     * @param array  $fields
     * @param array  $data
     * @param string $orientation
     * @param string $unit
     * @param string $size
     */
    public function __construct($fields, $data, $orientation = 'P', $unit = 'pt', $size = 'A4'){
        $this->fields       = $fields;
        $this->data         = $data;
        $this->orientation  = $orientation;
        $this->unit         = $unit;
        $this->size         = $size;
    }

    /**
     * Start to generate using original, save to dest
     * @param string $formPath
     * @param string $dest
     * 
     * @throws \Exception
     * @return void
     */
    public function start(string $formPath, string $dest) {
        $this->fpdf = new FPDF($this->orientation, $this->unit, $this->size);

        $this->fpdf->SetMargins(10, 10, 10);

        $this->fpdf->SetAutoPageBreak(true, 0);

        $this->fpdf->AddPage();

        $this->fpdf->AliasNbPages();
        $this->fpdf->SetFont('Arial', 'B', '8');


        // writing fields, if value not defined defaults to blank string
        $this->writeFields($this->fields, $this->data, 841.890);

        // generated path
        $generated = "tmp/temp.pdf";

        $this->fpdf->Output("F", $generated, true);

        // merge original with our pdf
        $this->merge($formPath, $generated, $dest);

        // clean generated not merged
        unlink($generated);
    }

    /**
     * Write fields on current pdf with data
     *
     * @param Field[] $fields
     * @param array $data
     *
     * @param int   $pageSize : 841.890 for A4
     * @param int   $offset : 20 (fpdf default)
     *
     * @return void
     * @throws \Exception
     */
    public function writeFields(array $fields, array $data, int $pageSize, int $offset = 20) {
        $currentPage = null;

        foreach($fields as $field){
            // Keep in mind that due to FPDF limitations, we can't move to previous/next page without hacking FPDF himself..
            // ensure we are on the good page, but fields HAVE TO be ordered by page number
            while($this->fpdf->PageNo() != $field->getPage()){
                $this->fpdf->AddPage();
            }

            // Set with good coords system.
            $this->fpdf->SetXY($field->getLlx(), PDFHelper::reverseYAxis($pageSize, $offset, $field->getLly()));
 
            // Write !
            if(array_key_exists($field->getId(), $data))
                $field->setValue($data[$field->getId()]);
            else
                $field->setValue("");

            // 20 is fpdf offset for new pages
            $offset = 20;
            $this->fpdf->Cell($field->getWidth(), $field->getHeight() + $offset, utf8_decode($field->getValue()));
        }
    }

    /**
     * Merge two PDF (doc A and over doc B)
     * @param string $pdfA path of pdfA
     * @param string $pdfB path of pdfB
     * @param string $dest 
     * 
     * @return Boolean
     * 
     * @throws \Exception
     */
    public function merge($pdfA, $pdfB, $dest) : bool {
        $pdf = new FPDI();
        $pdf->setSourceFile($pdfA);

        $pageCount = $pdf->currentParser->getPageCount();   

        for($i = 1; $i <= $pageCount; $i++){
            $pdf->addPage();   

            // Adding background pdf
            $pdf->setSourceFile($pdfA);            
            $pageA = $pdf->importPage($i, '/MediaBox');
            $pdf->useTemplate($pageA);

            // Looking for file B -> our generated pdf with data
            $pdf->setSourceFile($pdfB);

            // If page exists on it
            if($i <= $pdf->currentParser->getPageCount()){
                $pageB = $pdf->importPage($i, '/MediaBox');            
                $pdf->useTemplate($pageB);
            }
        }

        // Done.
        try {
            $pdf->Output("F", $dest, true);
        } catch (\Exception $e){
            // Path not writable, probably
            return false;
        }

        return true;
    }

}