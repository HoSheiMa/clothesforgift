add = () => {
    let form = $("form").serializeArray();
    let dataObj = {};
    $(form).each(function (i, field) {
        dataObj[field.name] = field.value;
    });
    fetch(`/Shipping`, {
        method: "POST", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, *cors, same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
            // 'Content-Type': 'application/x-www-form-urlencoded',
            accept: "application/json",
        },
        redirect: "follow", // manual, *follow, error
        referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
        body: JSON.stringify({
            ...dataObj,
        }), // body data type must match "Content-Type" header
    }).then((res) => {
        if (res.status == 200)
            Swal.fire(`نجح`, "تم الاضافة .", "success").then((d) => {
                window.location.reload();
            });
        else {
            Swal.fire(`فشل`, "لا يوجد بيانات .", "error").then((d) => {
                window.location.reload();
            });
        }
    });
};

remove = (id) => {
    fetch(`/Shipping/${id}/delete`, {
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
