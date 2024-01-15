const currentDateElement = document.getElementById("currentDate");
const options = { day: "2-digit", month: "2-digit", year: "numeric" };
const formattedDate = new Date().toLocaleDateString(undefined, options);
currentDateElement.textContent = formattedDate;