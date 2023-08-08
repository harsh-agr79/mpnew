$(document).ready(function () {
    var msgSection = document.querySelector("#chatboxmsgdiv");
    msgSection.scrollTo(0, msgSection.scrollHeight);
});

$("#message-inp").on("submit", (e) => {
    // console.log('Hello');
    e.preventDefault();
    let ip_address = "192.168.1.208";
    let socket_port = "3000";
    let socket = io(ip_address+":"+socket_port);
    // let message = $('#msgval').val();
    // console.log(chatInput);
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: "/addmsgadmin",
        data: $("#message-inp").serialize(),
        type: "post",
        success: function (response) {
            // console.log(response)
            socket.emit("sendMsgToServer", response);
            $("#chatboxmsgdiv").append(
                ' <div class="col s12" style="margin:0, padding: 0;">\
                    <div class="chat-message message-right right">' +
                    response[0].message +
                    '<br> <span style="font-size: 7px; padding: 0; margin: 0;">' +
                    response[0].sentname +
                    "</span></div>\
                    <div>");
            chatlist(response[0].sid);
            var msgSection = document.querySelector("#chatboxmsgdiv");
            msgSection.scrollTo(0, msgSection.scrollHeight);
        },
    });
    $("#msgval").val("");
});

function chatlist(id) {
    $.ajax({
        type: "get",
        url: "/getchatlist",
        success: function (response) {
            // console.log(response);
            $("#chatlist").html("");
            $.each(response, function (key, item) {
                if (item.profileimg == null) {
                    img = "user.jpg";
                } else {
                    img = item.profileimg;
                }
                if (id == item.sid) {
                    act = "chat-active";
                } else {
                    act = "";
                }
                $("#chatlist").append(` \
                <a href="/chats/${item.sid}/${item.channel}" class="row valign-wrapper chat-list-item textcol ${act}">\
                        <div class="col s3">\
                            <img src="/${img}" class="chat-list-img" alt="">\
                        </div>\
                        <div class="col s9">\
                            <span class="chat-list-username">${item.name}</span><span class="chat-list-channel">${item.channel}</span><br>\
                            <span class="chat-list-message">${item.message}</span>\
                        </div>\
                    </a>\
                    `);
            });
        },
    });
}



function editchannel(id) {
    $.ajax({
        type: "get",
        url: "/getchannel/" + id,
        success: function (response) {
            console.log(response);
            $("#chid").val(response.id);
            $("#chname").val(response.name);
            $("#chsname").val(response.shortname);
            $("#chcolor").val(response.color);
            if (response.adminonly == "on") {
                $("#chaonly").attr("checked", "true");
            }
        },
    });
}
