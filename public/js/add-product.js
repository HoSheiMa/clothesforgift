addmoreInputForColor = (el) => {
    $clone = $(".select-new-color").first().clone();
    $($clone).append(
        '<button class="btn btn-danger mt-1" onclick="$(this).parent().remove()"}>delete</button>'
    );
    $(el).before($clone);
};
