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
    var a = $("#products").DataTable({
        ajax: "/api/products",
        // select: {
        //     style: "os",
        //     selector: "td:first-child",
        //     style: "multi",
        // },
        // columnDefs: [
        //     {
        //         orderable: false,
        //         className: "select-checkbox",
        //         targets: 0,
        //     },
        // ],
        columns: [
            // {
            //     data: null,
            //     defaultContent: "",
            // },
            {
                data: "created_by",
            },
            {
                data: "icon",
                render: function (d) {
                    return `<img src="${d}" width=50 />`;
                },
            },
            {
                data: { name: "name", id: "id" },
                render: function (d) {
                    return `<a href="/product/${d.id}" target="_blank">${d.name}</a>`;
                },
            },
            {
                data: { status: "status", id: "id" },
                render: function (d) {
                    let color = "bg-soft-warning text-warning";
                    let text = "في انتظار الموافقة";
                    switch (d.status) {
                        case "awaiting approve":
                            color = "bg-soft-warning text-warning";
                            text = "في انتظار الموافقة";

                            break;
                        case "approved":
                            color = "bg-soft-success text-success";
                            text = "مصرح به";

                            break;
                        case "cancelled":
                            color = "bg-soft-danger text-danger";
                            text = "غير مصرح به";

                            break;
                    }
                    return `<h5 style="cursor: pointer;" onclick="updateStatus(this, '${d.id}')"><span class="badge ${color}"> ${text}</span></h5>`;
                },
            },
            {
                data: "available",
            },
            {
                data: "price",
            },
            {
                data: "min_price",
            },
            {
                data: "max_price",
            },
            {
                data: "id",
                render: function (d) {
                    let remove;
                    if (user.role == "admin") {
                        remove = `
                    <i style="margin-top: 4px;" class=" far fa-trash-alt col text-danger" onclick="removeProduct(this, '${d}')"></i>
                    `;
                    } else {
                        remove = "";
                    }
                    return `
                    <div class="row">
                    ${remove}
                        <a href="/product/${d}/edit" class=col target=_blank>
                            <i class=" fas fa-pen  text-info" )"></i>
                        </a>
                    </div>

                    `;
                },
            },
        ],
        lengthChange: !1,
        // buttons: [
        //     {
        //         text: '<i class="far fa-check-square"></i>',
        //         titleAttr: "select all",
        //         action: function () {
        //             a.rows({
        //                 page: "current",
        //             }).select();
        //         },
        //     },
        //     {
        //         text: '<i class="far fa-square"></i>',
        //         titleAttr: "unselect all",
        //         action: function () {
        //             a.rows({
        //                 page: "current",
        //             }).deselect();
        //         },
        //     },
        //     {
        //         text: '<i class="far  fas fa-undo"></i>',
        //         titleAttr: "unselect all",
        //         action: function () {
        //             a.ajax.reload();
        //         },
        //     },
        //     {
        //         extend: "colvis",
        //         className: "btn-light",
        //     },
        //     {
        //         extend: "copy",
        //         className: "btn-light",
        //     },
        //     {
        //         extend: "print",
        //         className: "btn-light",
        //     },
        //     {
        //         extend: "pdf",
        //         className: "btn-light",
        //     },
        // ],
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
};

removeProduct = (el, id) => {
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
            fetch(`product/${id}/delete`).then(() => {
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

updateStatus = async (el, id) => {
    if (user.role !== "admin") return;
    const { value: status } = await Swal.fire({
        title: "اختر الحاله",
        input: "select",
        inputOptions: {
            "awaiting approve": "في انتظار الموافقة",
            approved: "مصرح به",
            cancelled: "غير مصرح به",
        },
        inputPlaceholder: "Select a Status",
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

    if (status) {
        let color = "bg-soft-warning text-warning";
        let text = "في انتظار الموافقة";
        switch (status) {
            case "awaiting approve":
                color = "bg-soft-warning text-warning";
                text = "في انتظار الموافقة";
                break;
            case "approved":
                color = "bg-soft-success text-success";
                text = "مصرح به";

                break;
            case "cancelled":
                color = "bg-soft-danger text-danger";
                text = "غير مصرح به";

                break;
        }
        $(el)
            .find("span")
            .text(text)
            .removeClass()
            .addClass("badge  " + color);
        fetch(`product/${id}/status/${status}`).then(() => {
            Swal.fire(`العملية نجحت`, "تم تحديث الحالة.", "success");
        });
    }
};
