const glassyElements=document.querySelectorAll(".glassy");glassyElements.forEach((e=>{let t=e.querySelector(".glass-effect-container");null==t&&(t=document.createElement("div"),t.classList.add("glass-effect-container"),e.appendChild(t));let l=t.querySelector(".glass-effect");null==l&&(l=document.createElement("span"),l.classList.add("glass-effect"),l.classList.add("hidden"),t.appendChild(l)),window.addEventListener("mousemove",(e=>{const s=t.getBoundingClientRect(),n=l.getBoundingClientRect();if(!(e.clientX>s.x&&s.x+s.width>e.clientX&&e.clientY>s.y&&s.y+s.height>e.clientY))return void l.classList.add("hidden");l.classList.remove("hidden");const c=e.clientX-(s.left+n.width/2),i=e.clientY-(s.top+n.height/2);l.style.top=i+"px",l.style.left=c+"px"}))}));