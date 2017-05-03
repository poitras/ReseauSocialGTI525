@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h1>Recherche d'ami</h1>
                <form action="{{ url('searchFriends') }}" method="GET" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="text" name="userInput" id="user-input" class="form-control" placeholder="Recherche...">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-default" ><i class="glyphicon glyphicon-search"></i>Recherche</button>
                        </span>
                    </div><!-- /input-group -->
                </form>
                <div class="list-group">
                    @if( isset($users) )
                        @if( count($users) > 0)
                            @foreach($users as $user)
                                @if($friendIds != null AND $friendIds->contains($user->id))
                                    <a href="/profile/{{ $user->id}}" class="list-group-item list-group-item-success">
                                        {{ $user->first_name }} {{ $user->last_name }}<i class="glyphicon glyphicon-ok-circle pull-right"></i>
                                    </a>
                                @elseif($pendingIds != null AND $pendingIds->contains($user->id))
                                    <a href="/profile/{{ $user->id }}" class="list-group-item list-group-item-warning">
                                        {{ $user->first_name }} {{ $user->last_name }}<i class="glyphicon glyphicon-exclamation-sign pull-right"></i>
                                    </a>
                                @elseif($sentIds != null AND $sentIds->contains($user->id))
                                    <a href="/profile/{{ $user->id }}" class="list-group-item list-group-item-default">
                                        {{ $user->first_name }} {{ $user->last_name }}<i class="glyphicon glyphicon-send pull-right"></i>
                                    </a>
                                @else
                                    <a href="/profile/{{ $user->id }}" class="list-group-item">{{ $user->first_name }} {{ $user->last_name }}</a>
                                @endif

                            @endforeach
                        @else
                            <a href="#" class="list-group-item list-group-item-danger">Aucun utilisateur trouvé...</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection