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
    updateStatus = async (el, id, _status, role, newStatus = null) => {
        if (
            role === "admin" ||
            (role === "support" && !["delivery", "delivered"].includes(_status))
        ) {
            if (el) {
                var { value: status } = await Swal.fire({
                    title: "اختر الحاله الجديدة",
                    input: "select",
                    inputOptions: {
                        new: "جديد",
                        pending: "معلق",
                        confirmed: "مؤكد",
                        prepared: "تم التجهيز ",
                        delivery: "قيد التوصيل",
                        delay: "مؤجل",
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
                    case "delay":
                        color = "bg-soft-warning text-warning";
                        text = "مؤجل ";
                        break;
                    case "pending":
                        color = "bg-soft-warning text-danger";
                        text = "معلق ";
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
                fetch(`/order/${id}/status/${status}`).then(() => {
                    if (el) {
                        Swal.fire(`نجح`, "تم تحديث الحاله.", "success");
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
                    title: "تحديث الحاله",
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

                fetch(`/order/${id}/Shipping/status/${status}`, {
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
                        Swal.fire(`نجح`, "تم تحديث الحاله.", "success");
                    }
                });
            }
        }
    };

    print = (id) => {
        let height = window.screen.availHeight - 100;
        let width = window.screen.availWidth - 150;
        window.open(
            "/invoice?ids=" + id,
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
    };

    updateInfo = (role, orderId) => {
        let $form = $("form").serializeArray();
        let dataObj = {};
        for (let i in $form) {
            if (dataObj[$form[i].name]) {
                if (typeof dataObj[$form[i].name] === "string") {
                    dataObj[$form[i].name] = [
                        dataObj[$form[i].name],
                        $form[i].value,
                    ];
                } else {
                    dataObj[$form[i].name] = [
                        ...dataObj[$form[i].name],
                        $form[i].value,
                    ];
                }
            } else {
                dataObj[$form[i].name] = $form[i].value;
            }
        }

        if (typeof dataObj.address == "string") {
            dataObj.address = [dataObj.address];
        }
        if (typeof dataObj.phone == "string") {
            dataObj.phone = [dataObj.phone];
        }
        fetch(`/order/${orderId}/update`, {
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
            body: JSON.stringify({
                ...dataObj,
            }), // body data type must match "Content-Type" header
        }).then(() => {
            Swal.fire(`نجح`, "تم تحديث .", "success").then((d) => {
                window.location.reload();
            });
        });
    };
    remove = (el, item_id) => {
        fetch(`/item/${item_id}/delete`, {
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
            Swal.fire(`نجح`, "تم تحديث .", "success").then((d) => {
                window.location.reload();
            });
        });
    };
    edit = (el, item_id) => {
        fetch(`/getColorsByItemId/${item_id}`)
            .then((d) => d.json())
            .then(async (d) => {
                // let available = +d.color.available + +d.item.needed;
                let options = {};
                let SelectedColor = null;
                for (i in d.colors) {
                    options[d.colors[i].id] =
                        d.colors[i].color + "|" + d.colors[i].size;
                    // available--;
                }
                const { value: color } = await Swal.fire({
                    title: "تحديث ",
                    input: "select",
                    inputOptions: {
                        ...options,
                    },
                    inputPlaceholder: "اختر اللون",
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
                for (i in d.colors) {
                    if (d.colors[i].id == color) {
                        SelectedColor = d.colors[i];
                        break;
                    }
                }
                let available = SelectedColor.available;
                options = {};
                while (available) {
                    options[available] = available;
                    available--;
                }
                const { value: qty } = await Swal.fire({
                    title: "تحديث ",
                    input: "select",
                    inputOptions: {
                        ...options,
                    },
                    inputPlaceholder: "اختر الكمية",
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

                if (SelectedColor && qty) {
                    fetch(`/item/${item_id}/update`, {
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
                            needed: qty,
                            color: color,
                        }),
                    }).then(() => {
                        Swal.fire(`نجح`, "تم تحديث .", "success").then((d) => {
                            window.location.reload();
                        });
                    });
                }
            });
    };
    add_unlock_Order = (e, order_id) => {
        fetch(`/unlock/${order_id}`, {
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
        }).then((e) => {
            if (e.status == 200) {
                location.assign("/show-products");
                return;
            }
            Swal.fire(`خطأ`, "لم يتم التحديث .", "error").then((d) => {
                window.location.reload();
            });
        });
    };
};
