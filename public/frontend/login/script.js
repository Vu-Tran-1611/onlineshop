const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

// Check the condition
const signUpForm = document.querySelector('.sign-up form');
const logInForm = document.querySelector('.sign-in form');
const signUpInputs = document.querySelector('.sign-up input');
const logInInputs = document.querySelector('.sign-in input');

// // Set event if user click  on sign up button
// signUpForm.addEventListener('submit', function (e) {
//     e.preventDefault();
//     const signUpInputs = document.querySelectorAll('.sign-up input');
//     // If there are empty fields
//     if (checkEmptyInputs(signUpInputs)) {
//         alert('Please fill in all fields');
//         return;
//     }

//     // Check email input value
//     const emailInput = document.getElementById("email");
//     const email = emailInput.value;
//     // Check if the email is valid
//     if (!isValidEmail(email)) {
//         alert('Please enter a valid email address');
//         return;
//     }

//     // Check username input value
//     const nameInput = document.getElementById('name');
//     const name = nameInput.value.trim();    // Loại bỏ khoảng trắng đầu và cuối
//     // Kiểm tra tên có hợp lệ không
//     if (!isValidName(name)) {
//         alert('Please enter a valid name (only alphabetic characters)');
//         return;
//     }
// });

// // Set event if user click on log in button
// logInForm.addEventListener('submit', function (e) {
//     e.preventDefault();
//     const logInInputs = document.querySelectorAll('.sign-in input');
//     // If there are empty fields
//     if (checkEmptyInputs(logInInputs)) {
//         alert('Please fill in all fields');
//         return;
//     }
// });
