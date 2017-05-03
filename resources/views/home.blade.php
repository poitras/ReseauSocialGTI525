@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @include('/layouts/userTickets')
            @include('/layouts/publicite')
        </div>
    </div>
</div>
@endsection
