$(document).ready(function () {
    $(".materialboxed").materialbox();
    seenup($("#userid").text(), $("#channel").text());
    updatemsgcnt()
    var msgSection = document.querySelector("#chatboxmsgdiv");
    msgSection.scrollTo(0, msgSection.scrollHeight);
});

function getDateTime(date) {
    var date = new Date(date * 1000).toString({
        timeZone: "Asia/Kathmandu",
    });
    var date2 = new Date(date);
    const months = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
    ];
    var d =
        date2.getDate() +
        "-" +
        months[date2.getMonth()] +
        " " +
        date2.getHours() +
        ":" +
        date2.getMinutes();
    return d;
}

$("#message-inp").on("submit", (e) => {
    e.preventDefault();
    // let ip_address = "192.168.1.208";
    // let socket_port = "3000";
    // let socket = io(ip_address + ":" + socket_port);
    let ip_address = 'socket.startuplair.com';
    // let socket_port = '3000';
    let socket = io(ip_address);
    let formData = new FormData($("#message-inp")[0]);
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: "/addmsgadmin",
        data: formData,
        contentType: false,
        processData: false,
        type: "POST",
        success: function (response) {
            // console.log(response)
            socket.emit("sendMsgToServer", response);
            var d = getDateTime(response[0].created_at);
            if (response[0].msgtype == "text") {
                $("#chatboxmsgdiv").append(
                    ' <div class="col s12" id="' +
                        response[0].id +
                        '" style="margin:0, padding: 0;">\
                        <div class="chat-message message-right right">' +
                        response[0].message +
                        '<br> <span style="font-size: 7px; padding: 0; margin: 0;"><span class="left">' +
                        response[0].sentname +
                        "</span>\
                        <span class='right'>" +
                        d +
                        "</span></span></div>\
                        <div>"
                );
            } else {
                $("#chatboxmsgdiv").append(`
                    <div class="col s12" id="${response[0].id}" style="margin: 5px 0; padding: 0;">
                                <div class="right bg-content">
                                    <img src="/${response[0].image}" class="materialboxed" height="150" alt="">
                                    <div style="font-size: 7px; padding: 3px; margin: 0; width: 100%">
                                                    <span class="left">${response[0].sentname}</span>  <span class="right">${d}</span>
                                                </div>
                                </div>
                            </div>
                `);
                $(".materialboxed").materialbox();
            }

            chatlist(response[0].sid);
            channelList(response[0].sid);
            updatemsgcnt()
            var msgSection = document.querySelector("#chatboxmsgdiv");
            msgSection.scrollTo(0, msgSection.scrollHeight);
        },
    });
    $("#msgval").val("");
    $("#msgval").focus();
    $("#imginp").val("");
});

function chatlist(id) {
    
    $.ajax({
        type: "get",
        url: "/getchatlist",
        success: function (response) {
            // console.log(response);
            $("#chatlist").html("");
            $("#chatlist2").html("");
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
                if (item.seen != "seen" && item.sendtype == "user") {
                    cls = "bold";
                    txt = "";
                    setg = "hide";
                    dep = 'chat-unseen';
                } else if (item.seen == "seen" && item.sendtype == "user") {
                    cls = "";
                    txt = "";
                    setg = "hide";
                    dep = ''
                } else {
                    if (item.seen != "seen") {
                        cls = "";
                        txt = "You:";
                        setg = "hide";
                        dep = ''
                    } else {
                        cls = "";
                        txt = "You:";
                        setg = "";
                        dep = ''
                    }
                }
                if(item.unseen > 0 && id==item.sid){
                    dep = '';
                    act = 'chat-active'
                }
                else if(item.unseen > 0 && id!=item.sid){
                    dep = 'chat-unseen';
                    act = ''
                }
                $("#chatlist").append(` \
                <a href="/chats/${item.sid}/${item.channel}" class="row valign-wrapper chat-list-item textcol ${dep} ${act}">\
                        <div class="col s3 center">\
                            <img src="/${img}" class="chat-list-img">\
                        </div>\
                        <div class="col s9">\
                            <div class="left-align">
                                <span class="chat-list-username ${cls}">${item.name}</span>\
                                <span class="chat-list-channel ${cls}">${item.channel}</span>\
                            </div>\
                            <div>
                                <div class="chat-list-message left ${cls}">${txt}${item.message}</div>
                                <div class="right ${setg}" style = "margin-right: 10px;">
                                        <img src="/${img}" height="15"
                                                    style="border-radius: 50%" alt="">
                                </div>
                                ${item.unseen > 0 ? `<div class="right" style="margin-right: 10px;">\
                                        <div class="red white-text" style="width:22px; height: 22px; font-size: 15px; border-radius: 50%; display:flex; align-items: center; justify-content: center;"><div>${item.unseen}</div></div>\
                                    </div>` : ``}
                            </div>\
                        </div>\
                    </a>\
                    `);
                    $("#chatlist2").append(` \
                    <a href="/admin/m/chats/${item.sid}/${item.channel}" class="row valign-wrapper chat-list-item textcol ${dep} ${act}">\
                            <div class="col s3">\
                                <img src="/${img}" class="chat-list-img">\
                            </div>\
                            <div class="col s9">\
                                <div class="left-align">
                                    <span class="chat-list-username ${cls}">${item.name}</span>\
                                    <span class="chat-list-channel ${cls}">${item.channel}</span>\
                                </div>\
                                <div>
                                    <div class="chat-list-message left ${cls}">${txt}${item.message}</div>
                                    <div class="right ${setg}" style = "margin-right: 10px;">
                                            <img src="/${img}" height="15"
                                                        style="border-radius: 50%" alt="">
                                    </div>
                                    ${item.unseen > 0 ? `<div class="right" style="margin-right: 10px;">\
                                            <div class="red white-text" style="width:22px; height: 22px; font-size: 15px; border-radius: 50%; display:flex; align-items: center; justify-content: center;"><div>${item.unseen}</div></div>\
                                        </div>` : ``}
                                </div>\
                            </div>\
                        </a>\
                        `);
            });
        },
    });
}

$(function () {
    let ip_address = 'socket.startuplair.com';
    // let socket_port = '3000';
    let socket = io(ip_address);

    socket.on("sendMsgToClient", (message) => {
        // console.log(message);
        if (
            message[0].sendtype == "user" &&
            message[0].sid == $("#userid").text() &&
            message[0].channel == $("#channel").text()
        ) {
            var d = getDateTime(message[0].created_at);
            if (message[0].msgtype == "text") {
                $("#chatboxmsgdiv").append(`\
                <div class="col s12" id="${message[0].id}" style="margin:0, padding: 0;">\
                    <div class="chat-message message-left left">\
                        ${message[0].message}<br>\
                        <span style="font-size: 7px; padding: 0; margin: 0;">
                        <span class="left">${d}</span>
                    </span>
                    </div>\
                </div>\
            `);
            } else {
                $("#chatboxmsgdiv").append(`
                    <div class="col s12" id="${message[0].id}" style="margin: 5px 0; padding: 0;">
                                <div class="left">
                                    <img src="/${message[0].image}" class="materialboxed" height="150" alt="">
                                    <div class="user-img-msg-bg" style="font-size: 7px; padding-bottom: 10px; width: 100%;">
                                    <span class="left">${d}</span>
                                </div>
                                </div>
                            </div>
                `);
                $(".materialboxed").materialbox();
            }

            seenup(message[0].sid, message[0].channel);
            updatemsgcnt()
            var userimg = $("#userimg").text();
            $("#seenbox").remove();
            if (userimg === "") {
                var img = `user.jpg`;
            } else {
                var img = userimg;
            }
            $(`#${message[0].id}`).after(`
        <div class="col s12" id="seenbox" style="margin:0, padding: 0;">\
                <div class="right">\
                        <img src="/${img}" height="20" style="border-radius: 50%"\
                </div>\
            </div>\
        `);
            chatlist(message[0].sid);
            channelList(message[0].sid);
            var msgSection = document.querySelector("#chatboxmsgdiv");
            msgSection.scrollTo(0, msgSection.scrollHeight);
        } else {
            chatlist($("#userid").text());
            channelList($("#userid").text());
        }
    });
    socket.on("userSeenToAdmin", (message) => {
        seenupdate(message);
        chatlist($("#userid").text())
        channelList($("#userid").text())
        updatemsgcnt()
    });
});

function seenupdate(message) {
    // console.log(message);
    if (
        message.id == $("#userid").text() &&
        message.channel == $("#channel").text()
    ) {
        updatemsgcnt()
        var userimg = $("#userimg").text();
        $("#seenbox").remove();
        if (userimg === "") {
            var img = `user.jpg`;
        } else {
            var img = userimg;
        }
        if (message.chatidad > message.chatidus) {
            $(`#${message.chatidad}`).after(`
            <div class="col s12" id="seenbox" style="margin:0, padding: 0;">\
                <div class="right">\
                        <img src="/${img}" height="20" style="border-radius: 50%"\
                </div>\
            </div>\
            `);
        } else if (message.chatidad < message.chatidus) {
            $(`#${message.chatidus}`).after(`
                 <div class="col s12" id="seenbox" style="margin:0, padding: 0;">\
                <div class="right">\
                        <img src="/${img}" height="20" style="border-radius: 50%"\
                </div>\
            </div>\
            `);
        } else if (message.chatidad < message.chatidus) {
            $("#seenbox").remove();
        }
    }
}

function editchannel(id) {
    $.ajax({
        type: "get",
        url: "/getchannel/" + id,
        success: function (response) {
            // console.log(response);
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

function seenup(id, channel) {
    // console.log(channel);
    if ($("#channel").text() == channel && id == $("#userid").text()) {
        let ip_address = 'socket.startuplair.com';
        // let socket_port = '3000';
        let socket = io(ip_address);
        $.ajax({
            type: "get",
            url: "/admin/chat/seenupdate/" + id + "/" + channel,
            success: function (response) {
                // console.log(response);
                socket.emit("adminSeenToServer", response);
                chatlist(id);
                channelList(id);
                seenupdate(response);
                updatemsgcnt()
            },
        });
    }
}

function channelList(id) {
    if (id == $("#userid").text()) {
        $.ajax({
            type: "get",
            url: "/admin/chat/getchannels/"+id,
            success: function (response) {
                // console.log(response);
                updatemsgcnt()
                $('#channel-list-div').html("");
                $('#chanlist').html("");
                a = 0;
                $.each(response, function (key, item) {
                    $('#channel-list-div').append(`  <div class="col s12 chat-box-channel">
                    <a class="channel-item textcol"
                        href="/chats/${id}/${item.shortname}"
                        style="background: ${item.color}">${item.channel}
                        ${item.unseen > 0 ? `<span class="red white-text center" style="margin-left: 15px; padding: 5px; border-radius: 20px; font-size: 10px;">${item.unseen}</span>` : ``}
                    </a>
                </div>`);
                $('#chanlist').append(`
                    <li style="background: ${item.color}"><a href="/admin/m/chats/${id}/${item.shortname}">${item.channel} ${item.unseen > 0? `<span class="red" style="font-size: 12px; padding: 3px 4px; border-radius: 20px;">${item.unseen}</span>`: ``}</a></li>
                `)
                a  = a + item.unseen;
                })
                $('#chanunmsg').text(a);
            },
        });
    }
}

function updatemsgcnt(){
    $.ajax({
            url: "/admin/msgcnt",
            type: 'get',
            success: function(response) {
                $('#msgcnt').text(response)
                $('#msgcnt2').text(response)
            }
        })
}
