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
    if ($("#notif").length) {
        window.a = $("#notif").DataTable({
            scrollX: true,
            ajax: "/notif/get",
            columns: [
                {
                    data: "id",
                },
                {
                    data: "message",
                },
                {
                    data: "type",
                },
                {
                    data: { status: "status" },
                    render: (d) => {
                        let text;
                        switch (d.status) {
                            case 0:
                                text = `<input onchange="updateStatus(this, '${d.id}')" type="checkbox" class="switch-checkbox" id="switch" /><label class="switch-checkbox-label" for="switch">Toggle</label>`;
                                break;
                            case 1:
                                text = `<input onchange="updateStatus(this, '${d.id}')" type="checkbox" class="switch-checkbox" checked id="switch" /><label  class="switch-checkbox-label" for="switch">Toggle</label>`;
                                break;
                        }
                        return text;
                    },
                },
                {
                    data: { id: "id" },
                    render: function (d) {
                        delete_btn = `
                    <i  style="margin-top: 4px;" class=" far fa-trash-alt col text-danger" onclick="removeNotif(this, '${d.id}')"></i>
                    `;

                        return `
                    <div class="row">
                       ${delete_btn}
                    </div>

                    `;
                    },
                },
            ],
            lengthChange: !1,
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

                // 'Content-Type': 'application/x-www-form-urlencoded',
            },
            redirect: "follow", // manual, *follow, error
            referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
            body: JSON.stringify(data), // body data type must match "Content-Type" header
        });
        if (response.status === 500 || response.status === 302) {
            return Swal.fire("Error", "There problem happened", "error");
        }
        return response; // parses JSON response into native JavaScript objects
    }

    removeNotif = (el, id) => {
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
                fetch(`notif/${id}/delete`).then(() => {
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

    addNotif = async (btn) => {
        const { value: formValues } = await Swal.fire({
            title: "اضافة اشعار",
            html:
                `<select id="type"  class="swal2-input">
                    <option value="alert">تنبيه</option>
                    <option value="notice">اشعار</option>
                </select>` +
                `<select id="for"  class="swal2-input">
                    <option value="All">عام</option>
                    <option value="seller">تاجر</option>
                    <option value="admin">ادمن</option>
                    <option value="support">دعم فني</option>
                    <option value="pagesCoordinator">منسق صفحات</option>
                    <option value="Shippingcompany">شركة شحن</option>
                    <option value="marketer">مسوق</option>
                    <option value="leader">ليدر</option>
                </select>` +
                '<textarea id="message" class="swal2-input"></textarea>',
            focusConfirm: false,
            preConfirm: () => {
                return {
                    type: document.getElementById("type").value,
                    for: document.getElementById("for").value,
                    message: document.getElementById("message").value,
                };
            },
        });

        if (formValues.type && formValues.for && formValues.message) {
            postData(`/add/notif`, formValues).then((d) => {
                Swal.fire("تم التحديث", "تمت العملية بنجاح", "success").then(
                    (d) => window.location.reload()
                );
            });
        } else {
            Swal.fire("خطأ", "لازم تكمل الباينات ", "error");
        }
    };
    updateStatus = async (el, notif_id) => {
        let status = el.checked ? 1 : 0;
        let role = user.role;
        if (role === "admin") {
            fetch(`notif/${notif_id}/status/${status}`).then(() => {
                Swal.fire(`نجح`, "تم تحديث الحاله.", "success");
            });
        }
    };
};
