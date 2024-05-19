"use strict";
(() => {
   new SmoothScroll('a[href*="#"]', {
      speed: 300, // scroll speed in milliseconds
      easing: "easeInOutCubic", // easing function
      offset: 80, // distance between the top of the page and the target element
   });

   let currentScroll = 0;
   const navbar = document.getElementById("navbar");
   const dropdownMenuButton = document.getElementById("dropdownMenuButton");

   window.addEventListener("scroll", function () {
      currentScroll = window.pageYOffset;
      if (navbar && currentScroll > 100) {
         navbar.classList.add("shadow-card", "bg-white", "dark:bg-gray-900");
      } else {
         navbar.classList.remove("shadow-card", "bg-white", "dark:bg-gray-900");
      }
   });

   if (navbar && dropdownMenuButton) {
      dropdownMenuButton.addEventListener("click", () => {
         if (
            currentScroll < 100 &&
            navbar.classList.contains("bg-white") &&
            navbar.classList.contains("dark:bg-gray-900")
         ) {
            navbar.classList.remove("bg-white", "dark:bg-gray-900");
         } else {
            navbar.classList.add("bg-white", "dark:bg-gray-900");
         }
      });
   }

   // light mode or dark mode handler
   const selectedTheme = localStorage.getItem("theme_mode");
   let lightDarkButton = document.querySelectorAll(".lightOrDarkMode");

   if (selectedTheme === "dark") {
      let body = document.querySelector("html");
      body.classList.remove("light");
   } else {
      let body = document.querySelector("html");
      body.classList.add("light");
   }

   if (lightDarkButton) {
      lightDarkButton.forEach((element) => {
         element.addEventListener("click", () => {
            let body = document.querySelector("html");
            const theme = localStorage.getItem("theme_mode");

            if (body.classList.contains("dark")) {
               body.classList.remove("dark");
            } else {
               body.classList.add("dark");
            }

            if (theme === "dark") {
               localStorage.setItem("theme_mode", "light");
            } else {
               localStorage.setItem("theme_mode", "dark");
            }
         });
      });
   }

   // Video control
   const videoPlay = document.getElementById("videoPlay");
   if (videoPlay) {
      videoPlay.addEventListener("click", () => {
         const videoPlayer = document.getElementById("videoPlayer");
         const videoThumbnail = document.getElementById("videoThumbnail");
         videoThumbnail.classList.add("hidden");
         videoPlayer.classList.remove("hidden");
         videoPlayer.classList.remove("block");
         videoPlayer.querySelector("video").play();
      });
   }

   // Templates slider
   new Swiper(".swiper-1", {
      // Optional parameters
      direction: "horizontal",
      loop: true,
      slidesPerView: 3,
      spaceBetween: 30,
      autoplay: {
         delay: 0,
         disableOnInteraction: false,
      },
      speed: 6000,
      freeMode: true,

      // Navigation arrows
      navigation: {
         nextEl: ".swiper-button-next",
         prevEl: ".swiper-button-prev",
      },

      // And if we need scrollbar
   });

   new Swiper(".swiper-container", {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 30,
      speed: 2000,
      freeMode: true,
      pagination: {
         el: ".swiper-pagination",
         clickable: true,
      },
      autoplay: {
         delay: 2000,
         disableOnInteraction: false,
      },
      breakpoints: {
         768: {
            slidesPerView: 2,
         },
         1024: {
            slidesPerView: 3,
         },
      },
   });
})();
