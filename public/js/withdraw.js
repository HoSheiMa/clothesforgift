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

    let params = new URLSearchParams(location.search);
    let filter = params.get("filter") ? params.get("filter") : "1";
    // 1 = all orders;
    if ($("#withdraws").length) {
        window.a = $("#withdraws").DataTable({
            scrollX: true,
            ajax: "/withdraws/get?filter=" + filter,
            columns: [
                {
                    data: "id",
                },
                {
                    data: "money_needed",
                },
                {
                    data: "type",
                },
                {
                    data: "receiver_details",
                },
                {
                    data: "status",
                    render: (status) => {
                        let text;
                        switch (status) {
                            case "await":
                                text = "معلق";
                                break;
                            case "confirmed":
                                text = "مؤكد";
                                break;
                            case "delivered":
                                text = "تم التسليم ";
                                break;
                            case "cancelled":
                                text = "ملغي ";
                                break;
                        }
                        return text;
                    },
                },
                {
                    data: "receiver_name",
                },
                {
                    data: "created_at",
                    render: (d) => {
                        return new Date(d).toLocaleString();
                    },
                },
                {
                    data: { role: "role", id: "id" },
                    render: function (d) {
                        edit_btn = `

        <button onclick="updateStatus(${d.id})" class="btn btn-light dropdown-toggle show" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <i class=" fas fa-pen  text-info"></i>

        </button>


                `;

                        return `
                    <div class="row">
                       ${edit_btn}
                       <button onclick="remove(${d.id})" class="btn btn-light dropdown-toggle show" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                       <i class=" far fa-trash-alt col text-danger"></i>
                       </button>

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
                accept: "application/json",
                // 'Content-Type': 'application/x-www-form-urlencoded',
            },
            redirect: "follow", // manual, *follow, error
            referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
            body: JSON.stringify(data), // body data type must match "Content-Type" header
        });
        if (response.status === 500 || response.status === 422) {
            Swal.fire("Error", "There problem happened", "error");
            throw Error("لا يوجد بيانات");
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
            Swal.fire("تم التحديث", "تم تحديث البينات بنجاح", "success").then(
                (d) => window.location.reload()
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
    addWithdraw = (btn) => {
        let $form = $("form").serializeArray();
        let data = {};
        for (let i in $form) {
            data[$form[i].name] = $form[i].value;
        }

        postData(`/add/withdraw`, data).then((d) => {
            Swal.fire("تم التحديث", "تم العملية بنجاح", "success").then((d) =>
                window.location.reload()
            );
        });
    };
    updateStatus = async (withdraw_id) => {
        let role = user.role;
        if (role === "admin" || role === "support") {
            var { value: status } = await Swal.fire({
                title: "اختر الحاله الجديدة",
                input: "select",
                inputOptions: {
                    confirmed: "مؤكد",
                    delivered: "تم التسليم ",
                    cancelled: "ملغي ",
                },
                inputPlaceholder: "اختر الحاله الجديدة",
                showCancelButton: true,
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (value) {
                            resolve();
                        } else {
                            resolve("You need to select value :)");
                        }
                    });
                },
            });

            if (status) {
                fetch(`withdraw/${withdraw_id}/status/${status}`).then(() => {
                    Swal.fire(`نجح`, "تم تحديث الحاله.", "success");
                });
            }
        }
    };
    remove = (id) => {
        fetch(`/withdraw/${id}/delete`, {
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
        }).then(() => {
            Swal.fire(`نجح`, "تم المسح .", "success").then((d) => {
                window.location.reload();
            });
        });
    };
};
