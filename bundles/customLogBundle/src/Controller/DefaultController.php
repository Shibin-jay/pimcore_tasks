<?php

namespace customLogBundle\Controller;

use Pimcore\Security\User\TokenStorageUserResolver;
use customLogBundle\EventListener\CustomLogListener;
use customLogBundle\Model\CustomLog;
use Pimcore\Controller\FrontendController;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends FrontendController
{
    protected TokenStorageUserResolver $userResolver;

    public function __construct(TokenStorageUserResolver $userResolver)
    {
        $this->userResolver = $userResolver;
    }
    /**
     * @Route("/custom_log")
     */
    public function indexAction(Request $request): Response
    {
        return new Response('Hello world from custom_log');
    }

    /**
     * @Route("/custom_log2")
     */
    public function testAction(Request $request, LoggerInterface $logger) {
        $logEntry = new CustomLog();

        $timestamp = new \DateTime();
        $formattedTimestamp = $timestamp->format('Y-m-d H:i:s');

        $logEntry->setAction($request->attributes->get('_route'));
        $logEntry->setTimestamp($formattedTimestamp);
        $logEntry->setController($request->attributes->get('_controller'));
        $logEntry->setAdminUserId(0);
        // Debugging and logging
        $logger->debug('Action: ' . $logEntry->getAction());
        $logger->debug('Timestamp: ' . $logEntry->getTimestamp());
        $logger->debug('AdminUserId: ' . $logEntry->getAdminUserId());
        $logger->debug('controller: ' . $logEntry->getController());

        // Save the log entry
        $logEntry->save();

        // Check for errors during save
        if ($logEntry->getId()) {
            $logger->info('Log entry saved with ID: ' . $logEntry->getId());
        } else {
            $logger->error('Failed to save log entry.');
        }

        return $this->render("test/test.html.twig", ['item' => $logEntry]);
    }

    /**
     * @Route("/admin_log_data", name="admin_log_data")
     */
    public function getAdminLogData(Request $request):Response
    {
        // Use the CustomLogService to fetch data from custom_activity_logs
        $listing = new CustomLog\Listing();
        $listing->setCondition("id > ?",[1]);
        $logList = $listing->load();
        $totalRecords = count($logList);
        $logListArray = [];
        foreach ( $logList as $log)
        {
            $logListArray []= [
                'id'=>$log->getId(),
                'userId' => $log->getAdminUserId(),
                'action' => $log->getAction(),
                'timestamp'=> $log->getTimestamp(),
                'controller' => $log->getController()
            ];
        }
        $page = $request->query->get('page', 1);
        $pageSize = 50;
        $offset = ($page - 1) * $pageSize;
        $pagedData = array_slice($logListArray, $offset, $pageSize);
        return new JsonResponse([
            'total' => $totalRecords,
            'logs' => $pagedData,
        ]);

    }

}
