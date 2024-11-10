let hoverInterval;
const logo = document.querySelector(".logo");
const initialGradient = window
  .getComputedStyle(logo)
  .getPropertyValue("background-image");

function startGradient() {
  let degree = 0;
  hoverInterval = setInterval(() => {
    degree = (degree + 1) % 360;
    const color1 = `hsl(${degree + 197}, 100%, 24%)`;
    const color2 = `hsl(${(degree + 177) % 360}, 89%, 46%)`;
    logo.style.backgroundImage = `linear-gradient(to top, ${color1}, ${color2})`;
  }, 50);
}

function stopGradient() {
  clearInterval(hoverInterval);
  logo.style.backgroundImage = initialGradient;
}
