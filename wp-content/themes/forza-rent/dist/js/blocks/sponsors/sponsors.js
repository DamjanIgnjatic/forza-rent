(()=>{document.addEventListener("DOMContentLoaded",function(){let t=document.querySelector(".track");if(!t)return;let o=.9,r=t.innerHTML;t.innerHTML+=r;let n=0,e=()=>{n-=o,t.style.transform=`translateX(${n}px)`,Math.abs(n)>=t.scrollWidth/2&&(n=0),requestAnimationFrame(e)};e()});})();
//# sourceMappingURL=sponsors.js.map
