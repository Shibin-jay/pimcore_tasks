<?php
namespace App\Service;

use Psr\Log\LoggerInterface;
class CustomLogService {
    private LoggerInterface $customLogLogger;
    public function __construct(LoggerInterface $customLogLogger)
    {
        $this->customLogLogger = $customLogLogger;
    }
    public function loggerService()
    {
        $this->customLogLogger->debug("custom log msg");
    }
}

