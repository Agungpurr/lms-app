// Apply theme immediately (prevent flash)
if (localStorage.getItem("theme") === "light") {
    document.documentElement.classList.add("light");
}

function toggleTheme() {
    const isLight = document.documentElement.classList.toggle("light");
    localStorage.setItem("theme", isLight ? "light" : "dark");
    const btn = document.getElementById("theme-btn");
    if (btn) btn.textContent = isLight ? "☀️" : "🌙";
}
