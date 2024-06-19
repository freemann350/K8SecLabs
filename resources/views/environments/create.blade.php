@extends('template.layout')

@section('main-content')
<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Add new Definition</h4>
      <p class="card-description">Here you can add a new definition</p>
      <form method="POST" action="{{ route('Environments.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Name *</label>
          <div class="col-sm-12">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Your environment" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Definition *</label>
          <div class="col-sm-12">
            <select class="form-select" name="definition">
              @foreach($user_definitions as $user_definition)
                <option value="{{ $user_definition->id }}">{{ $user_definition->definition->name }}</option>
              @endforeach
            </select>
            @error('definition')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Access Code *</label>
          <div class="col-sm-12">
            <input type="text" name="access_code" class="form-control @error('access_code') is-invalid @enderror" value="{{ old('access_code') }}" placeholder="********" required>
            @error('access_code')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Scenario quantity *</label>
          <div class="col-sm-12">
            <input type="text" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" placeholder="3" required>
            @error('quantity')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Initial exposed port *</label>
          <div class="col-sm-12">
            <input type="text" name="port" class="form-control @error('port') is-invalid @enderror" value="{{ old('port') }}" placeholder="30000" required>
            @error('port')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Description</label>
          <div class="col-sm-12">
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description">{{ isset($definition['description']) ? $definition['description']  : '' }}</textarea>
            @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <button type="submit" class="btn btn-primary me-2">Submit</button>
        <button type="button" class="btn btn-light" onclick="window.location='{{ route('Environments.index') }}'">Cancel</button>
      </form>
    </div>
  </div>
</div>
@endsection
