<?php

namespace App\Http\Modules\Admin\Controllers\Web\Support;

use App\Models\SupportTicket;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class TicketController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function open (): Renderable
    {
        return view('admin.support.tickets.open')->with('tickets',
            $this->paginateWithQuery(SupportTicket::query()->latest()->where('status', SupportTicket::TICKET_OPEN))
        );
    }

    public function closed (): Renderable
    {
        return view('admin.support.tickets.closed')->with('tickets',
            $this->paginateWithQuery(SupportTicket::query()->latest('updated_at')->where('status', SupportTicket::TICKET_CLOSED))
        );
    }

    public function resolved (): Renderable
    {
        return view('admin.support.tickets.resolved')->with('tickets',
            $this->paginateWithQuery(SupportTicket::query()->latest('updated_at')->where('status', SupportTicket::TICKET_RESOLVED))
        );
    }

    public function markResolved (SupportTicket $ticket): RedirectResponse
    {
        if ($ticket->status == SupportTicket::TICKET_OPEN) {
            $ticket->update(['status' => SupportTicket::TICKET_RESOLVED]);
            return redirect()->route('admin.support.tickets.open')->with('success', 'Ticket was marked resolved.');
        }
        return redirect()->route('admin.support.tickets.open')->with('error', 'Ticket is already either closed or resolved.');
    }

    public function markClosed (SupportTicket $ticket): RedirectResponse
    {
        if ($ticket->status == SupportTicket::TICKET_OPEN) {
            $ticket->update(['status' => SupportTicket::TICKET_CLOSED]);
            return redirect()->route('admin.support.tickets.open')->with('success', 'Ticket was marked closed.');
        }
        return redirect()->route('admin.support.tickets.open')->with('error', 'Ticket is already either closed or resolved.');
    }
}