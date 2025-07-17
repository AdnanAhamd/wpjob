let slideIndex = 1;
  includedLanguages: 'ar,en';

  function plusSlides(n) {
    showSlides(slideIndex += n);
  }

  function showSlides(n) {
    const slides = document.getElementsByClassName("mySlides");
    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }
    for (let i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }
    slides[slideIndex - 1].style.display = "block";
  }

  // Show the first slide manually
  showSlides(slideIndex);

// sign up form
