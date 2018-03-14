<?php

declare(strict_types=1);

include('./libraries/GuestListLibrary.php');

use intercom\repositories\CustomerRepository;
use intercom\libraries\GuestListLibrary;
use intercom\libraries\LocationLibrary;
use PHPUnit\Framework\TestCase;
use Mockery;

final class GuestListLibraryTest extends TestCase
{
    protected function invokeProtectedMethod($object, $methodName, ...$args)
    {
        $method = new \ReflectionMethod($object, $methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }

    public function isCustomerWithinKilometerDistanceProvider()
    {
        return [
            'within' => [true, 100, 200],
            'not-within' => [false, 200, 100],
        ];
    }

    /**
     * @dataProvider isCustomerWithinKilometerDistanceProvider
     **/
    public function testIsCustomerWithinKilometerDistance($expectedResponse, $havesineDistance, $distanceLimit)
    {
        $locationLibrary = Mockery::mock(
            LocationLibrary::class.'[getCoordinatesDistanceInKilometersByHavesineFormula]',
            [],
            function ($mock) use ($havesineDistance) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->shouldReceive('getCoordinatesDistanceInKilometersByHavesineFormula')->andReturn($havesineDistance);
            }
        );

        $guestLibrary = new GuestListLibrary(
            new CustomerRepository(),
            $locationLibrary
        );

        $this->assertEquals(
            $expectedResponse,
            $this->invokeProtectedMethod(
                $guestLibrary,
                'isCustomerWithinKilometerDistance',
                ['latitude' => '1', 'longitude' => '2'],
                $distanceLimit
            )
        );
    }

    public function getGuestListProvider()
    {
        return [
            'is-within' => [true, 'assertNotEmpty'],
            'not-within' => [false, 'assertEmpty'],
        ];
    }

    /**
     * @dataProvider getGuestListProvider
     **/
    public function testGetGuestList($isWithinDistance, $assertion)
    {
        $customerRepository = Mockery::mock(
            CustomerRepository::class.'[get]',
            [],
            function ($mock) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->shouldReceive('get')->andReturn([['latitude' => 1, 'longitude' => 2]]);
            }
        );

        $guestListLibrary = Mockery::mock(
            GuestListLibrary::class.'[isCustomerWithinKilometerDistance]',
            [$customerRepository, new LocationLibrary()],
            function ($mock) use ($isWithinDistance) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->shouldReceive('isCustomerWithinKilometerDistance')->andReturn($isWithinDistance);
            }
        );

        $response = $guestListLibrary->getGuestList();
        $this->$assertion($response);
    }
}
