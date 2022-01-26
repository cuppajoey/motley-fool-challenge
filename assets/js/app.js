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

document.addEventListener('DOMContentLoaded', () => {
    getCompanyExchangeData();
})