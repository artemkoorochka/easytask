function complete(id) {
    $.get("/", {
        action: "edit",
        id: id,
        complete: "Y"
    });
}