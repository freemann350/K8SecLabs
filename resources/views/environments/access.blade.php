@extends('template.layout')
@section('main-content')
@foreach ($environments as $key => $environment)
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><strong>Environment #{{$key+1}}</strong></h4>
                <div class="row">
                    <div class="col-md-6">
                        <address>
                            <p class="fw-bold">
                                Assigned User
                            </p>
                            <p class="mb-2">
                                {{$environment->user_id == null ? "None" : $environment->user->name}}
                            </p>
                        </address>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-dark btn-lg btn-block {{$environment->user_id == null ? '' : 'disabled' }}" href="{{route("Environments.access",$environment->id)}}">
                            Join this environment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection