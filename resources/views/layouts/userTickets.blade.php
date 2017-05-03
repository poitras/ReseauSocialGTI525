<!-- userTickets.blade.php : Used to show currently logged in tickets. Currently included in : profile.blade.php -->

<h2>Your tickets</h2>
@foreach($arrayTickets as $ticket)
    <div>
        <div>
            <div class="row">
                <div class="col-md-3">
                        <img alt="" style="width:600px;" title="" class="img-circle img-thumbnail isTooltip" src="{{$ticket->image}}" data-original-title="Usuario">
                        <ul title="Download" class="list-inline ratings text-center">
                                <a href="{{ route('pdfTicket',['download'=>'pdf', 'uniqueID'=>$ticket->unique_id]) }}"> Download Ticket </a>
                        </ul>
                </div>
                <div class="col-md-9">
                    <strong>Informations</strong><br>
                    <div class="table-responsive">
                        <table class="table table-condensed table-responsive table-user-information">
                            <tbody>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-asterisk text-primary"></span>
                                        Ticket unique ID
                                    </strong>
                                </td>
                                <td class="text-primary">{{ $ticket->unique_id }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-user  text-primary"></span>
                                        Owner name
                                    </strong>
                                </td>
                                <td class="text-primary">{{ $ticket->owner_first_name }} {{$ticket->owner_last_name}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-star text-primary"></span>
                                        Artist
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $ticket->artist }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-music text-primary"></span>
                                        Event
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $ticket->event }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-calendar text-primary"></span>
                                        Date
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ \Carbon\Carbon::parse($ticket->date_event)->format('Y-m-d') }}
                                </td>
                            </tr>


                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-equalizer text-primary"></span>
                                        Venue
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $ticket->venue }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-road text-primary"></span>
                                        City
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $ticket->city }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-align-left text-primary"></span>
                                        Description
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $ticket->description }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
