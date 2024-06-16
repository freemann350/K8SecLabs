@extends('template.layout')
@section('main-content')
@if ($environments == null)
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-header">
            <h5 class="display-5">Running environments</h5>
        </div>
        <div class="card-body">
            <h4 class="card-title">There are currently no running environments</h4>
            <p class="card-description">
                You must wait for a lecturer to start one environment to join it
            </p>
        </div>
    </div>
</div>
@else
<br>
<div class="col-md-12 mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="display-5">List of all running environments</h5>
        </div>
        <div class="card-body">
            <div class="row">
            @foreach ($environments as $environment)
                @if ($environment->user_count != $environment->quantity)
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="display-5"><strong>{{$environment['name']}}</strong></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <address>
                                        <p class="fw-bold">
                                            Created by:
                                        </p>
                                        <p>
                                            {{$environment->userDefinition->user->name}}
                                        </p>
                                        <p class="fw-bold">
                                            Category
                                        </p>
                                        <p>
                                            {{$environment->userDefinition->definition->category->name}}
                                        </p>
                                        <p class="fw-bold">
                                            Joinable environments
                                        </p>
                                        <p>
                                            {{$environment->user_count}}/{{$environment->quantity}}
                                        </p>
                                    </address>
                                </div>
                                <div class="col-md-8">
                                    <address>
                                        <p class="fw-bold">
                                            Description
                                        </p>
                                        <description>
                                            {!! $environment['description'] !!}
                                        </description>
                                    </address>
                                    <hr>
                                    <button class="btn btn-outline-dark btn-lg btn-block" onclick="joinEnvironment({{ $environment['id'] }})">Join environment</button><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
            </div>
        </div>
    </div>
</div>
@endif
<script>
    function joinEnvironment(environmentId) {
        Swal.fire({
            title: 'Enter Access Code',
            html: '<form id="join-form" method="POST" action="/JoinEnvironment/' + environmentId + '">' +
                  '@csrf' +
                  '<input type="text" id="access-code" name="access_code" class="swal2-input" placeholder="Access Code">' +
                  '</form>',
            focusConfirm: false,
            preConfirm: () => {
                const form = Swal.getPopup().querySelector('#join-form');
                const accessCode = form.querySelector('#access-code').value;
                if (!accessCode) {
                    Swal.showValidationMessage('Access code is required');
                } else {
                    form.submit();
                }
            }
        });
    }
</script>
@endsection