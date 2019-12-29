document.addEventListener('DOMContentLoaded', () =>
    requestAnimationFrame(updateTime)
);

function updateTime() {

    moment.locale('pl');
    var dateObj = new Date();
    var month = dateObj.getMonth() + 1; //months from 1-12
    var day = dateObj.getDate();

    document.documentElement.style.setProperty('--timer-day', "'" + day + "." + month);
    document.documentElement.style.setProperty('--timer-hours', "'" + moment().format("H") + "'");
    document.documentElement.style.setProperty('--timer-minutes', "'" + moment().format("mm") + "'");
    document.documentElement.style.setProperty('--timer-seconds', "'" + moment().format("ss") + "'");
    requestAnimationFrame(updateTime);
}