const modal = document.getElementById("modal");
const user = document.getElementById("user");

// Элементы для форм
const loginEmailInput = document.getElementById("login-email");
const loginPasswordInput = document.getElementById("login-password");
const registrationEmailInput = document.getElementById("registration-email");
const registrationPasswordInput = document.getElementById(
  "registration-password"
);
const confirmRegistrationPasswordInput =
  document.getElementById("confirm-password");
const confirmLogout = document.getElementById("confirmLogout");

// Открытие и закрытие модального окна
user.addEventListener("click", (event) => {
  modal.style.display = "block";
  event.stopPropagation();
});

document.querySelectorAll(".close").forEach((button) => {
  button.addEventListener("click", (event) => {
    modal.style.display = "none";
    event.stopPropagation();
  });
});

modal.addEventListener("click", (event) => {
  if (
    event.target.closest(".modal") &&
    !event.target.closest(".modal-content")
  ) {
    modal.style.display = "none";
    event.stopPropagation();
  }
});

// Скрытие/раскрытие пароля
const togglePasswordVisibility = (input, button) => {
  button.addEventListener("click", () => {
    input.type = input.type === "password" ? "text" : "password";
    button.innerHTML =
      input.type === "text"
        ? '<i class="fa fa-eye" style="font-size: 20px"></i>'
        : '<i class="fa fa-eye-slash" style="font-size: 20px"></i>';
  });
};

togglePasswordVisibility(
  loginPasswordInput,
  document.getElementById("login-togglePassword")
);
togglePasswordVisibility(
  registrationPasswordInput,
  document.getElementById("registration-togglePassword")
);
togglePasswordVisibility(
  confirmRegistrationPasswordInput,
  document.getElementById("confirm-togglePassword")
);

// Переключение между формами
document
  .getElementById("show-registration")
  .addEventListener("click", (event) => {
    event.preventDefault();
    document.querySelector(".login").style.display = "none";
    document.querySelector(".registration").style.display = "block";
  });

document.getElementById("show-login").addEventListener("click", (event) => {
  event.preventDefault();
  document.querySelector(".registration").style.display = "none";
  document.querySelector(".login").style.display = "block";
});

// Общая функция для обработки ошибок
const displayErrors = (errors) => {
  // Очистка всех полей от предыдущих ошибок
  document.querySelectorAll(".input-container").forEach((container) => {
    container.classList.remove("input-container-error"); // Убираем класс ошибки
  });

  document.querySelectorAll(".error-message").forEach((msg) => {
    msg.textContent = ""; // Очищаем текст ошибок
    msg.style.display = "none"; // Скрываем поле с ошибкой
  });

  // Обработка ошибок, если они есть
  for (const key in errors) {
    const inputContainer = document.getElementById(`${key}`).parentElement; // Находим родительский div
    const errorMessageElement = document.getElementById(`${key}-error-message`); // Находим элемент для вывода ошибки

    if (inputContainer) {
      inputContainer.classList.add("input-container-error"); // Добавляем класс ошибки для контейнера
    }

    if (errorMessageElement) {
      errorMessageElement.textContent = errors[key]; // Устанавливаем текст ошибки
      errorMessageElement.style.display = "block"; // Отображаем поле с ошибкой
    }
  }
};

// Обработка отправки формы входа
document
  .querySelector(".login form")
  .addEventListener("submit", async (event) => {
    event.preventDefault();

    const formData = {
      "login-email": loginEmailInput.value,
      "login-password": loginPasswordInput.value,
    };

    try {
      const response = await fetch("validate_login.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify(formData),
      });
      const data = await response.json();

      if (data.errors) {
        displayErrors(data.errors);
      } else if (data.success) {
        modal.style.display = "none";

        if (data.redirect) {
          window.location.href = data.redirect;
        }
      }
    } catch (error) {
      console.error("Error:", error);
    }
  });

// Обработка отправки формы регистрации
document
  .querySelector(".registration form")
  .addEventListener("submit", async (event) => {
    event.preventDefault();

    const formData = {
      "registration-email": registrationEmailInput.value,
      "registration-password": registrationPasswordInput.value,
      "confirm-password": confirmRegistrationPasswordInput.value,
    };

    try {
      const response = await fetch("validate_registration.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify(formData),
      });
      const data = await response.json();

      if (data.errors) {
        displayErrors(data.errors);
      } else if (data.success) {
        modal.style.display = "none";

        if (data.redirect) {
          window.location.href = data.redirect;
        }
      }
    } catch (error) {
      console.error("Error:", error);
    }
  });
