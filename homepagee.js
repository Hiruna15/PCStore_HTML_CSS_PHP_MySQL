const scrollBtnEl = document.querySelector(".scroll-btn");
const scrollBtnLeftEl = document.querySelector(".scroll-btn-left");
const itemsEl = document.querySelector(".items");
const body = document.body;
const bodyWidth = body.clientWidth - 70;

scrollBtnEl.addEventListener("click", () => {
  itemsEl.scrollTo({
    left: itemsEl.scrollLeft + bodyWidth,
    behavior: "smooth",
  });
});

scrollBtnLeftEl.addEventListener("click", () => {
  itemsEl.scrollTo({
    left: itemsEl.scrollLeft - bodyWidth,
    behavior: "smooth",
  });
});

itemsEl.addEventListener("wheel", (event) => {
  if (event.deltaX !== 0) {
    event.preventDefault();
  }
});
