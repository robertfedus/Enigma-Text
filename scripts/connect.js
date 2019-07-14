$(document).ready(function(){

    var conn = new WebSocket('ws://localhost:8080');

    conn.onopen = (e) => {

    let username = 'connecting...';
    let msg = 'connecting...';

    let data = {
    username: username,
    msg: msg
    };        
    conn.send(JSON.stringify(data));
        //document.getElementById('send').click();
        console.log("Connection established!");
    };

    //$('#send').click();

    conn.onmessage = (e) => {

        console.log(e.data);
        let data = JSON.parse(e.data);
        let chat = $('#chat').val();
        //document.getElementById('chat').value;
        console.log(chat);

        //if(data.username.toLowerCase() == chat.toLowerCase() /*|| data.username == 'You'*/){
            //if(data.username == 'You')
                //var row = '<tr><td align="right"><div style="width: 50%; text-align: right;"><div style="padding: 3px; font-family: Verdana; border-bottom: 3px solid green; background-color: black; display: inline-block; font-size: 18px; border-radius: 1em; text-align: center; margin-right: 0.2em;"><p style="margin-left: 1em; margin-right: 0.35em;">' + data.msg + '</p></div></div>';
            //else
                var row = '<tr><td align="right"><div style="width: 50%; text-align: right;"><div style="padding: 3px; font-family: Verdana; border-bottom: 3px solid green; background-color: black; display: inline-block; font-size: 18px; border-radius: 1em; text-align: center; margin-right: 0.2em;"><p style="margin-left: 1em; margin-right: 0.35em;">' + data.msg + '</p></div></div>';
                $('#chat > tbody').append(row);
        //}

        var objDiv = document.getElementById('message-div');
        objDiv.scrollTop = objDiv.scrollHeight;
         

    };

    $('#send').click(() => {
        let username = $('#username').val();
        let msg = $('#msg').val();
        //let chat = $('#chat').val();
        let data = {
            username: username,
            msg: msg
            //chat: chat
        };        
        conn.send(JSON.stringify(data));
        //$('#msg').val('');
  
    })

})