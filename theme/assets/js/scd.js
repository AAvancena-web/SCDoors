/*! SC Garage Doors 2026 front end. Built from the approved static design. */

(function(){
  "use strict";

  /* sticky header shadow */
  var header = document.getElementById("scdHeader");
  window.addEventListener("scroll", function(){
    header.classList.toggle("scd-is-stuck", window.scrollY > 10);
  }, {passive:true});

  /* mobile menu */
  var burger = document.getElementById("scdBurger");
  var mobileNav = document.getElementById("scdDrawer");
  burger.addEventListener("click", function(){
    var open = mobileNav.classList.toggle("scd-is-open");
    burger.classList.toggle("scd-is-open", open);
    burger.setAttribute("aria-expanded", open ? "true" : "false");
    document.body.classList.toggle("scd-menu-open", open);
    document.documentElement.classList.toggle("scd-menu-open", open);
  });
  function closeMenu(){
    mobileNav.classList.remove("scd-is-open");
    burger.classList.remove("scd-is-open");
    burger.setAttribute("aria-expanded","false");
    document.body.classList.remove("scd-menu-open");
    document.documentElement.classList.remove("scd-menu-open");
  }
  mobileNav.addEventListener("click", function(e){
    if(e.target.closest("a")) closeMenu();
  });
  document.addEventListener("keydown", function(e){
    if(e.key === "Escape" && mobileNav.classList.contains("scd-is-open")){
      closeMenu();
      burger.focus();
    }
  });

  /* a resize past the drawer breakpoint must not leave the page locked */
  window.addEventListener("resize", function(){
    if(window.innerWidth > 1040 && mobileNav.classList.contains("scd-is-open")) closeMenu();
  });

  /* drawer submenu, the label still navigates, the caret expands */
  document.querySelectorAll(".scd-m-toggle").forEach(function(btn){
    btn.addEventListener("click", function(e){
      e.stopPropagation();
      var item = btn.closest(".scd-has-sub");
      var open = item.classList.toggle("scd-is-open");
      btn.setAttribute("aria-expanded", open ? "true" : "false");
    });
  });

  /* FAQ accordion */
  document.querySelectorAll(".scd-faq-item button").forEach(function(btn){
    btn.addEventListener("click", function(){
      var item = btn.parentElement;
      var open = item.classList.toggle("scd-is-open");
      btn.setAttribute("aria-expanded", open ? "true" : "false");
    });
  });

  /* form demo handling, replace with Contact Form 7 in WordPress */
  document.querySelectorAll("form").forEach(function(form){
    form.addEventListener("submit", function(e){
      e.preventDefault();
      var valid = true;
      form.querySelectorAll("[required]").forEach(function(input){
        if(!input.value.trim()){
          valid = false;
          input.style.borderColor = "#d64545";
        } else {
          input.style.borderColor = "";
        }
      });
      if(!valid) return;
      var note = form.querySelector(".form-success");
      if(note) note.classList.add("is-visible");
      form.reset();
    });
  });

  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* ---------- reveal on scroll, staggered within each group ---------- */
  if("IntersectionObserver" in window && !reduced){
    var io = new IntersectionObserver(function(entries){
      entries.forEach(function(entry){
        if(!entry.isIntersecting) return;
        var el = entry.target;
        var siblings = Array.prototype.slice.call(el.parentNode.children).filter(function(n){
          return n.classList && n.classList.contains("scd-reveal");
        });
        var i = Math.max(0, siblings.indexOf(el));
        el.style.transitionDelay = Math.min(i, 6) * 90 + "ms";
        el.classList.add("scd-is-in");
        io.unobserve(el);
      });
    }, {threshold:0.1, rootMargin:"0px 0px -60px 0px"});
    document.querySelectorAll(".scd-reveal").forEach(function(el){ io.observe(el); });
  } else {
    document.querySelectorAll(".scd-reveal").forEach(function(el){ el.classList.add("scd-is-in"); });
  }

  /* ---------- scroll progress and back to top ---------- */
  var bar = document.getElementById("scdProgress");
  var toTop = document.getElementById("scdToTop");
  var heroBg = document.getElementById("scdHeroBg");
  var ticking = false;

  function onScroll(){
    var y = window.scrollY || window.pageYOffset;
    var max = document.documentElement.scrollHeight - window.innerHeight;
    if(bar) bar.style.width = (max > 0 ? (y / max) * 100 : 0) + "%";
    if(toTop) toTop.classList.toggle("scd-is-shown", y > 700);
    /* gentle parallax, the banner drifts slower than the copy */
    if(heroBg && !reduced && y < window.innerHeight * 1.4){
      heroBg.style.transform = "translate3d(0," + (y * 0.18) + "px,0)";
    }
    ticking = false;
  }
  window.addEventListener("scroll", function(){
    if(!ticking){ ticking = true; window.requestAnimationFrame(onScroll); }
  }, {passive:true});
  onScroll();

  if(toTop){
    toTop.addEventListener("click", function(){
      window.scrollTo({top:0, behavior: reduced ? "auto" : "smooth"});
    });
  }

  /* ---------- count up the stats when they come into view ---------- */
  var counters = document.querySelectorAll("[data-count]");
  if(counters.length && "IntersectionObserver" in window && !reduced){
    /* the markup already carries the final figure, so it is correct without
       JS. Only zero it once we know we are going to animate it up. */
    counters.forEach(function(el){
      var dec = parseInt(el.getAttribute("data-decimals") || "0", 10);
      el.textContent = (0).toFixed(dec) + (el.getAttribute("data-suffix") || "");
    });
    var cio = new IntersectionObserver(function(entries){
      entries.forEach(function(entry){
        if(!entry.isIntersecting) return;
        var el = entry.target;
        cio.unobserve(el);
        var target = parseFloat(el.getAttribute("data-count")) || 0;
        var dec = parseInt(el.getAttribute("data-decimals") || "0", 10);
        var suffix = el.getAttribute("data-suffix") || "";
        var started = null, dur = 1500;
        function tick(now){
          if(started === null) started = now;
          var p = Math.min((now - started) / dur, 1);
          var eased = 1 - Math.pow(1 - p, 3);
          el.textContent = (target * eased).toFixed(dec) + suffix;
          if(p < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
      });
    }, {threshold:0.5});
    counters.forEach(function(el){ cio.observe(el); });
  }
})();
