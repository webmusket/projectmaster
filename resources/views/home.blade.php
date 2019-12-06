@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div>

                        <form method="post" action="/update-userdata" enctype="multipart/form-data">
                        
        {{ csrf_field() }}
                            <input type="hidden" value="{{$user->id}}" name="id">
                            <div class="form-group row">
                                <label for="titleid" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                    <input name="name" type="text" value="{{$user->name}}" class="form-control" placeholder="Your Name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="publisherid" class="col-sm-3 col-form-label">E-amil</label>
                                <div class="col-sm-9">
                                    <input name="email" type="email" value="{{$user->email}}" class="form-control"  placeholder="Your Email">
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="offset-sm-3 col-sm-9">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
