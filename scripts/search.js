const input = document.querySelector(".search-container input");
const searchContainer = document.querySelector(".search-container");

input.addEventListener("focus", function () {
  searchContainer.classList.add("focused");
});

input.addEventListener("blur", function () {
  searchContainer.classList.remove("focused");
});
