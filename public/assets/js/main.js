const menuBtn = document.getElementById("menuBtn");
const mobileMenu = document.getElementById("mobileMenu");
const overlay = document.getElementById("overlay");
const closeMenu = document.getElementById("closeMenu");

if (menuBtn && mobileMenu && overlay && closeMenu) {
  menuBtn.addEventListener("click", () => {
    mobileMenu.style.left = "0";
    overlay.classList.remove("opacity-0", "pointer-events-none");
  });

  function closeMenuFunc() {
    mobileMenu.style.left = "-100%";
    overlay.classList.add("opacity-0", "pointer-events-none");
  }

  closeMenu.addEventListener("click", closeMenuFunc);
  overlay.addEventListener("click", closeMenuFunc);
}
