@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Cryptocurrencies</strong>
                </div>
                <div class="panel-body">
                    <currency-symbol-search></currency-symbol-search>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Stocks</strong>
                </div>
                <div class="panel-body">
                    <symbol-search></symbol-search>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection