<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use FormFiller\PDF\PDFHelper;

/**
 * @covers PDFHelper
 */
final class PDFHelperTest extends TestCase
{
    public function testReverseYAxis(): void
    {
        $this->assertEquals(
            571,
            PDFHelper::reverseYAxis(841, 20, 250)
        );
    }
}