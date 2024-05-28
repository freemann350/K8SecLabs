@extends('template.layout')

@section('main-content')

<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit Category</h4>
            <p class="card-description">
                Here you can edit a category
            </p>
            <form method="POST" action="{{ route('Categories.update', $category->category_id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="col-sm-3 col-form-label">Name</label>
                    <div class="col-sm-12">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $category->name }}" placeholder="Category name" required>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 col-form-label">Training Type</label>
                    <div class="col-sm-12">
                        <select class="form-select" name="training_type">
                            <option value="R" {{ $category->training_type == 'Red' ? 'selected' : '' }}>Red</option>
                            <option value="B" {{ $category->training_type == 'Blue' ? 'selected' : '' }}>Blue</option>
                            <option value="U" {{ $category->training_type == 'Undefined' ? 'selected' : '' }}>Undefined</option>
                        </select>
                        @error('training_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-fw">Submit</button>
            </form>
        </div>
    </div>
</div>

@endsection
