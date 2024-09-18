function initListeners() {
  burgerListener();
  messageListener();
}

$(document).ready(function () {
  initListeners();
});

function burgerListener() {
  $("#burger").on("click", () => {
    if ($("#burger").hasClass("burger-off")) {
      $("#burger").addClass("burger-on").removeClass("burger-off");
      $("#drawer").addClass("show-menu").removeClass("close-menu");
    } else if ($("#burger").hasClass("burger-on")) {
      $("#burger").addClass("burger-off").removeClass("burger-on");
      $("#drawer").addClass("close-menu").removeClass("show-menu");
    }
  });
}

function messageListener() {
  $("#nav-message").on("click", () => {
    $("#nav-message").addClass("hidden");
  });
}

function showEdit(id) {
  $("#user-info-" + id).addClass("hidden");
  $("#user-inputs-" + id).removeClass("hidden");
}

function hideEdit(id) {
  $("#user-info-" + id).removeClass("hidden");
  $("#user-inputs-" + id).addClass("hidden");
}

function deleteModal(id) {
  window.scrollTo(0, 0);

  $("#modal").html(`
      <div class="bg-white rounded-md p-5 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-105 text-center">
        <p>Are you sure you want to delete this post?</p>
        <div class="flex items-center justify-center">
          <button id="deleteCancel" onclick="closeModal(this, event)" class="form-button mr-2">Cancel</button>
          <a href="/posts/delete/${id}" class="form-button hover:bg-red-400 hover:text-white">Delete</a>
        </div>
      </div>  
    `);

  openModal();
}

function openModal() {
  $("#modal").removeClass("hidden");
  $("body").addClass("overflow-hidden");
}

function closeModal(element, event) {
  if (event.target.id === "modal" || event.target.id === "deleteCancel") {
    $("#modal").addClass("hidden");
    $("body").removeClass("overflow-hidden");
  }
}
