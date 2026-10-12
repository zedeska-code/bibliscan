/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");

//burger menu
window.onload = () => {
    const burgerButton = document.querySelector("#burger-button");
    const burgerList = document.querySelector("#burger-list");

    burgerButton.addEventListener("click", (event) => {
        event.stopPropagation();
        burgerList.nextElementSibling.classlist.toggle("hidden");
    });

    document.addEventListener("click", () => {
        burgerButton.nextElementSibling.classList.remove("show");
    });
};
