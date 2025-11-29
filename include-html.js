async function loadIncludes(callback) {
  const includeElements = document.querySelectorAll("[include-html]");
  let total = includeElements.length;
  let loaded = 0;

  includeElements.forEach(async (el) => {
    const file = el.getAttribute("include-html");
    try {
      const response = await fetch(file);
      el.innerHTML = await response.text();
    } catch (e) {
      el.innerHTML = "Failed to load " + file;
    }

    loaded++;
    if (loaded === total && typeof callback === "function") {
      callback(); // all includes finished
    }
  });
}
loadIncludes(() => {
  initMenu();
});
