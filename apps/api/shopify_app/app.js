
const express = require('express');
const axios = require('axios');

const app = express();
const PORT = 3000;

app.get('/predict', async (req, res) => {
    try {
        const response = await axios.post('http://<API_HOST>/api/v1/sales/predict', {
            data: { /* example data */ }
        });
        res.json(response.data);
    } catch (error) {
        res.status(500).send('Error connecting to API');
    }
});

app.listen(PORT, () => console.log(`Shopify App listening on port ${PORT}`));
    