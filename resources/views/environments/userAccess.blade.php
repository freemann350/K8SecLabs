@extends('template.layout')
@section('main-content')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{$environment->environment->name}}</h4>
            <div class="row">
                <div class="col-md-5">
                    <address>
                        <h4 class="card-title">Main Info</h4>
                        <p class="mb-2"><b><u>Created by:</u></b> {{ $environment->environment->userDefinition->user->name }}</p>
                        <p class="mb-2"><b><u>Category:</u></b> {{ $environment->environment->userDefinition->definition->category->name }}</p>
                        <p class="mb-2"><b><u>Created at:</u></b> {{ $environment->created_at }}</p>
                    </address>
                </div>
                <div class="col-md-2">
                    <address>
                        <h4 class="card-title">Status</h4>
                        @if ($environment->environment->end_date == null)
                        <label class="badge badge-success">Running</label>
                        @else
                        <label class="badge badge-dark">Terminated</label>
                        @endif
                    </address>
                </div>
                <div class="col-md-5">
                    <address>
                        <h4 class="card-title">Tags</h4>
                        @if (isset($environment->environment->userDefinition->definition->tags))
                        @php
                            $tags = explode(',', $environment->environment->userDefinition->definition->tags);
                        @endphp
                        @foreach($tags as $tag)
                            <label class="badge badge-info" style="margin:5px 0 5px 0">#{{$tag}}</label> &nbsp;
                        @endforeach
                        @else
                            <p class="mb-2">There are no tags on this environment</p>
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
            <div class="col-md-12">
                <h4 class="card-title">Description</h4>
                {!! $environment->description !!}
            </div>
        </div>
    </div>
</div>
@endsection