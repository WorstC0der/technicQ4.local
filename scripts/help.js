// Выпадающее меню со сдвигом (аккордеон)
document.addEventListener("DOMContentLoaded", function () {
  // Код для аккордеона
  var helpCards = document.querySelectorAll(".help-card");

  helpCards.forEach(function (helpCard) {
    var h4 = helpCard.querySelector("h4");

    h4.addEventListener("click", function () {
      var accordion = helpCard.querySelector(".accordion");
      var arrow = h4.querySelector(".arrow");

      if (accordion.classList.contains("active")) {
        accordion.classList.remove("active");
        helpCard.style.height = "60px";
        arrow.classList.remove("opened");
        h4.classList.remove("opened");
        accordion.classList.add("closing"); // для моментального закрытия без анимации
      } else {
        accordion.classList.remove("closing");
        accordion.classList.add("active");
        helpCard.style.height = "auto";
        arrow.classList.add("opened");
        h4.classList.add("opened");
      }
    });
  });

  // Проверка хэша URL и открытие соответствующего аккордеона
  var hash = window.location.hash;
  if (hash) {
    var helpCard = document.getElementById(hash.substring(1));
    if (helpCard && helpCard.classList.contains("help-card")) {
      var accordion = helpCard.querySelector(".accordion");
      var arrow = helpCard.querySelector("h4 .arrow");

      if (accordion && arrow) {
        accordion.classList.add("active");
        helpCard.style.height = "auto";
        arrow.classList.add("opened");
        arrow.parentElement.classList.add("opened");
      }
    }
  }
});
