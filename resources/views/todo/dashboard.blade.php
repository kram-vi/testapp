@extends('layouts.app')
@section('title', 'Home ')
@section('content')
 <div class="row">
    <div class="col-md-9">
    <ul class="list-group">
    @if($todos != false)
      @foreach ($todos as $todo)
        <li class="list-group-item">
          <a class="secondary-content" href="{{url('/todo/'.$todo->id)}}">
            <span class="fa fa-play"></span>
          </a>
          <a class="secondary-content" href="{{url('/todo/'.$todo->id).'/edit'}}">
            <span class="fa fa-edit"></span>
          </a>
          <a href="#" class="secondary-content" onclick="event.preventDefault();
                                              $(this).next().submit();">
            <span class="fa fa-trash"></span>
          </a>
          <form id="delete-form" action="{{url('/todo/'.$todo->id)}}" method="POST" style="display: none;">
                            {{ method_field('DELETE') }} @csrf
          </form> {{$todo->todo}}
        </li>
      @endforeach
    @else
    <li class="list-group-item"> No Todo added yet
      <a href="{{ url('/todo/create') }}"> click here</a> to add new todo.
    </li>
  @endif
    </ul>
    </div>
      <div class="col-md-3">
          <img class="img-responsive img-circle" src="{{asset('storage/'.$image)}}" style="width:250px;height:250px;">
      </div>
    </div>
@endsection