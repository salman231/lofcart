var googleUser = {};
window.startApp = function() {
  gapi.load('auth2', function(){
    window.auth2 = gapi.auth2.init({
      client_id: window.google_client_id,
      cookiepolicy: 'single_host_origin',
      scope: 'profile email'
    });
    attachSignin(document.getElementsByClassName('googleClass')[0]);
    attachSignin(document.getElementsByClassName('googleClass')[1]);
  });
};

window.onbeforeunload = function(e){
  gapi.auth2.getAuthInstance().signOut();
};

window.attachSignin= function attachSignin(element) {

  window.auth2.attachClickHandler(element, {},
    function(googleUser) {
          var profile = googleUser.getBasicProfile();
          var id = profile.getId();
          var name = profile.getName();
          var email = profile.getEmail();
          var social_type = 'google';
          var form_Key = window.form_Key;
          var request_data = 'id='+id+'&name='+name+'&email='+email+'&social_type='+social_type+'&form_key='+form_Key;
          
          var xhttp = window.xhttp;
           xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(this.responseText);
                var url = data.data.redirect;
                if(url!=''){
                  window.location.href = url;
                }
            }
          };

          xhttp.open("POST", window.request_url, true);
          xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xhttp.send(request_data);

    }, function(error) {

  });

}
window.onload=(function(){
  startApp();
}).bind(this);
