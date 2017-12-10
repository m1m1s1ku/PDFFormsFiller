<?php
declare(strict_types=1);

use FormFiller\PDF\Field;
use PHPUnit\Framework\TestCase;

/**
 * @covers Field
 */
final class FieldTest extends TestCase
{

    /**
     * Test if a field is produced with an array of fields
     *
     * @throws Exception
     */
    public function testFieldFromArray(){
        $fields = '[{"cat_name":{"llx":278.585,"lly":363.377,"urx":428.585,"ury":386.902,"width":150,"height":23.525,"page":1}},{"reward":{"llx":366.262,"lly":297.106,"urx":555.914,"ury":314.53,"width":189.652,"height":17.424,"page":1}},{"phone":{"llx":365.753,"lly":250.59,"urx":555.406,"ury":268.523,"width":189.653,"height":17.933,"page":1}}]';

        $fields = json_decode($fields, true);

        $fieldEntities = [];

        foreach($fields as $field) {
            $fieldEntities[] = Field::fieldFromArray($field);
        }

        $this->assertEquals(3, count($fieldEntities));
    }


}