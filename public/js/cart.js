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
    let cart = localStorage["cart"];
    cart = cart ? (JSON.parse(cart) ? JSON.parse(cart) : []) : [];

    $tbody = $("tbody").first();

    $tbody.html("");
    cart.forEach((order, index) => {
        let colorId = order.info[0].value;
        let color = {};
        order.product.colors.forEach((_color) => {
            if (_color.id == +colorId) {
                color = _color;
            }
        });
        $tbody.append(`
        <tr>
            <td>
                <img src="${order.product.icon}" width=50 />
            </td>
            <td>
                ${order.info[1].value} <!-- price --!>
            </td>
            <td>
                ${order.info[2].value} <!-- available --!>
            </td>
            <td>
            ${color.color} <!-- available --!>
            </td>
            <td>
            ${color.size} <!-- available --!>
            </td>
            <td>
                ${+order.info[2].value * +order.info[1].value}
            </td>
            <td>
                ${
                    role === "support" || role === "pagesCoordinator"
                        ? 0
                        : order.info[2].value * order.info[1].value -
                          order.info[2].value * order.product.price
                }
            </td>
            <td>
            <a onclick="remove(this, ${index});" class="action-icon"> <i class="mdi mdi-delete"></i></a>
            </td>
        </tr>
    `);
    });

    updateInvoice = () => {
        let $total = $("#total");
        let total = 0;
        let benefit = 0;
        let $benefit = $("#benefit");
        let $Shipping = $("#Shipping");
        let $ShippingPrice = $("#Shipping-dropdown")
            .find(":selected")
            .attr("price");
        cart.forEach((order) => {
            total += +order.info[1].value * +order.info[2].value;
            if (role !== "support" || role !== "pagesCoordinator") {
                benefit +=
                    order.info[2].value * order.info[1].value -
                    order.info[2].value * order.product.min_price;
            } else {
                if (role === "support") {
                    benefit = 5;
                } else {
                    benefit = 10;
                }
            }
        });
        if (user.leader_id) {
            let leader_ratio = 50; // default
            if (user.leader_ratio) {
                leader_ratio = Number(user.leader_ratio);
            }
            benefit = benefit * ((100 - leader_ratio) / 100);
        }

        $total.text(+total + +$ShippingPrice + ".00");
        $benefit.text(benefit + ".00");
        $Shipping.text($ShippingPrice + ".00");
    };
    updateInvoice();
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
        if (response.status !== 200) {
            Swal.fire(
                "Failed",
                "Your info not complete or there problem in server, try again later",
                "error"
            );
        }
        return response.json(); // parses JSON response into native JavaScript objects
    }
    remove = (el, index) => {
        let cart = localStorage["cart"];
        cart = cart ? (JSON.parse(cart) ? JSON.parse(cart) : []) : [];
        cart.splice(index, 1);
        localStorage["cart"] = JSON.stringify(cart);
        updateInvoice();
        $(el).parent().parent().remove();
    };
    checkout = (el) => {
        let $form = $("form").serializeArray();
        let data = {};
        for (let i in $form) {
            if (data[$form[i].name]) {
                if (typeof data[$form[i].name] === "string") {
                    data[$form[i].name] = [data[$form[i].name], $form[i].value];
                } else {
                    data[$form[i].name] = [
                        ...data[$form[i].name],
                        $form[i].value,
                    ];
                }
            } else {
                data[$form[i].name] = $form[i].value;
            }
        }

        if (typeof data.address == "string") {
            data.address = [data.address];
        }
        if (typeof data.phone == "string") {
            data.phone = [data.phone];
        }
        let cart = localStorage["cart"];
        cart = cart ? (JSON.parse(cart) ? JSON.parse(cart) : []) : [];
        let orders = [];
        for (let i in cart) {
            orders.push({
                sizeId: +cart[i].info[0].value,
                neededPrice: +cart[i].info[1].value,
                neededQuantity: +cart[i].info[2].value,
                productId: +cart[i].product.id,
            });
        }
        data = {
            ...data,
            orders: orders,
        };
        Swal.fire({
            title: "جاري المعالجة ...",
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });
        postData("/checkout", data).then((d) => {
            $(el).removeAttr("disabled");
            if (d.success) {
                localStorage["cart"] = "[]";

                Swal.fire(
                    "Success",
                    "Your order is now under processing",
                    "success"
                ).then(() => {
                    if (data.add_for) {
                        window.location.assign(`/order/${data.add_for}/edit`);
                    } else {
                        window.location.assign("/show-products");
                    }
                });
            } else {
                Swal.fire("error", data.msg, "error");
            }
            console.log(data); // JSON data parsed by `data.json()` call
        });
    };
};
