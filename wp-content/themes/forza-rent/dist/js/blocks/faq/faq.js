(()=>{document.addEventListener("DOMContentLoaded",function(){let e=Array.from(document.querySelectorAll(".faq-section-wrapper--box-item"));e.length&&(console.log(e),e.forEach(t=>{t.addEventListener("click",function(){let c=t.classList.contains("active");e.forEach(n=>{n.classList.remove("active")}),c||t.classList.add("active")})}))});})();
//# sourceMappingURL=faq.js.map
