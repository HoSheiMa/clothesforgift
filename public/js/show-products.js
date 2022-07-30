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

    fetch("/api/products/approved")
        .then((d) => d.json())
        .then((d) => {
            window.ajax_products = d.data;

            for (let i in d.data) {
                let colors = ``;

                let product = d.data[i];
                let _colors = [];
                for (let c in product.colors) {
                    if (_colors.includes(product.colors[c].color)) continue;
                    colors += ` <option value="${product.colors[c].color}">${product.colors[c].color}</option>`;
                    _colors.push(product.colors[c].color);
                }
                $("#contents").html(
                    $("#contents").html() +
                        `
            <div class="col-md-6 col-xl-3 _product_" type="${product.type}" productId="${product.id}">
            <div class="card product-box">
                <div class="card-body">
                    <div class="bg-light">
                        <img src="${product.icon}" alt="product-pic" class="img-fluid">
                    </div>

                    <div class="product-info">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="font-16 mt-0 sp-line-1"><a href="/product/${product.id}" class="text-dark">${d.data[i].name}</a> </h5>
                                <div class="text-warning mb-2 font-13">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                                <h5 class="m-0"> <span class="text-muted"> Stocks : ${d.data[i].available}</span></h5>
                            </div>
                            <div class="col-auto">
                                <div class="product-price-tag">
                                ${product.min_price}
                                </div>
                            </div>
                        </div> <!-- end row -->
                        <div class="row align-items-center">
                        <form productId="${product.id}" class="d-flex flex-wrap align-items-center mb-4">
                                            <label class="my-1 me-2" for="quantityinput">اللون</label>
                                            <div class="me-3 w-100">
                                                <select onchange="updateInputs(event, this, 'color')" class="form-select my-1" id="color-${product.id}">
                                                    <option selected="" value="">اختر</option>
                                                              ${colors}
                                                                                                    </select>
                                            </div>
                                            <br>

                                            <label class="my-1 me-2" for="sizeinput">المقاس</label>
                                            <div class="me-sm-3 w-100">
                                                <select name="size" class="form-select my-1" id="size-${product.id}" onchange="updateInputs(event, this, 'size')"><option selected="" value="">اختر</option>
                                                </select>
                                            </div>
                                            <br>
                                            <label class="my-1 me-2" for="pirce">السعر</label>
                                            <div class="me-sm-3 w-100">
                                                <select onchange="updatePrice()" name="price" class="form-select my-1" id="price-${product.id}">
                                                <option selected="" value="">اختر</option>
                                                </select>
                                            </div>
                                            <label class="my-1 me-2" for="sizeinput">الكمية</label>
                                            <div class="me-sm-3 w-100">
                                                <input type="number" disabled onchange="updatePrice()" name="available" class="form-control my-1" id="available-${product.id}" />
                                            </div>
                                            <div class="mt-2 mb-2 me-2" id="">
                                                 المكسب الاجمالي:

                                                <strong id="total-${product.id}">0</strong>
                                            </div>
                                            <div class="mt-2 mb-2 me-2" id="">
                                            الكمية المتاحة:

                                           <strong id="max-${product.id}">0.00</strong>
                                       </div>

                                        </form>
                                        <div class="row">
                                            <button id="love-${product.id}" onclick="love(this,  '${product.id}')" type="button" class="btn btn-danger me-2"><i class="mdi mdi-heart-outline"></i></button>
                                            <button onclick="addToCart(this, '${product.id}')" type="button" class="btn btn-success waves-effect waves-light my-2">
                                                <span class="btn-label"><i class="mdi mdi-cart"></i></span>اضافة
                                            </button>
                                            <button onclick="window.location.assign('/wholesale/${product.id}')" type="button" class="btn btn-warning waves-effect waves-light">
                                                <span class="btn-label"><i class="mdi mdi-cart"></i></span>طلب جملة
                                            </button>
                                        </div>
                        </div>
                    </div> <!-- end product info-->
                </div>
            </div> <!-- end card-->
        </div> `
                );
            }
            // init love
            let loves = localStorage["love"];
            loves = loves ? (JSON.parse(loves) ? JSON.parse(loves) : []) : [];
            for (let i in loves) {
                $love = $(`#love-${loves[i]} > i`);
                $love.toggleClass("mdi-heart-outline").toggleClass("mdi-heart");
            }
        });

    // there colorsList variable globally
    updatePrice = () => {
        $("form").each((i, e) => {
            let fid = $(e).attr("productId");

            let $formData = $(e).serializeArray();
            let $total = $(`#total-${fid}`);
            let $needed = +$(`#available-${fid}`).val()
                ? +$(`#available-${fid}`).val()
                : 0;
            let $price = +$($(`#price-${fid}`)).val()
                ? +$(`#price-${fid}`).val()
                : 0;
            let productInfo = "";
            for (i in ajax_products) {
                if (ajax_products[i].id == fid) {
                    productInfo = ajax_products[i];
                }
            }
            let min_price = +productInfo.min_price;
            let totalWithoutBonus = $needed * min_price;
            let totalWithBonus = $needed * $price;
            let bonus = totalWithBonus - totalWithoutBonus;
            let role = window.role;
            if (role === "support") {
                $total.text("5.00");
                return;
            }
            if (role === "pagesCoordinator") {
                $total.text("10.00");
                return;
            }
            if (user.leader_id) {
                let leader_ratio = 50; // default
                if (user.leader_ratio) {
                    leader_ratio = Number(user.leader_ratio);
                }
                bonus = bonus * ((100 - leader_ratio) / 100);
            }
            if (bonus < 0) {
                bonus = 0;
            }
            $total.text(bonus + ".00");
        });
    };
    updateInputs = (event, el, type) => {
        let pid = +$(el.closest("form")).attr("productId");
        let p = null;
        let productInfo = null;
        for (i in ajax_products) {
            if (ajax_products[i].id === pid) {
                p = ajax_products[i];
                productInfo = ajax_products[i];
            }
        }
        let colorsList = p.colors;
        let selectedColor = el.value;
        if (type == "color") {
            let availableSize = [];

            for (let i in colorsList) {
                if (colorsList[i].color === selectedColor) {
                    availableSize.push([colorsList[i].size, colorsList[i].id]);
                }
            }

            $("#size-" + pid).html(`<option selected value="">اختر</option>`); // empty

            for (let i in availableSize) {
                $("#size-" + pid).html(
                    $("#size-" + pid).html() +
                        `<option selected value="${availableSize[i][1]}">${availableSize[i][0]}</option>`
                );
            }
            $("#size-" + pid).val($(`#size-${pid} option:first`).val());
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
                let ava = colorInfo.available;
                let max = productInfo.max_price;
                let min = productInfo.min_price;
                let start_price = min;
                $("#max" + `-${pid}`).text(ava);
                $("#available" + `-${pid}`)
                    .attr("max", ava)
                    .attr("min", 1)
                    .val(1)
                    .removeAttr("disabled"); // empty
                $("#price" + `-${pid}`).html(
                    `<option selected value="">اختر</option>`
                ); // empty

                while (start_price <= max) {
                    $("#price" + `-${pid}`).html(
                        $("#price" + `-${pid}`).html() +
                            `<option selected value="${start_price}">${start_price}</option>`
                    ); // empty
                    start_price += 5;
                }
                $("#price" + `-${pid}`).val(
                    $(`#price-${pid} option:first`).val()
                );
            } else {
                $("#available" + `-${pid}`).attr("disabled", "disabled");
                $("#price" + `-${pid}`).html(
                    `<option selected value="0">غير متوفر</option>`
                );
            }
        }
        updatePrice();
    };

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
        $(el)
            .find("i")
            .toggleClass("mdi-heart-outline")
            .toggleClass("mdi-heart");
    };
    addToCart = (el, productId, product, _data, max_available) => {
        let productInfo;
        if (productId) {
            productInfo = null;
            for (i in ajax_products) {
                if (ajax_products[i].id == productId) {
                    productInfo = ajax_products[i];
                }
            }
        } else {
            productInfo = product;
        }
        let data = _data ? _data : $(el).parent().prev().serializeArray();
        // validation
        let _data_ = {};
        for (let i in data) {
            _data_[data[i].name] = data[i].value;
        }
        if (
            _data_.available &&
            _data_.available.length > 0 &&
            Number(_data_.available) > 0 &&
            Number(_data_.available) <=
                Number(
                    max_available
                        ? max_available
                        : $(el)
                              .parent()
                              .prev()
                              .find('[name="available"]')
                              .attr("max")
                ) &&
            _data_.price &&
            _data_.price.length > 0 &&
            _data_.size &&
            _data_.size.length > 0
        ) {
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
                    break;
                }
            }
            if (found) {
                cart[index].info[2].value =
                    Number(cart[index].info[2].value) + Number(data[2].value);
            } else {
                cart.push({
                    info: data,
                    product: productInfo,
                });
            }
            localStorage["cart"] = JSON.stringify(cart);
            Swal.fire("نجح", "تم الاضافة ", "success");
        } else {
            Swal.fire("خطأ", "يرجى تكملة البيانات.", "error");
        }
    };
    mutliAdd = async () => {
        $("form .row").each(function () {
            console.log(this);
            let data = [
                { name: "size", value: $(this).attr("id") },
                {
                    name: "price",
                    value: $(this).find('[name="price"]').val(),
                },
                {
                    name: "available",
                    value: $(this).find('[name="needed"]').val(),
                },
            ];
            let max_available = Number(
                $(this).find('[name="needed"]').attr("max")
            );
            if (data[2].value == "0" || data[2].value == 0) return;
            addToCart(null, null, product, data, max_available);
        });
    };
    isLoved = (id) => {
        let love = localStorage["love"];
        love = love ? (JSON.parse(love) ? JSON.parse(love) : []) : [];
        if (love.includes(id)) {
            return true;
        }
        return false;
    };
    recycleView = (selected) => {
        $projects = $("._product_");
        if (selected === "*") {
            $projects.each((i, e) => {
                $(e).fadeIn();
            });
        } else {
            if (selected == "loved") {
                $projects.each((i, e) => {
                    let id = $(e).attr("productId");
                    if (isLoved(id)) {
                        $(e).fadeIn();
                    } else {
                        $(e).fadeOut();
                    }
                });
            } else {
                $projects.each((i, e) => {
                    if ($(e).attr("type") === selected) {
                        $(e).fadeIn();
                    } else {
                        $(e).fadeOut();
                    }
                });
            }
        }
    };
};
