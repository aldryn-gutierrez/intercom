<?php

namespace intercom\repositories;

include('./repositories/BaseRepository.php');

class CustomerRepository extends BaseRepository
{
	public function get()
	{
		$customers = [];

		$rawData = $this->getFileContents(getcwd().'/repositories/customer.json');
		$customersJsons = explode("\n", $rawData);

		foreach ($customersJsons as $customerJson) {
		   $customer = json_decode($customerJson, true);
		   $customers[$customer['user_id']] = $customer;
		}

		ksort($customers);
		return $customers;
	}

	protected function getFileContents($location)
	{
		return file_get_contents($location);
	}
}

