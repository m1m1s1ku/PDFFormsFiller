<?php

namespace FormFiller\PDF;

/**
 * PDFHelper
 *
 */
class PDFHelper {

    /**
     * As setasign extract gives me coords in reversed -> llx, lly.. Y isn't good
     * Let's convert it to FPDF coords
     *
     * @param int $pageSize
     * @param int $offset
     * @param int $y
     *
     * @return int $y inverted axis
     */
    public static function reverseYAxis(int $pageSize, int $offset, int $y) : int {
        // 841.890 is the page size in points - 20 (fpdf offset)
        return $pageSize - $offset - $y;
    }

}