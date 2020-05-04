@extends('layouts.app')
@section('title', $todo->todo)
@section('content')
<div class="row">
  <div class="col-md-8 offset-md-4">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3>{{$todo->todo}}
          <a href="{{url('/todo/'.$todo->id).'/edit'}}" class="btn btn-warning btn-group-sm pull-right ">Edit</a>
        </h3>
      </div>
        <div class="panel-body">
          {{$todo->description}}
        </div>
        <div class="panel-footer">
          <strong>Category:</strong> {{$todo->category}}
        </div>
    </div>
  </div>
</div>
@endsection