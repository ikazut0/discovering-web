var currentTime = new Date().getHours();

var greetingElement = document.getElementById('greeting-admin');

var greeting;
if (currentTime >= 5 && currentTime < 12) {
    greeting = 'ДОБРОГО РАНКУ 👋';
} else if (currentTime >= 12 && currentTime < 18) {
    greeting = 'ДОБРОГО ДНЯ 👋';
} else {
    greeting = 'ДОБРОГО ВЕЧОРА 👋';
}

greetingElement.textContent = '👋 aPanel - ' + greeting;