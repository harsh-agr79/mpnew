$(document).ready(function () {
    $(".materialboxed").materialbox();
    seenup($("#userid").text(), $("#channel").text());
    var msgSection = document.querySelector("#chatboxmsgdiv");
    msgSection.scrollTo(0, msgSection.scrollHeight);
});

function getDateTime(date) {
    var date = new Date(date * 1000).toLocaleString({
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
    let ip_address = "192.168.1.208";
    let socket_port = "3000";
    let socket = io(ip_address + ":" + socket_port);
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
            var msgSection = document.querySelector("#chatboxmsgdiv");
            msgSection.scrollTo(0, msgSection.scrollHeight);
        },
    });
    $("#msgval").val("");
    $("#imginp").val("");
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
                if (item.seen != "seen" && item.sendtype == "user") {
                    cls = "bold";
                    txt = "";
                    setg = "hide";
                    dep = 'z-depth-1';
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
                $("#chatlist").append(` \
                <a href="/chats/${item.sid}/${item.channel}" class="row valign-wrapper chat-list-item textcol ${dep} ${act}">\
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
                            </div>\
                        </div>\
                    </a>\
                    `);
            });
        },
    });
}

$(function () {
    let ip_address = "192.168.1.208";
    let socket_port = "3000";
    let socket = io(ip_address + ":" + socket_port);

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
            var msgSection = document.querySelector("#chatboxmsgdiv");
            msgSection.scrollTo(0, msgSection.scrollHeight);
        } else {
            chatlist($("#userid").text());
        }
    });
    socket.on("userSeenToAdmin", (message) => {
        seenupdate(message);
        chatlist($("#userid").text())
    });
});

function seenupdate(message) {
    // console.log(message);
    if (
        message.id == $("#userid").text() &&
        message.channel == $("#channel").text()
    ) {
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
        let ip_address = "192.168.1.208";
        let socket_port = "3000";
        let socket = io(ip_address + ":" + socket_port);
        $.ajax({
            type: "get",
            url: "/admin/chat/seenupdate/" + id + "/" + channel,
            success: function (response) {
                console.log(response);
                socket.emit("adminSeenToServer", response);
                chatlist(id);
                seenupdate(response);
            },
        });
    }
}
