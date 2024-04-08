<?php declare (strict_types = 1);

use PHPUnit\Framework\TestCase;

final class ModelTest extends TestCase
{
    public function testModelWithBark(): void
    {
        $model = new \Thing\Model;
        $bark  = $model->do("bark");
        $this->assertSame("bark", $bark);
    }

    public function testModelWithMeaw(): void
    {
        $model = new \Thing\Model;
        $meaw  = $model->do("meaw");
        $this->assertSame("meaw", $meaw);
    }
}
