setInterval(() => {
    let cart = localStorage["cart"];
    cart = cart ? (JSON.parse(cart) ? JSON.parse(cart) : []) : [];
    $("#cart-number").text(cart.length);
}, 1000);

search = ({ key }, element, productsList = "products-list") => {
    let search = element.value + (key.length == 1 ? key : "");
    if (search.length == 0) {
        return;
    }
    $(`.${productsList}`).html(
        `<center><div class="spinner-border avatar-lg text-primary m-2" role="status"></div></center>`
    );

    fetch(`/api/products/approved`, {
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
    })
        .then((d) => d.json())
        .then((products) => {
            let found = 0;
            $(`.${productsList}`).html("");
            products.data.forEach((p) => {
                if (p.name && p.name.includes(search)) {
                    found++;
                    $(`.${productsList}`).html(
                        $(`.${productsList}`).html() +
                            ` <a href="/product/${p.id}" class="dropdown-item notify-item">
                        <div class="d-flex align-items-start">
                            <img class="d-flex me-2 rounded-circle" src="${p.icon}" alt="Generic placeholder image" height="32">
                            <div class="w-100">
                                <h5 class="m-0 font-14">${p.name}</h5>
                                <span class="font-12 mb-0">${p.type}</span>
                            </div>
                        </div>
                        </a>`
                    );
                }
            });
            if (found == 0) {
                $(`.${productsList}`).html("<h3>لا يوجد بينات</h3>");
            }
        });
};
