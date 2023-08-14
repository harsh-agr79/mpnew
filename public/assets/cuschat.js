$(document).ready(function() {
    seenup($('#userid').text(), $('#channel').text())
    // chatlist()
    var msgSection = document.querySelector("#userchatbox");
    msgSection.scrollTo(0, msgSection.scrollHeight);
});

function getDateTime(date){
    var date3 = new Date(date*1000).toLocaleString({ timeZone: 'Asia/Kathmandu'});
    var date2 = new Date(date3);
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var d = date2.getDate()+"-"+months[date2.getMonth()]+" "+date2.getHours()+":"+date2.getMinutes();
    return d;
}
$(function(){
    let ip_address = 'socket.startuplair.com';
    // let socket_port = '3000';
    let socket = io(ip_address);
    let type = ['admin', 'staff', 'marketer']

    socket.on("sendMsgToClient", (message) => {
        // console.log(message);
        if (message[0].sid == $('#userid').text() && message[0].channel == $('#channel').text() && type.indexOf(message[0].sendtype) > -1){
            // chatlist();
            var d = getDateTime(message[0].created_at);
            if(message[0].msgtype == 'text'){
                $('#userchatbox').append(`
                <div class="col s12" id="${message[0].id}">\
                        <div class="user-message-div user-message-left">\
                            <span style="font-size: 12px;">\
                              ${message[0].message} \
                            </span><br>\
                            <span style="font-size: 7px">
                            <span class="left">${message[0].sentname}</span>
                            <span class="right">${d}</span>\
                        </div>\
                    </div>\
                `)
            }
            else{
                $("#userchatbox").append(`
                <div class="col s12" id="${message[0].id}" style="margin:0, padding: 5px;">
                            <div class="left">
                                <img src="/${message[0].image}" class="materialboxed" height="150" alt="">
                                <div class="user-img-msg-bg" style="font-size: 7px; padding-bottom: 10px; margin: 0; width: 100%">
                                    <span class="left">${message[0].sentname}</span><span class="right">${d}</span>
                                </div>
                            </div>
                        </div>
            `);
            $('.materialboxed').materialbox();
            }
            
            seenup(message[0].sid, message[0].channel);
            $('#seenbox').remove();
            $(`#${message[0].id}`).after(`
            <div class="col s12" id="seenbox">\
                <div class="right">\
                    <span style="font-size: 10px; margin-right: 20px;">seen</span>\
                </div>\
            </div>\
            `);
            // changeseenbar(message[0].id,message[0].sid,message[0].channel)
            var msgSection = document.querySelector("#userchatbox");
            msgSection.scrollTo(0, msgSection.scrollHeight);
        }
           
    });

    socket.on("adminSeenToUser", (message) => { 
        // console.log($('#channel').text());
        // console.log(message);
       if(message.channel == $('#channel').text() && message.id == $('#userid').text()){
        $('#seenbox').remove();
        if (message.chatidad > message.chatidus) {
            $(`#${message.chatidad}`).after(`
                <div class="col s12" id="seenbox">\
                    <div class="right">\
                        <span style="font-size: 10px; margin-right: 20px;">seen</span>\
                    </div>\
                </div>\
                `);
        } else {
            $(`#${message.chatidus}`).after(`
                <div class="col s12" id="seenbox">\
                    <div class="right">\
                        <span style="font-size: 10px; margin-right: 20px;">seen</span>\
                    </div>\
                </div>\
                `);
        }
        var msgSection = document.querySelector("#userchatbox");
        msgSection.scrollTo(0, msgSection.scrollHeight);
       }
    })
});
$('#sendmessage').on('submit', function(e) {
    e.preventDefault();
    let ip_address = 'socket.startuplair.com';
    // let socket_port = '3000';
    let socket = io(ip_address);
    let formData = new FormData($('#sendmessage')[0]);
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: "/addmsguser",
        data: formData,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(response) {
            // console.log(response)
            socket.emit("sendMsgToServer", response);
            // chatlist()
            var d = getDateTime(response[0].created_at);
            if(response[0].msgtype == 'text'){
                $('#userchatbox').append(`
                <div class="col s12" id="${response[0].id}">\
                    <div class="user-message-div user-message-right">\
                        <span style="font-size: 12px;">\
                          ${response[0].message} \
                        </span><br>\
                        <span class="right" style="font-size: 7px">${d}</span>\
                    </div>\
                </div>\
            `)
            }
            else{
                $("#userchatbox").append(`
                <div class="col s12" id="${response[0].id}" style="margin:0, padding: 0;">
                            <div class="right bg-content">
                                <img src="/${response[0].image}" class="materialboxed" height="150" alt="">
                                <div style="font-size: 7px; padding: 3px; margin: 0; width: 100%">
                                    <span class="right">${d}</span>
                                </div>
                            </div>
                        </div>
            `);
            $('.materialboxed').materialbox();
            }
           
            var msgSection = document.querySelector("#userchatbox");
            msgSection.scrollTo(0, msgSection.scrollHeight);
        },
    });
    $("#msgval").val("");
})

function seenup(id, channel) {
    if($('#channel').text() ===  channel && id === $('#userid').text()){
        let ip_address = 'socket.startuplair.com';
        // let socket_port = '3000';
        let socket = io(ip_address);
        $.ajax({
            type: 'get',
            url: '/user/chat/seenupdate/'+id+'/'+channel,
            success: function(response) {
                // chatlist()
                socket.emit("userSeenToServer", response);
            }
        })
    }
}

function changeseenbar(id,sid, channel){
    if($('#channel').text() ===  channel && sid === $('#userid').text()){
    $('#seenbox').remove();
    $(`#${id}`).after(`
    <div class="col s12" id="seenbox">\
        <div class="right">\
            <span style="font-size: 10px; margin-right: 20px;">seen</span>\
        </div>\
    </div>\
    `);
    }
}

function chatlist(){
    console.log('chatlist')
    $.ajax({
        type: 'get',
        url: '/user/getchatlist',
        success: function(response) {
            // console.log(response);
        }
    })
}