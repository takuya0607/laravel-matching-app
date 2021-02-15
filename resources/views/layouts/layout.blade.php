<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

    @yield('content')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>


<!-- user.js -->
<script>
  $(document).on("change", "#file_photo", function (e) {
    var reader;
    if (e.target.files.length) {
      reader = new FileReader;
      reader.onload = function (e) {
        var userThumbnail;
        userThumbnail = document.getElementById('thumbnail');
        $("#userImgPreview").addClass("is-active");
        userThumbnail.setAttribute('src', e.target.result);
      };
      return reader.readAsDataURL(e.target.files[0]);
    }
  });
</script>


<!-- jTinder -->
<script>
var currentUserIndex = 0;
var postReaction = function (to_user_id, reaction) {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
  });
  $.ajax({
    type: "POST",
    url: "/api/like",
    data: {
      to_user_id: to_user_id,
      from_user_id: from_user_id,
      reaction: reaction,
    },
    success: function (j_data) {
      console.log("success")
    }
  });
}
$("#tinderslide").jTinder({
  onDislike: function (item) {
    currentUserIndex++;
    checkUserNum();
    var to_user_id = item[0].dataset.user_id
    postReaction(to_user_id, 'dislike')
  },
  onLike: function (item) {
    currentUserIndex++;
    checkUserNum();
    var to_user_id = item[0].dataset.user_id
    postReaction(to_user_id, 'like')
  },
  animationRevertSpeed: 200,
  animationSpeed: 400,
  threshold: 1,
  likeSelector: '.like',
  dislikeSelector: '.dislike'
});
$('.actions .like, .actions .dislike').click(function (e) {
  e.preventDefault();
  $("#tinderslide").jTinder($(this).attr('class'));
});

function checkUserNum() {
  // スワイプするユーザー数とスワイプした回数が同じになればaddClassする
  if (currentUserIndex === usersNum) {
    $(".noUser").addClass("is-active");
    $("#actionBtnArea").addClass("is-none")
    return;
  }
}
</script>


<script>
/*
 * jTinder v.1.0.0
 * https://github.com/do-web/jTinder
 * Requires jQuery 1.7+, jQuery transform2d
 *
 * Copyright (c) 2014, Dominik Weber
 * Licensed under GPL Version 2.
 * https://github.com/do-web/jTinder/blob/master/LICENSE
 */
; (function ($, window, document, undefined) {
  var pluginName = "jTinder",
    defaults = {
      onDislike: null,
      onLike: null,
      animationRevertSpeed: 200,
      animationSpeed: 400,
      threshold: 1,
      likeSelector: '.like',
      dislikeSelector: '.dislike'
    };

  var container = null;
  var panes = null;
  var $that = null;
  var xStart = 0;
  var yStart = 0;
  var touchStart = false;
  var posX = 0, posY = 0, lastPosX = 0, lastPosY = 0, pane_width = 0, pane_count = 0, current_pane = 0;

  function Plugin(element, options) {
    this.element = element;
    this.settings = $.extend({}, defaults, options);
    this._defaults = defaults;
    this._name = pluginName;
    this.init(element);
  }

  Plugin.prototype = {


    init: function (element) {

      container = $(">ul", element);
      panes = $(">ul>li", element);
      pane_width = container.width();
      pane_count = panes.length;
      current_pane = panes.length - 1;
      $that = this;

      $(element).bind('touchstart mousedown', this.handler);
      $(element).bind('touchmove mousemove', this.handler);
      $(element).bind('touchend mouseup', this.handler);
    },

    showPane: function (index) {
      panes.eq(current_pane).hide();
      current_pane = index;
    },

    next: function () {
      return this.showPane(current_pane - 1);
    },

    dislike: function () {
      panes.eq(current_pane).animate({ "transform": "translate(-" + (pane_width) + "px," + (pane_width * -1.5) + "px) rotate(-60deg)" }, $that.settings.animationSpeed, function () {
        if ($that.settings.onDislike) {
          $that.settings.onDislike(panes.eq(current_pane));
        }
        $that.next();
      });
    },

    like: function () {
      panes.eq(current_pane).animate({ "transform": "translate(" + (pane_width) + "px," + (pane_width * -1.5) + "px) rotate(60deg)" }, $that.settings.animationSpeed, function () {
        if ($that.settings.onLike) {
          $that.settings.onLike(panes.eq(current_pane));
        }
        $that.next();
      });
    },

    handler: function (ev) {
      ev.preventDefault();

      switch (ev.type) {
        case 'touchstart':
          if (touchStart === false) {
            touchStart = true;
            xStart = ev.originalEvent.touches[0].pageX;
            yStart = ev.originalEvent.touches[0].pageY;
          }
        case 'mousedown':
          if (touchStart === false) {
            touchStart = true;
            xStart = ev.pageX;
            yStart = ev.pageY;
          }
        case 'mousemove':
        case 'touchmove':
          if (touchStart === true) {
            var pageX = typeof ev.pageX == 'undefined' ? ev.originalEvent.touches[0].pageX : ev.pageX;
            var pageY = typeof ev.pageY == 'undefined' ? ev.originalEvent.touches[0].pageY : ev.pageY;
            var deltaX = parseInt(pageX) - parseInt(xStart);
            var deltaY = parseInt(pageY) - parseInt(yStart);
            var percent = ((100 / pane_width) * deltaX) / pane_count;
            posX = deltaX + lastPosX;
            posY = deltaY + lastPosY;

            panes.eq(current_pane).css("transform", "translate(" + posX + "px," + posY + "px) rotate(" + (percent / 2) + "deg)");

            var opa = (Math.abs(deltaX) / $that.settings.threshold) / 100 + 0.2;
            if (opa > 1.0) {
              opa = 1.0;
            }
            if (posX >= 0) {
              panes.eq(current_pane).find($that.settings.likeSelector).css('opacity', opa);
              panes.eq(current_pane).find($that.settings.dislikeSelector).css('opacity', 0);
            } else if (posX < 0) {

              panes.eq(current_pane).find($that.settings.dislikeSelector).css('opacity', opa);
              panes.eq(current_pane).find($that.settings.likeSelector).css('opacity', 0);
            }
          }
          break;
        case 'mouseup':
        case 'touchend':
          touchStart = false;
          var pageX = (typeof ev.pageX == 'undefined') ? ev.originalEvent.changedTouches[0].pageX : ev.pageX;
          var pageY = (typeof ev.pageY == 'undefined') ? ev.originalEvent.changedTouches[0].pageY : ev.pageY;
          var deltaX = parseInt(pageX) - parseInt(xStart);
          var deltaY = parseInt(pageY) - parseInt(yStart);

          posX = deltaX + lastPosX;
          posY = deltaY + lastPosY;
          var opa = Math.abs((Math.abs(deltaX) / $that.settings.threshold) / 100 + 0.2);

          if (opa >= 1) {
            if (posX > 0) {
              panes.eq(current_pane).animate({ "transform": "translate(" + (pane_width) + "px," + (posY + pane_width) + "px) rotate(60deg)" }, $that.settings.animationSpeed, function () {
                if ($that.settings.onLike) {
                  $that.settings.onLike(panes.eq(current_pane));
                }
                $that.next();
              });
            } else {
              panes.eq(current_pane).animate({ "transform": "translate(-" + (pane_width) + "px," + (posY + pane_width) + "px) rotate(-60deg)" }, $that.settings.animationSpeed, function () {
                if ($that.settings.onDislike) {
                  $that.settings.onDislike(panes.eq(current_pane));
                }
                $that.next();
              });
            }
          } else {
            lastPosX = 0;
            lastPosY = 0;
            panes.eq(current_pane).animate({ "transform": "translate(0px,0px) rotate(0deg)" }, $that.settings.animationRevertSpeed);
            panes.eq(current_pane).find($that.settings.likeSelector).animate({ "opacity": 0 }, $that.settings.animationRevertSpeed);
            panes.eq(current_pane).find($that.settings.dislikeSelector).animate({ "opacity": 0 }, $that.settings.animationRevertSpeed);
          }
          break;
      }
    }
  };

  $.fn[pluginName] = function (options) {
    this.each(function () {
      if (!$.data(this, "plugin_" + pluginName)) {
        $.data(this, "plugin_" + pluginName, new Plugin(this, options));
      }
      else if ($.isFunction(Plugin.prototype[options])) {
        $.data(this, 'plugin_' + pluginName)[options]();
      }
    });

    return this;
  };

})(jQuery, window, document);
</script>



<script>
$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $('.messageInputForm_input').keypress(function (event) {
    if (event.which === 13) {
      event.preventDefault();
      $.ajax({
        type: 'POST',
        url: '/chat/chat',
        data: {
          chat_room_id: chat_room_id,
          user_id: user_id,
          message: $('.messageInputForm_input').val(),
        },

      })

        .done(function (data) {
          //console.log(data);
          event.target.value = '';
        });

    }
  });

  window.Echo.channel('ChatRoomChannel')
    .listen('ChatPusher', (e) => {
      console.log(e, e.message.user_id);
      if (e.message.user_id === user_id) {
        console.log(true);
        $('.messages').append(
          '<div class="message"><span>' + current_user_name +
          ':</span><div class="commonMessage"><div>' +
          e.message.message + '</div></div></div>');
      } else {
        console.log(false);
        $('.messages').append(
          '<div class="message"><span>' + chat_room_user_name +
          ':</span><div class="commonMessage"><div>' +
          e.message.message + '</div></div></div>');
      }
    });


});

</script>
</body>
</html>
