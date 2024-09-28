<?php

namespace App\Health\Checks;

use App\Models\ApiEndpoint; // Assuming you have an ApiEndpoint model
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;
use Spatie\Health\Checks\CheckFailed;
use Spatie\Health\Checks\CheckSucceeded;
use Spatie\Health\Checks\Checks\HttpPingCheck;

class ApiHealthCheck extends Check
{
    public function run(): Result
    {
        // Get all API endpoints from the database
        $endpoints = ApiEndpoint::all();

        // Array to store the results of each API health check
        $results = [];

        foreach ($endpoints as $endpoint) {
            // Monitor the API using HttpPingCheck
            try {
                $httpPingCheck = HttpPingCheck::new()
                    ->url($endpoint->url)
                    ->timeout(5) // Set a timeout for the request
                    ->expectStatusCode(200); // Expecting a 200 status code

                // Add each API result to the array
                $results[$endpoint->name] = $httpPingCheck->run();
            } catch (\Exception $e) {
                // If the API fails, log the error or update the health status
                $results[$endpoint->name] = Result::make()
                    ->failed()
                    ->shortSummary("API check failed: " . $e->getMessage());
            }
        }

        // Return a combined result for all APIs
        $overallResult = Result::make();

        foreach ($results as $name => $result) {
            if ($result->status === CheckSucceeded::class) {
                $overallResult->shortSummary("API $name is up and running");
            } else {
                $overallResult->shortSummary("API $name failed the check");
            }
        }

        return $overallResult;
    }
}
