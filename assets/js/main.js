document.addEventListener("DOMContentLoaded", function () {
  const currentPath = window.location.pathname;
  const links = document.querySelectorAll(".navItems a");

  links.forEach((link) => {
    if (link.getAttribute("href") === currentPath) {
      link.classList.add("active");
      link.setAttribute("aria-current", "page");
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const toggleIcon = document.getElementById("mobileToggle");
  const navMenu = document.getElementById("navMenu");

  toggleIcon.addEventListener("click", function () {
    navMenu.classList.toggle("active");
  });
});
function setActiveSidebarLink() {
  const currentPath = window.location.pathname;
  const sidebarLinks = document.querySelectorAll(".sidebarNav a");

  sidebarLinks.forEach((link) => {
    link.classList.remove("active");
    link.removeAttribute("aria-current");

    if (link.getAttribute("href") === currentPath) {
      link.classList.add("active");
      link.setAttribute("aria-current", "page");
    }
  });
}

document.addEventListener("DOMContentLoaded", setActiveSidebarLink);

const handleStudentCorner = () => {
  window.location.pathname = "/student-corner.html";
};
const handleLogin = () => {
  window.location.pathname = "/dashboard/";
};
