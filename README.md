# Motley Fool Stock Advisor Challenge

Welcome! This is a custom Wordpress built for a coding challenge focused on adding 3rd party stock quotes, and company profiles, into articles about recommended stock picks.

## Installing the theme

- Clone this repo into the themes folder of your Wordpress installation
- Activate the theme in your Dashboard
- Import the sample content backup. *My import includes extra fake posts to test the pagination*.
- Define your API key in the wp-config.php folder. *See API Key Requirement below*.

## API Key Requirements

This theme relies on the https://site.financialmodelingprep.com/developer API to pull key stats and stock quotes for companies that are recommended on the website. In order for this data to load, you must register for your own API key. An API key is NOT included in this theme.

1. Register for an API key at https://site.financialmodelingprep.com/developer
2. Define your API key as a constant, in your wp-config.php file, with the name **MFSA_API_KEY**

Example:
```php
/**
 * API key for stock quotes and company stats
 */
define( 'MFSA_API_KEY', '[YOUR API KEY]' );
```

## Future Improvements

- **Make it mobile-friendly:** I designed the theme with mobile devices in mind, but due to time constraints I have not thoroughly tested it on smaller devices.
- **Caching:** The stock recommendations and company pages make requests to a 3rd party API to display the latest data about the company. These requests are made on each page load. I think the site would benefit from caching some of these results for improved performance. I would anticipate that much of the company data does not change regulary *i.e., Company Name, symbol, CEO, Logo, etc..*
- **Ticker Symbols:** This theme relies on custom post meta boxes for associating posts with stock recommendations and company profiles. These meta boxes contain select elements with options populated by a static array for demonstration. If I had more time, I would've liked to populate the choices from an API call to this endpoint: https://site.financialmodelingprep.com/developer/docs/stock-market-quote-free-api


## My Struggles

Has you ever had a project go so smoothly that you never had any new problems to solve? If so, that must be so nice! I had a couple note-worthy problems that took some extra brain power to solve. 😅

### API Rate Limits
The project instructions for the company profile page required me to make two calls to the API because it did not have a single endpoint with all the required data. In the beginning this was not a problem. I had been requesting the data via PHP and rendering it into the template. 

Late into the project, I noticed an intermittent error. On some page loads, the 2nd API request would load too quickly, being rejected by the API due to my free account. My solution was to refactor to utilize Javascript asynchronous behavior to avoid the rate limits. *This solution is detailed further in the Solutions section below*.

### Pagination
The pagination requirement for the Other Coverage articles on the company profile, revealed a tough problem. I've worked with Wordpress's built-in pagination features many times, but I have never tried to include pagination on a single post template. 

I built my company profiles as a custom post type. The issue was each subsequent page (i.e., /page/2/) would get rewritten by WP back to the single post URL. Through some research (Hello StackOverflow! 😄) I discovered that this is a well documented error on Wordpress Trac, and [I found a solution](https://wordpress.stackexchange.com/a/364743) to disable rewriting on custom post types.
