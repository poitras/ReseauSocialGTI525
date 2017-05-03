@extends('layouts.app')

@section('content')
    <script>
        function confirmModification() {
            var result = confirm("êtes-vous sur de vouloir faire cette modification?");

            if (result) {
                return true;
            } else {
                return false;
            }
        }
    </script>
    <meta charset="UTF-8">
    <div class="container">
        @if (session('succes'))
            <div class="alert alert-success">
                {{ session('succes') }}
            </div>
        @endif
        @if (session('fail'))
            <div class="alert alert-danger">
                {{ session('fail') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <img src="{{ Auth::user()->avatar }}" style="width:150px; height:150px; float:left; border-radius:50%; margin-right: 25px;">
                <h2>Profile de {{ Auth::user()->first_name }}</h2>

                <form enctype="multipart/form-data" action="/profileSettingsAvatar" method="POST">
                    {{ csrf_field() }}
                    <label>Choisissez une image sur votre ordinateur</label>
                    <input type="file" name="avatarFile">
                    <label>Entrez l'url de l'image</label><br>
                    <input type="text" name="avatarURL">
                    <input type="submit" class="btn-primary" value="Soumettre">
                </form>
            </div>
        </div>
    </div><br><br>

    <div class="row">
        <div class="panel panel-default col-md-8 col-md-offset-2" >
            <div class="panel-heading">
                <h3 class="panel-title">Paramètre généraux du compte</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" action="/profileSettingsUpdate" method="POST" onsubmit="return confirmModification()">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-group">
                        <label for="first_name" class="col-sm-2 control-label">Prenom</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='first_name' id="first_name" value="{{ $user->first_name }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="col-sm-2 control-label">Nom</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='last_name' id="last_name" value="{{ $user->last_name }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Courriel</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name='email' id="email" value="{{ $user->email }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address" class="col-sm-2 control-label">Adresse</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='address' id="address" value="{{ $user->address }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="city" class="col-sm-2 control-label">Ville</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='city' id="city" value="{{ $user->city }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="province" class="col-sm-2 control-label">Province</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='province' id="province" value="{{ $user->province }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="postal_code" class="col-sm-2 control-label">Code postal</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='postal_code' id="postal_code" value="{{ $user->postal_code }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-danger">Modifier</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div><br>

    <div class="row">
        <div class="panel panel-default col-md-8 col-md-offset-2" >
            <div class="panel-heading">
                <h3 class="panel-title">Modifier votre mot de passe</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" action="/profileSettingsUpdatePw" method="POST" onsubmit="return confirmModification()">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="form-group">
                        <label for="current_password" class="col-sm-2 control-label">Mot de passe</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name='current_password' id="current_password" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Nouveau</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name='password' id="password" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="col-sm-2 control-label">Confirmation</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name='password_confirmation' id="password_confirmation" >
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-danger">Modifier</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection