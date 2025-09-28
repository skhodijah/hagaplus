/**
 * Attendance Management JavaScript
 * Handles month navigation and attendance calendar functionality
 */

document.addEventListener("DOMContentLoaded", function () {
    // Month navigation
    const prevMonthBtn = document.getElementById("prev-month");
    const nextMonthBtn = document.getElementById("next-month");
    const currentMonthSpan = document.getElementById("current-month");

    if (prevMonthBtn && nextMonthBtn && currentMonthSpan) {
        prevMonthBtn.addEventListener("click", function () {
            changeMonth(-1);
        });

        nextMonthBtn.addEventListener("click", function () {
            changeMonth(1);
        });
    }

    function changeMonth(direction) {
        const currentMonth = currentMonthSpan.textContent;
        const [monthName, year] = currentMonth.split(" ");
        const monthNames = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ];
        const monthIndex = monthNames.indexOf(monthName);
        let newMonthIndex = monthIndex + direction;
        let newYear = parseInt(year);

        if (newMonthIndex < 0) {
            newMonthIndex = 11;
            newYear--;
        } else if (newMonthIndex > 11) {
            newMonthIndex = 0;
            newYear++;
        }

        const newMonth = `${newYear}-${String(newMonthIndex + 1).padStart(
            2,
            "0"
        )}`;
        window.location.href = `${window.location.pathname}?month=${newMonth}`;
    }

    // Initialize any other attendance-related functionality here
    initializeAttendanceCalendar();
});

/**
 * Initialize attendance calendar specific functionality
 */
function initializeAttendanceCalendar() {
    // Add any additional calendar-specific functionality here
    console.log("Attendance calendar initialized");
}

/**
 * Utility function to format dates
 * @param {Date} date - Date object to format
 * @returns {string} Formatted date string
 */
function formatDate(date) {
    return date.toISOString().split("T")[0];
}

/**
 * Utility function to get current month in YYYY-MM format
 * @returns {string} Current month in YYYY-MM format
 */
function getCurrentMonth() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0");
    return `${year}-${month}`;
}
