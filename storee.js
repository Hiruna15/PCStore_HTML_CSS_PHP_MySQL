const offerSliderEl = document.querySelector(".offer-slider");
const slideBtn = document.querySelector(".slide-btn");
const slideBtnReverce = document.querySelector(".slide-btn-reverce");
const offerItems = document.querySelectorAll(".offer-item");

let translationValue = 0;
const maxTranslationValue = (offerItems.length - 1) * offerItems[0].offsetWidth;

const updateTranslation = () => {
  offerSliderEl.style.transform = `translateX(${translationValue}px)`;
};

slideBtn.addEventListener("click", () => {
  if (offerItems.length > 1 && translationValue > -maxTranslationValue) {
    translationValue -= offerItems[0].offsetWidth;
    updateTranslation();
  } else {
    // Reset to the first item
    translationValue = 0;
    updateTranslation();
  }
});

slideBtnReverce.addEventListener("click", () => {
  if (offerItems.length > 1 && translationValue < 0) {
    translationValue += offerItems[0].offsetWidth;
    updateTranslation();
  } else {
    // Go to the last item
    translationValue = -maxTranslationValue;
    updateTranslation();
  }
});
