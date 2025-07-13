$(document).ready(function () {
    $(".edituser").on("click", function () {
        const id = $(this).data("id");
        $.ajax({
            url: "/dashboard/users/" + id + "/edit",
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
                    "/dashboard/users/" + data.id
                );
            },
        });
    });
});
