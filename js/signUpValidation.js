const passwordField=document.querySelector("#password-field"),passwordFieldConfirm=document.querySelector("#confirm-password-field"),policiesConfirm=document.querySelector("#policies-field"),signUpForm=document.querySelector("#sign-up");signUpForm.addEventListener("submit",(e=>{passwordField.value!=passwordFieldConfirm.value&&e.preventDefault(),policiesConfirm.checked||e.preventDefault()}));