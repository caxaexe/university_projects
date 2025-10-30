/** @type {Array<Object>} */
let transactions = [];

/**
 * Function to add a transaction to the list and update the table.
 * @returns {Object} - The added transaction.
 */
function addTransaction() {
    /** @type {string} */
    const id = document.getElementById("transactionId").value;
    /** @type {string} */
    const date = document.getElementById("transactionDate").value;
    /** @type {number} */
    const amount = parseFloat(document.getElementById("transactionAmount").value);
    /** @type {string} */
    const category = document.getElementById("transactionCategory").value;
    /** @type {string} */
    const description = document.getElementById("transactionDescription").value;

    /** @type {Object} */
    let transaction = {
        id: id,
        date: date,
        amount: amount,
        category: category,
        description: description
    };

    transactions.push(transaction);
    updateTable(transaction);
    calculateTotal();

    return transaction;
}

/**
 * Function to update the transactions table.
 * @param {Object} transaction - The added transaction.
 */
function updateTable(transaction) {
    /** @type {HTMLTableElement} */
    const table = document.getElementById("transactionTable");
    /** @type {HTMLTableRowElement} */
    const newRow = table.insertRow();

    newRow.dataset.description = transaction.description;

    if (transaction.amount > 0) {
        newRow.style.backgroundColor = "rgba(67, 255, 67, 0.678)";
    } else {
        newRow.style.backgroundColor = "rgba(255, 64, 64, 0.753)";
    }

    newRow.insertCell(0).textContent = transaction.id;
    newRow.insertCell(1).textContent = transaction.date;
    newRow.insertCell(2).textContent = transaction.amount.toFixed(2);
    newRow.insertCell(3).textContent = transaction.category;

    let formattedDescription = transaction.description.split(" ").slice(0, 4).join(" ");

    newRow.insertCell(4).textContent = formattedDescription;

    const deleteButton = document.createElement("button");
    deleteButton.textContent = "Delete";
    deleteButton.classList.add("newButton");
    deleteButton.addEventListener("click", function () {
        const rowIndex = this.parentNode.parentNode.rowIndex;
        table.deleteRow(rowIndex);
        transactions.splice(rowIndex - 1, 1);
        calculateTotal();
    });

    newRow.insertCell(5).appendChild(deleteButton);
}

/**
 * Function to calculate the total amount of transactions.
 */
function calculateTotal() {
    /** @type {number} */
    let totalAmount = 0;

    for (let i = 0; i < transactions.length; i++) {
        totalAmount += parseFloat(transactions[i].amount);
    }

    document.getElementById("totalAmount").textContent = "Total amount : " + totalAmount.toFixed(2);
}

/** @type {HTMLTableElement} */
const transactionTable = document.getElementById("transactionTable");

transactionTable.addEventListener("click", function (event) {
    const targetRow = event.target.closest("tr");
    if (targetRow) {
        const transactionId = targetRow.cells[0].textContent;
        const transactionDescription = targetRow.dataset.description;

        document.getElementById("transactionDescriptionBlock").textContent = "Transaction ID: " + transactionId + "\nDescription: " + transactionDescription;
    }
});

/** @type {HTMLFormElement} */
const addTransactionForm = document.getElementById("addTransactionForm");

addTransactionForm.addEventListener("submit", function (event) {
    event.preventDefault();

    addTransaction();

    addTransactionForm.reset();
});
