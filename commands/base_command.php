<?php

class base_command {
	public $name;
	public $description;
	public $permission = USER;

	function __construct() {
	}
	function run($chat_id, $text, $message, $state) {
		return false;
	}
}
