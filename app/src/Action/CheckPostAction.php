<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Helpers\Calculator;

final class CheckPostAction
{
    private $view;
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        //error_log($request->getAttribute('student'));
        $this->logger->info("Check post page action dispatched");
        $studentCalculation = new Calculator($request->getAttribute('student'));
        //$back = $studentCalculation->calculateResult();
        
      // headers
        //$response = $response->withHeader('Content-Type','application/json');
        // body
        $response->getBody()->write(json_encode($studentCalculation->result()));
    }

}