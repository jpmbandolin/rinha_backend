let batchTimeout;
let isProcessing = false;
const BATCH_SIZE = 200;
let accumulatedData = [];
const Redis = require("ioredis");
const redis = new Redis(6379, "redis");
const mysql = require('mysql2/promise');
const dbConfig = {
    host: 'mysql',
    user: 'root',
    password: 'password',
    database: 'rinha'
};
const pool = mysql.createPool(dbConfig);
redis.subscribe("createUser");

function resetBatchTimeout() {
    clearTimeout(batchTimeout);
    batchTimeout = setTimeout(processBatch, 10000); // Process accumulated data after 10 seconds
}

async function processBatch() {
    if (accumulatedData.length === 0 || isProcessing) {
        return;
    }
    isProcessing = true;

    try {
        const placeholders = [];
        const values = [];

        for (const item of accumulatedData) {
            placeholders.push("(?, ?, ?, ?, ?)");
            values.push(item.id, item.apelido, item.nome, item.nascimento, item.stack);
        }

        const sql = `
            INSERT INTO people (uuid, nickname, name, birthdate, stack)
            VALUES ${placeholders.join(",")}
        `;
        await pool.query(sql, values);
    } catch (error) {
        console.error('Error saving to MySQL:', error.message);
    } finally {
        accumulatedData = []; // Clear the accumulated data after insertion
        isProcessing = false;
        resetBatchTimeout(); // Reset the timeout after processing
    }
}

redis.on("message", async (channel, message) => {
    if (!isJson(message)) {
        console.log("Trash message: ", message);
        return;
    }

    const data = JSON.parse(message);
    accumulatedData.push(data);

    if (accumulatedData.length >= BATCH_SIZE) {
        await processBatch();
    }
});

function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

resetBatchTimeout(); // Initialize the batch processing timeout
