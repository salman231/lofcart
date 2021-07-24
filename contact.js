$(document).ready(function(){
  $('.btn').click(function(event){

    console.log('Clicked button')

    var name = $('.name').val()
    var email = $('.email').val()
    var phone = $('.phone').val()
    var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
    var message = $('.message').val()
    var  statusElm = $('.status')
    statusElm.empty()

    if(email.length > 5 && email.includes('@') && email.includes('.')){
      statusElm.append('<div>Email is valid</div>')
    }else{
        event.preventDefault()
      statusElm.append('<div>Email is not valid</div>')
    }

    if(name.length >= 3 ){
      statusElm.append('<div>Name is valid</div>')
    }else{
        event.preventDefault()
      statusElm.append('<div>Name is not valid</div>')
    }

    if(phone.match(phoneno)){
      statusElm.append('<div>Phone no. is valid</div>')
    }else{
        event.preventDefault()
      statusElm.append('<div>Phone no. is not valid</div>')
    }

    if(message.length > 20 ){
      statusElm.append('<div>Message length is valid</div>')
    }else{
        event.preventDefault()
      statusElm.append('<div>Message length should be atleast 20 characters</div>')
    }
  })
})
