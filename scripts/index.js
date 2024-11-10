// Карусель
const track = document.querySelector(".carousel-track");
const items = document.querySelectorAll(".carousel-item");
const prevButton = document.querySelector(".prev");
const nextButton = document.querySelector(".next");

let index = 0;
const itemWidth = items[0].offsetWidth;
const visibleItems = Math.floor(track.offsetWidth / itemWidth); // Количество видимых элементов

prevButton.addEventListener("click", () => {
  if (index > 0) {
    index = Math.max(index - 1, 0);
    updateTrack();
    updateButtonsState();
  }
});

nextButton.addEventListener("click", () => {
  if (index < items.length - 5) {
    index = Math.min(index + 1, items.length - visibleItems);
    updateTrack();
    updateButtonsState();
  }
});

function updateTrack() {
  const offsetX = -index * itemWidth;
  track.style.transform = `translateX(${offsetX}px)`;
}

function updateButtonsState() {
  if (index === 0) {
    prevButton.classList.add("disabled");
  } else {
    prevButton.classList.remove("disabled");
  }

  if (index >= items.length - visibleItems) {
    nextButton.classList.add("disabled");
  } else {
    nextButton.classList.remove("disabled");
  }
}
