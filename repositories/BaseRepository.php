<?php

namespace intercom\repositories;

include('./repositories/RepositoryContract.php');

abstract class BaseRepository implements RepositoryContract
{
	abstract function get();
}