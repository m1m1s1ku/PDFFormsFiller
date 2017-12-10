<?php
declare(strict_types=1);

use FormFiller\PDF\PDFGenerator;
use FormFiller\PDF\Field;
use PHPUnit\Framework\TestCase;

/**
 * @covers FormFiller\PDF\PDFGenerator
 */
final class PDFGeneratorTest extends TestCase
{

    /**
     * Test if generate produce a file
     *
     * Also test if field could be created
     * @covers FormFiller\PDF\PDFGenerator::start()
     *
     * Todo : Refactor use mocks
     *
     * @throws Exception
     */
    public function testGenerateCeremony(){
        $fieldEntities = [];

        foreach($this->getFakeData() as $k => $fake){
            $field = new Field;
            $field->setId($k);
            $field->setWidth(230);
            $field->setHeight(230);
            $field->setLlx(230);
            $field->setLly(230);
            $field->setUrx(230);
            $field->setUry(230);
            $field->setPage(1);
            $field->setValue('miaow');

            $fieldEntities[] = $field;
        }

        $fakePDFPath = dirname(__DIR__) . "/examples";

        $original = $fakePDFPath . "/FormAcrobat6.pdf";
        $dest = $fakePDFPath . "/FormFilledByTravis.pdf";

        if(file_exists($dest))
            unlink($dest);


        $pdfGenerator = new PDFGenerator($fieldEntities, $this->getFakeData(), 'P', 'pt', 'A4');

        if($pdfGenerator->start($original, $dest)) {
            $this->assertEquals(true, file_exists($dest));
        }

    }

    /**
     * Get fake data for test suite
     *
     * @return array
     */
    private function getFakeData(){
        return [
            'cat_name'    => [
                "size"  => 67,
                'family'  => 'Helvetica',
                "style" => 'B',
                'value' => 'Mickey'
            ],
            'reward' => [
                "size"  => 28,
                'family'  => 'Arial',
                "style" => 'B',
                'value' => '2 beers'
            ],
            'phone' => [
                "size"  => 24,
                'family'  => 'Helvetica',
                "style" => 'B',
                'value' => "+3361265656565"
            ],
        ];
    }
}
