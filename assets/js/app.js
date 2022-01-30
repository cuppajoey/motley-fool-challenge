const TOKEN = "";

const getCompanyExchangeData = () => {
    return fetch(`https://financialmodelingprep.com/api/v3/profile/AAPL?apikey=${TOKEN}`)
        .then((response) => response.json())
        .then((json) => {
            
            // Required company data
            let companyData = {
                "logo": json[0].image,
                "name": json[0].companyName,
                "exchange": json[0].exchangeShortName,
                "description": json[0].description,
                "industry": json[0].industry,
                "sector": json[0].sector,
                "ceo": json[0].ceo,
                "website": json[0].website,
            };

            // updateCompanyCard();
            console.log(companyData);
        })
        .catch((error) => {
            console.error(error);
        });
};

const getCompanyStockProfile = () => {
    let getCompanyQuote = fetch("http://stockadvisor.local/wp-json/mfsa/v1/quote/?symbol=sbux");
    let getCompanyProfile = fetch("http://stockadvisor.local/wp-json/mfsa/v1/profile/?symbol=sbux");
    
    Promise.all([getCompanyQuote, getCompanyProfile])
        .then(values => Promise.all(values.map(value => value.json())))
        .then(combinedResponse => {
            let quoteResponse = combinedResponse[0];
            let profileResponse = combinedResponse[1];

            console.log(combinedResponse)
            // updateCompanyStats(quoteResponse, profileResponse);
        })
        .catch((error) => {
            console.error(error);
        });
};

const updateCompanyStats = (quoteData, profileData) => {
    let statsTable = document.getElementById("mfsa-stats");
    let stats = {
        "Current Price": quoteData[0].price,
        "Today's Change": quoteData[0].change,
        "Change Percentage": quoteData[0].changesPercentage,
        "Yearly Range:": profileData[0].range,
        "Beta": profileData[0].beta,
        "Average Volume": quoteData[0].avgVolume,
        "Market Cap": quoteData[0].marketCap,
        "Dividend": profileData[0].lastDiv
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
    // getCompanyExchangeData();
    getCompanyStockProfile();
})