<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#details_modal_{{$ticket->id}}">
    Show
</button>

<div class="modal fade" id="details_modal_{{$ticket->id}}" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ticket Description</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group row">
                        <label for="issue" class="col-sm-2 col-form-label">Issue</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" id="issue"
                                   value="{{$ticket->issue}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="subIssue" class="col-sm-2 col-form-label">Sub Issue</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" id="subIssue"
                                   value="{{$ticket->subIssue}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" id="email"
                                   value="{{$ticket->email}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Subject</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" id="email"
                                   value="{{$ticket->subject}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <textarea readonly class="form-control"
                                      id="description">{{$ticket->description}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-2 col-form-label">Related orders</label>
                        <div class="col-sm-10 my-auto">
                            @foreach($ticket->orderId as $orderId)
                                <a target="_blank" href="{{route('admin.orders.show',$orderId)}}"
                                   class="badge badge-dark">Order
                                    #{{$orderId}}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="callbackNumber" class="col-sm-2 col-form-label">Callback Number</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control"
                                   id="callbackNumber" value="{{$ticket->callbackNumber}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-2 col-form-label">Attachments</label>
                        <div class="col-sm-10 my-auto">
                            @foreach($ticket->attachments as $attachment)
                                <a href="{{$attachment}}" target="_blank" class="badge badge-dark">Attachment
                                    #{{$loop->index+1}}</a>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                @if(!isset($disabled))
                    <a href="{{route('admin.support.tickets.mark.resolved',$ticket->id)}}" class="btn btn-success">Mark
                        Resolved</a>
                    <a href="{{route('admin.support.tickets.mark.closed',$ticket->id)}}" class="btn btn-warning">Mark
                        Closed</a>
                @endif
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>