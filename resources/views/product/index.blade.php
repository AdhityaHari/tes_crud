
@extends('layouts.app')
 
@section('body')
    <div class="d-flex align-items-center justify-content-between">
        @livewire('product-list')
        <h1 class="mb-0">Daftar Product</h1>
        <a href="{{ route('product.create') }}" class="btn btn-primary">Add Product</a>
    </div>
    <form action="{{ route('product.search') }}" method="GET">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search" name="search" value="{{ old('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </div>
    </form>
    <hr />
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <table class="table table-hover table-striped">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Image</th>
                <th>Price</th>
                <th>Product Code</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($product->count() > 0)
                @foreach($product as $rs)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $rs->title }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $rs->image) }}" alt="Product Image" width="100">
                        </td>
                        <td class="align-middle">{{ $rs->price }}</td>
                        <td class="align-middle">{{ $rs->product_code }}</td>
                        <td class="align-middle">{{ $rs->description }}</td>
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('product.show', $rs->id) }}">
                                    <button  type="button" class="btn btn-secondary">
                                        Detail
                                    </button> 
                                </a>
                                <a href="{{ route('product.edit', $rs->id)}}">
                                    <button  type="button" class="btn btn-primary mx-2">
                                        Edit
                                    </button>
                                </a>
                                <form action="{{ route('product.destroy', $rs->id) }}" method="POST" onsubmit="return confirm('Delete?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="5">Product not found</td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $product->links() }}
@endsection