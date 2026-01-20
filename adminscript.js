const body = document.querySelector("body");
const darkLight = document.querySelector("#darkLight");
const sidebar = document.querySelector(".sidebar");
const submenuItems = document.querySelectorAll(".submenu_item");
const sidebarOpen = document.querySelector("#sidebarOpen");
const sidebarClose = document.querySelector(".collapse_sidebar");
const sidebarExpand = document.querySelector(".expand_sidebar");
sidebarOpen.addEventListener("click", () => sidebar.classList.toggle("close"));

sidebarClose.addEventListener("click", () => {
  sidebar.classList.add("close", "hoverable");
});
sidebarExpand.addEventListener("click", () => {
  sidebar.classList.remove("close", "hoverable");
});

sidebar.addEventListener("mouseenter", () => {
  if (sidebar.classList.contains("hoverable")) {
    sidebar.classList.remove("close");
  }
});
sidebar.addEventListener("mouseleave", () => {
  if (sidebar.classList.contains("hoverable")) {
    sidebar.classList.add("close");
  }
});

darkLight.addEventListener("click", () => {
  body.classList.toggle("dark");
  if (body.classList.contains("dark")) {
    document.setI;
    darkLight.classList.replace("bx-sun", "bx-moon");
  } else {
    darkLight.classList.replace("bx-moon", "bx-sun");
  }
});

submenuItems.forEach((item, index) => {
  item.addEventListener("click", () => {
    item.classList.toggle("show_submenu");
    submenuItems.forEach((item2, index2) => {
      if (index !== index2) {
        item2.classList.remove("show_submenu");
      }
    });
  });
});

if (window.innerWidth < 768) {
  sidebar.classList.add("close");
} else {
  sidebar.classList.remove("close");
}
document.addEventListener("DOMContentLoaded", function () {
  // Get the "Subject" menu item
  const subjectMenuItem = document.querySelector('.navlink[data-name="Subject"]');

  // 
  document.addEventListener("DOMContentLoaded", function () {
    // Get the "Subject" menu item
    const subjectMenuItem = document.querySelector('.navlink[data-name="Subject"]');
  
    // Add a click event listener to the "Subject" menu item
    subjectMenuItem.addEventListener("click", function () {
      // Redirect to subject.html when clicked
      window.location.href = "subject.html";
    });
  });
  document.addEventListener("DOMContentLoaded", function() {
    const profileImg = document.getElementById("profileImg");
    const logoutBtn = document.getElementById("logoutBtn");
  
    profileImg.addEventListener("click", function() {
      logoutBtn.style.display = "block";
    });
  
  
        
    });
  
});
