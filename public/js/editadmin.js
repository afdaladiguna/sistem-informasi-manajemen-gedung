$(document).ready(function () {
    $(".editadmin").on("click", function () {
        const id = $(this).data("id");
        $.ajax({
            url: "/dashboard/admin/" + id + "/edit",
            data: {
                id: id,
            },
            type: "get",
            dataType: "json",
            success: function (data) {
                $("#id").val(data.id);
                $("#name").val(data.name);
                $("#phone_number").val(data.phone_number);
                $("#email").val(data.email);
                $("#password").val(data.password);
                $("#role_id").val(data.role_id);
                $("#editformuser").attr(
                    "action",
                    "/dashboard/admin/" + data.id
                );
            },
        });
    });
});
