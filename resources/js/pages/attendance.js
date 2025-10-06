import QrScanner from "qr-scanner";

document.addEventListener("DOMContentLoaded", function () {
    console.log("Attendance page loaded");

    const video = document.getElementById("qr-video");
    const startButton = document.getElementById("start-scan");
    const stopButton = document.getElementById("stop-scan");
    const errorText = document.getElementById("scan-error");
    const resultText = document.getElementById("scan-result");
    let scanner = null;

    function startScan() {
        console.log("starting...");
        errorText.classList.add("hidden");
        resultText.classList.add("hidden");

        scanner
            .start()
            .then(() => {
                // Hide placeholder and show video
                document
                    .getElementById("qr-placeholder")
                    .classList.add("hidden");
                document
                    .getElementById("video-wrapper")
                    .classList.remove("hidden");

                startButton.classList.add("hidden");
                stopButton.classList.remove("hidden");
            })
            .catch((error) => {
                onScanError(error);
            });
    }

    function stopScan() {
        if (scanner) {
            scanner.stop();
            stopButton.classList.add("hidden");
            startButton.classList.remove("hidden");

            // Show placeholder and hide video
            document
                .getElementById("qr-placeholder")
                .classList.remove("hidden");
            document.getElementById("video-wrapper").classList.add("hidden");
        }
    }

    // Function to handle successful scans
    function onScanSuccess(result) {
        console.log(result);

        stopScan();

        // Send the scanned data to your backend
        fetch("/employee/attendance/scan", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({ qr_data: result.data }),
        })
            .then((response) => response.json())
            .then((data) => {
                resultText.textContent = data.message;
                resultText.classList.remove("hidden");
                errorText.classList.add("hidden");

                // Stop scanning after successful scan
                if (scanner) {
                    scanner.stop();
                    stopButton.classList.add("hidden");
                    startButton.classList.remove("hidden");
                }
            })
            .catch((error) => {
                errorText.textContent =
                    "Error processing scan. Please try again.";
                errorText.classList.remove("hidden");
                resultText.classList.add("hidden");
            });
    }

    // Function to handle scan errors
    function onScanError(error) {
        console.error(error);
        errorText.textContent = "Camera error: " + error.message;
        errorText.classList.remove("hidden");
        resultText.classList.add("hidden");
    }

    // Initialize QR Scanner
    QrScanner.hasCamera().then((hasCamera) => {
        if (!hasCamera) {
            errorText.textContent = "No camera found on this device.";
            errorText.classList.remove("hidden");
            startButton.disabled = true;
            return;
        }

        scanner = new QrScanner(video, onScanSuccess, {
            onDecodeError: (error) => {
                // We don't need to show decode errors as they happen frequently
                console.log("Decode error:", error);
            },
            highlightScanRegion: true,
            highlightCodeOutline: true,
        });
    });

    // Start scanning button
    startButton.addEventListener("click", () => startScan());

    // Stop scanning button
    stopButton.addEventListener("click", () => stopScan());

    // Clean up on page unload
    window.addEventListener("beforeunload", () => {
        if (scanner) {
            scanner.stop();
        }
    });
});
