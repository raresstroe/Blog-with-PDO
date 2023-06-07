/**
 * Handle the delete button for deleting articles
 */
$("a.delete").on("click", function (e) {
  e.preventDefault();

  if (confirm("Are you sure?")) {
    var frm = $("<form>");
    frm.attr("method", "post");
    frm.attr("action", $(this).attr("href"));
    frm.appendTo("body");
    frm.submit();
  }
});

/**
 * Validating a form inside js for better UX
 */
$.validator.addMethod(
  "dateTime",
  function (value, element) {
    return value == "" || !isNaN(Date.parse(value));
  },
  "Must be a valid date and time"
);

$("#formArticle").validate({
  rules: {
    title: {
      required: true,
    },
    content: {
      required: true,
    },
    published_at: {
      dateTime: true,
    },
  },
});

/**
 * Handle the publish button for publishing articles
 */
$("button.publish").on("click", function (e) {
  var id = $(this).data("id");
  var button = $(this);

  $.ajax({
    url: "/admin/publish-article.php",
    type: "POST",
    data: { id: id },
  })
    .done(function (data) {
      //When Ajax request is done it returns a time element containing a new publish date
      button.parent().html(data);
    })
    .fail(function (data) {
      alert("An error occurred");
    });
});

/**
 * Show the date and time picker for the published at field
 */
$("#published_at").datetimepicker({ format: "Y-m-d H:i:s" });

/**
 * Validating the contact form
 */
$("#formContact").validate({
  rules: {
    email: {
      required: true,
      email: true,
    },
    subject: {
      required: true,
    },
    message: {
      required: true,
    },
  },
});
