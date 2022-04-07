window.onload = async () => {
    let user = await fetch("getUser", {
        headers: {
            accept: "application/json",
        },
    });
    user = await user.json();
    window.user = user;
    if (user.blocked == "true") {
        window.location.assign("/blocked");
    }

    openChat = (id, username, page = 1, more = false, order_id) => {
        window.focus_chat = id;
        $(".loading").removeClass("d-none");
        $("#chatBox").addClass("d-none");
        fetch(`chat/${id}/?page=${page}`, {
            mode: "cors", // no-cors, *cors, same-origin
            cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
            credentials: "same-origin", // include, *same-origin, omit
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
                // 'Content-Type': 'application/x-www-form-urlencoded',
            },
        })
            .then((d) => (d.status === 200 ? d.json() : []))
            .then((d) => {
                $(".loading").addClass("d-none");

                let messages = d.data;
                let messages_html = ``;
                if (d.current_page < d.last_page) {
                    messages_html += `
                    <li class="text-center">
                    <a class="btn btn-info" onclick="openChat('${id}', '${username}', '${
                        d.current_page + 1
                    }', true);$(this).remove()">المزيد</a>
                    </li>

                    `;
                }
                for (let i in messages) {
                    let odd = "";
                    let message = messages[i];
                    if (Number(message.from) !== Number(user.id)) odd = "odd";
                    messages_html += `
                    <li class="clearfix ${odd}">
                    <div class="chat-avatar">
                        <img src="/assets/images/users/user1.png" class="rounded" alt="Geneva M" />
                        <i>${new Date(message.created_at).toLocaleString()}</i>
                    </div>
                    <div class="conversation-text">
                        <div class="ctext-wrap">
                            <i>${message.name}</i>
                            <p style=" white-space: pre-wrap;">
                            ${message.message}
                            </p>
                        </div>
                    </div>
                    <div class="conversation-actions dropdown">
                        <button class="btn btn-sm btn-link" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical font-16"></i></button>

                        <div class="dropdown-menu">
                            <a onclick="deleteMsg('${
                                message.id
                            }')" class="dropdown-item" href="#">Delete</a>
                        </div>
                    </div>
                </li>



                `;
                }
                order_html = order_id
                    ? `
        <li class="text-center">
        <a class="btn btn-info" onclick="openChat('${id}', '${username}', '${
                          d.current_page + 1
                      }', true);$(this).remove()">هذه  المحادثة حول الطلب رقم ${order_id}</a>
        </li>

        `
                    : "";
                if (more) {
                    $("#chatBox")
                        .find(".simplebar-content")
                        .html(
                            messages_html +
                                $("#chatBox").find(".simplebar-content").html()
                        );
                } else {
                    $("#chatBox")
                        .find(".simplebar-content")
                        .html(messages_html + order_html);
                }
                $("#chatBox").removeClass("d-none");
                if (username) {
                    $(".user-name").text(username);
                }
                setTimeout(
                    () =>
                        $($(".simplebar-content-wrapper")[1]).scrollTop(
                            $($(".simplebar-content-wrapper")[1]).height()
                        ),
                    0
                );
            });
    };
    async function postData(url = "", data = {}) {
        // Default options are marked with *
        const response = await fetch(url, {
            method: "POST", // *GET, POST, PUT, DELETE, etc.
            mode: "cors", // no-cors, *cors, same-origin
            cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
            credentials: "same-origin", // include, *same-origin, omit
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),

                // 'Content-Type': 'application/x-www-form-urlencoded',
            },
            redirect: "follow", // manual, *follow, error
            referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
            body: JSON.stringify(data), // body data type must match "Content-Type" header
        });
        if (response.status === 500) {
            return Swal.fire("Error", "There problem happened", "error");
        }
        return response; // parses JSON response into native JavaScript objects
    }

    send = () => {
        let msg = $("#msg").val();
        let focus_chat = window.focus_chat;
        if (msg && focus_chat) {
            postData(`chat/${focus_chat}/message/create`, {
                message: msg,
            }).then((data) => {
                openChat(focus_chat, null);
            });
        }
    };
    deleteMsg = (id) => {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                postData(`message/${id}/delete`, {
                    message: msg,
                }).then((data) => {
                    Swal.fire(
                        "Deleted!",
                        "Your file has been deleted.",
                        "success"
                    ).then(() => {
                        openChat(focus_chat, null);
                    });
                });
            }
        });
    };
    deleteChat = () => {
        let focus_chat = window.focus_chat;
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                postData(`chat/${focus_chat}/delete`, {
                    message: msg,
                }).then((data) => {
                    Swal.fire(
                        "Deleted!",
                        "Your file has been deleted.",
                        "success"
                    ).then(() => {
                        openChat(focus_chat, null);
                    });
                });
            }
        });
    };
    MessageTo = async (el) => {
        let role = user.role;
        $to_input =
            role == "admin" || role == "support"
                ? '<input id="to" placeholder="الي" class="form-control" required> <br/>'
                : "";
        const { value: formValues } = await Swal.fire({
            title: "ارسال رسالة دعم",
            html:
                $to_input +
                '<textarea placeholder="الرساله" id="message" class="form-control" required></textarea>',
            focusConfirm: false,
            preConfirm: () => {
                return {
                    to: document.getElementById("to")?.value,
                    message: document.getElementById("message").value,
                };
            },
        });

        if (formValues && formValues.message.length > 0) {
            postData(`chat/create`, formValues).then((data) => {
                Swal.fire(
                    "Sended!",
                    "Your Messages has been sended.",
                    "success"
                ).then(() => {
                    openChat(focus_chat, null);
                });
            });
        } else {
            return Swal.fire("خطأ", "خطأ في البيانات", "error");
        }
    };
};
