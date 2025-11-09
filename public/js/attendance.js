/**
 * Attendance Management JavaScript
 * Handles month navigation and attendance calendar functionality
 */

document.addEventListener("DOMContentLoaded", function () {
    // Month navigation
    const prevMonthBtn = document.getElementById("prev-month");
    const nextMonthBtn = document.getElementById("next-month");
    const currentMonthSpan = document.getElementById("current-month");

    function changeMonth(direction) {
        // Get current month from data attribute or fallback to parsing text
        let currentMonth =
            prevMonthBtn?.getAttribute("data-month") ||
            nextMonthBtn?.getAttribute("data-month");

        if (!currentMonth && currentMonthSpan) {
            // Fallback to parsing text content
            const currentMonthText = currentMonthSpan.textContent.trim();
            const parts = currentMonthText.split(" ");
            if (parts.length >= 2) {
                const monthName = parts[0];
                const year = parts[1];
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
                if (monthIndex !== -1) {
                    currentMonth = `${year}-${String(monthIndex + 1).padStart(
                        2,
                        "0"
                    )}`;
                }
            }
        }

        if (!currentMonth) {
            console.error("Could not determine current month");
            return;
        }

        const [year, month] = currentMonth.split("-");
        let newYear = parseInt(year);
        let newMonth = parseInt(month) + direction;

        if (newMonth < 1) {
            newMonth = 12;
            newYear--;
        } else if (newMonth > 12) {
            newMonth = 1;
            newYear++;
        }

        const newMonthFormatted = `${newYear}-${String(newMonth).padStart(
            2,
            "0"
        )}`;
        const currentPath = window.location.pathname;
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set("month", newMonthFormatted);
        window.location.href = `${currentPath}?${urlParams.toString()}`;
    }

    if (prevMonthBtn && nextMonthBtn) {
        prevMonthBtn.addEventListener("click", function (e) {
            e.preventDefault();
            // Close any open Alpine.js dropdowns
            closeAllDropdowns();
            changeMonth(-1);
        });

        nextMonthBtn.addEventListener("click", function (e) {
            e.preventDefault();
            // Close any open Alpine.js dropdowns
            closeAllDropdowns();
            changeMonth(1);
        });
    }

    function closeAllDropdowns() {
        // Close all Alpine.js dropdowns by triggering click outside
        document.body.click();
        // Small delay to ensure dropdowns are closed
        setTimeout(() => {}, 50);
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
