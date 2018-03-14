<?php

include('./libraries/GuestListLibrary.php');

$guestList = [];

try {
    $guestListLibrary = new intercom\libraries\GuestListLibrary(
        new intercom\repositories\CustomerRepository(),
        new intercom\libraries\LocationLibrary()
    );

    $guestList = $guestListLibrary->getGuestList();

} catch (\Exception $exception) {
    echo $exception->getMessage();
}

foreach ($guestList as $guest) {
    echo "User Id: ".$guest['user_id']."\n";
    echo "Name: ".$guest['name']."\n";
    echo "\n";
}