@extends('app')

@section('title')
    UC-Insight - SQL Query Tool
@stop

@section('content')

    <div id="chart-div"></div>
    {!!  Lava::render('DonutChart', 'Cisco IP Phones', 'chart-div') !!}
    @donutchart('Cisco IP Phones', 'chart-div')

@stop