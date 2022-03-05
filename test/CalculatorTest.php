<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\Helpers\Calculator;

final class CalculatorTest extends TestCase
{

    

    public function testPontokGeneralas()
    {
        $params = ['exampleData', 'exampleData1', 'exampleData2', 'exampleData3'];
        $k = array_rand($params);
        $v = $params[$k]; 
        error_log('Használt minta: '.$v);
        $box = new Calculator($v);
        $cp = $box->checkPoints();
        $this->assertArrayHasKey('alap', $cp);
        $this->assertArrayHasKey('tobblet', $cp);
    }

    public function testACheckLowPercentAzArray()
    {
        $params = ['exampleData', 'exampleData1', 'exampleData2', 'exampleData3'];
        $k = array_rand($params);
        $v = $params[$k]; 
        error_log('Használt minta: '.$v);
        $box = new Calculator($v);
        $cp = $box->checkLowPercent();
        $this->assertIsArray($cp);
    }

    public function testACheckKotelezokAzArray()
    {
        $params = ['exampleData', 'exampleData1', 'exampleData2', 'exampleData3'];
        $k = array_rand($params);
        $v = $params[$k]; 
        error_log('Használt minta: '.$v);
        $box = new Calculator($v);
        $cp = $box->checkKotelezok();
        $this->assertIsArray($cp);
    }
}
