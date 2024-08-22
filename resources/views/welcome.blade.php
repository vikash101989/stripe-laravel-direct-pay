@extends('layouts.master') 

@section('css')
@endsection 

@section('content')

    <div class="row">
        <div class="text-center">
            <h2>Product List</h2>
            <p>The product list page to show the list :</p>
        </div>
        @if(Session::has('message'))
            <p class="alert text-center {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
        @endif
        <div class="offset-1 col-md-9 text-justify">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ( $porducts as $product)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->amount }}</td>
                        <td>{{ $product->description }}</td>
                        <td>
                        
                            <a href="{{route('product.payment', $product->id)}}" class="fa fa-eyedropper" data-toggle="tooltip"> 
                                <button type="button" class="btn btn-primary btn-small">Buy Now</button>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center"> No data found </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
