import {axios} from './axios';

let formImage = document.getElementById("form-get-image");
let input = document.getElementById('key');
formImage.addEventListener("submit", function (e) {
    e.preventDefault();
    let bodyFormData = new FormData();
    bodyFormData.append("key", input.value);
    input.value = "";
    axios.post(this.action,bodyFormData).then(function (data) {
        console.log(data);
    });
});
