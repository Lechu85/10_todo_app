<?php

// src/Message/SmsNotification.php
namespace App\Message;

class SmsNotification
{
	private $content;
	private $userId;

	public function __construct(string $content, int $userId)
	{
		$this->content = $content;
		$this->userId = $userId;
	}

	public function getUserId(): int
	{
		return $this->userId;
	}

	public function getContent(): string
	{
		return $this->content;
	}
}