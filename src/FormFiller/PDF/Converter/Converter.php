<?php

namespace FormFiller\PDF\Converter;


/**
 * Class Converter
 * @package App\Core\Generator\PDF\SetaPDF
 *
 * Generate easily fields.json to use with PDFGenerator
 *
 * You need to do a PDF Form with Acrobat, and the string to convert is given by this page :
 * https://www.setasign.com/products/setapdf-core/demos/find-form-field-coordinates/
 *
 * This converter gives you a JSON Array containing fields with locations / page, in a form usable by the Generator
 *
 * Usage : $converter = new Converter($string);
 *         $converter->loadPagesWithFieldsCount();
 *
 *         $json = $converter->formatFieldsAsJson($pages);
 */
class Converter {

    /**
     * @var "yml" given by SetaSign demo
     */
    private $string;
    private $pages;

    /**
     * Converter constructor.
     *
     * @param $string
     */
    public function __construct ($string) {
        $this->string = $string;
    }

    /**
     * Format fields as JSON
     *
     * @return bool|string
     */
    public function formatFieldsAsJSON(){
        // Find every field to convert into JSON
        $re = '/(\S*): (\S*)/';

        preg_match_all($re, $this->string, $matches, PREG_SET_ORDER, 0);

        $coords = ['llx', 'lly', 'urx', 'ury', 'width', 'height'];
        $objects = [];
        $json = "";

        foreach($matches as $match){
            if(!in_array($match[1], $coords)){
                $newObject = "{\"".$match[1]."\":{";
            } else if($match[1] == 'height') {
                $onPage = count($objects);
                $page = $this->findPageForField($onPage);
                /** @var string $newObject */
                $newObject .= "\"" . $match[1] . "\":" . $match[2] . ",\"page\":$page}},";
                $objects[] = $newObject;
                $json .= $newObject;
            } else {
                /** @var string $newObject */
                $newObject .= "\"" . $match[1] . "\":" . $match[2] . ",";
            }
        }

        $formatJSON = "[";

        foreach($objects as $field){
            $formatJSON .= $field;
        }

        $formatJSON = substr($formatJSON, 0, -1);

        $formatJSON .= "]";

        return $formatJSON;
    }

    /**
     * Get an array with Pages and fields count, to write our JSON Object
     *
     * $arr[pageId] = fieldsCount
     * @return void
     */
    public function loadPagesWithFieldsCount(){
        $re = '/(\d*).* page (\d)/';
        $matches = [];
        $pages = [];

        preg_match_all($re, $this->string, $matches, PREG_SET_ORDER, 0);

        $previousPageCount = 0;
        foreach($matches as $pageInfo){
            if($pageInfo[1] != 0){
                $pages[$pageInfo[2]] = $pageInfo[1] + $previousPageCount;
                $previousPageCount+= $pageInfo[1];
            }
        }

        $this->pages = $pages;
    }

    /**
     * Find Page for Field based on actual count and extracted data
     *
     * @param $count
     *
     * @return int|string
     */
    private function findPageForField($count){
        foreach($this->pages as $p => $page){
            if($count <= $page){
                return $p;
            }
        }
    }
}