<?php

namespace App\Repositories\Ticket;

interface TicketRepositoryInterface
{

    public function createReferenceId($email);

    public function createTicket($ticket);

    public function sendEmail($message, $email, $subject, $url, $buttonText);

    public function getTicketList($filters);

    public function getTicket($referenceId);

    public function createReply($reply);

    public function getReplyForTicket($id);
}