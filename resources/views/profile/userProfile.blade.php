@extends('layouts.app')

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>
        function confirmDelete() {
            var result = confirm("êtes-vous sur de vouloir retirer cette personne de votre liste d'ami");

            if (result) {
                return true;
            } else {
                return false;
            }
        }
        function confirmTransfer() {
            var result = confirm('Voulez vous vraiment transféré ce billet?');

            if (result) {
                return true;
            } else {
                return false;
            }
        }
    </script>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <img src="{{ asset($user->avatar) }}" style="width:150px; height:150px; float:left; border-radius:50%; margin-right: 25px;">
                <h2>{{ $user->first_name }}</h2>
                @if( $isFriend->get('is_friend') )

                    <h3>Ami <i class="glyphicon glyphicon-ok"></i></h3>
                    <form action="{{ url('profile/'.$user->id) }}" method="POST" onsubmit="return confirmDelete()" >
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" id="remove-friendship" class="btn btn-danger">Retirer de la liste d'ami <i class="glyphicon glyphicon-remove-circle"></i></button>
                    </form>

                    <h3>Transférer un billet à {{ $user->first_name }}:</h3>
                    <form action="{{ url('ticketTransfer/'.$user->id) }}" method="POST" onsubmit="return confirmTransfer()" >
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}

                        <div class="form-group">
                            <label for="idTicket">Vos Billets:</label>
                            <select class="form-control" name="uniqueTicketId">
                                @if($authUserTicket != null)
                                    @foreach($authUserTicket as $ticket)
                                        <option value="{{ $ticket->unique_id }}" >{{ $ticket->event }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <button type="submit" id="accept-friendship" class="btn btn-primary" >
                            Transférer <i class="glyphicon glyphicon-transfer"></i>
                        </button>
                    </form>

                @elseif( $isPending->get('has_pending_friend_request') )

                    <h3>Demande en attente</h3><br><br>

                    <div class="row">

                        <form action="{{ url('profile/'.$user->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <button type="submit" id="accept-friendship" class="btn btn-success">Accepter <i class="glyphicon glyphicon-ok-circle"></i></button>
                        </form>

                        <form action="{{ url('profile/'.$user->id) }}" method="POST" onsubmit="return confirmDelete()">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="submit" id="remove-friendship" class="btn btn-danger">Rejeter <i class="glyphicon glyphicon-remove-circle"></i></button>
                        </form>

                    </div>

                @elseif( $hasSendRequest->get('has_pending_friend_request') )

                    <h3>Demande d'amitié envoyé! <i class="glyphicon glyphicon-send"></i></h3>
                    <form action="{{ url('profile/'.$user->id) }}" method="POST" onsubmit="return confirmDelete()">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" id="remove-friendship" class="btn btn-danger">Rejeter <i class="glyphicon glyphicon-remove-circle"></i></button>
                    </form>

                @else

                    <h3>Not Friend</h3>

                    <form action="{{ url('profile/'.$user->id) }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="requester" value="{{ Auth::id() }}">
                        <input type="hidden" name="user_requested" value="{{ $user->id }}">
                        <button type="submit" id="add-friendship" class="btn btn-primary">Ajouter <i class="glyphicon glyphicon-plus-sign"></i></button>
                    </form>

                @endif

            </div>
        </div>
        <h2 class="text-center">Les billets de {{ $user->first_name }}</h2>
        @include('/layouts/friendTickets')
    </div>
@endsection