document.addEventListener("DOMContentLoaded", function() {
    // Render poliklinik data ke dalam elemen HTML
    let container = document.getElementById("poliklinik-container");
    polikliniks.forEach(function(poliklinik) {
        let item = document.createElement("div");
        item.classList.add("dashboard-item");
        item.style.backgroundColor = poliklinik.color;

        // Icon
        let icon = document.createElement("i");
        icon.classList.add("icon", "fa", `fa-${poliklinik.icon}`);
        
        // Name
        let name = document.createElement("span");
        name.textContent = poliklinik.name;

        // Append icon and name to the item
        item.appendChild(icon);
        item.appendChild(name);

        // Append item to the container
        container.appendChild(item);
    });
});
