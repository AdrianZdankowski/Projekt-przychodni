const button = document.querySelector("#copytext");
const text = document.querySelector("#newpassword");

button.addEventListener("click", () => {
    text.select();
    document.execCommand("copy");
});



    