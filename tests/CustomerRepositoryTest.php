<?php

declare(strict_types=1);

include('./repositories/CustomerRepository.php');

use intercom\repositories\CustomerRepository;
use PHPUnit\Framework\TestCase;
use Mockery;

final class CustomerRepositoryTest extends TestCase
{
    public function testGetCustomers(): void
    {
        $customerRepository = Mockery::mock(
            CustomerRepository::class.'[getFileContents]',
            [],
            function ($mock) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->shouldReceive('getFileContents')->andReturn(
                    "{\"latitude\": \"52.986375\", \"user_id\": 12, \"name\": \"Christina McArdle\", \"longitude\": \"-6.043701\"}".
                    "\n{\"latitude\": \"51.92893\", \"user_id\": 1, \"name\": \"Alice Cahill\", \"longitude\": \"-10.27699\"}"
                );
            }
        );

        $expectedResponse = [
            1 => [
                'latitude' => '51.92893',
                'user_id' => 1,
                'name' => 'Alice Cahill',
                'longitude' => '-10.27699',
            ],
            12 => [
                'latitude' => '52.986375',
                'user_id' => 12,
                'name' => 'Christina McArdle',
                'longitude' => '-6.043701',
            ],
        ];

        $this->assertEquals($expectedResponse, $customerRepository->get());
    }
}

