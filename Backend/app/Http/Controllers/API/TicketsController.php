<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Requests\Reply\ReplyRequest;
use App\Resources\TicketResource;
use App\Requests\Ticket\OpenTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\Ticket\TicketRepositoryInterface;
use Illuminate\Support\Facades\Config;

class TicketsController extends Controller
{
    private $ticketRepository;

    public function __construct(TicketRepositoryInterface $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function openTicket(OpenTicketRequest $request): JsonResponse
    {
        $ticket = $request->all();
        $ticket['reference_id'] = $this->ticketRepository->createReferenceId($ticket['email']);
        $ticket['status'] = Ticket::STATUS_OPEN;
        $createdTicket = $this->ticketRepository->createTicket($ticket);

        $subject = 'Ticket creation acknowledgement!';
        $url = 'https://example.com';
        $buttonText = 'Click Here to go to ticket';
        $message = 'Hi, This email is acknowledge that ticket creation in Online support system is successfull. Please use ' . $createdTicket->reference_id . ' track the status of the ticket';

        $this->ticketRepository->sendEmail($message, $createdTicket->email, $subject, $url, $buttonText);

        return response()->json([
            'message' => 'Ticket is created.'],
            200);
    }

    public function getTickets(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $filters = $request->query();
        $pageSize = Config::get('pagination.page_size', '10');
        $ticketList = TicketResource::collection(
            $this->ticketRepository->getTicketList($filters)->paginate($pageSize)
        );
        return $ticketList;
    }

    public function getTicket(string $referenceId): JsonResponse
    {
        $ticket = $this->ticketRepository->getTicket($referenceId);
        if ($ticket->status == Ticket::STATUS_OPEN) {
            $ticket->status_name = "Open";
        } else {
            $ticket->status_name = "Closed";
        }

        if (!empty($ticket)) {
            $reply = $this->ticketRepository->getReplyForTicket($ticket->id);
            return response()->json([
                'ticket' => $ticket,
                'reply' => $reply,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Reference id is not valid.',
            ], 404);
        }

    }

    public function sendReply(ReplyRequest $request,string $referenceId): JsonResponse
    {
        $ticket = $this->ticketRepository->getTicket($referenceId);
        if (!empty($ticket)) {
            $replyInput['ticket_id'] = $ticket->id;
            $replyInput['reply'] = $request['reply'];
            $reply = $this->ticketRepository->createReply($replyInput);

            $subject = 'Reply to the create issue!';
            $url = 'https://example.com';
            $buttonText = 'Click Here to go to ticket';
//            $message = 'Hi, This email carries reply for the ticket with Reference number: ' . $ticket->reference_id . ' and Reply: '.$reply->reply;
            $message = 'Hi, This email carries reply for the ticket with Reference number: ' . $ticket->reference_id . ' and Reply: '.$reply->reply;
            $htmlMessage = '<p>'.$message.'</p><a href="'.$url.'"><button style="background-color:#008CBA; border:none; color:white; padding: 15px 32px; text-align:center; text-decoration:none; display:inline-block; font-size:16px; margin: 4px 2px; cursor:pointer;">'.$buttonText.'</button></a>';

            $this->ticketRepository->sendEmail($message, $ticket->email, $subject, $url, $buttonText);
            return response()->json([
                'message' => 'Reply is sent.'],
                200);
        } else {
            return response()->json([
                'message' => 'Reference id is not valid.',
            ], 404);
        }
    }
}
