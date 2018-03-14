<?php

namespace intercom\libraries;

include('./libraries/LocationLibrary.php');
include('./repositories/CustomerRepository.php');

use intercom\repositories\CustomerRepository;
use intercom\libraries\LocationLibrary;

class GuestListLibrary
{
	protected $customerRepository;

	protected $partyLatitude;

	protected $partyLongitude;

	protected $locationLibrary;

	public function __construct(
		CustomerRepository $customerRepository,
		LocationLibrary $locationLibrary,
		$partyLatitude = '53.339428',
		$partyLongitude = '-6.257664'
	) {
		$this->customerRepository = $customerRepository;
		$this->locationLibrary = $locationLibrary;
		$this->partyLatitude = $partyLatitude;
		$this->partyLongitude = $partyLongitude;
	}

	public function getGuestList()
	{
		$customers = $this->customerRepository->get();
		$allowedCustomers = [];
		foreach ($customers as $customer) {
			if ($this->isCustomerWithinKilometerDistance($customer, 100)) {
				$allowedCustomers[] = $customer;
			}
		}

		return $allowedCustomers;
	}

	protected function isCustomerWithinKilometerDistance(
		array $customer, 
		$kilometerDistanceLimit
	) {
		$kilometerDistanceFromParty = $this->locationLibrary->getCoordinatesDistanceInKilometersByHavesineFormula(
			$this->partyLatitude,
			$this->partyLongitude,
			$customer['latitude'],
			$customer['longitude']
		);

		return ($kilometerDistanceFromParty <= $kilometerDistanceLimit);
	}
}