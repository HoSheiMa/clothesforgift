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
    sendUserMessage = async (user_id) => {
        let role = user.role;
        $to_input =
            role == "admin" || role == "support"
                ? `<input value="${user_id}" disabled id="to" placeholder="الي" class="form-control" required> <br/>`
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

        if (formValues) {
            postData(`chat/create`, formValues).then((data) => {
                Swal.fire(
                    "Sended!",
                    "Your Messages has been sended.",
                    "success"
                ).then(() => {
                    openChat(focus_chat, null);
                });
            });
        }
    };
};
