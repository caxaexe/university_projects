class TransactionAnalyzer {
    
    /**
     * Initialize TransactionAnalyzer with transactions array.
     * @param {Array<Object>} transactions - Array of transaction objects.
     */
    constructor(transactions) {
        this.transactions = transactions;
    }

    /**
     * Add a transaction to the transactions array.
     * @param {Object} transaction - Transaction object to be added.
     */
    addTransaction(transaction) {
        this.transactions.push(transaction);
    }

    /**
     * Retrieve all transactions.
     * @returns {Array<Object>} Array of all transactions.
     */
    getAllTransactions() {
        return this.transactions;
    }

    /**
     * Get unique transaction types.
     * @returns {Array<string>} Array of unique transaction types.
     */
    getUniqueTransactionType() {
    const types = new Set(this.transactions.map(transaction => transaction.transaction_type));
    return Array.from(types);
    }

    /**
     * Calculate total amount of all transactions.
     * @returns {number} Total amount of all transactions.
     */
    calculateTotalAmount() {
        return this.transactions.reduce((total, transaction) => total + transaction.transaction_amount, 0); 
    }

    /**
     * Calculate total amount of transactions on a specific date.
     * @param {number} year - Year.
     * @param {number} month - Month (1-12).
     * @param {number} day - Day of the month.
     * @returns {number} Total amount of transactions on the specified date.
     */
    calculateTotalAmountByDate(year, month, day) {
        let totalAmount = 0;
        for (const transaction of this.transactions) {
            const transactionDate = new Date(transaction.transaction_date);
            if (
                transactionDate.getFullYear() === year &&
                transactionDate.getMonth() === month - 1 &&
                transactionDate.getDate() === day
            ) {
                totalAmount += transaction.transaction_amount; 
            }
        }
        return totalAmount;
    }
      
    /**
     * Get transactions by transaction type.
     * @param {string} type - Transaction type to filter by.
     * @returns {Array<Object>} Array of transactions with the specified type.
     */
    getTransactionByType(type) {
        return this.transactions.filter(t => t.transaction_type === type);
    }

    /**
     * Get transactions within a specified date range.
     * @param {string} startDate - Start date of the range.
     * @param {string} endDate - End date of the range.
     * @returns {Array<Object>} Array of transactions within the specified date range.
     */
    getTransactionsInDateRange(startDate, endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        return this.transactions.filter(t => {
            const date = new Date(t.transaction_date);
            return date >= start && date <= end;
        });
    }

    /**
     * Get transactions by merchant name.
     * @param {string} merchantName - Name of the merchant to filter by.
     * @returns {Array<Object>} Array of transactions from the specified merchant.
     */
    getTransactionsByMerchant(merchantName) {
        return this.transactions.filter(t => t.merchant_name === merchantName);
    }

    /**
     * Calculate average transaction amount.
     * @returns {number} Average transaction amount.
     */
    calculateAverageTransactionAmount() {
        return this.calculateTotalAmount() / this.transactions.length;
    }

    /**
     * Get transactions within a specified amount range.
     * @param {number} minAmount - Minimum transaction amount.
     * @param {number} maxAmount - Maximum transaction amount.
     * @returns {Array<Object>} Array of transactions within the specified amount range.
     */
    getTransactionsByAmountRange(minAmount, maxAmount) {
        return this.transactions.filter(t => t.transaction_amount >= minAmount && t.transaction_amount <= maxAmount);
    }

    /**
     * Calculate total debit amount.
     * @returns {number} Total debit amount.
     */
    calculateTotalDebitAmount() {
        return this.getTransactionByType("debit").reduce((sum, t) => sum + t.transaction_amount, 0);
    }

    /**
     * Find the month with the most transactions.
     * @returns {number} The month index with the most transactions (0-11).
     */
    findMostTransactionsMonth() {
        const counts = {}; // пустой объект-счетчик
        for (const t of this.transactions) {
            const month = new Date(t.transaction_date).getMonth();
            counts[month] = (counts[month] || 0) + 1;
        }
        return Object.keys(counts).reduce((a, b) => (counts[a] > counts[b] ? a : b));
    }

    /**
     * Find the month with the most debit transactions.
     * @returns {number} The month index with the most debit transactions (0-11).
     */
    findMostDebitTransactionMonth() {
        const counts = {};
        for (const t of this.getTransactionByType("debit")) {
            const month = new Date(t.transaction_date).getMonth();
            counts[month] = (counts[month] || 0) + 1;
        }
        return Object.keys(counts).reduce((a, b) => (counts[a] > counts[b] ? a : b));
    }

    /**
     * Find the most common transaction type.
     * @returns {string} The most common transaction type.
     */
    mostTransactionTypes() {
        const typeCounts = {};
        for (const t of this.transactions) {
            if (typeCounts[t.transaction_type]) {
                typeCounts[t.transaction_type]++;
            } else { 
                typeCounts[t.transaction_type] = 1;
            }
        }
        const types = Object.keys(typeCounts);
        if (types.length === 1) { 
            return types[0];
        } else {
            let mostCommonType = types[0]; 
            for (const type of types) { 
                if (typeCounts[type] > typeCounts[mostCommonType]) {
                    mostCommonType = type;
                }
            }
            return mostCommonType;
        }
    }
    
    /**
     * Get transactions before a specified date.
     * @param {string} date - Date to compare against.
     * @returns {Array<Object>} Array of transactions before the specified date.
     */
    getTransactionsBeforeDate(date) {
        const data = new Date(date);
        return this.transactions.filter(t => new Date(t.transaction_date) < data);
    }

    /**
     * Find a transaction by its ID.
     * @param {string} id - ID of the transaction to find.
     * @returns {Object|null} Transaction object if found, otherwise null.
     */
    findTransactionById(id) {
        return this.transactions.find((t) => t.transaction_id == id);
    }

    /**
     * Map transaction descriptions.
     * @returns {Array<string>} Array of transaction descriptions.
     */
    mapTransactionDescriptions() {
        return this.transactions.map(t => t.transaction_description);
    }
}


const transactions = require('./transaction.json'); 
const analyzer = new TransactionAnalyzer(transactions);

// первый метод - тип транзакции
// console.log(analyzer.getUniqueTransactionType());

// второй метод - общая сумма всех транзакций
// console.log(analyzer.calculateTotalAmount());

// третий метод - общая сумма транзакций за год, месяц, день
// console.log(analyzer.calculateTotalAmountByDate()); // (yyyy, m, d)

// четвертый метод - транзакции указанного типа
// console.log(analyzer.getTransactionByType('credit'));

// пятый метод - транзакции в определенный период от - до
// console.log(analyzer.getTransactionsInDateRange()); // ('yyyy, m,, d', 'yyyy, m, d'), исключая второй элемент

// шестой метод - транзакции с указанным рабочим местом
// console.log(analyzer.getTransactionsByMerchant());

// седьмой метод - среднее значение транзакций
// console.log(analyzer.calculateAverageTransactionAmount());

// восьмой метод - транзакции с суммой от мин до макс
// console.log(analyzer.getTransactionsByAmountRange()); // min, max

// девятый метод - сумма дебетовых транзакций
// console.log(analyzer.calculateTotalDebitAmount());

// десятый метод - месяц, в котором больше траназакций
// console.log(analyzer.findMostTransactionsMonth());

// одиннадцатый метод - месяц, в котором больше дебетовых транзакций
// console.log(analyzer.findMostDebitTransactionMonth());

// двенадцатый метод - каких транзакций больше (дебетовых, кредитовых, одинаковы)
// console.log(analyzer.mostTransactionTypes());

// тринадцатый метод - транзакции до указазанной даты
// console.log(analyzer.getTransactionsBeforeDate()); // ('yyyy, m, d')

// четырнадцатый метод - поиск траназкций по айди
// console.log(analyzer.findTransactionById());

// пятнадцатый метод - описание
// console.log(analyzer.mapTransactionDescriptions());
