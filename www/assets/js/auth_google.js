/* google로부터 가져온 유저의 정보를 php back단으로 다시 보내기 위한 ajax 소스
만약 ajax 통신을 성공해서 정상적인 유저의 정보를 업데이트 시키고, session도 정상적으로 구워졌다면
zendesk 로그인의 연동을 위해서 다시 auth/sign/zendeskin 으로 가서 zendesk에도 로그인을 시켜놓고 siteList로 돌아온다.*/

function onSignIn(googleUser) {
        // Useful data for your client-side scripts:
        var profile = googleUser.getBasicProfile();

        var Id = profile.getId(); // Don't send this directly to your server!
        var Name = profile.getName();
        var Email = profile.getEmail();
                var obj = {name: Name, ID: Id, email:Email};

  		$.ajax({
        url : '/auth/sign/google',
        dataType : 'json',
        data: {"data" : obj},
        type : 'POST',
        success : function( data ){
            //성공
            if( data == "ok" )
            { 
                console.log("success");
                location.replace('/auth/sign/zendeskin');
                var auth2 = gapi.auth2.getAuthInstance();
                auth2.signOut().then(function () {
                    console.log('User signed out.');
                });
                return true;
            }

            //facebook 으로 이미 가입한 경우
            else if( data == "is_facebook" )
            { 
                makeModalAlert('Notice', c2ms("W_emailExists"));
                return true; 
            }
            
            //google 유저가 아닌경우
            else if( data == "unverified" ){ 
                //var text = "You are not verified user in Google+, Please Login another way or take a verifying step in Google+";
                makeModalAlert('Notice', c2ms("W_hasError"));
            }
         },
        error: function (e) {
            console.log(e);
        }
    });

    return true;
};
