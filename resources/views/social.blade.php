<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Inklua - Auth Social</title>
  <meta name="google-signin-client_id" content="{{ env("GOOGLE_CLIENT_ID") }}">
</head>

<body class="bg-gradient-primary">

    <script>
        window.fbAsyncInit = function() {
        FB.init({
            appId      : {{ env("FACEBOOK_CLIENT_ID") }},
            cookie     : true,
            xfbml      : true,
            version    : 'v10.0'
        });
            
        FB.AppEvents.logPageView();
        };

        (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        function checkLoginState() {
            FB.getLoginStatus(function(response) {
                statusChangeCallback(response);
            });
        }

        function statusChangeCallback(response){
            console.log(response.authResponse.accessToken);
            //post('/api/auth/facebook', {token: response.authResponse.accessToken})
        }


        /* Google */

        function onSignIn(googleUser) {
            console.log(googleUser.getAuthResponse(true).access_token);
        }
        function onFailure(error) {
            console.log(error);
        }
    </script>

    <script src="https://apis.google.com/js/platform.js" async defer></script>


    <fb:login-button  scope="public_profile,email" onlogin="checkLoginState();"> </fb:login-button>

    <div class="g-signin2" data-onsuccess="onSignIn" data-onfailure="onFailure"></div>


</body>

</html>
