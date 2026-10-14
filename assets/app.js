/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
console.log("js is running");
//burger menu
const burgerButton = document.querySelector("#burger-button");
const burgerList = document.querySelector("#burger-list");
burgerButton.addEventListener("click", (event) => {
    event.stopPropagation();
    burgerList.classList.toggle("hidden");
    console.log("clicked");
});

document.addEventListener("click", (event) => {
    {
        if (!burgerList.contains(event.target))
            burgerList.classList.add("hidden");
    }
});
