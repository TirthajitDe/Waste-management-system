const signUpButton = document.getElementById('signUpButton');
const signInButton = document.getElementById('signInButton');
const signIn = document.getElementById('signIn');
const signUp = document.getElementById('signup');

signUpButton.onclick = () => {
    signIn.classList.remove("active");
    signUp.classList.add("active");
};

signInButton.onclick = () => {
    signUp.classList.remove("active");
    signIn.classList.add("active");
};