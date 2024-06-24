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
          <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Add new variable
            </button>
            <div class="dropdown-menu">
              <button type="button" class="dropdown-item" onClick="appendInput('string')">String</button>
              <button type="button"  class="dropdown-item" onClick="appendInput('number')">Number</a>
              <button type="button"  class="dropdown-item" onClick="appendInput('random')">Random Number</a>
              <button type="button"  class="dropdown-item" onClick="appendInput('flag')">Flag</a>
            </div>
          </div>
          <div class="col-sm-12" id="string">
            @if (old('str_name'))
                @foreach (old('str_name') as $index => $str)
                    <div class="input-group mb-3 dynamic-input">
                        <div class="input-group-prepend">
                            <span class="input-group-text">String name</span>
                        </div>
                        <input type="text" class="form-control fix-height @error("str_name.$index") is-invalid @enderror" name="str_name[]" value="{{old("str_name.$index")}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Value</span>
                        </div>
                        <input type="text" class="form-control fix-height @error("str_val.$index") is-invalid @enderror" name="str_val[]" value="{{old("str_val.$index")}}">
                        <button type="button" class="btn btn-danger removeInput fix-height"><i class="ti-trash removeInput"></i></button>
                        @error("str_name.$index")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        @error("str_val.$index")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @endforeach
            @endif
          </div>
          <div class="col-sm-12" id="number">
            @if (old('num_name'))
                @foreach (old('num_name') as $index => $num)
                    <div class="input-group mb-3 dynamic-input">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Number name</span>
                        </div>
                        <input type="text" class="form-control fix-height @error("num_name.$index") is-invalid @enderror" name="num_name[]" value="{{old("num_name.$index")}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Value</span>
                        </div>
                        <input type="text" class="form-control fix-height @error("num_val.$index") is-invalid @enderror" name="num_val[]" value="{{old("num_val.$index")}}">
                        <button type="button" class="btn btn-danger removeInput fix-height"><i class="ti-trash removeInput"></i></button>
                        @error("num_name.$index")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        @error("num_val.$index")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @endforeach
            @endif
          </div>
          <div class="col-sm-12" id="random">
            @if (old('rand_name'))
                @foreach (old('rand_name') as $index => $rand)
                    <div class="input-group mb-3 dynamic-input">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rand. name</span>
                        </div>
                        <input type="text" class="form-control fix-height @error("rand_name.$index") is-invalid @enderror" name="rand_name[]" value="{{old("rand_name.$index")}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Min</span>
                        </div>
                        <input type="text" class="form-control fix-height @error("min.$index") is-invalid @enderror" name="min[]" value="{{old("min.$index")}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Max</span>
                        </div>
                        <input type="text" class="form-control fix-height max-input @error("max.$index") is-invalid @enderror" name="max[]" value="{{old("max.$index")}}">
                        <button type="button" class="btn btn-danger removeInput fix-height"><i class="ti-trash removeInput"></i></button>
                        @error("rand_name.$index")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        @error("max.$index")
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
                @endforeach
            @endif
          </div>
          <div class="col-sm-12" id="flag">
            @if (old('flag_name'))
                @foreach (old('flag_name') as $index => $flag)
                    <div class="input-group mb-3 dynamic-input">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Flag name</span>
                        </div>
                        <input type="text" class="form-control fix-height @error("flag_name.$index") is-invalid @enderror" name="flag_name[]" value="{{old("flag_name.$index")}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Value</span>
                        </div>
                        <input type="text" class="form-control fix-height @error("flag_val.$index") is-invalid @enderror" name="flag_val[]" value="{{old("flag_val.$index")}}" placeholder="Empty value creates a random sha256 flag (different each environment)">
                        <button type="button" class="btn btn-danger removeInput fix-height"><i class="ti-trash removeInput"></i></button>
                        @error("flag_name.$index")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        @error("flag_val.$index")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
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
