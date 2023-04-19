<?php

namespace App\Repositories\Ticket;

use App\Models\Reply;
use App\Models\Ticket;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\AbstractPart;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

class TicketRepository implements TicketRepositoryInterface
{
    public function createReferenceId($email)
    {
        $randomId = substr(Hash::make($email), 0, 10);
        $referenceIdExist = Ticket::where('reference_id', $randomId)->first();
        if (empty($referenceIdExist)) {
            return $randomId;
        } else {
            $this->createReferenceId($email);
        }
    }

    public function createTicket($ticket)
    {
        return Ticket::create($ticket);
    }

    public function sendEmail($message, $email, $subject, $url, $buttonText)
    {
        $senderEmail = Config::get('emailing.sender_email');
        $senderName = Config::get('emailing.sender_name');
        $senderUsername = Config::get('emailing.sender_user_name');
        $senderPassword = Config::get('emailing.sender_password');
        $senderHost = Config::get('emailing.sender_host');
        $senderPort = Config::get('emailing.sender_port');


        $mailMessage = (new Email())
            ->subject($subject)
            ->from(new Address($senderEmail, $senderName))
            ->to($email)
            ->html($message);
        $transport = Transport::fromDsn(
            'smtp://' . $senderUsername . ':' . $senderPassword . '@' . $senderHost . ':' . $senderPort
        );
        $mailer = new Mailer($transport);
        $mailer->send($mailMessage);
    }

    public function getTicketList($filters)
    {
        $tickets = Ticket::orderBy('id', 'desc');
        if (!empty($filters['query'])) {
            $queryParam = '%' . $filters['query'] . '%';
            $tickets = $tickets->where('name', 'like', $queryParam);
        }
        return $tickets;
    }

    public function getTicket($referenceId)
    {
        return Ticket::where('reference_id', $referenceId)->first();
    }

    public function createReply($reply)
    {
        return Reply::create($reply);
    }

    public function getReplyForTicket($id)
    {
        return Reply::where('ticket_id', $id)->first();
    }
}