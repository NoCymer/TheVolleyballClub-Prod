const users=document.querySelectorAll(".user-display");users.forEach((e=>{const r=e.querySelector(".user-id");e.addEventListener("click",(e=>{window.location=`/dashboard/members/view?user=${r.innerHTML}`}))}));