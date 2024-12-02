<?php
session_start(); 
include("./role_based_header.php"); 
?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Q&A pharmacist</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" />
    <!-- Font Icons css -->
    <link rel="stylesheet" href="css/font-icons.css">
    <!-- plugins css -->
    <link rel="stylesheet" href="css/plugins.css">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive css -->
    <link rel="stylesheet" href="css/responsive.css">

    <style>
        body {
          font-family: Arial, sans-serif;
          padding: 20px;
        }
    
        .qa-container {
          margin-bottom: 20px;
          border: 1px solid #ddd;
          border-radius: 5px;
          padding: 15px;
          background-color: #f9f9f9;
        }
    
        .question-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          font-weight: bold;
        }
    
        .question-text {
          margin: 10px 0;
        }
    
        .qa-buttons {
          display: flex;
          gap: 10px;
        }
    
        .qa-buttons button {
          padding: 5px 10px;
          font-size: 0.9em;
          cursor: pointer;
          border: none;
          border-radius: 3px;
          background-color: #007bff;
          color: white;
        }
    
        .qa-buttons button:hover {
          background-color: #0056b3;
        }
    
        .answer-section {
          margin-top: 15px;
          padding: 10px;
          border-top: 1px solid #ddd;
        }
    
        .answer-text {
          margin-bottom: 10px;
        }
    
        .answer-input {
          display: flex;
          flex-direction: column;
          gap: 10px;
        }
    
        .pin {
          background-color: #ffcc00;
          color: black;
        }
    
        .delete {
          background-color: #dc3545;
          color: white;
        }
    
        .delete:hover {
          background-color: #b02a37;
        }
      </style>
</head>

<body>
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <!-- Add your site or application content here -->

<!-- Body main wrapper start -->
<div class="body-wrapper">
    <div class="ltn__utilize-overlay"></div>

    
<!-- Shop Details Start -->
    <div class="ltn__shop-details-area pb-85">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="ltn__shop-details-inner mb-60">
                        <div id="questionsContainer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .qa-container {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
            transition: box-shadow 0.3s ease;
        }
    
        .qa-container:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    
        .qa-buttons-hover {
            position: absolute;
            top: 15px;
            right: 15px;
            display: none;
            flex-direction: column;
            gap: 5px;
        }
    
        .qa-container:hover .qa-buttons-hover {
            display: flex;
        }
    
        .qa-buttons-hover button {
            padding: 5px 10px;
            font-size: 0.9em;
            cursor: pointer;
            border: none;
            border-radius: 3px;
            transition: background-color 0.2s ease;
        }
    
        .qa-buttons-hover .answer {
            background-color: #28a745;
            color: white;
        }
    
        .qa-buttons-hover .answer:hover {
            background-color: #218838;
        }
    
        .qa-buttons-hover .pin {
            background-color: #ffc107;
            color: black;
        }
    
        .qa-buttons-hover .pin:hover {
            background-color: #e0a800;
        }
    
        .qa-buttons-hover .delete {
            background-color: #dc3545;
            color: white;
        }
    
        .qa-buttons-hover .delete:hover {
            background-color: #b02a37;
        }
    </style>
    
    <script>
        // Function to fetch questions from the backend
        document.addEventListener("DOMContentLoaded", () => {
    async function fetchQuestions() {
        try {
            const response = await fetch('./get_questions_pharmacist.php');

            if (!response.ok) {
                console.error("HTTP Error:", response.status, response.statusText);
                return;
            }

            const questions = await response.json(); // Parse JSON directly
            console.log("Fetched Questions:", questions);

            // Define the container element
            const container = document.getElementById('questionsContainer');
            if (!container) {
                console.error("Container element with ID 'questionsContainer' not found in the DOM.");
                return;
            }

            container.innerHTML = ""; // Clear existing questions

            if (questions.length === 0) {
                container.innerHTML = "<p>No questions found.</p>";
                return;
            }

            // Render questions
            questions.forEach((q) => {
                const questionCard = document.createElement("div");
                questionCard.classList.add("qa-container");
                questionCard.style.border = q.pinned === "1" ? "2px solid gold" : "1px solid #ddd";
                questionCard.innerHTML = `
                    <div class="question-header" style="font-weight: bold; margin-bottom: 10px;">
                        <span class="pin-status" id="pinStatus_${q.id}" style="float: right;">${q.pinned === "1" ? "Pinned" : "Unpinned"}</span>
                    </div>
                    <p><strong>Subject:</strong> ${q.subject}</p>
                    <p><strong>Message:</strong> ${q.message}</p>

                    <!-- Hover Buttons -->
                    <div class="qa-buttons-hover">
                        <button class="answer" onclick="navigateToAnswerPage(${q.id})">Answer</button>
                        <button class="pin" onclick="togglePin(${q.id}, ${q.pinned === "1" ? 0 : 1})">
                            ${q.pinned === "1" ? "Unpin" : "Pin"}
                        </button>
                        <button class="delete" onclick="deleteQuestion(${q.id})">Delete</button>
                    </div>
                `;
                container.appendChild(questionCard);
            });
        } catch (error) {
            console.error("Error fetching questions:", error);
        }
    }

    async function togglePin(questionId, pinStatus) {
    try {
        // Send request to toggle pin status
        const response = await fetch('./toggle_pin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ question_id: questionId, pinned: pinStatus }),
        });

        const result = await response.json();
        if (result.success) {
            // Update UI based on the new pin status
            const pinElement = document.getElementById(`pinStatus_${questionId}`);
            if (pinElement) {
                pinElement.textContent = pinStatus === 1 ? 'Pinned' : 'Unpinned';
            }

            const pinButton = document.querySelector(`button[onclick="togglePin(${questionId}, ${pinStatus})"]`);
            if (pinButton) {
                pinButton.textContent = pinStatus === 1 ? 'Unpin' : 'Pin';
                pinButton.setAttribute('onclick', `togglePin(${questionId}, ${pinStatus === 1 ? 0 : 1})`);
            }
            alert(result.message);
        } else {
            console.error('Failed to update pin status:', result.message);
            alert('Failed to update pin status.');
        }
    } catch (error) {
        console.error('Error toggling pin status:', error);
    }
}

// Deletes a question
async function deleteQuestion(questionId) {
    if (!confirm("Are you sure you want to delete this question?")) return;

    try {
        const response = await fetch('./delete_question.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ question_id: questionId }),
        });

        const result = await response.json();
        if (result.success) {
            const questionCard = document.querySelector(`.qa-container[data-id="${questionId}"]`);
            if (questionCard) {
                questionCard.remove();
            }
            alert(result.message);
        } else {
            console.error('Failed to delete question:', result.message);
            alert('Failed to delete question.');
        }
    } catch (error) {
        console.error('Error deleting question:', error);
    }
}

// Navigates to the answer page
function navigateToAnswerPage(questionId) {
    if (!questionId) {
        console.error("No question ID provided!");
        alert("No question ID found!"); // Add an alert for debugging
        return;
    }
    // Redirect to the answer page
    window.location.href = `view_answer.php?question_id=${questionId}`;
}

// Toggles the pin UI state for a question or answer
function togglePinUI(itemId, pinStatusId = null) {
    const item = document.getElementById(itemId);
    if (!item) {
        console.error(`Item with ID ${itemId} not found`);
        return;
    }

    if (item.style.border === '2px solid gold') {
        item.style.border = '';
        if (pinStatusId) {
            const pinStatusElement = document.getElementById(pinStatusId);
            if (pinStatusElement) {
                pinStatusElement.innerText = 'Unpinned';
            }
        }
    } else {
        item.style.border = '2px solid gold';
        if (pinStatusId) {
            const pinStatusElement = document.getElementById(pinStatusId);
            if (pinStatusElement) {
                pinStatusElement.innerText = 'Pinned';
            }
        }
    }
}

    fetchQuestions(); // Fetch questions on page load
});

</script>
    

       
</body>
</html>

