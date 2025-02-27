@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-center">Welcome to the Sales Analysis Dashboard</h1>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Sales</h5>
                    <p class="card-text">₹ {{ number_format(500000, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Best Selling Product</h5>
                    <p class="card-text">Product Name: Laptop</p>
                    <p class="card-text">Total Sold: 120 Units</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Highest Sales Month</h5>
                    <p class="card-text">December</p>
                    <p class="card-text">₹ {{ number_format(120000, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
