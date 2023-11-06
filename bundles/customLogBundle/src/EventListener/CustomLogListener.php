<?php

namespace customLogBundle\EventListener;

use customLogBundle\Model\CustomLog;
use Pimcore\Config;
use Pimcore\Http\Request\Resolver\PimcoreContextResolver;
use Pimcore\Bundle\CoreBundle\EventListener\Traits\PimcoreContextAwareTrait;
use Pimcore\Security\User\TokenStorageUserResolver;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CustomLogListener implements EventSubscriberInterface
{

    use LoggerAwareTrait;
    use PimcoreContextAwareTrait;
    protected TokenStorageUserResolver $userResolver;

    protected Config $config;
//    private $logger;


    public function __construct(TokenStorageUserResolver $userResolver, Config $config, LoggerInterface $logger)
    {
        $this->userResolver = $userResolver;
        $this->config = $config;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (!$event->isMainRequest()) {
            return;
        }
        if (!$this->matchesPimcoreContext($request, PimcoreContextResolver::CONTEXT_ADMIN)) {
            return;
        }

        $logEntry = new CustomLog();
        $timestamp = new \DateTime();
        $user = $this->userResolver->getUser();
        $formattedTimestamp = $timestamp->format('Y-m-d H:i:s');
        $logEntry->setAction($request->attributes->get('_route'));
        $logEntry->setTimestamp($formattedTimestamp);
        $logEntry->setAdminUserId($user ? $user->getId() : 'NA');
        $logEntry->setController($request->attributes->get('_controller'));
//        $logEntry->save(); //due to excessive save its off
//        if (strpos($request->getPathInfo(), '/admin') !== false) {
//            $action = $this->determineAction($request);
            $this->customLogActivity($request);
//        }

    }


    protected function customLogActivity(Request $request)
    {
        if (!empty($this->config['general']['disable_usage_statistics'])) {
            return;
        }

        $params = $this->getParams($request);
        $user = $this->userResolver->getUser();
        $this->logger->info($request->attributes->get('_controller'), [
            $user ? $user->getId() : '0',
            $request->attributes->get('_route'),
            $request->attributes->get('_route_params'),
            $params,
        ]);


    }
    protected function getParams(Request $request): array
    {
        $params = [];
        $disallowedKeys = ['_dc', 'module', 'controller', 'action', 'password'];

        $requestParams = array_merge(
            $request->query->all(),
            $request->request->all()
        );

        foreach ($requestParams as $key => $value) {
            if (is_json($value)) {
                $value = json_decode($value);
                if (is_array($value)) {
                    array_walk_recursive($value, function (&$item, $key) {
                        if (strpos((string)$key, 'pass') !== false) {
                            $item = '*************';
                        }
                    });
                }

                $value = json_encode($value);
            }

            if (!in_array($key, $disallowedKeys) && is_string($value)) {
                $params[$key] = (strlen($value) > 40) ? substr($value, 0, 40) . '...' : $value;
            }
        }
        return $params;
    }
}
