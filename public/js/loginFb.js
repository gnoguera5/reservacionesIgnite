function login(userIDFb,name,status,email) {
    $.ajax({
        type:'GET',
        url:'/loginfb/'+userIDFb+'/'+name+'/'+status+'/'+email,
        dataType: "json",
        success:function(data){
            //console.log('aqui');
            window.location.href = "/home";
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
    
        }
    });
}