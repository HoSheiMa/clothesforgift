// there colorsList variable globally
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

    updatePrice = () => {
        let $formData = $("form").serializeArray();
        let $total = $("#total");
        let $needed = +$("#available").val() ? +$("#available").val() : 0;
        let $price = +$("#price").val() ? +$("#price").val() : 0;
        let min_price = +productInfo.price;
        let totalWithoutBonus = $needed * min_price;
        let totalWithBonus = $needed * $price;
        let bonus = totalWithBonus - totalWithoutBonus;
        let role = window.role;
        if (role === "support" || role === "pagesCoordinator") {
            $total.text("0.00");
            return;
        }
        if (user.leader_id) {
            let leader_ratio = 50; // default
            if (user.leader_ratio) {
                leader_ratio = Number(user.leader_ratio);
            }
            bonus = bonus * (user.leader_ratio / 100);
        }
        $total.text(bonus + ".00");
    };
    updateInputs = (event, el, type) => {
        let selectedColor = el.value;
        if (type == "color") {
            let availableSize = [];

            for (let i in colorsList) {
                if (colorsList[i].color === selectedColor) {
                    availableSize.push([colorsList[i].size, colorsList[i].id]);
                }
            }

            $("#size").html(`<option selected value="">اختر</option>`); // empty

            for (let i in availableSize) {
                $("#size").html(
                    $("#size").html() +
                        `<option selected value="${availableSize[i][1]}">${availableSize[i][0]}</option>`
                );
            }
            $("#size").val($("#size option:first").val());
        } else if (type == "size") {
            // size
            let selectedColorId = el.value;
            let colorInfo = {};
            for (let i in colorsList) {
                if (colorsList[i].id == selectedColorId) {
                    colorInfo = colorsList[i];
                }
            }

            // available
            if (colorInfo.available > 0) {
                $("#available").html(`<option selected value="">اختر</option>`); // empty
                let ava = colorInfo.available;
                let max = productInfo.max_price;
                let min = productInfo.min_price;
                let start_price = min;
                while (ava !== 0) {
                    $("#available").html(
                        $("#available").html() +
                            `<option selected value="${ava}">${ava}</option>`
                    ); // empty
                    ava--;
                }
                $("#available").val($("#available option:first").val());
                $("#price").html(`<option selected value="">اختر</option>`); // empty

                while (start_price <= max) {
                    $("#price").html(
                        $("#price").html() +
                            `<option selected value="${start_price}">${start_price}</option>`
                    ); // empty
                    start_price += 5;
                }
                $("#price").val($("#price option:first").val());
            } else {
                $("#available").html(
                    `<option selected value="0">غير متوفر</option>`
                );
                $("#price").html(
                    `<option selected value="0">غير متوفر</option>`
                );
            }
        }
        updatePrice();
    };
    // init love
    let love = localStorage["love"];

    love = love ? (JSON.parse(love) ? JSON.parse(love) : []) : [];
    if (love.includes(`${productInfo.id}`)) {
        $("#love > i")
            .toggleClass("mdi-heart-outline")
            .toggleClass("mdi-heart");
    }

    love = (el, productId) => {
        let love = localStorage["love"];
        love = love ? (JSON.parse(love) ? JSON.parse(love) : []) : [];
        if (love.includes(productId)) {
            let index = love.indexOf(productId);
            love.splice(index, 1);
        } else {
            love.push(productId);
        }
        localStorage["love"] = JSON.stringify(love);
        $("#love > i")
            .toggleClass("mdi-heart-outline")
            .toggleClass("mdi-heart");
    };
    addToCart = (el, productId) => {
        let data = $("form").serializeArray();
        for (let i in data) {
            if (data[i].value == "") {
                return Swal.fire("خطأ", "خطأ في البيانات", "error");
            }
        }
        let cart = localStorage["cart"];
        cart = cart ? (JSON.parse(cart) ? JSON.parse(cart) : []) : [];
        let found = false;
        let index = null;

        for (let i in cart) {
            if (
                data[0].value == cart[i].info[0].value &&
                data[1].value == cart[i].info[1].value &&
                productInfo.id == cart[i].product.id
            ) {
                found = true;
                index = i;
            }
        }
        if (found) {
            cart[i].info[2].value =
                Number(cart[i].info[2].value) + data[2].value;
            Swal.fire("Success", "Your order waiting you in cart.", "success");
        } else {
            if (data && productInfo) {
                cart.push({
                    info: data,
                    product: productInfo,
                });
                Swal.fire(
                    "Success",
                    "Your order waiting you in cart.",
                    "success"
                );
            }
        }
        localStorage["cart"] = JSON.stringify(cart);
    };
};
