<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ModelTest extends TestCase
{
    public function testDoReturnsBark(): void
    {
        $model = new \Thing\Model();
        $result = $model->do('bark');
        $this->assertSame('bark', $result);
    }

    public function testDoReturnsMeaw(): void
    {
        $model = new \Thing\Model();
        $result = $model->do('meaw');
        $this->assertSame('meaw', $result);
    }
}
