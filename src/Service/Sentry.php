<?php
namespace App\Service;

class Sentry
{
    public function getTracesSampler(): callable
    {
        return function (\Sentry\Tracing\SamplingContext $context): float {
            // Implement your custom sampling logic here
            // For example, you can sample 50% of traces
            return 0.5; // 50% sampling rate
        };
    }
}
