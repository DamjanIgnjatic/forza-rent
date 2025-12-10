(()=>{document.addEventListener("DOMContentLoaded",function(){let c=document.getElementById("featuredImage"),t=document.querySelectorAll(".car-gallery-items .item");t.forEach(e=>{e.addEventListener("click",()=>{let a=e.querySelector("img");c.src=a.src,t.forEach(n=>n.classList.remove("active")),e.classList.add("active")})})});})();
//# sourceMappingURL=hero-global.js.map
