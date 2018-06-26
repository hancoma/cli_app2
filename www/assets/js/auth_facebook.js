/* facebook로부터 가져온 유저의 정보를 php back단으로 다시 보내기 위한 ajax 소스
만약 ajax 통신을 성공해서 정상적인 유저의 정보를 업데이트 시키고, session도 정상적으로 구워졌다면
zendesk 로그인의 연동을 위해서 다시 auth/sign/zendeskin 으로 가서 zendesk에도 로그인을 시켜놓고 siteList로 돌아온다.

facebook의 경우에는 facebook server로부터 script 소스 코드를 불러오는 것부터, 각 status에 따라서 어떻게 callback을 할것인지 적는 단계가 모두 이 곳에 담겨있다.*/

 window.fbAsyncInit = function() {
    FB.init({
      //appId      : '753967758074095',
	  appId		 : '960295420719909',
	  //appId		 : '1741049319471419',
 	  cookie     : true,
      xfbml      : true,
      version    : 'v2.5'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

// This is called with the results from from FB.getLoginStatus().

function statusChangeCallback(response) {
	// The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().

    if (response.status === 'connected') {
		// Logged into your app and Facebook.
		testAPI();
    } else if (response.status === 'not_authorized') {
		// The person is logged into Facebook, but not your app.
		document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function checkLoginState() {
	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
    });
}

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function testAPI() {

	//2.5 version 부터 fields를 넣어줘야만 한다
	FB.api('/me', {fields:"verified, name, email, first_name, last_name, link, birthday, gender, locale, updated_time"}, function(response) { 
		console.log(response);
		// document.getElementById('status').innerHTML =
		//   'Thanks for logging in, ' + response.name + '!';
		var fb_response = JSON.stringify(response);

		FB_function(fb_response);
		FB.logout(function(response) {
			// Person is now logged out
		});
    });
}

function FB_function(fb_response){ console.log("login"); console.log(fb_response);
  $.ajax({
		url : '/auth/sign/facebook',
    dataType : 'json',
    data: {"data" : fb_response},
    type : 'POST',
    success : function( data ){
      
	  if( data == "ok" ){ //성공
	  	console.log("success");
       	location.replace('/auth/sign/zendeskin');
        return true;
      }
      
      else if( data == "is_google" ){//google로 이미 가입한 경우
        //modal message 출력
        makeModalAlert('Notice', c2ms("W_emailExists"));
        return true; 
      }
      
      else if( data == "Email" ){//이메일이 없음. (핸드폰 번호로 가입한 고객인 경우)
        //modal message 출력
        makeModalAlert('Notice', c2ms("W_emailExists"));
        
        location.replace('/auth/sign/in');
        return true;
      }

      else if( data == "unverified" ){ //Facebook에 가입되지 않은 경우
        //modal message 출력
        makeModalAlert('Notice', c2ms("W_hasError"));
      }
    },
    error: function (e) {
		console.log(e);
    }
  });

  return true;
}
