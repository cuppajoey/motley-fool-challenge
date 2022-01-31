/*
 *  MFSA_DATA is an object enqueued with data required for the API calls
 *  
 *  Available properties: 
 *  site_url - the base url of the website
 *  symbol - the ticker symbol of current company (Ex: SBUX)
 */ 

const formatCurrency = (num) => {
    let formatHandler = new Intl.NumberFormat("en-us", {
        style: "currency",
        currency: "USD"
    });
    return formatHandler.format(num);
};

const formatPercentage = (num) => {
    let formatHandler = new Intl.NumberFormat("en-us", {
        style: "percent"
    });
    return formatHandler.format(num);
};

const formatDecimal = num => Number.parseFloat(num).toFixed(2);

const formatNumberWithSeparators = num => num.toLocaleString("en-us");

const getCompanyStockProfile = () => {
    let site_url = MFSA_DATA.site_url;
    let symbol = MFSA_DATA.symbol.toLowerCase();

    let getCompanyQuote = fetch(`${site_url}/wp-json/mfsa/v1/quote/?symbol=${symbol}`);
    let getCompanyProfile = fetch(`${site_url}/wp-json/mfsa/v1/profile/?symbol=${symbol}`);
    
    Promise.all([getCompanyQuote, getCompanyProfile])
        .then(values => Promise.all(values.map(value => value.json())))
        .then(combinedResponse => {
            let quoteResponse = combinedResponse[0];
            let profileResponse = combinedResponse[1];

            updateCompanyStats(quoteResponse, profileResponse);
        })
        .catch((error) => {
            console.error(error);
        });
};

const updateCompanyStats = (quoteData, profileData) => {
    let statsTable = document.getElementById("mfsa-stats");
    let yearlyRange = profileData[0].range;
    let splitRange = yearlyRange.split("-");

    let stats = {
        "Current Price": formatCurrency(quoteData[0].price),
        "Today's Change": formatDecimal(quoteData[0].change),
        "Change Percentage": formatPercentage(quoteData[0].changesPercentage),
        "Yearly Range:": formatCurrency(splitRange[0]) + "-" + formatCurrency(splitRange[1]),
        "Beta": formatDecimal(profileData[0].beta),
        "Average Volume": formatNumberWithSeparators(quoteData[0].avgVolume),
        "Market Cap": formatNumberWithSeparators(quoteData[0].marketCap),
        "Dividend": formatDecimal(profileData[0].lastDiv)
    }

    markup = '<div class="table-grid">';
        for (let key in stats) {
            markup += '<div class="table-cell">';
            markup += '<strong>' + key + '</strong>';
            markup += '<span>' + stats[key] + '</span>';
            markup += '</div>';
        }
    markup += '</div>';

    statsTable.innerHTML = markup;
};

document.addEventListener('DOMContentLoaded', () => {
    getCompanyStockProfile();
})