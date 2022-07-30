
window.onload = async () => {
    $.fn.dataTable.ext.errMode = 'none';

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

    window.a = $("#orders").DataTable({
        scrollX: true,
        ajax: "/api/orders?filter=" + filter,
        select: {
            style: "os",
            selector: "td:first-child",
            style: "multi",
        },
        columnDefs: [
            {
                orderable: false,
                className: "select-checkbox",
                targets: 0,
            },
        ],
        columns: [
            {
                data: null,
                defaultContent: "",
            },
            {
                data: {},
                render: (d) => {
                    let icon =
                        d.updated_by == user.id
                            ? ""
                            : `<span class="badge badge-outline-warning">🔥</span> `;
                    return icon + " " + d.id;
                },
            },
            {
                data: {},
                render: (data) => {
                    return `<a href="/profile/${data.created_id}" target="_blank">${data.created_by}</a>`;
                },
            },
            {
                data: "name",
            },
            {
                data: "phone",
                render: (phone) => {
                    return JSON.parse(phone).join(", ");
                },
            },
            {
                data: "Shipping_to",
            },
            {
                data: "address",
                render: (address) => {
                    return JSON.parse(address).join(", ");
                },
            },

            {
                data: {},
                render: (d) => {
                    return d.total - d.discount;
                },
            },
            {
                data: {},
                render: (d) => {
                    return d.totalWithoutShipping - d.discount;
                },
            },
            {
                data: { status: "status", id: "id" },
                render: function (d) {
                    // new
                    // pending
                    // confirmed
                    // prepared
                    // delivery
                    // delivered
                    // cancelled
                    let color = "bg-soft-info text-info";
                    let text = "جديد";
                    switch (d.status) {
                        case "new":
                            color = "bg-soft-info text-info";
                            text = "جديد";
                            break;
                        case "pending":
                            color = "bg-soft-warning text-danger";
                            text = "معلق ";
                            break;
                        case "confirmed":
                            color = "bg-soft-success text-success";
                            text = "مؤكد ";
                            break;
                        case "delay":
                            color = "bg-soft-success text-success";
                            text = "مؤجل ";
                            break;
                        case "prepared":
                            color = "bg-soft-info text-info";
                            text = "تم التجهيز ";
                            break;
                        case "delivery":
                            color = "bg-soft-danger text-danger";
                            text = "قيد التوصيل ";
                            break;
                        case "delivered":
                            color = "bg-soft-danger text-danger";
                            text = "تم التسليم ";
                            break;
                        case "cancelled":
                            color = "bg-soft-danger text-danger";
                            text = "ملغي ";
                            break;
                    }
                    return `<h5 style="cursor: pointer;" onclick="updateStatus(this, '${d.id}', '${d.status}', '${d.role}',null, '${d.created_id}')"><span class="badge ${color}"> ${text}</span></h5>`;
                },
            },
            {
                data: "Shipping_company",
                render: (company) => {
                    if (company) {
                        return company;
                    } else {
                        return "لا يوجد";
                    }
                },
            },
            {
                data: { status: "status", id: "id" },
                render: function (d) {
                    let color = "bg-soft-info text-info";
                    let text = "جديد";
                    switch (d.Shipping_status) {
                        case "awaiting":
                            color = "bg-soft-warning text-warning";
                            text = "لا يوجد شركة شحن";
                            break;
                        case "delivery":
                            color = "bg-soft-warning text-warning";
                            text = "قيد التوصيل ";
                            break;
                        case "delivered":
                            color = "bg-soft-success text-success";
                            text = "تم التسليم ";
                            break;
                        case "Partially delivered":
                            color = "bg-soft-warning text-warning";
                            text = "مسلم جزئي ";
                            break;
                        case "Refused to receive":
                            color = "bg-soft-danger text-danger";
                            text = "رفض الاستلام  ";
                            break;
                        case "Delayed":
                            color = "bg-soft-warning text-warning";
                            text = "مؤجل  ";
                            break;
                        case "returned product":
                            color = "bg-soft-danger text-danger";
                            text = "مرتجع  ";
                            break;
                    }
                    return `<h5 style="cursor: pointer;" onclick="updateShippingStatus(this, '${d.id}', '${d.status}', '${d.role}')"><span class="badge ${color}"> ${text}</span></h5>`;
                },
            },
            {
                data: {},
                render: function (data) {
                    return (
                        new Date(data.updated_at).toLocaleString() +
                        "<br/ >" +
                        data.updated_by_name
                    );
                },
            },
            {
                data: "created_at",
                render: function (d) {
                    return new Date(d).toLocaleString();
                },
            },
            {
                data: { role: "role", id: "id" },
                render: function (d) {
                    delete_btn = `
                    <i style="margin-top: 4px;" class=" far fa-trash-alt col text-danger" onclick="removeOrder(this, '${d.id}')"></i>
                    `;
                    whatsapp_btn = "";
                    edit_btn = "";
                    if (window.user.role !== "admin") {
                        delete_btn = "";
                    }
                    if (
                        window.user.role == "admin" ||
                        window.user.role == "support" 
                    ) {
                        whatsapp_btn = `
                    <a onclick="whatsapp_msg(${d.id})" class=col target=_blank>
                    <i class="fa-brands fa-whatsapp text-success"></i>
                </a>
                `;
                    }
                    if (
                        window.user.role == "admin" ||
                        window.user.role == "support" ||
                        window.user.id == d.created_id || 
                        window.user.role == "pagesCoordinator"

                    ) {
                        edit_btn = `
                        <a href="/order/${d.id}/edit" class=col target=_blank>
                        <i class=" fas fa-pen  text-info" )"></i>
                    </a>
                    `;
                    }
                    message_btn = `
                    <a onclick="sendOrderMessage('${d.id}', '${d.created_by_email}')" class=col target=_blank>
                    <i class="  fas fa-envelope
                    text-warning" )"></i>
                </a>`;
                    return `
                    <div class="row" style="width: 120px;">
                    ${delete_btn}
                    ${edit_btn}
                    ${whatsapp_btn}
                    ${message_btn}
                    </div>

                    `;
                },
            },
        ],
        lengthChange: !1,
        dom: "Blpftrip",

        buttons: [
            {
                text: '<i class="far fa-check-square"></i>',
                titleAttr: "select all",
                action: function () {
                    a.rows({
                        page: "current",
                    }).select();
                },
            },
            {
                text: '<i class="far fa-square"></i>',
                titleAttr: "unselect all",
                action: function () {
                    a.rows({
                        page: "current",
                    }).deselect();
                },
            },
            {
                text: '<i class="far  fas fa-undo"></i>',
                action: function () {
                    a.ajax.reload();
                },
            },
            {
                extend: "colvis",
                className:
                    "btn-light " +
                    (window.user.role == "admin" ||
                    window.user.role == "support"
                        ? ""
                        : "d-none"),
            },
            {
                extend: "copy",
                className: "btn-light",
                exportOptions: {
                    columns: ":visible",
                },
            },
            {
                text: "Print",
                className:
                    "btn-light " +
                    (window.user.role == "admin" ||
                    window.user.role == "support"
                        ? ""
                        : "d-none"),
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
                text: "<i class='  fas fa-luggage-cart                '></i>",
                className:
                    "btn-light " +
                    (window.user.role == "admin" ? "" : "d-none"),
                titleAttr: "اختيار شركة شحن",
                action: async function () {
                    var role = "";
                    var ids = $.map(
                        a.rows(".selected").data(),
                        function (item) {
                            role = item.role;
                            return item.id;
                        }
                    );
                    if (ids.length == 0 || role !== "admin") return;
                    companys = await fetch("/ShippingCompanies/get");
                    companys = await companys.json();
                    // [{id: "name"}]
                    var { value: companyId } = await Swal.fire({
                        title: "اختر شركة الشحن",
                        input: "select",
                        inputOptions: { ...companys },
                        inputPlaceholder: "اختر الحالة الجديدة",
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
                    if (companyId) {
                        await fetch(
                            "/order/Shipping?ids=" +
                                ids +
                                "&companyId=" +
                                companyId
                        );
                    }
                },
            },
            {
                text: "تحديث الحالة",
                className:
                    "btn-light " +
                    (window.user.role == "admin" ||
                    window.user.role == "support"
                        ? ""
                        : "d-none"),
                action: async function () {
                    var role = "";
                    var ids = $.map(
                        a.rows(".selected").data(),
                        function (item) {
                            role = item.role;
                            return {
                                id: item.id,
                                role: item.role,
                                status: item.status,
                            };
                        }
                    );
                    if (ids.length === 0) {
                        return;
                    }
                    var { value: status } = await Swal.fire({
                        title: "اختر الحالة الجديدة",
                        input: "select",
                        inputOptions: {
                            new: "جديد",
                            pending: "معلق",
                            confirmed: "مؤكد",
                            prepared: "تم التجهيز ",
                            delivery: "قيد التوصيل",
                            delivered: "تم التسليم ",
                            cancelled: "ملغي ",
                        },
                        inputPlaceholder: "اختر الحالة الجديدة",
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
                    updateStatus(
                        null,
                        ids.map((d) => d.id),
                        ids[0].status,
                        ids[0].role,
                        status
                    );

                    Swal.hideLoading();
                    Swal.fire("نجح", "تم التحديث بنجاح", "success");
                    Swal.hideLoading();
                },
            },
            {
                text: "رسالة واتس",
                className:
                    "btn-light " +
                    (window.user.role == "admin" ||
                    window.user.role == "support"
                        ? ""
                        : "d-none"),
                action: async function () {
                    var role = "";
                    var ids = $.map(
                        a.rows(".selected").data(),
                        function (item) {
                            role = item.role;
                            return {
                                id: item.id,
                                role: item.role,
                                status: item.status,
                            };
                        }
                    );
                    if (ids.length === 0) {
                        return;
                    }
                    whatsapp_msg(ids.map((d) => d.id));
                },
            },
            {
                text: " تحديث حالة الشحن",
                className:
                    "btn-light " +
                    (window.user.role == "admin" ||
                    window.user.role == "Shippingcompany"
                        ? ""
                        : "d-none"),
                action: async function () {
                    var role = "";
                    var ids = $.map(
                        a.rows(".selected").data(),
                        function (item) {
                            role = item.role;
                            return {
                                id: item.id,
                                role: item.role,
                                status: item.status,
                            };
                        }
                    );
                    if (ids.length === 0) {
                        return;
                    }
                    var { value: status } = await Swal.fire({
                        title: "تحديث الحالة",
                        input: "select",
                        inputOptions: {
                            delivery: "قيد التوصيل",
                            delivered: "تم التسليم",
                            "Partially delivered": "مسلم جزئي",

                            "Refused to receive": "رفض الاستلام",
                            Delayed: "مؤجل",
                            "returned product": "مرتجع",
                        },
                        inputPlaceholder: "اختر حالة جديدة",
                        showCancelButton: true,
                        inputValidator: (value) => {
                            return new Promise((resolve) => {
                                if (value) {
                                    resolve();
                                } else {
                                    resolve("You need to select oranges :)");
                                }
                            });
                        },
                    });
                    var { value: note } = await Swal.fire({
                        input: "textarea",
                        inputLabel: "الملاحظة",
                        inputPlaceholder: "اكتب ملاحظة",
                        showCancelButton: true,
                    });
                    updateShippingStatus(
                        null,
                        ids.map((d) => d.id),
                        ids[0].status,
                        ids[0].role,
                        status,
                        note
                    );
                    Swal.fire("نجح", "تم التحديث بنجاح", "success");
                },
            },
            {
                extend: "excel",
                className:
                    "btn-light " +
                    (window.user.role == "admin" ||
                    window.user.role == "support"
                        ? ""
                        : "d-none"),
                exportOptions: {
                    columns: ":visible",
                },
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
    localStorage["selected"] = "[]";
    a.buttons().container().addClass("pb-3");
    a.on("select", function (e, dt, type, indexes) {
        var _rowData = a.rows(indexes).data().toArray();
        for (let i in _rowData) {
            var rowData = _rowData[i];
            var selected = JSON.parse(localStorage["selected"]);
            selected.push(rowData["id"]);
            localStorage["selected"] = JSON.stringify(selected);
        }
    }).on("deselect", function (e, dt, type, indexes) {
        var _rowData = a.rows(indexes).data().toArray();
        for (let i in _rowData) {
            var rowData = _rowData[i];

            var selected = JSON.parse(localStorage["selected"]);
            var index = selected.indexOf(rowData["id"]);
            selected.splice(index, 1);
            localStorage["selected"] = JSON.stringify(selected);
        }
    });
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
    sendOrderMessage = async (order_id, email) => {
        let role = user.role;
        $to_input =
            role == "admin" || role == "support"
                ? '<input id="to" placeholder="الي" disabled value="' +
                  email +
                  '" class="form-control" required> <br/>'
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
        formValues.order_id = order_id;

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

removeOrder = (el, id) => {
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
            fetch(`order/${id}/delete`).then(() => {
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
updateStatus = async (
    el,
    id,
    _status,
    role,
    newStatus = null,
    order_creator = null
) => {
    if (
        role === "admin" ||
        (role === "support" && !["delivery", "delivered"].includes(_status)) ||
        (["new"].includes(_status) && user.id == order_creator)
    ) {
        if (el) {
            var { value: status } = await Swal.fire({
                title: "اختر الحالة الجديدة",
                input: "select",
                inputOptions:
                    ["new"].includes(_status) &&
                    user.id == order_creator &&
                    user.role != "admin" &&
                    user.role != "support"
                        ? {
                              new: "جديد",
                              cancelled: "ملغي ",
                          }
                        : {
                              new: "جديد",
                              pending: "معلق",
                              confirmed: "مؤكد",
                              delay: "مؤجل",
                              prepared: "تم التجهيز ",
                              delivery: "قيد التوصيل",
                              delivered: "تم التسليم ",
                              cancelled: "ملغي ",
                          },
                inputPlaceholder: "اختر الحالة الجديدة",
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
        } else {
            var status = newStatus;
        }

        if (status) {
            let color = "bg-soft-warning text-warning";
            let text = "جديد";

            switch (status) {
                case "new":
                    color = "bg-soft-info text-info";
                    text = "جديد";
                    break;
                case "pending":
                    color = "bg-soft-warning text-danger";
                    text = "معلق ";
                    break;
                case "delay":
                    color = "bg-soft-warning text-danger";
                    text = "مؤجل ";
                    break;
                case "confirmed":
                    color = "bg-soft-success text-success";
                    text = "مؤكد ";
                    break;
                case "prepared":
                    color = "bg-soft-info text-info";
                    text = "تم التجهيز ";
                    break;
                case "delivery":
                    color = "bg-soft-danger text-danger";
                    text = "قيد التوصيل ";
                    break;
                case "delivered":
                    color = "bg-soft-danger text-danger";
                    text = "تم التسليم ";
                    break;
                case "cancelled":
                    color = "bg-soft-danger text-danger";
                    text = "ملغي ";
                    break;
            }
            if (el) {
                $(el)
                    .find("span")
                    .text(text)
                    .removeClass()
                    .addClass("badge  " + color);
            } else {
                window.a.ajax.reload();
            }
            Swal.fire({
                title: "جاري المعالجة ...",
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                onAfterClose: () => {
                    Swal.hideLoading();
                },
            });
            fetch(`order/${id}/status/${status}`).then(() => {
                Swal.hideLoading();
                if (el) {
                    Swal.fire(`نجح`, "تم تحديث الحالة.", "success");
                }
            });
        }
    }
};

updateShippingStatus = async (
    el,
    id,
    _status,
    role,
    newStatus = null,
    newNote = null
) => {
    // if el not null that mean user try to update one row
    if (
        ["delivered", "cancelled"].includes(_status) &&
        role === "Shippingcompany"
    ) {
        return;
    }
    if (role === "admin" || role === "Shippingcompany") {
        if (el) {
            var { value: status } = await Swal.fire({
                title: "تحديث الحالة",
                input: "select",
                inputOptions: {
                    delivery: "قيد التوصيل",
                    delivered: "تم التسليم",
                    "Partially delivered": "مسلم جزئي",

                    "Refused to receive": "رفض الاستلام",
                    Delayed: "مؤجل",
                    "returned product": "مرتجع",
                },
                inputPlaceholder: "اختر حالة جديدة",
                showCancelButton: true,
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (value) {
                            resolve();
                        } else {
                            resolve("You need to select oranges :)");
                        }
                    });
                },
            });
            var { value: note } = await Swal.fire({
                input: "textarea",
                inputLabel: "الملاحظة",
                inputPlaceholder: "اكتب ملاحظة",
                showCancelButton: true,
            });
        } else {
            var status = newStatus;
            var note = newNote;
        }

        if (status) {
            let color = "bg-soft-warning text-warning";
            let text = "لا يوجد شركة شحن";

            switch (status) {
                case "awaiting":
                    color = "bg-soft-warning text-warning";
                    text = "لا يوجد شركة شحن";
                    break;
                case "delivery":
                    color = "bg-soft-warning text-warning";
                    text = "قيد التوصيل ";
                    break;
                case "delivered":
                    color = "bg-soft-success text-success";
                    text = "تم التسليم ";
                    break;
                case "Partially delivered":
                    color = "bg-soft-warning text-warning";
                    text = "مسلم جزئي ";
                    break;
                case "Refused to receive":
                    color = "bg-soft-danger text-danger";
                    text = "رفض الاستلام  ";
                    break;
                case "Delayed":
                    color = "bg-soft-warning text-warning";
                    text = "مؤجل  ";
                    break;
                case "returned product":
                    color = "bg-soft-danger text-danger";
                    text = "مرتجع  ";
                    break;
            }
            if (el) {
                $(el)
                    .find("span")
                    .text(text)
                    .removeClass()
                    .addClass("badge  " + color);
            } else {
                window.a.ajax.reload();
            }
            Swal.fire({
                title: "جاري المعالجة ...",
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                onAfterClose: () => {
                    Swal.hideLoading();
                },
            });
            fetch(`order/${id}/Shipping/status/${status}`, {
                method: "POST", // *GET, POST, PUT, DELETE, etc.
                mode: "cors", // no-cors, *cors, same-origin
                cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                credentials: "same-origin", // include, *same-origin, omit
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-Token": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    // 'Content-Type': 'application/x-www-form-urlencoded',
                },
                redirect: "follow", // manual, *follow, error
                referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
                body: JSON.stringify({
                    note: note ? note : "",
                }), // body data type must match "Content-Type" header
            }).then(() => {
                if (el) {
                    Swal.fire(`نجح`, "تم تحديث الحالة.", "success");
                }
            });
        }
    }
};

whatsapp_msg = async (order_id) => {
    const { value: formValues } = await Swal.fire({
        title: "رسالة واتس اب",
        html: `
                <div class="mt-2 text-start">
                    <label>الرسالة</label>
                    <textarea id="message" type="text" class="form-control"></textarea>
                </div>

                <div class="mt-2 text-start">
                    <label>صورة</label>
                    <input id="image_file" type="file" class="form-control" />
                </div>
                <div class="mt-2 text-start" >
                <input  id="invoice" type="checkbox" class="form-check" style="margin: 10px;display:inline-block;min-height: auto;" />
                    <label>فاتورة</label>
                </div>

            `,
        focusConfirm: false,
        showCancelButton: true,
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),

        preConfirm: () => {
            return {
                body: document.getElementById("message").value,
                image_file: document.getElementById("image_file").files[0],
                invoice: document.getElementById("invoice").checked,
            };
        },
    });
    if (formValues.body) {
        Swal.fire({
            title: "جاري المعالجة ...",
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
            onAfterClose: () => {
                Swal.hideLoading();
            },
        });

        let body = new FormData();
        for (k in formValues) body.append(k, formValues[k]);
        if (!formValues.image_file) {
            body.append("text_only", true);
        }
        fetch(`/whatsapp/${order_id}`, {
            method: "post",
            body: body,
            headers: {
                "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
                accept: "application/json",
            },
        })
            .then((d) => d.json())
            .then((d) => {
                if (d.success) {
                    Swal.fire(`نجح`, "تم الارسال.", "success");
                } else {
                    Swal.fire(`فشل`, "يوجد خطأ في البينات.", "error");
                }
            });
    }
};
