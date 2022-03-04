<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Helpers\Calculator;

final class CheckAction
{
    private $view;
    private $logger;

    public function __construct(Twig $view, LoggerInterface $logger)
    {
        $this->view = $view;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Check page action dispatched");
        $array = new Calculator('exampleData2');
        //$back = $array->simpleReturn();
        
        $this->view->render($response, 'check.twig', ['students' => ['exampleData' => 'exampleData', 'exampleData1' => 'exampleData1', 'exampleData2' => 'exampleData2', 'exampleData3' => 'exampleData3']]);
        return $response;
    }
}
