<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use FormFiller\PDF\PDFHelper;

/**
 * @covers FormFiller\PDF\PDFHelper
 */
final class PDFHelperTest extends TestCase
{

    /**
     * @covers FormFiller\PDF\PDFHelper::reverseYAxis()
     */
    public function testReverseYAxis(): void
    {
        $this->assertEquals(
            571,
            PDFHelper::reverseYAxis(841, 20, 250)
        );
    }
}