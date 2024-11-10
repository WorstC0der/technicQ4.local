// Header при скролле фиксируется
var headerPlaceholder = document.getElementById("header-placeholder");
var header = document.getElementById("header-container");
var scrollThreshold = 48;

window.addEventListener(
  "scroll",
  function () {
    var currentScroll = window.scrollY || window.pageYOffset;

    if (currentScroll > scrollThreshold) {
      // Прокрутка вниз
      headerPlaceholder.style.display = "block";
      header.classList.add("fixed");
    } else if (currentScroll <= scrollThreshold) {
      // Прокрутка вверх
      headerPlaceholder.style.display = "none";
      header.classList.remove("fixed");
    }
  },
  false
);
