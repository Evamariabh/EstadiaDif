const INFO = 1;
const MSG = 0;
const WARING = 2;
const ERROR = 3;
const OK = 4;
const UPDATE = 5;
const DELETE = 6;
const SPINNER = 7;

const NOTIFY_SHORT_DURATION = 1500;
const NOTIFY_DEFAULT_DURATION = 4500;
const NOTIFY_LONG_DURATION = 9500;

function SendNotify(Msg, icon, duration = NOTIFY_DEFAULT_DURATION) {
    let IconFA = '';
    switch (icon) {
        case 0:
            IconFA = 'fas fa-comment mrg-right-5';
            break;
        case 1:
            IconFA = 'fas fa-info-circle mrg-right-5 color-blue';
            break;
        case 2:
            IconFA = 'fas fa-exclamation-triangle mrg-right-5 color-yellow';
            break;
        case 3:
            IconFA = 'fas fa-times-circle mrg-right-5 color-red';
            break;
        case 4:
            IconFA = 'fas fa-check-circle mrg-right-5 color-green';
            break;
        case 5:
            IconFA = 'fas fa-sync-alt mrg-right-5 color-bluepants fa-spin';
            break;
        case 6:
            IconFA = 'fas fa-trash mrg-right-5 color-red';
            break;
        case 7:
            IconFA = 'fas fa-spinner mrg-right-5 color-yellow fa-spin';
            break;
        default:
            break;
    }
    let Notification = document.createElement("div");
    let Area = document.getElementById('NotificationArea');
    Notification.className = "NotifyItem";
    Notification.innerHTML = '<p class="NotifyText"><i class="' + IconFA + '"></i>' + Msg + '</p>';
    Area.appendChild(Notification);
    setTimeout(() => {
        Notification.style = "transform: scale(1); opacity: 1;";
        Area.scrollTo({ left: 0, top: Area.scrollHeight });
    }, 10);
    setTimeout(() => {
        Notification.style = "transform: scale(0); opacity: 0;";
        setTimeout(() => {
            Notification.remove();
        }, 350);
    }, duration);
}