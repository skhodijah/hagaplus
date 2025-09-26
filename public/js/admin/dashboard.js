document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts when DOM is loaded
    initializeCharts();

    // Auto-refresh dashboard data every 5 minutes
    setInterval(refreshDashboardData, 300000);

    function initializeCharts() {
        // Attendance Trend Chart
        const attendanceCtx = document.getElementById('attendanceChart');
        if (attendanceCtx && window.dashboardData) {
            const attendanceData = window.dashboardData.monthlyAttendance;

            new Chart(attendanceCtx, {
                type: 'line',
                data: {
                    labels: attendanceData.map(item => item.date),
                    datasets: [{
                        label: 'Present Employees',
                        data: attendanceData.map(item => item.present),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                label: function(context) {
                                    const data = attendanceData[context.dataIndex];
                                    return [
                                        `Present: ${context.parsed.y}`,
                                        `Rate: ${data.rate}%`
                                    ];
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                color: '#6b7280'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6b7280',
                                maxTicksLimit: 7
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }

        // Department Distribution Chart (if we want to add one later)
        initializeDepartmentChart();

        // Branch Performance Chart (if we want to add one later)
        initializeBranchChart();
    }

    function initializeDepartmentChart() {
        // This could be a pie chart or bar chart for department distribution
        // For now, we'll keep it as progress bars in the HTML
    }

    function initializeBranchChart() {
        // This could be a horizontal bar chart for branch performance
        // For now, we'll keep it as cards in the HTML
    }

    function refreshDashboardData() {
        // Fetch updated dashboard data
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update key metrics
            updateMetrics(data);

            // Update charts
            updateCharts(data);

            // Show refresh notification
            showNotification('Dashboard updated', 'success');
        })
        .catch(error => {
            console.error('Error refreshing dashboard:', error);
            showNotification('Failed to refresh dashboard', 'error');
        });
    }

    function updateMetrics(data) {
        // Update metric cards with new data
        if (data.totalEmployees !== undefined) {
            updateMetric('.metric-total-employees', data.totalEmployees);
        }
        if (data.presentToday !== undefined) {
            updateMetric('.metric-present-today', data.presentToday);
        }
        if (data.pendingPayroll !== undefined) {
            updateMetric('.metric-pending-payroll', data.pendingPayroll);
        }
        if (data.activeBranches !== undefined) {
            updateMetric('.metric-active-branches', data.activeBranches);
        }
    }

    function updateMetric(selector, value) {
        const element = document.querySelector(selector);
        if (element) {
            // Add animation class
            element.classList.add('metric-update');

            // Update value
            const valueElement = element.querySelector('.metric-value');
            if (valueElement) {
                valueElement.textContent = new Intl.NumberFormat().format(value);
            }

            // Remove animation class after animation
            setTimeout(() => {
                element.classList.remove('metric-update');
            }, 1000);
        }
    }

    function updateCharts(data) {
        // Update attendance chart if Chart.js instance exists
        if (window.attendanceChart && data.monthlyAttendance) {
            window.attendanceChart.data.labels = data.monthlyAttendance.map(item => item.date);
            window.attendanceChart.data.datasets[0].data = data.monthlyAttendance.map(item => item.present);
            window.attendanceChart.update('none'); // Update without animation for smooth refresh
        }
    }

    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.dashboard-notification');
        existingNotifications.forEach(notification => {
            document.body.removeChild(notification);
        });

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full dashboard-notification`;

        // Set colors based on type
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-black',
            info: 'bg-blue-500 text-white'
        };

        notification.classList.add(...colors[type].split(' '));
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fa-solid ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;

        // Add to page
        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Remove after 3 seconds for auto-refresh notifications
        setTimeout(() => {
            if (document.body.contains(notification)) {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }
        }, 3000);
    }

    // Add CSS for metric update animation
    const style = document.createElement('style');
    style.textContent = `
        .metric-update {
            animation: metricPulse 1s ease-in-out;
        }

        @keyframes metricPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .chart-container {
            position: relative;
            height: 300px;
        }
    `;
    document.head.appendChild(style);

    // Add loading states for interactive elements
    const quickActionLinks = document.querySelectorAll('.quick-action-link');
    quickActionLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Add loading state
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.add('fa-spinner', 'fa-spin');
                icon.classList.remove('fa-user-plus', 'fa-calendar-check', 'fa-money-bill-wave', 'fa-building');
            }
        });
    });

    // Handle window resize for responsive charts
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            // Re-render charts on resize
            if (window.attendanceChart) {
                window.attendanceChart.resize();
            }
        }, 250);
    });

    // Export dashboard data functionality (future enhancement)
    window.exportDashboard = function(format = 'pdf') {
        showNotification(`Exporting dashboard as ${format.toUpperCase()}...`, 'info');

        // This would typically make an AJAX call to generate and download the export
        setTimeout(() => {
            showNotification(`Dashboard exported as ${format.toUpperCase()}`, 'success');
        }, 2000);
    };

    // Initialize any additional dashboard features
    initializeDashboardFeatures();
});

function initializeDashboardFeatures() {
    // Add any additional dashboard initialization code here

    // For example, tooltips for metric cards
    const metricCards = document.querySelectorAll('.metric-card');
    metricCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            // Could add tooltip or highlight effect
        });

        card.addEventListener('mouseleave', function() {
            // Remove tooltip or highlight effect
        });
    });

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + R to refresh dashboard
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            refreshDashboardData();
            showNotification('Refreshing dashboard...', 'info');
        }
    });
}

// Make refresh function globally available
window.refreshDashboardData = function() {
    // This function is defined in the DOMContentLoaded event listener
    // but we need to make it available globally for the keyboard shortcut
    const event = new Event('refreshDashboard');
    document.dispatchEvent(event);
};

// Listen for custom refresh event
document.addEventListener('refreshDashboard', function() {
    // Re-trigger the refresh logic
    setTimeout(() => {
        location.reload();
    }, 100);
});