window.onload = async () => {
    console.log("onloaded");
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

    let params = new URLSearchParams(location.search);
    let filter = params.get("filter") ? params.get("filter") : "1";
    // 1 = all orders;
    if ($("#users").length) {
        window.a = $("#users").DataTable({
            scrollX: true,
            ajax: "/users/get?filter=" + filter,
            columns: [
                {
                    data: "id",
                },
                {
                    data: "created_at",
                    render: (d) => {
                        return new Date(d).toLocaleString();
                    },
                },
                {
                    data: "last_login",
                    render: (d) => {
                        return new Date(d).toLocaleString();
                    },
                },
                {
                    data: "name",
                },
                {
                    data: "active_balance",
                },
                {
                    data: "blocked",
                    render: (blocked) => {
                        if (blocked === "true") {
                            return `<span class="badge bg-danger rounded-pill">محظور</span>`;
                        } else {
                            return `<span class="badge bg-success rounded-pill">نشط</span>`;
                        }
                    },
                },
                {
                    data: "leader",
                    render: (d) => (!d ? "" : d.name),
                },
                {
                    data: "role",
                },
                {
                    data: { role: "role", id: "id" },
                    render: function (d) {
                        delete_btn = `
                        <i style="margin-top: 4px;" class=" far fa-trash-alt col text-danger" onclick="removeUser(this, '${d.id}')"></i>
                        `;
                        edit_btn = `
                        <a href="/user/${d.id}/edit" class=col target=_blank>
                        <i class=" fas fa-pen  text-info" )"></i>
                    </a>
                    `;
                        if (window.user.role !== "admin") {
                            delete_btn = "";
                        }
                        message_btn = `
                        <a onclick="sendUserMessage('${d.email}')" class=col target=_blank>
                        <i class="  fas fa-envelope
                        text-warning" )"></i>
                    </a>`;
                        return `
                        <div class="row">
                        ${delete_btn}
                        ${edit_btn}
                        ${message_btn}
                        </div>

                        `;
                    },
                },
            ],
            lengthChange: !1,
            buttons: [
                {
                    extend: "colvis",
                    className: "btn-light",
                },
                {
                    extend: "copy",
                    className: "btn-light",
                },
                {
                    text: "Print",
                    className: "btn-light",
                    titleAttr: "Print",
                    action: function () {
                        var ids = $.map(
                            a.rows(".selected").data(),
                            function (item) {
                                return item.id;
                            }
                        );
                        let height = window.screen.availHeight - 100;
                        let width = window.screen.availWidth - 150;
                        window.open(
                            "/invoice?ids=" + ids,
                            "targetWindow",
                            `toolbar=no,
                        location=no,
                        status=no,
                        menubar=no,
                        scrollbars=yes,
                        resizable=yes,
                        width=${width},
                        height=${height}`
                        );
                    },
                },

                {
                    extend: "pdf",
                    className: "btn-light",
                },
            ],
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-right'>",
                    next: "<i class='mdi mdi-chevron-left'>",
                },
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass(
                    "pagination-rounded"
                );
            },
        });
    }
};
async function postData(url = "", data = {}) {
    // Default options are marked with *
    var response = await fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
            Accept: "application/json",
        },

        body: JSON.stringify(data), // body data type must match "Content-Type" header
    });
    if (response.status === 500) {
        return Swal.fire("Error", "There problem happened", "error");
    }
    if (response.status === 302 || response.status === 422) {
        data = Object.keys((await response.json()).errors);
        let _trans = {
            name: "الاسم",
            email: "البريد الاكتروني",
            phone: "رقم الهاتف",
            password: "كلمة السر",
        };
        data = data.map((v, i) => (_trans[i] = _trans[v]));
        return await Swal.fire(
            "Error",
            "بيانات غير مكتملة  : " + data,
            "error"
        );
    }
    return response; // parses JSON response into native JavaScript objects
}

window.updateUser = () => {
    let $form = $("form").serializeArray();
    let data = {};
    for (let i in $form) {
        data[$form[i].name] = $form[i].value;
    }

    postData(`/user/${data.id}/update`, data).then((d) => {
        Swal.fire("تم التحديث", "تم تحديث البينات بنجاح", "success").then((d) =>
            window.location.reload()
        );
    });
};
removeUser = (el, id) => {
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
            fetch(`user/${id}/delete`).then(() => {
                $(el).parent().parent().parent().remove();
                Swal.fire({
                    icon: "success",
                    title: "Product is deleted",
                    showConfirmButton: false,
                    timer: 1500,
                });
            });
        }
    });
};
sendUserMessage = async (user_id) => {
    let role = user.role;
    $to_input =
        role == "admin" || role == "support"
            ? `<input value="${user_id}" disabled id="to" placeholder="الي" class="form-control" required> <br/>`
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

    if (formValues) {
        postData(`chat/create`, formValues).then((data) => {
            Swal.fire(
                "Sended!",
                "Your Messages has been sended.",
                "success"
            ).then(() => {
                openChat(focus_chat, null);
            });
        });
    }
};
addNewUser = async (btn) => {
    let $form = $("form").serializeArray();
    let data = {};
    for (let i in $form) {
        data[$form[i].name] = $form[i].value;
    }

    let r = await postData(`/add/user`, data);
    if (r.status == 200) {
        Swal.fire("تم التحديث", "تم العملية بنجاح", "success").then((d) =>
            window.location.reload()
        );
    }
};
