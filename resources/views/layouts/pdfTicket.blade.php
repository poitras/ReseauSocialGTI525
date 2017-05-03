<!-- This page is used ONLY for the generation of the PDF version of a ticket. -->

<table align="center">
    <tr>
        <th>First Name</th>
        <th> </th>
        <th>Last Name</th>
    </tr>

    <tr>
        <td>{{ $ticket[0]->owner_first_name }}</td>
        <td> </td>
        <td>{{ $ticket[0]->owner_last_name }}</td>
    </tr>

    <tr>
        <th>Event</th>
        <th>Artist</th>
        <th>Venue</th>
    </tr>

    <tr>
        <td>{{ $ticket[0]->event }}</td>
        <td>{{ $ticket[0]->artist }}</td>
        <td>{{ $ticket[0]->venue }}</td>
    </tr>

    <tr>
        <th>City</th>
        <th>Price</th>
        <th>Date</th>
    </tr>

    <tr>
        <td>{{ $ticket[0]->city }}</td>
        <td>{{ (number_format($ticket[0]->price, 2, '.', ',')) }}$ </td>
        <td>{{ \Carbon\Carbon::parse($ticket[0]->date_event)->format('Y-m-d') }}</td>
    </tr>

    <tr>
        <th align="center" colspan="3">QR Code</th>
    </tr>

    <tr>
        <td align="center" colspan="3"><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate($ticket[0]->unique_id)) !!} "></td>
    </tr>
</table>