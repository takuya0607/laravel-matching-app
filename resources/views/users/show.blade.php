@extends('layouts.layout')

@section('content')

<div class='usershowPage'>
  <div class='container'>
    <header class="header">
      <p class='header_logo mt-1 mr-2'>
        <a href="{{ route('home') }}">
          <i class="fas fa-fire-alt fa-4x" style="color:deeppink;"></i>
        </a>
      </p>
    </header>
    <div class='userInfo'>
      <div class='userInfo_img'>
          @isset($user->img_name)
            <img src="data:image/png;base64,{{ $user->img_name}}" alt onerror="this.onerror = null; this.src='';">
          @else
            <img src="/images/avatar-default.svg" class="rounded-circle">
          @endisset
      </div>
      <div class="row">
        <div class="col-sm-6 offset-sm-3">
          <div class='userInfo_name'>{{ $user -> name }}</div>
          <div class='userInfo_selfIntroduction'>{{ $user -> self_introduction }}</div>
        </div>
      </div>
    </div>

      <div class='userAction'>
        <div class="userAction_edit userAction_common">
          <a href="/users/edit/{{$user->id}}"><i class="fas fa-edit fa-2x"></i></a>
          <span>情報を編集</span>
        </div>
        <div class='userAction_logout userAction_common'>
            <i class="fas fa-cog fa-2x" data-toggle="modal" data-target="#logoutModal"></i>
          <span>ログアウト</span>
        </div>
      </div>

  </div>
</div>


<!-- ボタン・リンククリック後に表示される画面の内容 -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <label>ログアウトしますか？</label>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
        <div class="delete_btn">
          <button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#logoutModal">
          <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
          </a>ログアウト</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>



@endsection