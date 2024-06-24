@extends('template.layout')
@section('main-content')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Definition "{{$definition['name']}}" Info</h4>
            <p class="card-description">
                Shows all information about the Definition {{$definition['name']}}
            </p>
            <div class="row">
                <div class="col-md-4">
                    <address>
                        <h4 class="card-title">Main Info</h4>
                        <p class="fw-bold">
                            Name:
                        </p>
                        <p class="mb-2">{{$definition->name}}</p>
                        <p class="fw-bold">
                            Created by:
                        </p>
                        <p class="mb-2">{{ $definition->user->name }}</p>
                        <p class="fw-bold">
                            Category:
                        </p>
                        <p class="mb-2">{{ $definition->category->name }}</p>
                        <p class="fw-bold">
                            Visibility:
                        </p>
                        <p class="mb-2">{{ $definition->private == 0 ? 'Public' : 'Private' }}</p>
                    </address>
                </div>
                <div class="col-md-4">
                    <address>
                        <h4 class="card-title">Variables</h4>
                        @if (isset($variables) && count($variables)>0)
                        @foreach($variables as $variable)
                            <label class="badge badge-dark" style="margin:5px 0 5px 0">{{$variable}}</label> &nbsp;
                        @endforeach
                        @else
                            <p class="mb-2">No definition variables were detected</p>
                        @endif
                    </address>
                </div>
                <div class="col-md-4">
                    <address>
                        <h4 class="card-title">Tags</h4>
                        @if (isset($tags))
                        @foreach($tags as $tag)
                            <label class="badge badge-info" style="margin:5px 0 5px 0">#{{$tag}}</label> &nbsp;
                        @endforeach
                        @else
                            <p class="mb-2">There are no tags on this definition</p>
                        @endif
                    </address>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            @if ($definition != null)
            <div class="col-md-12">
                <h4 class="card-title">Definition {{$definition->name}}'s description</h4>
                <p class="card-description">
                    Shows the description of the Definition "{{$definition->name}}" that the trainees will see
                </p>
                {!! $definition->description !!}
            </div>
            @endif
        </div>
    </div>
</div>
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            @if ($definition != null)
            <div class="col-md-12">
                <h4 class="card-title">Definition {{$definition->name}}'s config file</h4>
                <p class="card-description">
                    Shows the config file of the Definition "{{$definition->name}}"
                </p>
                <pre id="json">{{ $json }}</pre>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection