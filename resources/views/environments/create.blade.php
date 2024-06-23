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
                <option value="{{ $user_definition->id }}" {{ old('definition')==$user_definition->id ? 'selected' : '' }}>{{ $user_definition->definition->name }}</option>
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
        <div class="form-group form-inline">
          <label class="col-sm-12 col-form-label">Variables</label>
          <div class="col-sm-12" id="variables">
            <button type="button" class="btn btn-dark" onClick="appendInput('variables')">+ Edit Variable</button>
            @if (old('type'))
            @foreach (old('type') as $index => $variableData)
                <div class="dynamic-input">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Type</span>
                    </div>
                    <select class="form-select fix-height @error("type.$index") is-invalid @enderror" name="type[]" onchange="handleTypeChange(this)">
                        <option value="string" {{ $variableData == "string" ? 'selected' : '' }}>String</option> 
                        <option value="number" {{ $variableData == "number" ? 'selected' : '' }}>Number</option> 
                        <option value="rand" {{ $variableData == "rand" ? 'selected' : '' }}>Random Number</option> 
                        <option value="flag" {{ $variableData == "flag" ? 'selected' : '' }}>Flag (empty value creates random sha256 flags)</option> 
                    </select>
                    @error("type.$index")
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text">Variable</span>
                      </div>
                      <input type="text" class="form-control fix-height @error("variable.$index") is-invalid @enderror" name="variable[]" value="{{old("variable.$index")}}">
                      @if (old("type.$index") == 'string' || old("type.$index") == 'number' || old("type.$index") == 'flag')
                      <div class="input-group-prepend value-label">
                          <span class="input-group-text">Value</span>
                      </div>
                      <input type="text" class="form-control fix-height value-input @error("value.$index") is-invalid @enderror" name="value[]" value="{{old("value.$index")}}">
                      @else
                      <div class="input-group-prepend value-label">
                          <span class="input-group-text">Min</span>
                      </div>
                      <input type="text" class="form-control fix-height value-input @error("min.$index") is-invalid @enderror" name="min[]" value="{{old("min.$index")}}">
                      <div class="input-group-prepend max-label">
                          <span class="input-group-text">Max</span>
                      </div>
                      <input type="text" class="form-control fix-height max-input @error("max.$index") is-invalid @enderror" name="max[]" value="{{old("max.$index")}}">
                      @endif
                      <button type="button" class="btn btn-danger removeInput fix-height"><i class="ti-trash removeInput"></i></button>
                      @error("variable.$index")
                          <div class="invalid-feedback">
                              {{ $message }}
                          </div>
                      @enderror
                      @error("value.$index")
                          <div class="invalid-feedback">
                              {{ $message }}
                          </div>
                      @enderror
                      @error("min.$index")
                          <div class="invalid-feedback">
                              {{ $message }}
                          </div>
                      @enderror
                      @error("max.$index")
                          <div class="invalid-feedback">
                              {{ $message }}
                          </div>
                      @enderror
                  </div>
                </div>
                @endforeach
            @endif
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
