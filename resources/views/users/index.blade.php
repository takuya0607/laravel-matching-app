@extends('layouts.layout')

@section('content')

<div class="matchingPage">
  <header class="header">
    <i class="fas fa-comments fa-3x"></i>
    <div class="header_logo"><a href="{{route('home')}}"><i class="fas fa-fire-alt fa-3x" style="color:deeppink;"></i></a></div>
  </header>
  <div class="container">
    <div class="mt-5">
      <div class="matchingNum">{{ $match_users_count }}人とマッチングしています</div>
      <h2 class="pageTitle">マッチングした人一覧</h2>
      <div class="matchingList">
				@foreach( $matching_users as $user)
          <div class="matchingPerson">
          <div class="matchingPerson_img">
            @isset($user->img_name)
              <img src="data:image/png;base64,{{ $user->img_name}}" alt onerror="this.onerror = null; this.src='';">
            @else
              <img src="/images/avatar-default.svg" class="rounded-circle">
            @endisset
          </div>
          <ul style="list-style: none; padding: 0;">
            <li class="matchingPerson_name mt-5" style="font-weight: bold;">{{ $user->name }}</li>
            <li class='match_userInfo_selfIntroduction mt-1' style="width:550px; height:50px;">{{ $user -> self_introduction }}</li>
          </ul>
            <form method="POST" action="{{ route('chat.show') }}">
            @csrf
              <input name="user_id" type="hidden" value="{{$user->id}}">
              <button type="submit" class="chatForm_btn">チャットを開く</button>
            </form>

          </div>
        @endforeach
      </div>
    <div>
  </div>
</div>

@endsection