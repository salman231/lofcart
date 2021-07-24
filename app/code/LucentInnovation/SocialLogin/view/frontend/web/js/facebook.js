window.fbAsyncInit = function() {
    FB.init({
        appId   : window.facebook_app_id,
        status  : true, // check login status
        cookie  : true, // enable cookies to allow the server to access the session
        xfbml   : true, // parse XFBML
      version    : 'v5.0' // The Graph API version to use for the call
    });
};

function ValidateEmail(inputText)
{
  var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  if(inputText.match(mailformat))
  {
    return true;
  }
  else
  {
    return false;
  }
}

function sendLoginRequest(id,name,email){
      var social_type = 'facebook';
      var form_Key = window.form_Key;
      var request_data = 'id='+id+'&name='+name+'&email='+email+'&social_type='+social_type+'&form_key='+form_Key;

      var xhttp = window.xhttp;
          xhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {

                  var myResponseText  = this.responseText.split('<script');
                  // var data = JSON.parse(this.responseText);
                  var data = JSON.parse(myResponseText[0]);
                  console.log('#########');
                  console.log(data);
                  console.log('*********');
                  if(data.email_not_exist=='yes'){
                      
                          document.getElementsByClassName('customer_name')[0].value = data.name;
                          document.getElementsByClassName('app_id')[0].value = data.app_id;
                          document.getElementsByClassName('abc')[0].style.display = "block";
                          return false;
                      
                  }else{
                    var url = data.data.redirect;
                    if(url!=''){
                      window.location.href = data.redirect;
                    }
                  }
                  
            }
          };
      xhttp.open("POST", window.request_url, true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send(request_data);
}

function email_hide(){
    document.getElementsByClassName('abc')[0].style.display = "none";
    return false;
}
function submitEmail(){
      if(document.getElementsByClassName('app_id')[0].value==''){
        document.getElementsByClassName('app_id')[0].style.border =  '1px solid #f00';
        return false;  
      }
      if(document.getElementsByClassName('customer_name')[0].value==''){
        document.getElementsByClassName('customer_name')[0].style.border =  '1px solid #f00';  
        return false;  
      }
      if(document.getElementsByClassName('email_id')[0].value==''){
        document.getElementsByClassName('email_id')[0].style.border =  '1px solid #f00';  
        return false;  
      }else{
        if(ValidateEmail(document.getElementsByClassName('email_id')[0].value)){
        }else{
          document.getElementsByClassName('email_id')[0].style.border =  '1px solid #f00';  
          return false;  
        }
      }
      var id = document.getElementsByClassName('app_id')[0].value;
      var name = document.getElementsByClassName('customer_name')[0].value;
      var email = document.getElementsByClassName('email_id')[0].value;
      //alert(id+'='+name+'='+email);
      //return false;
      //send request
      sendLoginRequest(id,name,email);

}


function fb_login(){
    FB.login(function(response) {

        if (response.authResponse) {

            FB.api('/me', 'GET',  {fields: 'email,name,id'},function(response) {
                // if(!response.email){
                //     document.getElementById('customer_name').value = response.name;
                //     document.getElementById('app_id').value = response.id;
                //     document.getElementsByClassName('abc')[0].style.display = "block";
                //     return false;
                // }

                var id = response.id;
                var name = response.name;
                var email = response.email;
                //function here
                sendLoginRequest(id,name,email);

            });

        } else {
            console.log('User cancelled login or did not fully authorize.');
        }
    }, {scope: 'email',return_scopes: true});
}(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
