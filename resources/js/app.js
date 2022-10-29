import "./bootstrap";

import axios from "axios";

let formImage = document.getElementById("form-get-image");
let input = document.getElementById("key");
let displayImage = document.getElementById("display-image");
let notFound = document.getElementById("notFound");
let imageSource = document.getElementById("imageSource");
let numberImages =document.getElementById('numberImages');
let totalSize =document.getElementById('totalSize');
let policy =document.getElementById('policy');
let hitRate =document.getElementById('hitRate');
let missRate =document.getElementById('missRate');
let clear_cache = document.getElementById('clear_cache');
let alert_clear_cache = document.getElementById('alert-clear-cache');
let alert_session = document.getElementById('alert-session');


if (formImage != null) {
    formImage.addEventListener("submit", function (e) {
        e.preventDefault();
        let bodyFormData = new FormData();
        bodyFormData.append("key", input.value);
        input.value = "";
        axios.post(this.action, bodyFormData).then(function (data) {
            if (data.status == 200) {
                if (data.data == "not found") {
                    notFound.style.display = "block";
                    displayImage.style.display = "none";
                } else {
                    displayImage.style.display = "block";
                    notFound.style.display = "none";
                    displayImage.src = '';
                    displayImage.src = data.data.imageSource == 'cache' ? "data:image/png;base64, " + data.data.image : "/uploads/" + data.data.image;
                    imageSource.innerHTML = data.data.imageSource;
                    console.log(data.data.cache);
                    console.log("total size: " + data.data.totalCacheSize);
                    console.log("total capacity: " + data.data.cacheCapacity);
                }
            }
        });
    });
}
if(clear_cache !=null){
    alert_clear_cache.style.display = 'none';
    clear_cache.addEventListener('click',(e) => {
        e.preventDefault();
        axios.post("/clearCache",[]).then(function (data) {
            if (data.status == 200) {
                console.log("clear Cache");
                alert_clear_cache.style.display = 'block';
                if(alert_session)
                    alert_session.style.display = 'none';
                alert_clear_cache.textContent = 'cache cleared success';
                setTimeout(() => {
                    location.reload();
                }, 1200);
            }
        });
    });
}

setInterval(() => {
    let bodyFormData = new FormData();
    axios.post("/storeStatistics",bodyFormData).then(function (data) {
        if (data.status == 200) console.log("statistics stored");
    });
}, 5000);



// setInterval(() => {
//     axios.get("/getStatistics").then(function (data) {
//         let statistics = data.data.statistics;
//         if(numberImages)
//             numberImages.textContent = statistics.number_of_items;
//         if(totalSize)
//             totalSize.textContent = statistics.total_items_size + 'MB';
//         if(policy)
//             policy.textContent = data.data.policy == 2 ? 'Least Recently used Replacement' : 'Random Replacement';
//         if(hitRate)
//             hitRate.textContent = statistics.hit_rate + '%';
//         if(missRate)
//             missRate.textContent = statistics.miss_rate + '%';
//     });
// }, 1000*60);
