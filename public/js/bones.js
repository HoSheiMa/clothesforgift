add = () => {
    let form = $("form").serializeArray();
    let dataObj = {};
    let error = 0;

    $(form).each(function (i, field) {
        if (field.value) {
            dataObj[field.name] = field.value;
        } else {
            Swal.fire(`فشل`, "لا يوجد بيانات .", "error").then((d) => {
                window.location.reload();
            });
            error = 1;
            return;
        }
    });
    if (error == 1) return;
    fetch(`/bones`, {
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
        Swal.fire(`نجح`, "تم الاضافة .", "success").then((d) => {
            window.location.reload();
        });
    });
};

remove = (id) => {
    fetch(`/bones/${id}/delete`, {
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
